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
        $category = $request->query('category', null);
        $search = $request->query('search');

        $query = Inventory::where('user_id', auth()->id());

        // Filter Category
        if ($category && $category !== 'all') {
            $query->where('category', $category);
        }
        
        // Filter Search
        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        $items = $query->latest()->get();

        return view('inventory.index', compact('items'));
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
}

