<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class VolunteerResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'email_verified_at' => $this->email_verified_at,
            'password' => $this->password,
            'remember_token' => $this->remember_token,
            'phone' => $this->phone,
            'address' => $this->address,
            'profile_photo_base64' => $this->profile_photo_base64,
            'identification_type' => $this->identification_type,
            'identification_number' => $this->identification_number,
            'user_type' => $this->user_type,
            'is_active' => $this->is_active,
            'active_until' => $this->active_until,
            'is_approved' => $this->is_approved,
            'approved_at' => $this->approved_at,
            'points' => $this->points,
            'skills' => $this->skills,
            'details' => $this->details,
        ];
    }
}
