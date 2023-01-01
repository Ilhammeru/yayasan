<?php

namespace App\Http\Controllers;

use App\Http\Requests\IntutitionRequest;
use App\Http\Services\InstitutionService;
use App\Models\InstitutionClass;
use App\Models\InstitutionClassLevel;
use App\Models\Intitution;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;
use Yajra\DataTables\Facades\DataTables;

class IntitutionController extends Controller
{
    private $vp;

    public function __construct()
    {
        $this->vp = 'master.intitutions';
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
                'active' => false
            ],
            [
                'name' => __('view.list'),
                'active' => false
            ],
        ];
        breadcrumb($breadcrumb);
        return view($this->vp . '.index');
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
            ->addColumn('action', function($d) {
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('. $d->id .')" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteItem('. $d->id .')" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="gi gi-bin"></i></button>
                </div>
                ';
            })
            ->addColumn('total_class', function($d) {
                return count($d->classes);
            })
            ->editColumn('status', function($d) {
                $text = '';
                if ($d->status) {
                    $text = '<span class="label label-success">Active</span>';
                } else {
                    $text = '<span class="label label-warning">Inactive</span>';
                }
                return $text;
            })
            ->rawColumns(['action', 'status', 'total_class'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): JsonResponse
    {
        $view = view($this->vp . '.form')->render();
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
            $service->store($request);

            DB::commit();
            return response()->json(['message' => 'Success create Intitution']);
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
        $data = Intitution::with('classes.levels')->find($id);
        $view = view($this->vp . '.form', compact('data'))->render();

        return response()->json(['message' => 'Success', 'view' => $view]);
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
            $data = Intitution::find($id);
            if ($data->name != $request->name) {
                if (Intitution::where('name', $request->name)->first()) {
                    return response()->json(['message' => 'This name already registered in database'], 500);
                }
            }
            $data->delete();
            $service = new InstitutionService();
            $service->store($request, $id);

            DB::commit();
            return response()->json(['message' => 'Success create Intitution']);
        } catch (\Throwable $th) {
            DB::rollBack();
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
            $model->delete();

            return response()->json(['message' => 'Success delete Intitution']);
        } catch (\Throwable $th) {
            return response()->json(['message' => $th->getMessage()], 422);
        }
    }
}
