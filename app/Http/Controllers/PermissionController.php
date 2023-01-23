<?php

namespace App\Http\Controllers;

use App\Models\PermissionGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Yajra\DataTables\Facades\DataTables;

class PermissionController extends Controller
{
    public $vp;

    public function __construct()
    {
        $this->vp = 'master.permissions';
        $this->middleware(['permission:master permission']);
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
                'name' => __('view.permissions'),
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
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $groups = PermissionGroup::all();
        $view = view($this->vp.'.form', compact('groups'))->render();

        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'method' => 'POST',
            'url' => '/permissions',
        ]);
    }

    /**
     * Function to get data for datatable
     */
    public function ajax()
    {
        $data = Permission::all();

        return DataTables::of($data)
            ->editColumn('name', function ($d) {
                return ucfirst($d->name);
            })
            ->addColumn('action', function ($d) {
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('.$d->id.', `'.__('view.update_permission').'`)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteItem('.$d->id.', `'.__('view.delete_text').'`)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="gi gi-bin"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action'])
            ->make(true);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->validate([
                'name' => 'required',
                'group' => 'required',
            ], [
                'name.required' => __('view.name_required'),
                'group.required' => __('view.group_required'),
            ]);

            Permission::create(['name' => strtolower($request->name), 'permission_group_id' => $request->group]);

            DB::commit();

            return response()->json(['message' => 'Success create permission']);
        } catch (\Throwable $th) {
            setup_log('save permission', $th);
            DB::rollBack();

            return response()->json(['message' => 'Failed to save permission'], 500);
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
        $data = Permission::findById($id);
        $groups = PermissionGroup::all();
        $view = view($this->vp.'.form', compact('data', 'groups'))->render();

        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'method' => 'PUT',
            'url' => '/permissions/'.$id,
        ]);
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
        $rule = [
            'name' => [
                'required',
                Rule::unique('permissions')->ignore($id),
            ],
            'group' => 'required',
        ];
        $request->validate($rule, [
            'name.required' => __('view.name_required'),
            'name.unique' => __('view.name_unique'),
            'group.required' => __('view.group_required'),
        ]);

        $data = Permission::findById($id);
        $data->delete();
        Permission::create(['name' => strtolower($request->name), 'permission_group_id' => $request->group]);

        return response()->json(['message' => 'Success update permission']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = Permission::findById($id);
        $data->delete();

        return response()->json(['message' => 'Success delete permission']);
    }
}
