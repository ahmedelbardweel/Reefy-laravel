<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Crop;
use App\Models\Product;
use App\Models\Order;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        
        // --- Activity Stats ---
        $activeCrops = $user->crops()->where('status', '!=', 'harvested')->count();
        $harvestedCrops = $user->crops()->where('status', 'harvested')->count();
        
        // --- Task Performance ---
        $totalTasks = $user->tasks()->count();
        $completedTasks = $user->tasks()->where('status', 'completed')->orWhere('completed', true)->count();
        $completionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $pendingHighPriority = $user->tasks()
            ->where('priority', 'high')
            ->where('status', '!=', 'completed')
            ->count();

        // --- products Stats ---
        $listedProducts = Product::where('user_id', $user->id)->count();
        
        // --- Financial & Yield (Mocking logic based on potential data) ---
        // Projected Yield Efficiency: (Harvested / (Harvested + Active)) * 100 roughly
        $totalCrops = $activeCrops + $harvestedCrops;
        $yieldEfficiency = $totalCrops > 0 ? round(($harvestedCrops / $totalCrops) * 100) : 0;

        // Financial Health (Mock)
        $totalSales = 0; // detailed logic would sum Order items for this user.
        // For now, let's keep it simple or zero if no orders yet.
        
        return response()->json([
            'metrics' => [
                'active_crops_count' => $activeCrops,
                'harvested_crops_count' => $harvestedCrops,
                'products_listed_count' => $listedProducts,
                'task_completion_rate' => $completionRate,
                'yield_efficiency' => $yieldEfficiency,
                'pending_high_priority_tasks' => $pendingHighPriority,
            ],
            'financials' => [
                'total_sales' => $totalSales,
                'currency' => 'SAR' // or default
            ],
            'recent_activities' => [] // Could fetch from logs if a Log model existed
        ]);
    }
}
