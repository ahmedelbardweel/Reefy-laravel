<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TaskResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'due_date' => $this->due_date,
            'priority' => $this->priority,
            'status' => $this->status,
            'completed' => (bool) $this->completed,
            'crop_id' => $this->crop_id,
            'crop' => new CropResource($this->whenLoaded('crop')),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
