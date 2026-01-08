<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class MessageResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'conversation_id' => $this->conversation_id,
            'sender_id' => $this->user_id, // For API consistency we might still call it sender_id in JSON, but map from user_id
            'content' => $this->content,
            'read_at' => $this->is_read ? $this->updated_at : null, // mapping is_read boolean to timestamp approximation or null
            'is_read' => (bool) $this->is_read,
            'is_me' => $this->user_id == $request->user()->id,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
