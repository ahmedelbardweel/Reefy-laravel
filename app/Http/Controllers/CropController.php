<?php

namespace App\Http\Controllers;

use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CropController extends Controller
{
    // Web: Show all crops
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'all');
        
        // Only get crops for the authenticated user
        $query = Crop::where('user_id', auth()->id());
        
        // Apply filters based on query parameter
        if ($filter === 'needs_water') {
            $query->where('water_level', '<', 30);
        } elseif ($filter === 'harvest_soon') {
            $query->whereNotNull('harvest_date')
                  ->whereDate('harvest_date', '<=', now()->addDays(7));
        } elseif ($filter === 'infected') {
            $query->where('status', 'infected');
        }
        
        $crops = $query->latest()->get();
        
        return view('crops.index', compact('crops', 'filter'));
    }

    // API: Get all crops
    public function apiIndex(Request $request)
    {
        $filter = $request->query('filter', 'all');
        
        // Only get crops for the authenticated user
        $query = Crop::where('user_id', auth()->id());
        
        if ($filter === 'needs_water') {
            $query->where('water_level', '<', 30);
        } elseif ($filter === 'harvest_soon') {
            $query->whereNotNull('harvest_date')
                  ->whereDate('harvest_date', '<=', now()->addDays(7));
        } elseif ($filter === 'infected') {
            $query->where('status', 'infected');
        }
        
        $crops = $query->latest()->get();
        
        return response()->json($crops);
    }

    // Web: Show create form
    public function create()
    {
        return view('crops.create');
    }

    // Web: Show edit form
    public function edit($id)
    {
        $crop = Crop::findOrFail($id);
        return view('crops.edit', compact('crop'));
    }

    // API & Web: Store new crop
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'planting_date' => 'required|date',
            'harvest_date' => 'nullable|date|after:planting_date',
            'status' => 'nullable|string|in:good,warning,excellent,infected',
            'water_level' => 'nullable|integer|min:0|max:100',
            'image' => 'nullable|image|max:2048', // Allow image file
            'field_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except('image');
        $data['user_id'] = auth()->id();

        // Handle Image Upload
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('crops', 'public');
            $data['image_url'] = '/storage/' . $path;
        }

        $crop = Crop::create($data);

        if ($request->wantsJson()) {
            return response()->json($crop, 201);
        }

        return redirect()->route('crops.index')->with('success', 'تم إضافة المحصول بنجاح');
    }

    // Web: Show single crop
    public function show($id)
    {
        $crop = Crop::findOrFail($id);
        $irrigations = \App\Models\Irrigation::where('crop_id', $id)
            ->orderBy('created_at', 'desc')
            ->take(3)
            ->get();
        return view('crops.show', compact('crop', 'irrigations'));
    }

    // API & Web: Update crop
    public function update(Request $request, $id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|string|max:255',
            'type' => 'sometimes|string|max:255',
            'planting_date' => 'sometimes|date',
            'harvest_date' => 'nullable|date|after:planting_date',
            'status' => 'nullable|string|in:good,warning,excellent,infected',
            'water_level' => 'nullable|integer|min:0|max:100',
            'image_url' => 'nullable|url',
            'field_name' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $crop->update($request->all());

        if ($request->wantsJson()) {
            return response()->json($crop);
        }

        return redirect()->route('crops.index')->with('success', 'تم تحديث المحصول بنجاح');
    }

    // API & Web: Delete crop
    public function destroy(Request $request, $id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $crop->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'تم حذف المحصول بنجاح']);
        }

        return redirect()->route('crops.index')->with('success', 'تم حذف المحصول بنجاح');
    }

    // Irrigate Crop
    public function irrigate(Request $request, $id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        $request->validate([
            'amount' => 'required|numeric|min:1'
        ]);

        $amount = $request->input('amount');

        // Log Irrigation
        \App\Models\Irrigation::create([
            'user_id' => auth()->id(),
            'date' => now(),
            'amount_liters' => $amount, 
            'crop_id' => $crop->id, 
            'notes' => 'Automatic irrigation via dashboard'
        ]);

        // Update Crop
        $crop->update(['water_level' => 100]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم ري المحصول بنجاح',
                'water_level' => 100
            ]);
        }
        
        return back()->with('success', 'تم ري المحصول بنجاح');
    }

    // Harvest Crop
    public function harvest(Request $request, $id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        // 1. Update Crop Status
        $crop->update([
            'status' => 'harvested', 
            'harvest_date' => now()
        ]);

        // 2. Create Inventory Item (Production)
        $inventory = \App\Models\Inventory::create([
            'user_id' => auth()->id(),
            'name' => $crop->name,
            'category' => 'harvest',
            'quantity_value' => rand(50, 200), // Estimated yield between 50-200 units
            'unit' => 'kg', // Default unit
            'description' => 'حصاد من الحقل: ' . ($crop->field_name ?? 'الرئيسي'),
            'image_url' => $crop->image_url, // Transfer image from crop
        ]);

        // 3. Return Success
        if ($request->wantsJson()) {
            return response()->json([
                'success' => true, 
                'message' => 'تم الحصاد ونقل المحصول إلى المستودع بنجاح',
            ]);
        }

        return redirect()->route('crops.index')->with('success', 'تم حصاد المحصول بنجاح ونقله للمخزون');
    }

    // Growth Report
    public function growthReport($id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        return view('crops.growth-report', compact('crop'));
    }

    // Sell to Market (convert crop to product directly)
    public function sellToMarket(Request $request, $id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $validated = $request->validate([
            'price' => 'required|numeric|min:0',
            'quantity' => 'required|numeric|min:1',
            'description' => 'nullable|string',
        ]);

        // Create Product directly from crop
        \App\Models\Product::create([
            'user_id' => auth()->id(),
            'name' => $crop->name,
            'category' => $crop->type ?? 'crops',
            'price' => $validated['price'],
            'stock_quantity' => $validated['quantity'],
            'description' => $validated['description'] ?? 'محصول طازج من المزرعة',
            'image_url' => $crop->image_url ?? 'https://placehold.co/400x300?text=' . urlencode($crop->name),
            'is_market_listed' => true,
        ]);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'تم عرض المحصول في السوق بنجاح'
            ]);
        }

        return redirect()->route('crops.show', $crop->id)->with('success', 'تم عرض المحصول في السوق بنجاح!');
    }

    // Activity Log (all activities)
    public function activityLog($id)
    {
        $crop = Crop::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $irrigations = \App\Models\Irrigation::where('crop_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(20);
        return view('crops.activity-log', compact('crop', 'irrigations'));
    }
}
