<?php

namespace App\Http\Services;

use App\Models\Employees;
use App\Models\InstitutionClass;
use App\Models\InstitutionClassLevel;
use App\Models\InstitutionIncomeCategory;
use App\Models\InternalUser;
use App\Models\Intitution;
use Carbon\Carbon;

class InstitutionService
{
    /**
     * Function to store new record of institution
     */
    public function store($request, $id = 0)
    {
        $model = new Intitution();
        $model->name = $request->name;
        if (! $request->has('status')) {
            $model->status = false;
        }
        $model->save();

        if ($request->has('has_class')) {
            $classes = collect($request->ins)->pluck('class_name')->map(function ($item) use ($model) {
                return [
                    'name' => $item,
                    'intitution_id' => $model->id,
                    'created_at' => Carbon::now(),
                    'updated_at' => Carbon::now(),
                ];
            })->all();

            $levels = [];
            for ($a = 0; $a < count($request->ins); $a++) {
                $model_class = InstitutionClass::insertGetId($classes[$a]);
                $filled_level = $request->ins[$a]['class'];
                $filled_level = collect($filled_level)->filter(function ($i) {
                    return $i['level'] != null;
                })->values();
                if (isset($filled_level)) {
                    foreach ($filled_level as $cl) {
                        $levels[] = [
                            'name' => $cl['level'],
                            'institution_class_id' => $model_class,
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now(),
                        ];
                    }
                }
            }
            if (isset($levels)) {
                InstitutionClassLevel::insert($levels);
            }
        }

        // save relation income categories
        $categories = $request->income_category_ids;
        $payload_category = [];
        for ($b = 0; $b < count($categories); $b++) {
            $payload_category[] = new InstitutionIncomeCategory(['income_category_id' => $categories[$b], 'created_at' => Carbon::now(), 'updated_at' => Carbon::now()]);
        }

        $model->incomeCategories()->saveMany($payload_category);

        // update current relation employee
        if ($id != 0) {
            Employees::where('institution_id', $id)
                ->update([
                    'institution_id' => $model->id,
                    'updated_at' => Carbon::now(),
                ]);
        }
    }

    public function update_data($request, $id)
    {
        $model = Intitution::find($id);
        $class_id_param = 0;
        $level_id_param = 0;

        /**
         * Check if has class is not set
         * If current data is has a class and level, check the relation in those class
         * and if has relation, return the error
         */
        if (!$request->has('has_class')) {
            $classes = $model->classes;
            if (count($classes) > 0) {
                foreach ($classes as $cl) {
                    $check_relation = InternalUser::where('institution_id', $id)
                        ->where('institution_class_id', $cl->id)
                        ->first();
                    if ($check_relation) {
                        return [
                            'error' => true,
                            'message' => __('view.cannot_delete_relation_class')
                        ];
                    }
                }
            }
        }

        $model->name = $request->name;
        if ($request->has('status')) {
            $model->status = $request->status;
        }
        $model->save();

        $ins = $request->ins;
        foreach ($ins as $i) {
            $class_level = $i['class'];

            if (isset($i['class_id'])) {

                /**
                 ** update if class_id key is exist
                 */
                InstitutionClass::where('id', $i['class_id'])
                    ->update(['name' => $i['class_name']]);

                foreach ($class_level as $cl) {
                    if (isset($cl['level_id'])) {

                        /**
                         ** update if level_id key is exist
                        */
                        InstitutionClassLevel::where('id', $cl['level_id'])
                            ->update(['name' => $cl['level']]);

                    } else {

                        InstitutionClassLevel::insert([
                            'institution_class_id' => $i['class_id'],
                            'name' => $cl['level'],
                            'created_at' => Carbon::now(),
                            'updated_at' => Carbon::now()
                        ]);

                    }
                }

            } else {

                /**
                 ** create a new class and level
                 */
                if ($i['class_name']) {
                    $new_class = new InstitutionClass();
                    $new_class->intitution_id = $id;
                    $new_class->name = $i['class_name'];
                    $new_class->save();
                }

                foreach ($class_level as $cl2) {
                    if ($cl2['level']) {
                        $new_level = new InstitutionClassLevel();
                        $new_level->institution_class_id = $new_class->id;
                        $new_level->name = $cl2['level'];
                        $new_level->save();
                    }
                }

            }

        }

        if ($request->has('income_category_ids')) {

            $incomes = $request->income_category_ids;
            $current_incomes = $model->incomeCategories;
            $current_incomes = collect($current_incomes)->pluck('income_category_id')->all();

            $new_incomes = [];
            $deleted_incomes = [];
            for ($aa = 0; $aa < count($incomes); $aa++) {
                if (!in_array($incomes[$aa], $current_incomes)) {
                    $new_incomes[] = $incomes[$aa];
                }
            }

            for ($bb = 0; $bb < count($current_incomes); $bb++) {
                if (!in_array($current_incomes[$bb], $incomes)) {
                    $deleted_incomes[] = $current_incomes[$bb];
                }
            }

            if (count($new_incomes) > 0) {
                for ($cc = 0; $cc < count($new_incomes); $cc++) {
                    $model_income = InstitutionIncomeCategory::find($new_incomes[$cc]);
                    if (!$model_income) {
                        $model_income = new InstitutionIncomeCategory();
                    }
                    $model_income->status = true;
                    $model_income->institution_id = $id;
                    $model_income->income_category_id = $new_incomes[$cc];
                    $model_income->save();
                }
            }

            if (count($deleted_incomes) > 0) {
                for ($dd = 0; $dd < count($deleted_incomes); $dd++) {
                    $model_income_delete = InstitutionIncomeCategory::find($deleted_incomes[$dd]);
                    if ($model_income_delete) {
                        $model_income_delete->status = false;
                        $model_income_delete->save();
                    }
                }
            }

        }

        /**
         * Create default param for detail page
         ** This function is run when user doing edit insitution from detail page
         */
        $default_params = [];
        if ($request->current_id != 0) {
            if (count($model->classes) > 0) {
                $class = $model->classes[0];
                $class_id_param = $class->id;
                if (count($class->levels) > 0) {
                    $level = $class->levels[0];
                    $level_id_param = $level->id;
                }
            }
            $default_params = [
                'institution_id' => $request->current_id,
                'class_id' => $class_id_param,
                'level_id' => $level_id_param,
            ];
        }

        return $default_params;
    }

    public function get_all_gender($institution_id, $class_id, $level_id)
    {
        $data = InternalUser::select('gender')
            ->where('institution_id', $institution_id)
            ->where('institution_class_id', $class_id)
            ->where('institution_class_level_id', $level_id)
            ->get();

        return collect($data)->pluck('gender')->countBy()->all();
    }
}
