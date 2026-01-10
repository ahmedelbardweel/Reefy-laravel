<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Crop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    /**
     * Display task list with filters
     */
    public function index(Request $request)
    {
        $filter = $request->query('filter', 'today');
        
        $query = Task::where('user_id', auth()->id())->with('crop');
        
        // Get all tasks for counts
        $allTasks = Task::where('user_id', auth()->id())->get();
        $todayTasks = Task::where('user_id', auth()->id())->dueToday()->where('status', '!=', 'completed')->get();
        $tomorrowTasks = Task::where('user_id', auth()->id())->dueTomorrow()->where('status', '!=', 'completed')->count();
        $overdueTasks = Task::where('user_id', auth()->id())->overdue()->get();
        
        // Filter by date/status
        switch ($filter) {
            case 'today':
                $query->dueToday()->where('status', '!=', 'completed');
                break;
            case 'tomorrow':
                $query->dueTomorrow()->where('status', '!=', 'completed');
                break;
            case 'week':
                $query->dueThisWeek()->where('status', '!=', 'completed');
                break;
            case 'overdue':
                $query->overdue(); // Scope already handles status check
                break;
            case 'completed':
                $query->where('status', 'completed');
                break;
        }
        
        $tasks = $query->orderBy('due_date', 'asc')->get();
        
        return view('tasks.index', compact('tasks', 'filter', 'allTasks', 'todayTasks', 'tomorrowTasks', 'overdueTasks'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        $crops = Crop::where('user_id', auth()->id())->get();
        return view('tasks.create', compact('crops'));
    }

    /**
     * Store new task
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'due_time' => 'nullable|date_format:H:i',
            'priority' => 'required|in:low,medium,high',
            'category' => 'required|in:irrigation,fertilization,harvest,inspection,other',
            'crop_id' => 'nullable|exists:crops,id',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->all();
        
        // Combine date and time if time is provided
        if ($request->has('due_time') && $request->due_time) {
            $data['due_date'] = $request->due_date . ' ' . $request->due_time . ':00';
        } else {
             // Default to start of day or keep as is (time is 00:00:00)
             $data['due_date'] = $request->due_date . ' 09:00:00'; // Default to 9 AM if no time specified
        }
        
        // Set reminder_date same as due_date for now, or 1 hour before? 
        // User asked "How do I determine the time that the task should alert me".
        // The due_date IS the alert/due time.
        $data['reminder_date'] = $data['due_date'];

        $data['user_id'] = auth()->id();
        
        $task = Task::create($data);

        // Schedule notification ONLY if reminder_date is in the future
        if ($task->reminder_date && $task->reminder_date->isFuture()) {
            $delay = now()->diffInSeconds($task->reminder_date, false);
            \App\Jobs\SendTaskNotification::dispatch($task)->delay($delay);
        }

        if ($request->wantsJson()) {
            return response()->json($task, 201);
        }

        return redirect()->route('tasks.index')->with('success', 'تم إضافة المهمة بنجاح');
    }

    /**
     * Toggle task completion
     */
    public function toggleComplete(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        
        if ($task->status === 'completed') {
            $task->markAsPending();
        } else {
            $task->markAsCompleted();
        }

        if ($request->wantsJson()) {
            return response()->json($task);
        }

        return back()->with('success', 'تم تحديث حالة المهمة');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $crops = Crop::where('user_id', auth()->id())->get();
        return view('tasks.edit', compact('task', 'crops'));
    }

    /**
     * Update task
     */
    public function update(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date',
            'priority' => 'sometimes|in:low,medium,high',
            'status' => 'sometimes|in:pending,in_progress,completed',
            'category' => 'sometimes|in:irrigation,fertilization,harvest,inspection,other',
            'crop_id' => 'nullable|exists:crops,id',
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }
            return back()->withErrors($validator)->withInput();
        }

        $data = $request->except(['due_time']); // Exclude auxiliary field

        // Handle date/time merge if present
        if ($request->filled('due_date')) {
             if(str_contains($request->due_date, 'T')) {
                 // It's from datetime-local input (from Edit page)
                 $data['due_date'] = str_replace('T', ' ', $request->due_date) . ':00';
             } elseif ($request->has('due_time') && $request->due_time) {
                 // It's from split inputs
                 $data['due_date'] = $request->due_date . ' ' . $request->due_time . ':00';
             } elseif (!str_contains($request->due_date, ':')) {
                 // Just a date was sent? Default time
                 $data['due_date'] = $request->due_date . ' 09:00:00';
             }
             
             // Update reminder too
             $data['reminder_date'] = $data['due_date'];
        }

        $task->update($data);

        if ($request->wantsJson()) {
            return response()->json($task);
        }

        return redirect()->route('tasks.index')->with('success', 'تم تحديث المهمة بنجاح');
    }

    /**
     * Show task details
     */
    public function show($id)
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->with('crop')->firstOrFail();
        return view('tasks.show', compact('task'));
    }

    /**
     * Delete task
     */
    public function destroy(Request $request, $id)
    {
        $task = Task::where('id', $id)->where('user_id', auth()->id())->firstOrFail();
        $task->delete();

        if ($request->wantsJson()) {
            return response()->json(['message' => 'تم حذف المهمة بنجاح']);
        }

        return redirect()->route('tasks.index')->with('success', 'تم حذف المهمة بنجاح');
    }
}
