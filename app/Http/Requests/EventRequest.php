<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Contracts\Validation\Validator;

class EventRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'target_hours' => 'integer',
            'points' => 'integer',
            'address' => 'string',
            'city' => 'string',
            'country' => 'string',
            'is_approved' => 'integer',
            'event_category_id' => 'integer',
            'organization_id' => 'integer',
            'image_base64' => 'nullable|string',
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
                'message' => __('Event validation failed'),
                'errors' => collect($validator->errors())->flatten()->toArray(),
            ], 422)
        );
    }
}
