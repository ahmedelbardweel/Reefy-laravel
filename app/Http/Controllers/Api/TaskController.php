<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Http\Resources\TaskResource;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()
            ->with('crop')
            ->orderBy('due_date', 'asc')
            ->get();
            
        return TaskResource::collection($tasks);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'required|date',
            'priority' => 'required|in:low,medium,high',
            'crop_id' => 'nullable|exists:crops,id',
        ]);

        $task = $request->user()->tasks()->create($validated);

        return new TaskResource($task);
    }

    public function show(Request $request, Task $task)
    {
        $this->authorize('view', $task);
        return new TaskResource($task);
    }

    public function update(Request $request, Task $task)
    {
        $this->authorize('update', $task);

        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'due_date' => 'sometimes|date',
            'priority' => 'sometimes|in:low,medium,high',
            'crop_id' => 'nullable|exists:crops,id',
            'completed' => 'boolean'
        ]);

        $task->update($validated);

        return new TaskResource($task);
    }

    public function destroy(Request $request, Task $task)
    {
        $this->authorize('delete', $task);
        $task->delete();

        return response()->json(['message' => 'Task deleted successfully']);
    }
    
    public function toggleComplete(Request $request, Task $task)
    {
        $this->authorize('update', $task);
        $task->update(['completed' => !$task->completed]);
        return new TaskResource($task);
    }
}
