<?php

namespace App\Http\Services;

use App\Models\InternalUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserService {
    public function get_data($param)
    {
        $q = InternalUser::query();

        if (isset($param['id'])) {
            $q->where('id', $param['id']);
        }

        if (isset($param['status'])) {
            $q->where('status', $param['status']);
        }

        if (isset($param['first_record'])) {
            $data = $q->first();
        } else {
            $data = $q->get();
        }
        return $data;
    }

    public function updateInternal($request, $id = 0)
    {
        $model = new InternalUser();
        $current_image = null;
        if ($id != 0) {
            $model = InternalUser::find($id);
            $current_image = $model->image;
        }

        $request->validate([
            'name' => 'required',
            'nis' => [
                'required',
                Rule::unique('internal_users', 'nis')->ignore($model)
            ],
            'phone' => 'required',
            'address' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
            'district_id' => 'required',
            'institution_id' => 'required',
            'institution_class_id' => 'required',
            'institution_class_level_id' => 'required',
        ], [
            'name.required' => __('view.name_required'),
            'nis.required' => __('view.nis_required'),
            'nis.unique' => __('view.nis_unique'),
            'phone.required' => __('view.phone_required'),
            'province_id.required' => __('view.province_id_required'),
            'city_id.required' => __('view.city_id_required'),
            'district_id.required' => __('view.district_id_required'),
            'institution_id.required' => __('view.institution_id_required'),
            'institution_class_id.required' => __('view.institution_class_id_required'),
            'institution_class_level_id.required' => __('view.institution_class_level_id_required'),
        ]);

        if ($request->is_delete_image == 1) {
            if (file_exists('storage/user_internal/' . $current_image)) {
                unlink('storage/user_internal/' . $current_image);
            }

            $model->image = NULL;
        }

        if ($request->has('file')) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $name = 'user_' . uniqid() . '.' . $ext;
            Storage::disk('public')->putFileAs('user_internal', $file, $name);
            $model->image = $name;
        }

        $model->name = $request->name;
        $model->nis = $request->nis;
        $model->phone = $request->phone;
        $model->parent_data = $request->parent;
        $model->address = $request->address;
        $model->province_id = $request->province_id;
        $model->city_id = $request->city_id;
        $model->district_id = $request->district_id;
        $model->institution_id = $request->institution_id;
        $model->institution_class_id = $request->institution_class_id;
        $model->institution_class_level_id = $request->institution_class_level_id;
        $model->status = $request->status;
        $model->save();
    }
}