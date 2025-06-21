<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class VolunteerRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $createRules = [
            'name' => 'required|string',
            'email' => 'required|string|email',
            'password' => 'required|string|min:8',
            'phone' => 'required|string',
            'address' => 'required|string',
            'profile_photo_base64' => 'nullable|string',
            'identification_type' => 'required|string|max:255|in:' . User::IDENTIFICATION_NATIONAL_ID,
            'identification_number' => 'required|string|max:255',
            'user_type' => 'required|string|max:255|in:' . User::TYPE_VOLUNTEER,
            'is_active' => 'required|boolean',
            'active_until' => 'nullable|date',
            'is_approved' => 'required|boolean',
            'approved_at' => 'nullable|date',
            'points' => 'nullable|numeric',
            'skills' => 'nullable|string',
            'details' => 'nullable|string',
        ];
    
        $updateRules = [
            'name' => 'nullable|string',
            'email' => 'nullable|string|email',
            'password' => 'nullable|string|min:8',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'profile_photo_base64' => 'nullable|string',
            'identification_type' => 'nullable|string|max:255|in:' . User::IDENTIFICATION_NATIONAL_ID,
            'identification_number' => 'nullable|string|max:255',
            'user_type' => 'nullable|string|max:255|in:' . User::TYPE_VOLUNTEER,
            'is_active' => 'nullable|boolean',
            'active_until' => 'nullable|date',
            'is_approved' => 'nullable|boolean',
            'approved_at' => 'nullable|date',
            'points' => 'nullable|numeric',
            'skills' => 'nullable|string',
            'details' => 'nullable|string',
        ];
    
        if ($this->method() === 'POST') {
            return $createRules;
        } else {
            return $updateRules;
        }
    }

    /**
     * Handle a failed validation attempt.
     *
     * @param  \Illuminate\Contracts\Validation\Validator  $validator
     * @return void
     *
     * @throws \Illuminate\Http\Exceptions\HttpResponseException
     */
    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(
            response()->json([
                'success' => false,
                'message' => 'Volunteer validation failed',
                'errors' => collect($validator->errors())->flatten()->toArray(),
            ], 422)
        );
    }
}
