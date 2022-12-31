<?php

namespace App\Http\Controllers;

use App\Http\Requests\PositionRequest;
use App\Models\Position;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class PositionController extends Controller
{
    public $vp;

    public function __construct()
    {
        $this->vp = 'master.positions';
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
                'active' => false
            ],
            [
                'name' => __('view.list'),
                'active' => false
            ],
        ]);
        return view($this->vp . '.index');
    }

    /**
     * Function to generate dataTables
     * 
     * @return DataTables
     */
    public function ajax()
    {
        $data = Position::all();
        return DataTables::of($data)
            ->editColumn('role_id', function ($d) {
                return ucfirst($d->role ? $d->role->name : '-');
            })
            ->addColumn('action', function($d) {
                // $text =
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('. $d->id .', `'. __('view.update_role') .'`)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteItem('. $d->id .', `'. __('view.delete_text') .'`)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="gi gi-bin"></i></button>
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
        $roles = Role::all();
        $view = view($this->vp . '.form', compact('roles'))->render();
        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'method' => 'POST',
            'url' => '/positions'
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PositionRequest $request)
    {
        try {
            $model = new Position();
            $model->name = $request->name;
            $model->role_id = $request->role_id;
            $model->save();
    
            return response()->json(['message' => 'Success stored position']);
        } catch (\Throwable $th) {
            setup_log('error save position', $th);
            return response()->json(['message' => 'Failed to save position'], 500);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function show(Position $position)
    {
        $roles = Role::all();
        $view = view($this->vp . '.form', compact('roles', 'position'))->render();
        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'method' => 'PUT',
            'url' => "/positions/" . $position->id
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function edit(Position $position)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function update(PositionRequest $request, Position $position)
    {
        try {
            $model = $position;
            $model->name = $request->name;
            $model->role_id = $request->role_id;
            $model->save();
    
            return response()->json(['message' => 'Success update position']);
        } catch (\Throwable $th) {
            setup_log('error update position', $th);
            return response()->json(['message' => 'Failed to update position'], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Position  $position
     * @return \Illuminate\Http\Response
     */
    public function destroy(Position $position)
    {
        try {
            $position->delete();
            return response()->json(['message' => 'Success delete position']);
        } catch (\Throwable $th) {
            setup_log('failed delete position', $th);
            return response()->json(['message' => 'Failed to delete position'], 500);
        }
    }
}
