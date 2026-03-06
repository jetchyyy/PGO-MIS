<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IssuanceStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('issuance.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'entity_name' => ['required', 'string', 'max:255'],
            'office_id' => ['required', 'exists:offices,id'],
            'employee_id' => ['required', 'exists:employees,id'],
            'fund_cluster_id' => ['required', 'exists:fund_clusters,id'],
            'transaction_date' => ['required', 'date'],
            'reference_no' => ['nullable', 'string', 'max:255'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.quantity' => ['required', 'integer', 'min:1'],
            'lines.*.unit' => ['required', 'string', 'max:100'],
            'lines.*.description' => ['required', 'string', 'max:1000'],
            'lines.*.property_no' => ['nullable', 'string', 'max:255'],
            'lines.*.date_acquired' => ['nullable', 'date'],
            'lines.*.unit_cost' => ['required', 'numeric', 'min:0.01'],
            'lines.*.estimated_useful_life' => ['nullable', 'string', 'max:100'],
            'lines.*.remarks' => ['nullable', 'string', 'max:500'],
        ];
    }
}
