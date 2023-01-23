<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class EmployeeRequest extends FormRequest
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
        $rules = [
            'name' => 'required',
            'email' => 'required',
            'nip' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'district_id' => 'required',
            'city_id' => 'required',
            'province_id' => 'required',
            'account_number' => 'required',
            'institution_id' => 'required',
            'position_id' => 'required',
        ];
        $employee = request()->employee;
        if (! $employee) {
            $rules['name'] = [
                'required',
                // Rule::unique('employees')->where(function($query) {
                //     return $query->where('deleted_at', NULL);
                // })
            ];
            $rules['email'] = [
                'required',
                Rule::unique('employees')->where(function ($query) {
                    return $query->where('deleted_at', null);
                }),
            ];
            $rules['username'] = [
                Rule::unique('users')->where(function ($query) {
                    return $query->where('deleted_at', null);
                }),
            ];
        } else {
            $rules['name'] = [
                'required',
                Rule::unique('employees')->where(function ($query) use ($employee) {
                    return $query->where('deleted_at', null)
                        ->where('id', '!=', $employee->id);
                }),
            ];
            $rules['email'] = [
                'required',
                Rule::unique('employees')->where(function ($query) use ($employee) {
                    return $query->where('deleted_at', null)
                        ->where('id', '!=', $employee->id);
                }),
            ];
            $rules['username'] = [
                Rule::unique('users')->where(function ($query) use ($employee) {
                    $user = $employee->user;
                    if ($user) {
                        return $query->where('deleted_at', null)
                            ->where('id', '!=', $user->id);
                    }
                }),
            ];
        }

        return $rules;
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
            'name.required' => __('view.name_required'),
            'email.required' => __('view.email_required'),
            'nip.required' => __('view.nip_required'),
            'phone.required' => __('view.phone_required'),
            'address.required' => __('view.address_required'),
            'district_id.required' => __('view.district_id_required'),
            'city_id.required' => __('view.city_id_required'),
            'province_id.required' => __('view.province_id_required'),
            'account_number.required' => __('view.account_number_required'),
            'institution_id.required' => __('view.institution_id_required'),
            'position_id.required' => __('view.position_id_required'),
            'username.unique' => __('view.username_unique'),
            'email.unique' => __('view.email_unique'),
            'name.unique' => __('view.name_unique'),
        ];

        return $rules;
    }
}
