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

        $status = "inprogress";
        if ($completionRate == 100) {
            $status = "completed";
        } else if ($completionRate < 100 && $this->deadline_date < now()) {
            // Check if it's less than 100 AND past the deadline.
            $status = 'overdue';
        } else {
            // All other cases (less than 100 and not overdue) are "inprogress"
            $status = 'inprogress';
        }

        return [
            'id' => $this->id,
            'title' => $this->title,
            'desc' => $this->desc,
            'tasks' => $this->whenLoaded('tasks', TaskResource::collection($this->tasks)),
            'status' => $status,
            'completion' => $completionRate,
            'deadlineDate' => $this->deadline_date,
            'createdAt' => $this->created_at,
        ];
    }
}
