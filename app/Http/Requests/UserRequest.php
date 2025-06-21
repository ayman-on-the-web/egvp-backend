<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class UserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string',
            'email' => 'string',
            'email_verified_at' => 'date',
            'password' => 'string',
            'remember_token' => 'string',
            'phone' => 'string',
            'address' => 'string',
            'profile_photo_base64' => 'string',
            'identification_type' => 'string',
            'identification_number' => 'string',
            'user_type' => 'string',
            'is_active' => 'boolean',
            'active_until' => 'date',
            'is_approved' => 'boolean',
            'approved_at' => 'date',
            'points' => 'numeric',
            'skills' => 'string',
            'details' => 'string',
        ];
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
                'message' => 'User validation failed',
                'errors' => collect($validator->errors())->flatten()->toArray(),
            ], 422)
        );
    }
}
