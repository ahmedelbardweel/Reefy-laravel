<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'seller_id' => $this->user_id, // Assuming user_id is seller
            'seller' => new UserResource($this->whenLoaded('user')),
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'image' => $this->image ? asset('storage/' . $this->image) : null,
            'category' => $this->category,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
