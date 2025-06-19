<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ApplicationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'volunteer_id' => $this->volunteer_id,
            'event_id' => $this->event_id,
            'voulnteer' => $this->volunteer,
            'event' => $this->event,
            'is_approved' => $this->is_approved,
            'status' => $this->status,
            'decision_at' => $this->decision_at,
        ];
    }
}
