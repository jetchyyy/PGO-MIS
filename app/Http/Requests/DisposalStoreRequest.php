<?php

namespace App\Http\Requests;

use App\Models\Disposal;
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
            'item_disposal_condition' => ['required', 'in:unserviceable,no_longer_needed,obsolete,others'],
            'item_disposal_condition_other' => ['nullable', 'required_if:item_disposal_condition,others', 'string', 'max:255'],
            'disposal_method' => ['required', 'in:public_auction,destruction,throwing,others'],
            'disposal_method_other' => ['nullable', 'required_if:disposal_method,others', 'string', 'max:255'],
            'or_no' => ['nullable', 'string', 'max:255'],
            'sale_amount' => ['nullable', 'numeric', 'min:0'],
            'appraised_value' => ['nullable', 'numeric', 'min:0'],
            'document_type' => ['nullable', 'in:'.implode(',', [
                Disposal::DOCUMENT_TYPE_IIRUP,
                Disposal::DOCUMENT_TYPE_IIRUSP,
                Disposal::DOCUMENT_TYPE_RRSEP,
            ])],
            'lines' => ['required', 'array', 'min:1'],
            'lines.*.item_id' => ['nullable', 'exists:items,id'],
            'lines.*.inventory_item_id' => ['nullable', 'exists:inventory_items,id'],
            'lines.*.property_transaction_line_id' => ['nullable', 'exists:property_transaction_lines,id'],
            'lines.*.date_acquired' => ['nullable', 'date'],
            'lines.*.particulars' => ['required', 'string', 'max:1000'],
            'lines.*.property_no' => ['nullable', 'string', 'max:255'],
            'lines.*.quantity' => ['required', 'integer', 'min:1'],
            'lines.*.unit' => ['nullable', 'string', 'max:100'],
            'lines.*.unit_cost' => ['required', 'numeric', 'min:0'],
            'lines.*.appraised_value' => ['nullable', 'numeric', 'min:0'],
            'lines.*.use_formula_depreciation' => ['nullable', 'boolean'],
            'lines.*.accumulated_depreciation' => ['nullable', 'numeric', 'min:0'],
            'lines.*.remarks' => ['nullable', 'string', 'max:255'],
        ];
    }
}
