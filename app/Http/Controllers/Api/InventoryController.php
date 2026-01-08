<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Inventory;
use App\Http\Resources\InventoryResource;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        $inventory = $request->user()->inventory()->orderBy('created_at', 'desc')->get();
        return InventoryResource::collection($inventory);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'quantity_value' => 'required|numeric',
            'unit' => 'required|string|max:50',
            'category' => 'required|in:seeds,fertilizers,pesticides,equipment,harvest,other',
            'description' => 'nullable|string',
        ]);

        $item = $request->user()->inventory()->create($validated);

        return new InventoryResource($item);
    }

    public function show(Request $request, Inventory $inventory)
    {
        $this->authorize('view', $inventory);
        return new InventoryResource($inventory);
    }

    public function update(Request $request, Inventory $inventory)
    {
        $this->authorize('update', $inventory);

        $validated = $request->validate([
            'item_name' => 'sometimes|string|max:255',
            'quantity' => 'sometimes|numeric',
            'unit' => 'sometimes|string|max:50',
            'category' => 'sometimes|string|max:100',
            'notes' => 'nullable|string',
        ]);

        $inventory->update($validated);

        return new InventoryResource($inventory);
    }

    public function destroy(Request $request, Inventory $inventory)
    {
        $this->authorize('delete', $inventory);
        $inventory->delete();

        return response()->json(['message' => 'Inventory item deleted successfully']);
    }
    
    public function listOnMarket(Request $request, Inventory $inventory) 
    {
        $this->authorize('update', $inventory);
        
        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity_to_sell' => 'required|numeric|min:0|max:'.$inventory->quantity_value,
            'description' => 'nullable|string'
        ]);
        
        // Logic to create product
        $product = \App\Models\Product::create([
             'user_id' => $request->user()->id,
             'name' => $inventory->name,
             'description' => $validated['description'] ?? $inventory->description,
             'price' => $validated['price'],
             'quantity' => $validated['quantity_to_sell'],
             'unit' => $inventory->unit,
             'category' => $inventory->category,
             // image logic if needed
        ]);
        
        // Deduct from inventory
        $inventory->decrement('quantity_value', $validated['quantity_to_sell']);
        
        return new \App\Http\Resources\ProductResource($product);
    }
    
    /**
     * Store a review for a product
     *
     * @param Request $request
     * @param \App\Models\Product $product
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeReview(Request $request, \App\Models\Product $product)
    {
        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        // Check if user has already reviewed this product
        $existingReview = \App\Models\Review::where('user_id', $request->user()->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => 'لقد قمت بتقييم هذا المنتج بالفعل'
            ], 422);
        }

        // Create review
        $review = \App\Models\Review::create([
            'user_id' => $request->user()->id,
            'product_id' => $product->id,
            'rating' => $validated['rating'],
            'comment' => $validated['comment'] ?? null,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم إضافة التقييم بنجاح',
            'review' => $review->load('user')
        ]);
    }
}
