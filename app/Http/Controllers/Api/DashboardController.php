<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Crop;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use App\Http\Resources\CropResource;
use App\Http\Resources\TaskResource;

class DashboardController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index(Request $request)
    {
        // Get real weather data
        // Check if lat/lon provided
        $weather = null;
        if ($request->has('lat') && $request->has('lon')) {
            $weather = $this->weatherService->getWeatherByCoordinates($request->lat, $request->lon);
        } else {
             $weather = $this->weatherService->getCurrentWeather();
        }

        $user = $request->user();
        if (!$user) {
            return response()->json(['message' => 'Unauthenticated'], 401);
        }

        // Get crops summary (User specific)
        $crops = $user->crops()->latest()->take(5)->get()->map(function ($crop) {
            $plantingDate = \Carbon\Carbon::parse($crop->planting_date);
            $harvestDate = $crop->expected_harvest_date ? \Carbon\Carbon::parse($crop->expected_harvest_date) : null;
            
            $progress = 0;
            if ($harvestDate) {
                $totalDays = $plantingDate->diffInDays($harvestDate);
                $elapsedDays = $plantingDate->diffInDays(now());
                $progress = $totalDays > 0 ? min(100, round(($elapsedDays / $totalDays) * 100)) : 0;
            }
            $crop->progress = $progress;
            return $crop;
        });

        // Get upcoming tasks
        $tasks = $request->user()->tasks()
            ->where('status', '!=', 'completed')
            ->whereDate('due_date', '>=', now())
            ->orderBy('due_date', 'asc')
            ->take(3)
            ->get();
            
        // Mock water usage for now, or calculate if we have data
        // Sum irrigation amount for user's crops in last 7 days
        // $waterUsage = ...

        if ($request->user()->role === 'client') {
            $recentProducts = \App\Models\Product::where('is_market_listed', true)
                ->with('user')
                ->latest()
                ->take(5)
                ->get();

            return response()->json([
                'weather' => $weather,
                'featured_products' => $recentProducts,
                'cart_summary' => [
                    'item_count' => $request->user()->cart ? $request->user()->cart->items()->count() : 0,
                ],
            ]);
        }

        // --- Farmer Response (Default) ---
        $data = [
            'weather' => $weather,
            'crops_status' => CropResource::collection($crops),
            'upcoming_tasks' => TaskResource::collection($tasks),
            'water_usage' => [
                'daily' => 850, // Placeholder
                'chart_data' => [40, 65, 30, 85, 0] // Placeholder
            ],
        ];

        return response()->json($data);
    }
}
