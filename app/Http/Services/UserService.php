<?php

namespace App\Http\Services;

use App\Models\InternalUser;
use App\Models\ExternalUser;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class UserService {
    public function get_data($param)
    {
        if ($param['type'] == 'internal') {
            $q = InternalUser::query();
        } else {
            $q = ExternalUser::query();
        }
        $q->with(['district']);
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

        // validate request
        $this->validate_internal_request($request);

        // upload user image
        $image = $this->upload_image($request, 'user_internal', $current_image);
        $model->image = $image;

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

    /**
     * @param mix request
     * @param int id
     * 
     * @return void
     */
    public function updateExternal($request, $id = 0)
    {
        $model = new ExternalUser();
        $current_image = null;
        if ($id != 0) {
            $model = ExternalUser::find($id);
            $current_image = $model->image;
        }

        // validate request
        $this->validate_external_request($request);

        // upload image
        $image = $this->upload_image($request, 'user_external', $current_image);
        $model->image = $image;

        $model->name = $request->name;
        $model->user_type = $request->user_type;
        $model->phone = $request->phone;
        $model->address = $request->address;
        $model->province_id = $request->province_id;
        $model->city_id = $request->city_id;
        $model->district_id = $request->district_id;
        $model->status = $request->status;
        $model->save();

    }

    public function upload_image($request, $folder, $current_image)
    {
        $image = null;
        if ($request->is_delete_image == 1) {
            if (file_exists('storage/'. $folder .'/' . $current_image)) {
                unlink('storage/'. $folder .'/' . $current_image);
            }

            $image = NULL;
        }

        if ($request->file) {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $name = 'user_' . uniqid() . '.' . $ext;
            Storage::disk('public')->putFileAs($folder, $file, $name);
            $image = $name;
        }

        return $image;
    }

    /**
     * Function to validate user external request
     */
    public function validate_external_request($request)
    {
        $request->validate([
            'name' => 'required',
            'phone' => 'required',
            'address' => 'required',
            'province_id' => 'required',
            'city_id' => 'required',
        ], [
            'name.required' => __('view.name_required'),
            'phone.required' => __('view.phone_required'),
            'province_id.required' => __('view.province_id_required'),
            'city_id.required' => __('view.city_id_required'),
            'district_id.required' => __('view.district_id_required'),
        ]);
    }

    /**
     * Function to validate user internal required
     * 
     * @return void
     */
    public function validate_internal_request($request)
    {
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
    }
}