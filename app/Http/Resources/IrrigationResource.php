<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class IrrigationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'crop_id' => $this->crop_id,
            'crop_name' => $this->whenLoaded('crop', function() {
                 return $this->crop->name;
            }),
            'date' => $this->date,
            'amount' => $this->amount,
            'method' => $this->method,
            'notes' => $this->notes,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
