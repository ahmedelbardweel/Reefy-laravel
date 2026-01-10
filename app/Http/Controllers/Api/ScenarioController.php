<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Crop;

class ScenarioController extends Controller
{
    /**
     * Analyze conditions and provide smart advice.
     */
    public function analyze(Request $request)
    {
        // Mock Input: { "crop_type": "tomato", "humidity": 80, "temperature": 30, "stage": "flowering" }
        // In real world, this might come from sensors or weather API.
        
        $cropType = $request->input('crop_type');
        $humidity = $request->input('humidity');
        $temperature = $request->input('temperature');
        $stage = $request->input('stage'); // e.g., seedling, vegetative, flowering, fruiting
        
        $advice = [];
        $alerts = [];

        // Basic "Smart" Logic Engine
        
        // 1. Humidity Checks
        if ($humidity > 70) {
            $alerts[] = [
                'level' => 'high',
                'title' => 'High Humidity Risk',
                'description' => 'High humidity increases risk of fungal diseases like Late Blight.'
            ];
            
            if ($cropType == 'tomato' || $cropType == 'potato') {
                $advice[] = [
                    'type' => 'action',
                    'content' => 'Consider applying preventive fungicides. Ensure good air circulation.'
                ];
            }
        }

        // 2. Temperature Checks
        if ($temperature > 35) {
             $alerts[] = [
                'level' => 'warning',
                'title' => 'Heat Stress Warning',
                'description' => 'Temperatures above 35°C can cause flower drop in many crops.'
            ];
            $advice[] = [
                'type' => 'irrigation',
                'content' => 'Increase irrigation frequency. Consider shade netting if possible.'
            ];
        }

        // 3. Stage Specific Advice
        if ($stage == 'flowering' && $temperature < 15) {
             $advice[] = [
                'type' => 'observation',
                'content' => 'Low temperatures during flowering may reduce fruit set.'
            ];
        }

        // If no specific alerts, give general good practice
        if (empty($alerts) && empty($advice)) {
            $advice[] = [
                'type' => 'general',
                'content' => 'Conditions look stable. Monitor soil moisture regularly.'
            ];
        }

        return response()->json([
            'status' => 'success',
            'analysis' => [
                'alerts' => $alerts,
                'advice' => $advice,
                'timestamp' => now()
            ]
        ]);
    }
}
