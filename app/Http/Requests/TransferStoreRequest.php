<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TransferStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('transfer.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'entity_name' => ['required', 'string', 'max:255'],
            'from_employee_id' => ['required', 'different:to_employee_id', 'exists:employees,id'],
            'to_employee_id' => ['required', 'exists:employees,id'],
            'fund_cluster_id' => ['required', 'exists:fund_clusters,id'],
            'transfer_type' => ['required', 'in:donation,reassignment_recall,relocate,retirement_resignation,others'],
            'transfer_type_other' => ['nullable', 'required_if:transfer_type,others', 'string', 'max:255'],
            'transfer_date' => ['required', 'date'],
            'document_type' => ['required', 'in:PTR,ITR'],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.property_transaction_line_id' => ['nullable', 'exists:property_transaction_lines,id'],
            'lines.*.date_acquired' => ['nullable', 'date'],
            'lines.*.reference_no' => ['required', 'string', 'max:255'],
            'lines.*.quantity' => ['required', 'integer', 'min:1'],
            'lines.*.unit' => ['required', 'string', 'max:100'],
            'lines.*.description' => ['required', 'string', 'max:1000'],
            'lines.*.amount' => ['required', 'numeric', 'min:0'],
            'lines.*.condition' => ['required', 'string', 'max:255'],
        ];
    }
}
