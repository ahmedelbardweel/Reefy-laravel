<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Crop;
use App\Models\Irrigation;
use App\Models\Task;
use App\Models\Inventory;
use App\Models\Expense;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        $user_id = auth()->id();
        $period = $request->query('period', 'monthly'); // weekly, monthly, quarterly, yearly

        // Define Date Ranges
        $now = Carbon::now();
        $startDate = $now->copy()->startOfMonth();
        $endDate = $now->copy()->endOfMonth();
        $prevStartDate = $now->copy()->subMonth()->startOfMonth();
        $prevEndDate = $now->copy()->subMonth()->endOfMonth();

        if ($period === 'weekly') {
            $startDate = $now->copy()->startOfWeek();
            $endDate = $now->copy()->endOfWeek();
            $prevStartDate = $now->copy()->subWeek()->startOfWeek();
            $prevEndDate = $now->copy()->subWeek()->endOfWeek();
        } elseif ($period === 'quarterly') {
            $startDate = $now->copy()->firstOfQuarter();
            $endDate = $now->copy()->lastOfQuarter();
            $prevStartDate = $now->copy()->subQuarter()->firstOfQuarter();
            $prevEndDate = $now->copy()->subQuarter()->lastOfQuarter();
        } elseif ($period === 'yearly') {
            $startDate = $now->copy()->startOfYear();
            $endDate = $now->copy()->endOfYear();
            $prevStartDate = $now->copy()->subYear()->startOfYear();
            $prevEndDate = $now->copy()->subYear()->endOfYear();
        }

        // 1. Production Stats (From Harvest Inventory)
        // Note: Assuming 'harvest' category items quantity represents production
        $productionCurrent = Inventory::where('user_id', $user_id)
            ->where('category', 'harvest')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->sum('quantity_value');

        $productionPrev = Inventory::where('user_id', $user_id)
            ->where('category', 'harvest')
            ->whereBetween('created_at', [$prevStartDate, $prevEndDate])
            ->sum('quantity_value');

        $productionGrowth = $productionPrev > 0 
            ? round((($productionCurrent - $productionPrev) / $productionPrev) * 100) 
            : ($productionCurrent > 0 ? 100 : 0);


        // 2. Tasks Stats
        $tasksQuery = Task::where('user_id', $user_id)->whereBetween('due_date', [$startDate, $endDate]);
        $totalTasks = $tasksQuery->count();
        $completedTasks = (clone $tasksQuery)->where('status', 'completed')->count();
        $taskCompletionRate = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        // Task Distribution for Donut
        $taskDistribution = [
            'completed' => (clone $tasksQuery)->where('status', 'completed')->count(),
            'in_progress' => (clone $tasksQuery)->where('status', 'in_progress')->count(),
            'pending' => (clone $tasksQuery)->where('status', 'pending')->count(), 
        ];


        // 3. Crop Performance (Chart Data)
        // Group harvest by crop name
        $cropPerformance = Inventory::where('user_id', $user_id)
            ->where('category', 'harvest')
            ->whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw('name, sum(quantity_value) as total')
            ->groupBy('name')
            ->pluck('total', 'name')
            ->toArray();


        // 4. Water Consumption
        $waterCurrent = Irrigation::where('user_id', $user_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount_liters');

        $waterPrev = Irrigation::where('user_id', $user_id)
            ->whereBetween('date', [$prevStartDate, $prevEndDate])
            ->sum('amount_liters');
            
        $waterChange = $waterPrev > 0
            ? round((($waterCurrent - $waterPrev) / $waterPrev) * 100)
            : ($waterCurrent > 0 ? 100 : 0);


        // 5. Expenses
        $expensesCurrent = Expense::where('user_id', $user_id)
            ->whereBetween('date', [$startDate, $endDate])
            ->sum('amount');
            
        $expensesPrev = Expense::where('user_id', $user_id)
            ->whereBetween('date', [$prevStartDate, $prevEndDate])
            ->sum('amount');

        $expensesChange = $expensesPrev > 0
            ? round((($expensesCurrent - $expensesPrev) / $expensesPrev) * 100)
            : ($expensesCurrent > 0 ? 100 : 0);

        return view('reports.index', compact(
            'period',
            'productionCurrent', 'productionGrowth',
            'taskCompletionRate', 'taskDistribution', 'totalTasks',
            'cropPerformance',
            'waterCurrent', 'waterChange',
            'expensesCurrent', 'expensesChange'
        ));
    }
}
