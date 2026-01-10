<?php


namespace App\Http\Controllers;

use App\Models\Crop;
use App\Models\Task;
use App\Services\WeatherService;
use Illuminate\Http\Request;
use App\Models\Irrigation;

class DashboardController extends Controller
{
    protected $weatherService;

    public function __construct(WeatherService $weatherService)
    {
        $this->weatherService = $weatherService;
    }

    public function index()
    {
        $user = auth()->user();

        // --- Admin Tips ---
        $tips = \App\Models\Post::where('type', 'tip')->latest()->take(3)->get();


        // --- Farmer Dashboard Logic ---

        // Get real weather data
        $weather = $this->weatherService->getCurrentWeather();
        
        // Get latest crops for authenticated user (max 3 for dashboard)
        // Get latest crops for authenticated user (max 3 for dashboard), excluding harvested
        $crops = Crop::where('user_id', auth()->id())
            ->where('status', '!=', 'harvested')
            ->latest()
            ->take(3)
            ->get();
        
        // Calculate crop progress
        // Calculate crop progress
        $crops = $crops->map(function ($crop) {
            // Ensure planting_date is a Carbon instance, default to now if missing/null
            $plantingDate = $crop->planting_date ? \Carbon\Carbon::parse($crop->planting_date) : now();
            
            // Handle harvest_date carefully
            $harvestDate = $crop->harvest_date ? \Carbon\Carbon::parse($crop->harvest_date) : null;
            
            if ($harvestDate) {
                // Determine total duration
                $totalDays = $plantingDate->diffInDays($harvestDate) ?: 1; // Prevent division by zero
                $elapsedDays = $plantingDate->diffInDays(now());
                
                // Calculate progress safely
                $crop->progress = min(100, max(0, round(($elapsedDays / $totalDays) * 100)));
                
                // Days remaining
                $crop->days_to_harvest = max(0, $harvestDate->diffInDays(now()));
            } else {
                $crop->progress = $crop->water_level ?? 0; // Fallback
                $crop->days_to_harvest = null;
            }
            
            return $crop;
        });
        
        // Get upcoming tasks
        $tasks = Task::where('user_id', auth()->id())
            ->where('status', '!=', 'completed')
            ->orderBy('due_date', 'asc')
            ->take(3)
            ->get();

        // Get unread messages count
        $unreadCount = \App\Models\Message::whereHas('conversation', function($q) {
            $q->where('sender_id', auth()->id())
              ->orWhere('receiver_id', auth()->id());
        })
        ->where('user_id', '!=', auth()->id()) // Message from other user
        ->where('is_read', false)
        ->count();

        // Actually, simpler logic if we trust the conversation participation:
        // A message is 'unread' for ME if I am NOT the sender.
        // But simply: Message where I am receiver??
        // Our message model has 'user_id' (sender). It doesn't have 'receiver_id' directly on Message table, only on Conversation or inferred.
        // Wait, Message table structure: conversation_id, user_id (sender), content.
        // So to find messages meant for me, I need to find conversations I'm in, then find messages NOT sent by me in those convos.
        
        $unreadCount = \App\Models\Message::whereHas('conversation', function($query) {
            $query->where('sender_id', auth()->id())
                  ->orWhere('receiver_id', auth()->id());
        })
        ->where('user_id', '!=', auth()->id())
        ->where('is_read', false)
        ->count();

        // Get water consumption for the last 7 days
        $chartData = $this->getWaterConsumptionData();

        return view('dashboard', compact('weather', 'crops', 'tasks', 'chartData', 'unreadCount', 'tips'));
    }

    private function getWaterConsumptionData()
    {
        $startDate = now()->subDays(6)->startOfDay();
        $waterConsumption = Irrigation::where('user_id', auth()->id())
            ->where('date', '>=', $startDate)
            ->selectRaw('date, sum(amount_liters) as total_amount')
            ->groupBy('date')
            ->get()
            ->keyBy(function($item) {
                return $item->date->format('Y-m-d');
            });

        $chartData = [];
        $days = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
        $daysArabic = ['أحد', 'إثنين', 'ثلاثاء', 'أربعاء', 'خميس', 'جمعة', 'سبت'];

        for ($i = 6; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $dateString = $date->format('Y-m-d');
            $dayName = $daysArabic[$date->dayOfWeek]; // Simplified Arabic mapping
            
            $amount = isset($waterConsumption[$dateString]) ? $waterConsumption[$dateString]->total_amount : 0;
            
            // Calculate increase from previous day? 
            // Let's just pass the amount. The visual "indicator" is the bar height + tooltip.
            
            $chartData[] = [
                'day' => $dayName,
                'amount' => $amount,
                'height_percentage' => $amount > 0 ? min(($amount / 500) * 100, 100) : 0, 
                'is_today' => $i === 0
            ];
        }

        return $chartData;
    }
}
