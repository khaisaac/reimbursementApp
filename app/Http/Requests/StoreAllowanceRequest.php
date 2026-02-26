<?php

namespace App\Http\Requests;

use App\Models\Attendance;
use Illuminate\Foundation\Http\FormRequest;

class StoreAllowanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'date' => ['required', 'date'],
            'description' => ['nullable', 'string', 'max:1000'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999'],
            'receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $hasAttendance = Attendance::where('user_id', $this->user()->id)
                ->where('project_id', $this->input('project_id'))
                ->whereDate('date', $this->input('date'))
                ->exists();

            if (! $hasAttendance) {
                $validator->errors()->add(
                    'date',
                    'No attendance record found for this date and project. An allowance claim is only valid with a matching attendance entry.'
                );
            }
        });
    }
}
