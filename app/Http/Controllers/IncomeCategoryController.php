<?php

namespace App\Http\Controllers;

use App\Models\IncomeCategory;
use App\Models\IncomeItem;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class IncomeCategoryController extends Controller
{
    public $vp;

    public function __construct()
    {
        $this->vp = 'master.income';
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
                'name' => __('view.income_category'),
                'active' => false
            ],
            [
                'name' => __('view.list'),
                'active' => false
            ],
        ]);
        return view($this->vp . '.category.index');
    }

    public function ajax()
    {
        $data = IncomeCategory::all();

        return DataTables::of($data)
            ->addColumn('action', function($d) {
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('. $d->id .', `'. __('view.update_income_category') .'`)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
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
        $view = view($this->vp . '.category.form')->render();
        return $this->render_response($view, '/income/category/0', 'PUT');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\IncomeCategory  $incomeCategory
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\IncomeCategory  $incomeCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = IncomeCategory::find($id);
        $view = view($this->vp . '.category.form', compact('data'))->render();
        return $this->render_response($view, '/income/category/' . $id, 'PUT');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\IncomeCategory  $incomeCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $rules = ['name' => 'required'];
        if ($id != 0) {
            $rules['name'] = [
                'required',
                Rule::unique('income_categories')->ignore($id)
            ];
        }
        $request->validate($rules, [
            'name.required' => __('view.name_required'),
            'name.unique' => __('view.name_unique'),
        ]);

        $model = new IncomeCategory();
        if ($id != 0) {
            $model = IncomeCategory::find($id);
        }
        $model->name = $request->name;
        $model->save();

        return $this->success_response(__('view.success_update_income_category'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\IncomeCategory  $incomeCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // check relation
        $check = IncomeItem::where('income_category_id', $id)->first();
        if ($check) {
            return $this->error_response(__('view.delete_failed_bcs_relation'));
        }
        
        $data = IncomeCategory::find($id);
        $data->delete();
        return $this->success_response(__('view.success_delete_item'));
    }
}
