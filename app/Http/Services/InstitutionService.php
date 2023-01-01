<?php

namespace App\Http\Services;

use App\Models\Employees;
use App\Models\InstitutionClass;
use App\Models\InstitutionClassLevel;
use App\Models\Intitution;
use Carbon\Carbon;
use Illuminate\Support\Facades\Request;

class InstitutionService {
    /**
     * Function to store new record of institution
     */
    public function store($request, $id = 0)
    {  
        $model = new Intitution();
        $model->name = $request->name;
        if (!$request->has('status')) {
            $model->status = false;
        }
        $model->save();

        if ($request->has('has_class')) {
            $classes = collect($request->ins)->pluck('class_name')->map(function($item) use($model) {
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
                $filled_level = collect($filled_level)->filter(function($i) {
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

        // update current relation employee
        if ($id != 0) {
            Employees::where('institution_id', $id)
                ->update([
                    'institution_id' => $model->id,
                    'updated_at' => Carbon::now()
                ]);
        }
    }
}