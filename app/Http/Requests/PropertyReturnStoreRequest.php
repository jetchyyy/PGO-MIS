<?php

namespace App\Http\Requests;

use App\Models\PropertyReturn;
use Illuminate\Foundation\Http\FormRequest;

class PropertyReturnStoreRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->can('return.manage') ?? false;
    }

    public function rules(): array
    {
        return [
            'entity_name' => ['required', 'string', 'max:255'],
            'employee_id' => ['required', 'exists:employees,id'],
            'designation' => ['nullable', 'string', 'max:255'],
            'station' => ['nullable', 'string', 'max:255'],
            'fund_cluster_id' => ['required', 'exists:fund_clusters,id'],
            'return_date' => ['required', 'date'],
            'return_reason' => ['nullable', 'string', 'max:255'],
            'document_type' => ['nullable', 'in:'.implode(',', [PropertyReturn::DOCUMENT_TYPE_PRS, PropertyReturn::DOCUMENT_TYPE_RRSP])],
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
            'lines.*.condition' => ['nullable', 'string', 'max:255'],
            'lines.*.remarks' => ['nullable', 'string', 'max:255'],
        ];
    }
}
