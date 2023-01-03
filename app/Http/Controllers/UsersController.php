<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Models\ExternalUser;
use App\Models\InstitutionClass;
use App\Models\InstitutionClassLevel;
use App\Models\InternalUser;
use App\Models\Intitution;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public $vp;
    public $uservice;

    public function __construct(
        UserService $userService
    )
    {
        $this->vp = 'master.users';
        $this->uservice = $userService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($type)
    {
        breadcrumb([
            [
                'name' => __('view.users'),
                'active' => false
            ],
            [
                'name' => __('view.list'),
                'active' => false
            ],
        ]);
        return view($this->vp . '.index', compact('type'));
    }

    public function ajax($type)
    {
        $data = $type == 'eksternal' ? ExternalUser::all() : $this->tableInternal();

        return $data;
    }

    public function tableInternal()
    {
        $data = InternalUser::all();
        return DataTables::of($data)
            ->editColumn('name', function($d) {
                return '<a href="#" onclick="showProfile(`internal`, '. $d->id .', `'. __('view.detail_profile') .'`)">'. ucfirst($d->name) .'</a>';
            })
            ->addColumn('institution', function($d) {
                return $d->institution->name . ' (' . $d->class->name . $d->level->name . ')';
            })
            ->editColumn('status', function($d) {
                $text = 'inactive';
                $bg = 'warning';
                if ($d->status) {
                    $text = 'active';
                    $bg = 'success';
                }
                return '<span class="label label-'. $bg .'">'. $text .'</span>';
            })
            ->addColumn('action', function($d) {
                // $text =
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('. $d->id .', `'. __('view.update_user') .'`, `internal`)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteItem('. $d->id .', `'. __('view.delete_text') .'`, `internal`)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="gi gi-bin"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'institution', 'status', 'name'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        $data = [];
        $param = [];
        if ($type == 'internal') {
            $classes = InstitutionClass::all();
            $levels = InstitutionClassLevel::all();

            $institutions = Intitution::active()->get();

            $provinces = \Indonesia::allProvinces();
            $view = view($this->vp . '.form_internal', compact('institutions', 'classes', 'levels', 'provinces'))->render();
        }

        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'url' => '/users/0/' . $type,
            'method' => 'POST'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $type)
    {
        if ($type == 'internal') {
            $param = [
                'id' => $id,
                'first_record' => true
            ];
            $data = $this->uservice->get_data($param);
            $view = view($this->vp . '.profile_internal', compact('data'))->render();
        }
        return response()->json([
            'message' => 'Success',
            'view' => $view
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type)
    {
        $data = [];
        $param = ['id' => $id, 'first_record' => true];
        if ($type == 'internal') {
            $data = $this->uservice->get_data($param);
            $ins_id = $data->institution->id;

            $classes = InstitutionClass::where('intitution_id', $ins_id)->get();
            $classes = collect($classes)->map(function($item) use($data) {
                $item['selected'] = '';
                if ($data->class->id == $item->id) {
                    $item['selected'] = 'selected';
                }
                return $item;
            })->all();

            $class_id = $data->institution_class_id;
            $levels = InstitutionClassLevel::where('institution_class_id', $class_id)->get();
            $levels = collect($levels)->map(function($item) use($data) {
                $item['selected'] = '';
                if ($data->level->id == $item->id) {
                    $item['selected'] = 'selected';
                }
                return $item;
            })->all();

            $institutions = Intitution::active()->get();
            $institutions = collect($institutions)->map(function($item) use($data) {
                $item['selected'] = '';
                if ($data->institution->id == $item->id) {
                    $item['selected'] = 'selected';
                }
                return $item;
            })->all();

            $province = \Indonesia::findProvince($data->province_id, ['cities', 'districts']);
            $cities = $province->cities;
            $districts = $province->districts;
            $provinces = \Indonesia::allProvinces();
            $provinces = collect($provinces)->map(function($item) use ($province) {
                $item['selected'] = '';
                if ($item->id == $province->id) {
                    $item['selected'] = 'selected';
                }

                return $item;
            })->all();
            $cities = collect($cities)->map(function($item) use($data) {
                $item['selected'] = '';
                if ($item->id == $data->city_id) {
                    $item['selected'] = 'selected';
                }
                return $item;
            })->all();
            $districts = collect($districts)->map(function($item) use ($data) {
                $item['selected'] = '';
                if ($item->id == $data->district_id) {
                    $item['selected'] = 'selected';
                }
                return $item;
            })->all();
            $view = view($this->vp . '.form_internal', compact('data', 'institutions', 'classes', 'levels', 'provinces', 'cities', 'districts'))->render();
        }

        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'url' => '/users/' . $id . '/' . $type,
            'method' => 'POST'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $type)
    {
        DB::beginTransaction();
        try {
            if ($type == 'internal') {
                $this->uservice->updateInternal($request, $id);
            }

            DB::commit();
            return response()->json(['message' => 'Success update user']);
        } catch (\Throwable $th) {
            setup_log('error update usser', $th->getMessage());
            DB::rollBack();
            return response()->json(['message' => 'Failed to update data'], 500);
        }
    }

    /**
     * Function to generate class based on given institution_id
     * 
     * @param int institution_id
     * 
     * @return JsonResponses
     */
    public function getClass(Request $request)
    {
        $id = $request->institution_id;
        $classes = InstitutionClass::where('intitution_id', $id)->get();
        return response()->json(['message' => 'Success', 'data' => $classes]);
    }

    /**
     * Function to generate level based on given class_id
     * 
     * @param int class_id
     * 
     * @return JsonResponses
     */
    public function getLevel(Request $request)
    {
        $id = $request->class_id;
        $classes = InstitutionClassLevel::where('institution_class_id', $id)->get();
        return response()->json(['message' => 'Success', 'data' => $classes]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $type)
    {
        if ($type == 'internal') {
            $data = InternalUser::find($id);
            $data->delete();
        }

        return response()->json(['message' => 'Success delete user']);
    }
}
