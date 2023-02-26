<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateInvoiceRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'invoice_number' => 'numeric',
            'invoice_Date' => 'date',
            'Due_date' => 'date',
            'Amount_collection' => 'numeric',
            'Amount_Commission' => 'numeric',
            'Discount' => 'numeric',
            'Value_VAT' => '',
            'Rate_VAT' => '',
            'Total' => '',
        ];
    }
}
