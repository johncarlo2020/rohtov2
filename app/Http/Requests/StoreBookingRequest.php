<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreBookingRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'date' => ['required', 'date_format:Y-m-d'],
            'slot_id' => ['required', 'integer', 'exists:booking_slots,id'],
            'customer_name' => ['required', 'string', 'max:255'],
            'customer_email' => ['required', 'email', 'max:255'],
            'customer_phone' => ['required', 'string', 'max:50'],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'date.required' => 'Please select a date for your appointment.',
            'date.date_format' => 'Invalid date format.',
            'slot_id.required' => 'Please select a time slot.',
            'slot_id.exists' => 'Selected time slot does not exist.',
            'customer_name.required' => 'Full name is required.',
            'customer_email.required' => 'Email address is required.',
            'customer_email.email' => 'Please enter a valid email address.',
            'customer_phone.required' => 'Phone number is required.',
        ];
    }
}
