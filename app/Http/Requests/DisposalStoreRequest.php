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
            'property_return_id' => ['required', 'exists:property_returns,id'],
            'disposal_date' => ['required', 'date'],
            'item_disposal_condition' => ['required', 'in:unserviceable,no_longer_needed,obsolete,others'],
            'item_disposal_condition_other' => ['nullable', 'required_if:item_disposal_condition,others', 'string', 'max:255'],
            'disposal_method' => ['required', 'in:public_auction,destruction,throwing,others'],
            'disposal_method_other' => ['nullable', 'required_if:disposal_method,others', 'string', 'max:255'],
            'or_no' => ['nullable', 'string', 'max:255'],
            'sale_amount' => ['nullable', 'numeric', 'min:0'],
            'appraised_value' => ['nullable', 'numeric', 'min:0'],
            'document_type' => ['nullable', 'in:'.implode(',', Disposal::documentTypes())],
        ];
    }
}
