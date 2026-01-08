<?php

namespace App\Http\Controllers;

use App\Models\Inventory;
use App\Models\Product; // Import Product
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $category = $request->query('category', 'all');
        $mode = $request->query('mode', 'inventory'); // 'inventory' or 'market'
        $search = $request->query('search');

        // Stats (User Inventory)
        $stats = [
            'total_items' => Inventory::where('user_id', auth()->id())->count(),
            'low_stock' => Inventory::where('user_id', auth()->id())->where('quantity_value', '<', 5)->count(),
        ];

        // Market Stats
        $marketStats = [
            'total_items' => Product::count(), // Just total products available
            'offers' => 3 // Hardcoded notifications count from image
        ];

        /* ================= Market Mode ================= */
        if ($mode === 'market') {
            $query = Product::query();

            // Filter Category
            if ($category !== 'all') {
                $query->where('category', $category);
            }

            // Filter Search
            if ($search) {
                $query->where('name', 'like', "%{$search}%");
            }

            // Filter Price
            if ($request->filled('max_price')) {
                 $query->where('price', '<=', $request->max_price);
            }

            $products = $query->latest()->with('user')->get();
            $items = collect([]); // Empty for inventory items
            
            return view('inventory.index', compact('items', 'products', 'stats', 'marketStats', 'category', 'mode'));
        }

        /* ================= Inventory Mode ================= */
        $query = Inventory::where('user_id', auth()->id());

        // Filter Category
        if ($category !== 'all') {
            $query->where('category', $category);
        }
        
        // Filter Search
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $items = $query->latest()->get();
        $products = collect([]); // Empty for market products

        return view('inventory.index', compact('items', 'products', 'stats', 'marketStats', 'category', 'mode'));
    }

    public function create()
    {
        return view('inventory.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:seeds,fertilizers,pesticides,equipment,harvest,other',
            'quantity_value' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50', // e.g., kg, liter, pieces
            'description' => 'nullable|string',
        ]);

        $validated['user_id'] = auth()->id();

        Inventory::create($validated);

        return redirect()->route('inventory.index')->with('success', 'تم إضافة العنصر بنجاح');
    }

    public function edit(Inventory $inventory)
    {
        // Ensure user owns the item
        if ($inventory->user_id !== auth()->id()) {
            abort(403);
        }
        return view('inventory.edit', compact('inventory'));
    }

    public function update(Request $request, Inventory $inventory)
    {
        if ($inventory->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|in:seeds,fertilizers,pesticides,equipment,harvest,other',
            'quantity_value' => 'required|numeric|min:0',
            'unit' => 'required|string|max:50',
            'description' => 'nullable|string',
        ]);

        $inventory->update($validated);

        return redirect()->route('inventory.index')->with('success', 'تم تحديث العنصر بنجاح');
    }

    public function destroy(Inventory $inventory)
    {
        if ($inventory->user_id !== auth()->id()) {
            abort(403);
        }

        $inventory->delete();

        return redirect()->route('inventory.index')->with('success', 'تم حذف العنصر');
    }

    /* ================= Market Selling Logic ================= */
    public function sellView(Inventory $inventory)
    {
        if ($inventory->user_id !== auth()->id()) {
            abort(403);
        }
        return view('inventory.sell', compact('inventory'));
    }

    public function listOnMarket(Request $request, Inventory $inventory)
    {
        if ($inventory->user_id !== auth()->id()) {
            abort(403);
        }

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity_to_sell' => 'required|numeric|min:1|max:' . $inventory->quantity_value,
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        // Handle image upload if provided
        $imageUrl = $inventory->image_url; // Use existing image by default
        
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
            $image->move(public_path('images/products'), $imageName);
            $imageUrl = '/images/products/' . $imageName;
            
            // Update inventory image
            $inventory->image_url = $imageUrl;
            $inventory->save();
        }

        // Create Product
        Product::create([
            'user_id' => auth()->id(),
            'name' => $inventory->name,
            'category' => $inventory->category, // Assuming mapping exists or is same
            'price' => $validated['price'],
            'stock_quantity' => $validated['quantity_to_sell'],
            'description' => $validated['description'] ?? $inventory->description,
            'image_url' => $imageUrl ?? 'https://placehold.co/400x300?text=' . urlencode($inventory->name),
            'is_market_listed' => true,
        ]);

        // Optional: Deduct from inventory? 
        // For now, let's just leave it or maybe update quantity.
        // Let's deduct to be realistic.
        $inventory->quantity_value -= $validated['quantity_to_sell'];
        if ($inventory->quantity_value <= 0) {
             // Don't delete, just set to 0
             $inventory->quantity_value = 0;
        }
        $inventory->save();

        return redirect()->route('inventory.index', ['mode' => 'market'])->with('success', 'تم عرض المنتج في السوق بنجاح');
    }

    public function show(Product $product)
    {
        $product->load('user', 'reviews.user');
        $relatedProducts = Product::where('category', $product->category)
            ->where('id', '!=', $product->id)
            ->take(6)
            ->get();
            
        return view('inventory.show', compact('product', 'relatedProducts'));
    }

    public function storeReview(Request $request, Product $product)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        \App\Models\ProductReview::create([
            'product_id' => $product->id,
            'user_id' => auth()->id(),
            'rating' => $request->rating,
            'comment' => $request->comment,
        ]);

        return redirect()->route('inventory.show', $product)->with('success', 'تم إضافة تقييمك بنجاح');
    }
}
