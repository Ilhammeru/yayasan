<?php

namespace App\Http\Controllers;

use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ExpenseCategoryController extends Controller
{
    public $vp;

    public function __construct()
    {
        $this->vp = 'master.expenses.category';
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
     * Function to generate data for DataTable
     * 
     * @return DataTables
     */
    public function ajax()
    {
        $data = ExpenseCategory::all();
        return DataTables::of($data)
            ->addColumn('action', function($d) {
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('. $d->id .', `'. __('view.update_category') .'`)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
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
        $view = view($this->vp . '.form')->render();
        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'url' => '/expenses/category',
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
        $request->validate([
            'name' => 'required|unique:expense_categories,name'
        ], [
            'name.required' => __('view.name_required'),
            'name.unique' => __('view.name_unique'),
        ]);
        $data = new ExpenseCategory();
        $data->name = $request->name;
        $data->save();

        return response()->json(['message' => 'Success save category']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function show(ExpenseCategory $expenseCategory)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = ExpenseCategory::find($id);
        $view = view($this->vp . '.form', compact('data'))->render();
        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'url' => '/expenses/category/' . $id,
            'method' => 'PUT'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $data = ExpenseCategory::find($id);
        $request->validate([
            'name' => [
                'required',
                Rule::unique('expense_categories')->ignore($data)
            ]
        ], [
            'name.required' => __('view.name_required'),
            'name.unique' => __('view.name_unique'),
        ]);
        $data->name = $request->name;
        $data->save();

        return response()->json(['message' => 'Success update category']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ExpenseCategory  $expenseCategory
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $data = ExpenseCategory::find($id);
        /**
         * TODO: check realtion in transaction table
         * If has transaction relate to it, this category cannot be delete
         */
        $data->delete();

        return response()->json(['message' => 'Success delete Category']);
    }
}
