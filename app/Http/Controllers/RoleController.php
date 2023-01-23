<?php

namespace App\Http\Controllers;

use App\Http\Services\PermissionService;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class RoleController extends Controller
{
    private $vp;

    public function __construct()
    {
        $this->vp = 'master.roles';
        $this->middleware(['permission:master role']);
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
                'name' => __('view.intitutions'),
                'active' => false,
            ],
            [
                'name' => __('view.list'),
                'active' => false,
            ],
        ]);

        return view($this->vp.'.index');
    }

    /**
     * Generate data for datatables
     *
     * @return DataTables
     */
    public function ajax()
    {
        $data = Role::all();

        return DataTables::of($data)
            ->editColumn('name', function ($d) {
                return strtoupper($d->name);
            })
            ->addColumn('action', function ($d) {
                // $text =
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('.$d->id.', `'.__('view.update_role').'`)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteItem('.$d->id.', `'.__('view.delete_text').'`)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="gi gi-bin"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $s = new PermissionService();
        $permissions = $s->get_permission_group();
        $view = view($this->vp.'.form', compact('permissions'))->render();

        return response()->json(['message' => 'Success', 'view' => $view]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'required',
        ]);
        $name = strtolower($request->name);
        $role = Role::create(['name' => $name]);
        $p = $request->permissions;
        for ($a = 0; $a < count($p); $a++) {
            $permission = Permission::findById($p[$a]);
            $role->givePermissionTo($permission);
        }

        return response()->json(['message' => 'Success create role']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Role::findById($id);
        $s = new PermissionService();
        $permissions = $s->get_permission_group($data);
        $view = view($this->vp.'.form', compact('data', 'permissions'))->render();

        return response()->json(['message' => 'Success', 'view' => $view]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'permissions' => 'required',
        ]);
        $data = Role::findById($id);
        $current_permissions = $data->permissions;
        $permissions = $request->permissions;

        // remove permission if needed
        if (count($current_permissions) > 0) {
            foreach ($current_permissions as $cp) {
                $data->revokePermissionTo($cp);
            }
        }

        // assign permission
        for ($a = 0; $a < count($permissions); $a++) {
            $permit = Permission::findById($permissions[$a]);
            $data->givePermissionTo($permit);
        }

        return response()->json(['message' => 'Success update role']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::findById($id);
        $role->delete();

        return response()->json(['message' => 'Delete role success']);
    }
}
