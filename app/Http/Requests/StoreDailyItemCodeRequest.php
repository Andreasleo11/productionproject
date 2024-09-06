<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreDailyItemCodeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        // Set to true if authorization is not needed for this request
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
            'schedule_date' => 'required|date',
            'machine_id' => 'required|exists:users,id',
            'item_codes' => 'required|array|min:3',
            'item_codes.*' => 'required|string|distinct',
            'quantities' => 'required|array|min:3',
            'quantities.*' => 'required|integer|min:1',
            'start_times' => 'required|array|min:3',
            'start_times.*' => 'required|date_format:H:i',
            'end_times' => 'required|array|min:3',
            'end_times.*' => 'required|date_format:H:i',
            'shifts' => 'required|array|min:3',
            'shifts.*' => 'required|in:1,2,3|distinct',

        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'schedule_date.required' => 'The schedule date is required.',
            'schedule_date.date' => 'The schedule date must be a valid date.',
            'machine_id.required' => 'Please select a machine.',
            'machine_id.exists' => 'The selected machine does not exist.',
            'item_codes.required' => 'Item codes are required for all shifts.',
            'item_codes.array' => 'Item codes must be in an array format.',
            'item_codes.min' => 'You must provide at least 3 item codes.',
            'item_codes.*.required' => 'Each shift must have an item code.',
            'item_codes.*.string' => 'Item codes must be valid strings.',
            'item_codes.*.distinct' => 'Item codes must be unique across shifts.',
            'quantities.required' => 'Quantities are required for all shifts.',
            'quantities.array' => 'Quantities must be in an array format.',
            'quantities.min' => 'You must provide quantities for at least 3 shifts.',
            'quantities.*.required' => 'Each shift must have a quantity.',
            'quantities.*.integer' => 'Quantities must be numeric.',
            'quantities.*.min' => 'Quantities must be at least 1.',
            'start_times.required' => 'Start times are required for all shifts.',
            'start_times.array' => 'Start times must be in an array format.',
            'start_times.min' => 'You must provide start times for at least 3 shifts.',
            'start_times.*.required' => 'Each shift must have a start time.',
            'start_times.*.date_format' => 'Start times must be in the format HH:mm.',
            'end_times.required' => 'End times are required for all shifts.',
            'end_times.array' => 'End times must be in an array format.',
            'end_times.min' => 'You must provide end times for at least 3 shifts.',
            'end_times.*.required' => 'Each shift must have an end time.',
            'end_times.*.date_format' => 'End times must be in the format HH:mm.',
            'end_times.*.after' => 'End time must be after the start time for each shift.',
            'shifts.required' => 'Shift numbers are required.',
            'shifts.array' => 'Shifts must be in an array format.',
            'shifts.min' => 'You must provide shift numbers for at least 3 shifts.',
            'shifts.*.required' => 'Each shift must have a shift number.',
            'shifts.*.in' => 'Shift numbers must be 1, 2, or 3.',
            'shifts.*.distinct' => 'Shift numbers must be unique across shifts.',
        ];
    }
}
