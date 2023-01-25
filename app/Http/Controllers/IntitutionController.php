<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntutitionRequest;
use App\Http\Services\InstitutionService;
use App\Models\Employees;
use App\Models\IncomeCategory;
use App\Models\InstitutionClass;
use App\Models\InstitutionClassLevel;
use App\Models\InstitutionIncomeCategory;
use App\Models\InternalUser;
use App\Models\Intitution;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class IntitutionController extends Controller
{
    private $vp;
    private $service;

    public function __construct(
        InstitutionService $service
    )
    {
        $this->vp = 'master.intitutions';
        $this->service = $service;
        $this->middleware(['permission:master institution']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $breadcrumb = [
            [
                'name' => __('view.intitutions'),
                'active' => false,
            ],
            [
                'name' => __('view.list'),
                'active' => false,
            ],
        ];
        breadcrumb($breadcrumb);

        return view($this->vp.'.index');
    }

    /**
     * Function to render datatable
     *
     * @return DataTables
     */
    public function ajax()
    {
        $data = Intitution::with('classes.levels')->get();

        return DataTables::of($data)
            ->editColumn('name', function($d) {
                return '<a href="'. route('intitutions.show', $d->id) .'">'. $d->name .'</a>';
            })
            ->addColumn('action', function ($d) {
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('.$d->id.')" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteItem('.$d->id.')" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="gi gi-bin"></i></button>
                </div>
                ';
            })
            ->addColumn('total_class', function ($d) {
                return count($d->classes);
            })
            ->editColumn('status', function ($d) {
                $text = '';
                if ($d->status) {
                    $text = '<span class="label label-success">Active</span>';
                } else {
                    $text = '<span class="label label-warning">Inactive</span>';
                }

                return $text;
            })
            ->rawColumns(['action', 'status', 'total_class', 'name'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): JsonResponse
    {
        $income_categories = IncomeCategory::all();
        $income_categories = collect($income_categories)->map(function($item) {
            $item['selected'] = false;
            return $item;
        })->all();
        $view = view($this->vp.'.form', compact('income_categories'))->render();

        return response()->json(['view' => $view]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Htstp\Response
     */
    public function store(IntutitionRequest $request)
    {
        DB::beginTransaction();
        try {
            $service = new InstitutionService();
            $data = $service->update_data($request, 26);
            return response()->json(['message' => $data, 'req' => $request->all()]);
            $service->store($request);
            
            DB::commit();

            return response()->json(['message' => __('view.success_save_institution')]);
        } catch (\Throwable $th) {
            DB::rollBack();

            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Intitution::with('classes.levels')->find($id);
        $classes = $data->classes;

        /**
         ** create a default param to get detail of institution data
         ** Param should have key like in detailDataInstitution() function description
         */
        $class_id = 0;
        $level_id = 0;
        if (count($data->classes) > 0) {
            $class = $data->classes[0];
            $class_id = $class->id;
            if (count($class->levels) > 0) {
                $level = $class->levels[0];
                $level_id = $level->id;
            }
        }
        $default_param = [
            'institution_id' => $id,
            'class_id' => $class_id,
            'level_id' => $level_id,
        ];

        $all_students = 1456;
        $female = 875;
        $male = $all_students - $female;
        $all_paid_income = '240000000';

        // set breadcrumb
        $breadcrumb = [
            [
                'name' => __('view.intitutions'),
                'active' => false,
            ],
            [
                'name' => __('view.list'),
                'active' => true,
                'href' => route('intitutions.index')
            ],
            [
                'name' => $data->name,
                'active' => false,
            ],
        ];
        breadcrumb($breadcrumb);

        return view($this->vp . '.show', compact(
            'data',
            'all_students',
            'female',
            'male',
            'all_paid_income',
            'classes',
            'default_param'
        ));
    }

    /**
     * Function to show detail data in selected institution
     * @param int class_id
     * @param int level_id
     * @param int institution_id
     * 
     * @return JsonResponse
     */
    public function detailDataInstitution(Request $request)
    {
        try {
            $class_id = $request->class_id;
            $level_id = $request->level_id;
            $institution_id = $request->institution_id;

            $data = Intitution::with([
                    'classes' => function ($query) use ($class_id) {
                        $query->with('levels.homeroomTeacher');
                        $query->where('id', $class_id);
                    }
                ])
                ->where('id', $institution_id)
                ->first();

            /**
             * If level_id is 0, search manually based on class_id and institution_id
             */
            if ($level_id == 0) {
                if (count($data->classes) > 0) {
                    $class = $data->classes[0];
                    $class_id = $class->id;
                    if (count($class->levels) > 0) {
                        $level = $class->levels[0];
                        $level_id = $level->id;
                    }
                }
            }

            $genders = [];
            if ($level_id != 0) {
                $genders = $this->service->get_all_gender($institution_id, $class_id, $level_id);
            }
            
            $is_update = false;
            if ($request->has('update')) {
                $is_update = $request->update;
            }
            $selected_level = null;
            if (count($data->classes[0]->levels) > 0) {
                $selected_level = collect($data->classes[0]->levels)->where('id', $level_id)->values()[0];
            }

            $view = view($this->vp . '.components.detail_institution_data', compact(
                'data', 'level_id',
                'institution_id', 'class_id',
                'genders', 'selected_level',
                'is_update'
            ))->render();
            
            return $this->render_custom_response($view, ['data' => $data, 'level_id' => $level_id, 'req' => $request->all()]);
        } catch (\Throwable $th) {
            return $this->error_response('', ['file' => $th->getFile(), 'message' => $th->getMessage(), 'line' => $th->getLine()]);
        }
    }

    /**
     * Function to assign homeroom teacher form selected class
     * @param int class_id
     * @param int level_id
     * @param int insitution_id
     * @param int homeroom
     * 
     * @return JsonResponse
     */
    public function storeHomeroom(Request $request)
    {
        DB::beginTransaction();
        try {
            $id = $request->homeroom;
            $class_id = $request->class_id;
            $level_id = $request->level_id;
            $institution_id = $request->institution_id;
    
            $model = InstitutionClassLevel::find($level_id);
            /**
             * Validate selected homeroom
             * Employee cannot be homeroom in more than 1 class
             */
            $validate = am_i_homeroom_another_class($id, ['institution_id' => $institution_id, 'class_id' => $class_id, 'level_id' => $level_id]);
            if (
                $validate['status']
            ) {
                DB::rollBack();

                return $this->error_response(
                    __('view.double_homeroom_validation',
                    ['name' => $validate['current_homeroom']])
                );
            }
            $model->homeroom_teacher = $id;
            $model->save();
            $homeroom = $model->homeroomTeacher->name;
    
            /**
             * Assign wali kelas role
             * and update user homeroom data
             */
            $user_id = $model->homeroomTeacher->user_id;
            $user = User::find($user_id);
            $wali_kelas_role = Role::findByName('wali kelas');
            $user->assignRole($wali_kelas_role);
            
            $user->homeroom_institution_id = $institution_id;
            $user->homeroom_class_id = $class_id;
            $user->homeroom_level_id = $level_id;
            $user->save();

            /**
             * Update REDIS data
             */
            $my_homeroom = my_homeroom($id);
            Redis::set('user_homeroom_data', json_encode($my_homeroom));
            DB::commit();
    
            return $this->success_response(
                __('view.homeroom_teacher_stored'),
                [
                    'homeroom' => $homeroom,
                    'class_id' => $class_id,
                    'level_id' => $level_id,
                    'institution_id' => $institution_id,
                ]
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            setup_log('error store homeroom', [
                'file' => $th->getFile(),
                'message' => $th->getMessage(),
                'line' => $th->getLine()
            ]);
            
            return $this->error_response($th->getMessage());
        }
    }

    public function showHomeroomTeacher(Request $request)
    {
        $class_id = request()->class_id;
        $level_id = request()->level_id;
        $institution_id = request()->institution_id;
        $data = Employees::active()->get();
        $view = view($this->vp . '.components.form_homeroom', compact(
            'data',
            'class_id',
            'level_id',
            'institution_id'
        ))->render();

        return $this->render_response($view);
    }

    /**
     * Function to delete class
     * @param array level
     * @param any class_name
     * @param int id
     * 
     * @return JsonResponse
     */
    public function deleteClass(Request $request)
    {
        $class_name = $request->class_name;
        $id = $request->id;
        $class = InstitutionClass::where('name', $class_name)
            ->where('intitution_id', $id)
            ->first();
        $class_id = $class->id;
        
        $check_relation = InternalUser::select('id')->where('institution_id', $id)
            ->where('institution_class_id', $class_id)
            ->count();
        if ($check_relation > 0) {
            return $this->error_response(__('view.cannot_delete_relation_class'));
        }

        $class->delete();

        return $this->success_response(__('view.success_delete_class'));
    }

    public function deleteLevel(Request $request)
    {
        $class_name = $request->class_name;
        $level = $request->level[0];
        $id = $request->id;
        $class = InstitutionClass::where('intitution_id', $id)
            ->where('name', $class_name)
            ->first();
        $class_level = InstitutionClassLevel::where('institution_class_id', $class->id)
            ->where('name', $level)
            ->first();
        
        $check_relation = InternalUser::where('institution_id', $id)
            ->where('institution_class_level_id', $class_level->id)
            ->count();
        if ($check_relation > 0) {
            return $this->error_response(__('view.cannot_delete_relation_class_level'));
        }

        $class_level->delete();

        return $this->success_response(__('view.success_delete_level'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Intitution::with(['classes.levels', 'incomeCategories.category'])->find($id);
        $income_categories = IncomeCategory::all();
        $incomes = $data->incomeCategories;
        $income_categories = collect($income_categories)->map(function ($item) use ($incomes) {
            $item['selected'] = false;
            if (count($incomes) > 0) {
                foreach($incomes as $income) {
                    if ($income->income_category_id == $item->id) {
                        $item['selected'] = true;
                    }
                }
            }

            return $item;
        })->all();
        $view = view($this->vp.'.form', compact('data', 'income_categories'))->render();

        return response()->json(['message' => 'Success', 'view' => $view]);
    }

    public function generateClassLevelForm(Request $request)
    {
        $id = $request->id;
        $data = Intitution::with(['classes.levels'])->find($id);
        $classes = $data->classes;
        $view = view($this->vp . '.class_level_form', compact('classes'))->render();

        return $this->render_response($view);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(IntutitionRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $service = new InstitutionService();
            $data = $service->update_data($request, $id);
            DB::commit();

            return $this->success_response(__('view.success_update_institution'), ['param' => $data]);
        } catch (\Throwable $th) {
            DB::rollBack();
            setup_log('error_update_institution', ['file' => $th->getFile(), 'message' => $th->getMessage(), 'line' => $th->getLine()]);

            return response()->json(['message' => $th->getMessage()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $model = Intitution::find($id);
            $incomes = $model->incomeCategories;
            foreach($incomes as $income) {
                $income->delete();
            }
            $model->delete();

            return response()->json(['message' => 'Success delete Intitution']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }
}
