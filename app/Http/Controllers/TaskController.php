<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Helpers\ResponseHelper;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Task::query();
        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('desc', 'like', '%' . $request->search . '%');
            });
        }

        $tasks = $query->orderBy('created_at', 'desc')->get();
        return ResponseHelper::success(TaskResource::collection($tasks), 'success', 200);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'assignedDate' => 'required|date',
            'projectId'   => 'required|int|exists:projects,id',
        ]);

        try {
            $task = Task::create([
                'title' => $request->title,
                'desc' => $request->desc,
                'status' => $request->status,
                'assigned_date' => $request->assignedDate,
                'project_id' => $request->projectId
            ]);
            return ResponseHelper::success(new TaskResource($task), 'Task created', 201);
        } catch (\Exception $e) {

            Log::error('Task creation failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to create task', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $request->merge(['id' => $request->route('id')]);
        $request->validate([
            'id' => 'required|integer',
        ]);

        $task = Task::where('id', $request->id)
            ->first();

        if (! $task) {
            return ResponseHelper::success([], "No record found", 404);
        }

        try {
            return  ResponseHelper::success(new TaskResource($task));
        } catch (\Exception $e) {
            Log::error('Failed to fetch task', [
                'task_id' => $task->id,
                'error' => $e->getMessage()
            ]);

            return ResponseHelper::error("Unable to fetch task details.", 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'status' => ['required', Rule::enum(TaskStatus::class)],
            'assignedDate' => 'required|date',
        ]);

        try {
            $task->update([
                'title' => $request->title,
                'desc' => $request->desc,
                'status' => $request->status,
                'assigned_date' => $request->assignedDate,
            ]);
            return ResponseHelper::success(new TaskResource($task), 'Task updated', 202);
        } catch (\Exception $e) {

            Log::error('Task updating failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to update task', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        try {
            $task->delete();
            return ResponseHelper::success(new TaskResource($task), 'Task deleted', 204);
        } catch (\Exception $e) {
            Log::error('Task deletion failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to delete task', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    public function markComplete(Task $task)
    {
        try {
            $task->update(['status' => TaskStatus::Completed->value]);
            return ResponseHelper::success(new TaskResource($task), 'Task completed', 202);
        } catch (\Exception $e) {
            Log::error('Task update failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to update task', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
