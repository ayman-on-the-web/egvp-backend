<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
            'target_hours' => $this->target_hours,
            'points' => $this->points,
            'address' => $this->address,
            'city' => $this->city,
            'country' => $this->country,
            'status' => $this->status,
            'is_approved' => $this->is_approved,
            'event_category_id' => $this->event_category_id,
            'organization_id' => $this->organization_id,
            'event_category' => $this->event_category,
            'organization' => $this->organization,
            'auth_applied' => $this->has_applied(),
            'auth_application' => $this->auth_application(),
            'volunteers' => $this->volunteers(),
        ];
    }
}
