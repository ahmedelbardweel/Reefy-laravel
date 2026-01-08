<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class CropResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'area' => $this->area,
            'planting_date' => $this->planting_date,
            'expected_harvest_date' => $this->expected_harvest_date,
            'description' => $this->description,
            'status' => $this->status,
            'image' => $this->image_url ? asset($this->image_url) : null,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'irrigations' => IrrigationResource::collection($this->whenLoaded('irrigations')),
            'tasks' => TaskResource::collection($this->whenLoaded('tasks')),
            // 'harvests' => HarvestResource::collection($this->whenLoaded('harvests')), // Assuming Harvest model exists or is part of crop/inventory
        ];
    }
}
