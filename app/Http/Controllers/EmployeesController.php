<?php

namespace App\Http\Controllers;

use App\Http\Requests\EmployeeRequest;
use App\Models\Employees;
use App\Models\Intitution;
use App\Models\Position;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class EmployeesController extends Controller
{
    public $vp;

    public function __construct()
    {
        $this->vp = 'master.employees';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        breadcrumb([
            [
                'name' => __('view.employees'),
                'active' => false
            ],
            [
                'name' => __('view.list'),
                'active' => false
            ],
        ]);
        return view($this->vp . '.index');
    }

    public function ajax()
    {
        $data = Employees::all();
        return DataTables::of($data)
            ->editColumn('name', function($d) {
                return '<a href="">'. ucfirst($d->name) .'</a>';
            })
            ->editColumn('status', function($d) {
                $class = 'warning';
                $text = 'Inactive';
                if ($d->status == 1) {
                    $class = 'primary';
                    $text = 'Active';
                }
                return '<span class="label label-'. $class .'">'. $text .'</span>';
            })
            ->editColumn('position_id', function($d) {
                return $d->position ? $d->position->name : '-';
            })
            ->editColumn('institution_id', function($d) {
                return $d->institution ? $d->institution->name : '';
            })
            ->addColumn('action', function($d) {
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('. $d->id .', `'. __('view.update_employee') .'`)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteItem('. $d->id .', `'. __('view.delete_text') .'`)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="gi gi-bin"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'status', 'name'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $institutions = Intitution::active()->get();
        $positions = Position::all();
        $provinces = \Indonesia::allProvinces();
        $view = view($this->vp . '.form', compact('institutions', 'positions', 'provinces', 'institutions'))->render();
        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'method' => 'POST',
            'url' => '/employees'
        ]);
    }

    /**
     * Function to get city based on province_id
     * @param int province_id
     * 
     * @return JsonResponse
     */
    public function getCity(Request $request)
    {
        $id = $request->province_id;
        $cities = \Indonesia::findProvince($id, ['cities']);
        return response()->json(['message' => 'Success', 'data' => $cities->cities]);
    }

    /**
     * Function to get city based on province_id
     * @param int province_id
     * 
     * @return JsonResponse
     */
    public function getDistrict(Request $request)
    {
        $id = $request->city_id;
        $districts = \Indonesia::findCity($id, ['districts']);
        return response()->json(['message' => 'Success', 'data' => $districts->districts]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(EmployeeRequest $request)
    {
        DB::beginTransaction();
        try {
            $position = $request->position_id;
            $role_id = Position::getRole($position);
            $role_id = $role_id->role_id;
            
            $user_registered = false;
            if ($request->username && $request->password) {
                $user = new User();
                $user->username = $request->username;
                $user->password = Hash::make($request->password);
                $user->role = $role_id;
                $user->save();
                $user_registered = true;
            }

            $model = new Employees();
            $model->name = $request->name;
            $model->nip = $request->nip;
            $model->email = $request->email;
            $model->phone = $request->phone;
            $model->address = $request->address;
            $model->district_id = $request->district_id;
            $model->city_id = $request->city_id;
            $model->province_id = $request->province_id;
            $model->account_number = $request->account_number;
            $model->institution_id = $request->institution_id;
            $model->position_id = $request->position_id;
            $model->status = $request->status;
            $model->user_id = 0;
            if ($user_registered) {
                $model->user_id = $user->id;
            }
            $model->save();

            // registers role
            if ($user_registered) {
                $role = Role::findById($role_id);
                $u = User::find($user->id);
                $u->assignRole($role);
            }

            DB::commit();
            return response()->json(['message' => 'Success create employee']);
        } catch (\Throwable $th) {
            DB::rollBack();
            setup_log('save employee', $th);
            return response()->json(['message' => 'Failed to save employee'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Employees  $employees
     * @return \Illuminate\Http\Response
     */
    public function show(Employees $employees)
    {
        try {
            $institutions = Intitution::active()->get();
            $positions = Position::all();
            $provinces = \Indonesia::allProvinces();
            $view = view($this->vp . '.form', compact('institutions', 'positions', 'provinces', 'institutions', 'employees'))->render();
            return response()->json([
                'message' => 'Success',
                'view' => $view,
                'method' => 'POST',
                'url' => '/employees'
            ]);
        } catch (\Throwable $th) {
            setup_log('error show emp', $th);
            return response()->json(['message' => 'Failed to show employee'], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Employees  $employees
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        try {
            $employee = Employees::find($id);
            $institutions = Intitution::active()->get();
            $positions = Position::all();
            $provinces = \Indonesia::allProvinces();
            $view = view($this->vp . '.form', compact('institutions', 'positions', 'provinces', 'institutions', 'employee'))->render();
            return response()->json([
                'message' => 'Success',
                'view' => $view,
                'method' => 'PUT',
                'url' => '/employees/' . $id
            ]);
        } catch (\Throwable $th) {
            setup_log('error edit emp', $th->getMessage());
            return response()->json(['message' => 'Failed to show edit employee'], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Employees  $employees
     * @return \Illuminate\Http\Response
     */
    public function update(EmployeeRequest $request, Employees $employee)
    {
        DB::beginTransaction();
        try {
            $position = $request->position_id;
            $role_id = Position::getRole($position);
            $role_id = $role_id->role_id;
            
            $user_registered = false;
            if ($request->username) {
                $user = $employee->user;
                $user->username = $request->username;
                if ($request->password) {
                    $user->password = Hash::make($request->password);
                }
                $user->role = $role_id;
                $user->save();
                $user_registered = true;
            }

            $model = $employee;
            $model->name = $request->name;
            $model->nip = $request->nip;
            $model->email = $request->email;
            $model->phone = $request->phone;
            $model->address = $request->address;
            $model->district_id = $request->district_id;
            $model->city_id = $request->city_id;
            $model->province_id = $request->province_id;
            $model->account_number = $request->account_number;
            $model->institution_id = $request->institution_id;
            $model->position_id = $request->position_id;
            $model->status = $request->status;
            $model->save();

            // registers role
            if ($user_registered) {
                $role = Role::findById($role_id);
                $u = User::find($user->id);
                $u->assignRole($role);
            }

            DB::commit();
            return response()->json(['message' => 'Success update employee']);
        } catch (\Throwable $th) {
            DB::rollBack();
            setup_log('update employee', $th);
            return response()->json(['message' => 'Failed to update employee'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Employees  $employees
     * @return \Illuminate\Http\Response
     */
    public function destroy(Employees $employee)
    {
        DB::beginTransaction();
        try {
            // delete user
            $user_id = $employee->user_id;
            $user = User::find($user_id);
            $role_id = $user->role;
            $role = Role::findById($role_id);

            // delete user
            $user->delete();

            // delete employee
            $employee->delete();

            // revoke role
            $user->removeRole($role);

            DB::commit();
            return response()->json(['message' => 'Success delete employee']);
        } catch (\Throwable $th) {
            DB::rollBack();
            setup_log('delete employee', $th);
            return response()->json(['message' => 'Failed delete employee']);
        }
    }
}
