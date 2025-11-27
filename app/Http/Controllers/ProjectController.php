<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;
use App\Helpers\ResponseHelper;
use App\Models\Project;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $id = auth('sanctum')->id();
        $query = Project::with(['tasks'])->where("user_id", '=', $id);
        // Search filter
        if ($request->has('search') && $request->search) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                    ->orWhere('desc', 'like', '%' . $request->search . '%');
            });
        }

        $projects = $query->orderBy('created_at', 'desc')->get();
        return ResponseHelper::success($projects, 'success', 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'deadlineDate' => 'required|date',
        ]);

        try {
            $project = Project::create([
                'title' => $request->title,
                'desc' => $request->desc,
                'deadline_date' => $request->deadlineDate,
                'user_id' => auth('sanctum')->id()
            ]);
            return ResponseHelper::success($project, 'Project created', 201);
        } catch (\Exception $e) {

            Log::error('Project creation failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to create project', 500, [
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

        $project = Project::where('id', $request->id)
            ->where('user_id', auth('sanctum')->id())
            ->first();

        if (! $project) {
            return ResponseHelper::success([], "No record found", 404);
        }

        try {
            $project->load([
                'tasks' => function ($query) {
                    $query->where("user_id", '=', auth('sanctum')->id());
                    $query->orderBy('assigned_date', 'desc');
                },
            ]);
            return ResponseHelper::success($project);
        } catch (\Exception $e) {
            Log::error('Failed to fetch project', [
                'project_id' => $project->id,
                'error' => $e->getMessage()
            ]);

            return ResponseHelper::error("Unable to fetch project details.", 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'desc' => 'required|string',
            'deadlineDate' => 'required|date',
        ]);

        try {
            $project->update([
                'title' => $request->title,
                'desc' => $request->desc,
                'deadline_date' => $request->deadlineDate,
            ]);
            return ResponseHelper::success($project, 'Project updated', 202);
        } catch (\Exception $e) {

            Log::error('Project updation failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to update project', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        try {
            $project->delete();
            return ResponseHelper::success($project, 'Project deleted', 204);
        } catch (\Exception $e) {
            Log::error('Project deletion failed', [
                'error' => $e->getMessage(),
            ]);
            return ResponseHelper::error('Failed to delete project', 500, [
                'exception' => $e->getMessage()
            ]);
        }
    }
}
