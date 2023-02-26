<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreInvoiceRequest extends FormRequest
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
            'invoice_number' => 'required|numeric',
            'invoice_Date' => 'required|date',
            'Due_date' => 'required|date',
            'product' => 'required',
            'section_id' => 'required',
            'customer_id' => 'required',
            'Amount_collection' => 'required|numeric',
            'Amount_Commission' => 'required|numeric',
            'Discount' => 'required',
            'Value_VAT' => 'required',
            'Rate_VAT' => 'required',
            'Total' => 'required',

        ];
    }
    public function messages()
    {
        return [
            'customer.required' =>'يرجي ادخال العميل ',
            'invoice_number.required' =>'يرجي ادخال رقم الفاتوره',
            'invoice_Date.required' =>'يرجي ادخال تاريخ الفاتوره',
            'Due_date.required' =>'يرجي ادخال تاريخ الاستحقاق',
            'product.required' =>'يرجي ادخال اسم المنتج',
            'section_id.required' =>'يرجي ادخال القسم ',
            'Amount_collection.required' =>'يرجي ادخال مبلغ التحصيل ',
            'Amount_Commission.required' =>'يرجي ادخال مبلغ العمولة ',
            'Discount.required' =>'يرجي ادخال الخصم',
            'Value_VAT.required' =>'يرجي ادخال قيمة ضريبة القيمة المضافة ',
            'Rate_VAT.required' =>'يرجي ادخال نسبة ضريبة القيمة المضافة ',
            'Total.required' =>'يرجي ادخال الاجمالي ',
            'file_name.required' =>'يرجي ادخال الملف ',
            'invoice_number.integer' =>'رقم الفاتوره يجب ان يكون رقم',
            'invoice_Date.date' =>'تاريخ الفاتوره يجب ان يكون تاريخ',
            'Due_date.date' =>'تاريخ الاستحقاق يجب ان يكون تاريخ',
            'Amount_collection.integer' =>'مبلغ التحصيل يجب ان يكون رقم',
            'Amount_Commission.integer' =>'مبلغ العمولة يجب ان يكون رقم',

        ];
    }
}
