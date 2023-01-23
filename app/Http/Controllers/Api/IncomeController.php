<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Services\IncomeService;
use App\Models\InternalUser;
use Illuminate\Http\Request;

class IncomeController extends Controller
{
    private $service;

    public function __construct(
        IncomeService $service
    )
    {
        $this->service = $service; 
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Generate monthly view
     * @param array param
     * @return Renderable
     */
    public function reloadPeriodView(Request $request)
    {
        $institution_id = $request->institution_id;
        $class_id = $request->class_id;
        $level_id = $request->level_id;
        $income_category = $request->income_category;
        $income_type_id = $request->income_type_id;
        $income_category_name = $request->income_category_name;

        $students = InternalUser::with(['payments.docs'])
            ->where('institution_id', $institution_id)
            ->where('institution_class_id', $class_id)
            ->where('institution_class_level_id', $level_id)
            ->active()
            ->get();
        $monthly_payments = $this->service->get_period_payments($students);

        return $monthly_payments;
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
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
