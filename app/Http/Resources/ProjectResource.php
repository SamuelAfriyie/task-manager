<?php

namespace App\Http\Resources;

use App\Enums\TaskStatus;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */

    public function toArray(Request $request): array
    {
        $totalTasksCount = $this->tasks->count();

        $completedTasksCount = $this->tasks
            ->where('status', TaskStatus::Completed)
            ->count();

        $completionRate = ($totalTasksCount > 0)
            ? round(($completedTasksCount / $totalTasksCount) * 100, 2)
            : 0.0;

        return [
            'id' => $this->id,
            'title' => $this->title,
            'desc' => $this->desc,
            'tasks' => $this->whenLoaded('tasks', TaskResource::collection($this->tasks)),
            'completion' => $completionRate,
            'deadlineDate' => $this->deadline_date,
            'createdAt' => $this->created_at,
        ];
    }
}
