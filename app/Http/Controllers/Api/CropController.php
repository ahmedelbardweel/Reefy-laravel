<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Http\Resources\CropResource;
use Illuminate\Http\Request;

class CropController extends Controller
{
    public function index(Request $request)
    {
        $crops = $request->user()->crops()->orderBy('created_at', 'desc')->get();
        return CropResource::collection($crops);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'area' => 'required|numeric',
            'planting_date' => 'required|date',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('crops', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $crop = $request->user()->crops()->create($validated);

        return new CropResource($crop);
    }

    public function show(Request $request, Crop $crop)
    {
        $this->authorize('view', $crop);
        $crop->load(['irrigations' => function($query) {
            $query->orderBy('created_at', 'desc')->take(5);
        }, 'tasks' => function($query) {
            $query->orderBy('due_date', 'asc');
        }]);
        
        return new CropResource($crop);
    }

    public function update(Request $request, Crop $crop)
    {
        $this->authorize('update', $crop);

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'area' => 'sometimes|numeric',
            'planting_date' => 'sometimes|date',
            'description' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('crops', 'public');
            $validated['image_url'] = '/storage/' . $path;
        }

        $crop->update($validated);

        return new CropResource($crop);
    }

    public function destroy(Request $request, Crop $crop)
    {
        $this->authorize('delete', $crop);
        $crop->delete();

        return response()->json(['message' => 'Crop deleted successfully']);
    }
    
    public function irrigate(Request $request, Crop $crop)
    {
        $this->authorize('update', $crop);
        
        $validated = $request->validate([
           'amount' => 'required|numeric',
           'date' => 'required|date',
           'method' => 'required|string', 
           'notes' => 'nullable|string'
        ]);
        
        $irrigation = $crop->irrigations()->create([
            'user_id' => $request->user()->id,
            'amount_liters' => $validated['amount'],
            'date' => $validated['date'],
            'notes' => $validated['notes'],
            // 'method' => $validated['method'] // Migration doesn't have method column, so ignoring it
        ]);
        
        return response()->json([
            'message' => 'Irrigation recorded successfully',
            'data' => new \App\Http\Resources\IrrigationResource($irrigation)
        ]);
    }
    
    public function harvest(Request $request, Crop $crop)
    {
        $this->authorize('update', $crop);
        
        $validated = $request->validate([
            'harvest_date' => 'required|date',
            'yield_amount' => 'required|numeric',
            'notes' => 'nullable|string',
            'add_to_inventory' => 'boolean'
        ]);
        
        $crop->update(['status' => 'harvested']);
        
        if ($request->add_to_inventory) {
            $request->user()->inventory()->create([
                'name' => $crop->name,
                'quantity_value' => $validated['yield_amount'],
                'unit' => 'kg', 
                'category' => 'Harvest',
                'description' => 'Harvested from ' . $crop->name,
            ]);
        }
        
        return response()->json(['message' => 'Crop harvested successfully']);
    }

    public function sellToMarket(Request $request, $id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        // Create Product directly from crop
        $product = \App\Models\Product::create([
            'user_id' => auth()->id(),
            'name' => $crop->name,
            'category' => $crop->type ?? 'crops',
            'price' => $validated['price'],
            'stock_quantity' => $validated['quantity'],
            'description' => $validated['description'] ?? 'محصول طازج من المزرعة',
            'image_url' => $crop->image ?? null, // Use image if available
            // 'is_market_listed' => true, // Assuming this column exists or default
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Crop listed on market successfully',
            'product_id' => $product->id
        ]);
    }

    public function growthReport(Request $request, $id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        // Calculate basic growth stats
        $daysPlanted = \Carbon\Carbon::parse($crop->created_at)->diffInDays(now());
        $irrigationCount = $crop->irrigations()->count();
        $tasksCount = $crop->tasks()->count();
        $completedTasks = $crop->tasks()->where('status', 'completed')->count(); // Using correct status check now

        return response()->json([
            'crop_name' => $crop->name,
            'days_planted' => $daysPlanted,
            'health_status' => 'Good', // This could be logic-based
            'irrigation_count' => $irrigationCount,
            'tasks_completion' => $tasksCount > 0 ? round(($completedTasks / $tasksCount) * 100) . '%' : 'N/A',
            'estimated_harvest' => '2025-12-30' // Placeholder or calculation
        ]);
    }

    public function activityLog(Request $request, $id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        $irrigations = $crop->irrigations()->orderBy('created_at', 'desc')->get()->map(function($item) {
            $item->type = 'irrigation';
            return $item;
        });
        
        // You could merge tasks here too if desired
        
        return response()->json([
            'activities' => $irrigations
        ]);
    }
}
