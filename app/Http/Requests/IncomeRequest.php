<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class IncomeRequest extends FormRequest
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
            'user' => 'required',
            'amount_total' => 'required',
            // 'due_date' => 'required',
            'attachments' => 'required',
            'income_method_id' => 'required',
            'income_type_id' => 'required',
            'invoice_number' => 'required',
            'items.*.price' => 'required',
            'items.*.month' => 'required',
            'items.*.income_category_id' => 'required',
            // 'remaining_bill' => 'required',
            'transaction_start_date' => 'required',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.*
     *
     * @return array
     */
    protected function failedValidation(Validator $validator)
    {
        $errs = $validator->errors()->all();
        if (count($errs) > 5) {
            $errs = __('view.fields_required');
        }
        throw new HttpResponseException(response()->json([
            'message' => $errs,
            'status' => true,
        ], 422));
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        $rules = [
            'user.required' => __('view.user_required'),
            'amount_total.required' => __('view.amount_total_required'),
            'due_date.required' => __('view.due_date_required'),
            'income_method_id.required' => __('view.income_method_id_required'),
            'income_type_id.required' => __('view.income_type_id_required'),
            'invoice_number.required' => __('view.invoice_number_required'),
            'items.*.price.required' => __('view.items_required'),
            'items.*.income_category_id.required' => __('view.items_required'),
            'items.*.month.required' => __('view.month_required'),
            'remaining_bill.required' => __('view.remaining_bill_required'),
            'transaction_start_date.required' => __('view.transaction_start_date_required'),
            'attachments.required' => __('view.attachments_required'),
        ];

        return $rules;
    }
}
