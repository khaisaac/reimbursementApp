<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreReimbursementRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'project_id' => ['required', 'exists:projects,id'],
            'type' => ['required', 'in:direct_claim,ca_settlement'],
            'description' => ['nullable', 'string', 'max:1000'],
            'cash_advance_ids' => ['nullable', 'array'],
            'cash_advance_ids.*' => ['exists:cash_advances,id'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.date' => ['required', 'date'],
            'items.*.category' => ['required', 'in:transport,konsumsi,perjadin,akomodasi,entertain,material,ekspedisi,ipl,komunikasi,atk,safety,pemeliharaan'],
            'items.*.description' => ['required', 'string', 'max:500'],
            'items.*.amount' => ['required', 'numeric', 'min:1'],
            'items.*.receipt' => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            if ($this->input('type') === 'ca_settlement') {
                $caIds = $this->input('cash_advance_ids', []);
                if (empty($caIds)) {
                    $validator->errors()->add(
                        'cash_advance_ids',
                        'Please select at least one Cash Advance to settle.'
                    );
                }
            }
        });
    }
}

    {
        $validated = $request->validate([
            'project_no' => ['required', 'string', 'max:20', 'unique:projects,project_no,' . $project->id],
            'project_name' => ['required', 'string', 'max:255'],
            'pic_name' => ['required', 'string', 'max:255'],
            'alt_pic_name' => ['nullable', 'string', 'max:255'],
        ]);

        $project->update($validated);

        return redirect()->route('projects.index')->with('success', 'Project updated successfully.');
    }

    public function destroy(Project $project): RedirectResponse
    {
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully.');
    }
}
