<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMealRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Use the MealPolicy's create method
        return $this->user()->can('create', \App\Models\Meal::class);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'available_portions' => 'required|integer|min:1',
            'expires_at' => 'required|date|after:now',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The meal name is required.',
            'name.max' => 'The meal name cannot be longer than 255 characters.',
            'available_portions.required' => 'Please specify the number of available portions.',
            'available_portions.min' => 'There must be at least 1 portion available.',
            'expires_at.required' => 'Please specify when the meal expires.',
            'expires_at.after' => 'The expiration date must be in the future.',
        ];
    }
}
