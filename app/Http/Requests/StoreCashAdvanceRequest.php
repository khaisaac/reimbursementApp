<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreCashAdvanceRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'description' => ['required', 'string', 'max:1000'],
            'amount' => ['required', 'numeric', 'min:1', 'max:999999999'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $user = $this->user();
            $amount = (float) $this->input('amount', 0);

            if (! $user->canCreateCashAdvance($amount)) {
                $outstanding = number_format($user->totalOutstandingCashAdvance(), 0, ',', '.');
                $validator->errors()->add(
                    'amount',
                    "Cannot create Cash Advance. Your total outstanding (Rp {$outstanding}) plus this request would exceed the Rp 15,000,000 limit."
                );
            }
        });
    }

    public function messages(): array
    {
        return [
            'amount.required' => 'The amount is required.',
            'amount.min' => 'The amount must be at least Rp 1.',
        ];
    }
}
