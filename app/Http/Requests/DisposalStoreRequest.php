<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DisposalStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('disposal.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'entity_name' => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'exists:employees,id'],
            'designation' => ['nullable', 'string', 'max:255'],
            'station' => ['nullable', 'string', 'max:255'],
            'fund_cluster_id' => ['required', 'exists:fund_clusters,id'],
            'disposal_date' => ['required', 'date'],
            'disposal_type' => ['required', 'in:sale,transfer,destruction,others'],
            'disposal_type_other' => ['nullable', 'required_if:disposal_type,others', 'string', 'max:255'],
            'or_no' => ['nullable', 'string', 'max:255'],
            'sale_amount' => ['nullable', 'numeric', 'min:0'],
            'appraised_value' => ['nullable', 'numeric', 'min:0'],
            'document_type' => ['required', 'in:IIRUP,RRSEP'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.property_transaction_line_id' => ['nullable', 'exists:property_transaction_lines,id'],
            'lines.*.date_acquired' => ['nullable', 'date'],
            'lines.*.particulars' => ['required', 'string', 'max:1000'],
            'lines.*.property_no' => ['nullable', 'string', 'max:255'],
            'lines.*.quantity' => ['required', 'integer', 'min:1'],
            'lines.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'lines.*.accumulated_depreciation' => ['nullable', 'numeric', 'min:0'],
        ];
    }
}
