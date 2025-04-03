<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TravelOrderResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'requester_name' => $this->requester_name,
            'destination' => $this->destination,
            'start_date' => $this->start_date->format('Y-m-d'),
            'end_date' => $this->end_date->format('Y-m-d'),
            'status' => $this->status,
            'created_at' => $this->created_at,
            'can_cancel' => $this->when(
                $this->status === 'aprovado',
                $this->canBeCancelled()
            ),
            'user' => $this->whenLoaded('user'),
        ];
    }
}
