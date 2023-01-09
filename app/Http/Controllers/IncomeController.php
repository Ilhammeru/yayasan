<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Http\Services\IncomeService;
use App\Http\Services\UserService;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\IncomeMethod;
use App\Models\IncomePayment;
use App\Models\IncomeType;
use App\Models\Intitution;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Yajra\DataTables\Facades\DataTables;

class IncomeController extends Controller
{
    public $vp;
    public $service;
    public $user_service;

    public function __construct(
        IncomeService $incomeService,
        UserService $userService
    )
    {
        $this->vp = 'incomes';
        $this->service = $incomeService;
        $this->user_service = $userService;
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
                'name' => __('view.income'),
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
        $q = Income::query();
        if (request()->status) {
            if (request()->status == 0) {
                $q->where('payment_status', '>=', 0);
            } else {
                $q->where('payment_status', request()->status);
            }   
        }
        $data = $q->get();
        return DataTables::of($data)
            ->addColumn('user', function($d) {
                return '<a href="#">'. $d->assignUser()->name .'</a>';
            })
            ->editColumn('invoice_number', function($d) { 
                return '<a href="'. route('incomes.show', $d->id) .'" id="index-invoice-number-'. $d->id .'">'. $d->invoice_number .'</a>';
            })
            ->editColumn('payment_status', function($d) {
                return '<span class="label" style="background: '. $d->payment_status_color .';">'. $d->payment_status_text .'</span>';
            })
            ->addColumn('remaining_bill', function($d) {
                return 'Rp.' . number_format($d->remaining_amount, 0, '.', ',');  
            })
            ->editColumn('total_amount', function($d) {
                return 'Rp.' . number_format($d->total_amount, 0, '.', ',');
            })
            ->editColumn('transaction_start_date', function($d) {
                $date = date('d F Y', strtotime($d->transaction_start_date));
                if (config('app.locale') === 'in') {
                    $date = generate_indo_date($d->transaction_start_date);
                }
                return $date;
            })
            ->editColumn('due_date', function($d) {
                $date = date('d F Y', strtotime($d->due_date));
                if (config('app.locale') === 'in') {
                    $date = generate_indo_date($d->due_date);
                }
                return $date;
            })
            ->addColumn('tippy_content', function($d) {
                $items = $d->items;
                $all_items = [];
                foreach($items as $item) {
                    $category = IncomeCategory::select('name')
                        ->where('id', $item->income_category_id)
                        ->first();
                    $category_name = $category->name;
                    $all_items[] = $category_name;
                }

                $element = '';
                for ($a = 0; $a < count($all_items); $a++) {
                    $element .= '
                        <div class="invoice-tooltip" style="border-bottom: 1px solid #fff; padding: 8px 12px;">
                        '. $all_items[$a] .'
                        </div>
                    ';
                }

                return $element;
            })
            ->rawColumns(['user', 'payment_status', 'remaining_bill', 'total_amount', 'transaction_start_date', 'due_date', 'invoice_number', 'tippy_content'])
            ->make(true);
    }

    /**
     * Function to generate transaction view based on payment status
     * Show transaction form if unpaid / partially paid
     * And show transaction recap if paid
     * 
     * @param int income_id
     * 
     * @return JsonResponse
     */
    public function generateTransaction(Request $request)
    {
        $id = $request->income_id;
        $data = Income::find($id);
        if ($data->payment_status == 1) {
            $view = view('incomes.components.transaction_recap', compact('data'))->render();
        } else {
            $view = view('incomes.components.transaction_form', compact('data'))->render();
        }

        return $this->render_custom_response($view, ['status' => $data->payment_status_text]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        breadcrumb([
            [
                'name' => __('view.income'),
                'active' => true,
                'href' => route('incomes.index'),
            ],
            [
                'name' => __('view.create_invoice'),
                'active' => false
            ],
        ]);
        $users = $this->service->get_all_user();
        $suggest_number = generate_invoice_number();
        $institutions = Intitution::active()->get();
        $incomeTypes = IncomeType::all();
        $incomeMethods = IncomeMethod::all();
        $incomeCategories = IncomeCategory::all();
        return view($this->vp . '.create', compact('users', 'suggest_number', 'institutions', 'incomeTypes', 'incomeMethods', 'incomeCategories'));
    }

    /**
     * Function to store image to temporary folder
     */
    public function uploadAttachment(Request $request)
    {
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            $folder = uniqid() . '-' . now()->timestamp;
            $ext = $file[0]->getClientOriginalExtension();
            $filename = 'temporary-' . now()->timestamp . '.' . $ext;
            $file[0]->storeAs('tmp/' . $folder, $filename, 'public');

            return $folder . '@@' . $filename . '@@' . '.' . $ext;
        }

        return '';
    }

    /**
     * Function to delete image and temporary folder
     *
     * This function run when user remove file during the upload action
     */
    public function deleteAttachment(Request $request) {
        $content = $request->getContent();
        if ($content) {
            $folder = '';
            $exp = explode('@@', (string)$content);
            $folder = str_replace(' ', '', $exp[0]);
            $link = public_path('storage/tmp/' . $folder . '/' . $exp[1]);
            unlink($link);
            rmdir(public_path('storage/tmp/' . $folder));
        }
        return '';
    }

    /**
     * Function to store image to temporary folder
     */
    public function uploadProof(Request $request)
    {
        if ($request->hasFile('proof_of_payment')) {
            $file = $request->file('proof_of_payment');
            $folder = uniqid() . '-' . now()->timestamp;
            $ext = $file[0]->getClientOriginalExtension();
            $filename = 'temporary-' . now()->timestamp . '.' . $ext;
            $file[0]->storeAs('tmp/' . $folder, $filename, 'public');

            return $folder . '@@' . $filename . '@@' . '.' . $ext;
        }

        return '';
    }

    /**
     * Function to delete image and temporary folder
     *
     * This function run when user remove file during the upload action
     */
    public function deleteProof(Request $request) {
        $content = $request->getContent();
        if ($content) {
            $folder = '';
            $exp = explode('@@', (string)$content);
            $folder = str_replace(' ', '', $exp[0]);
            $link = public_path('storage/tmp/' . $folder . '/' . $exp[1]);
            unlink($link);
            rmdir(public_path('storage/tmp/' . $folder));
        }
        return '';
    }

    /**
     * Function to generate item row
     * @param int len
     * 
     * @return JsonResponse
     */
    public function buildItemRow(Request $request)
    {
        $len = $request->len;
        $incomeCategories = IncomeCategory::all();
        $view = view($this->vp . '.components.item_row', compact('len', 'incomeCategories'))->render();

        return $this->render_response($view);
    }

    /**
     * Function to get detail user 
     * @param int user_id
     * @return JsonResponse
     */
    public function getDetailuser(Request $request)
    {
        $req = explode('-', $request->user_id);
        $user_id = $req[0];
        $type = $req[1];
        $param = ['id' => $user_id, 'first_record' => true, 'type' => $type];
        $detail = $this->user_service->get_data($param);
        $view = view($this->vp . '.components.user_detail', compact('detail'))->render();

        // create select option if type is internal
        $institutions = Intitution::active()->get();
        $institutions = collect($institutions)->map(function($item) use($detail, $type) {
            $item['selected'] = '';

            if ($type == 'internal') {
                if ($item->id == $detail->institution->id) {
                    $item['selected'] = 'selected';
                }
            }

            return $item;
        });

        return $this->render_custom_response($view, ['institutions' => $institutions, 'type' => $type]);
    }

    /**
     * Function to check invoice number is taken or not
     * @param string invoice_number
     * @return JsonResponse
     */
    public function checkInvoiceNumber(Request $request)
    {
        $number = $request->invoice_number;
        $data = Income::select('id')->where('invoice_number', $number)->count();
        $available = true;
        if ($data > 0) {
            $available = false;
        }

        return $this->success_response('success', ['available' => $available]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(IncomeRequest $request)
    {
        DB::beginTransaction();
        try {
            $save = $this->service->create_invoice($request);

            DB::commit();
            return $this->success_response(__('view.success_create_invoice'), ['item' => $save]);
        } catch (\throwable $th) {
            DB::rollBack();
            return $this->error_response($th->getMessage());
        }
    }

    /**
     * Function to show detail proof of payment
     * @param int income_payment_id
     * 
     * @return JsonResponse
     */
    public function proofOfPayment(Request $request)
    {
        $id = $request->income_payment_id;
        $data = IncomePayment::find($id);
        $images = $data->media;
        $view = view($this->vp . '.components.proof_payment', compact('images'))->render();
        return $this->render_response($view);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        breadcrumb([
            [
                'name' => __('view.income'),
                'active' => true,
                'href' => route('incomes.index'),
            ],
            [
                'name' => __('view.detail_invoice'),
                'active' => false
            ],
        ]);
        $users = $this->service->get_all_user();
        $suggest_number = generate_invoice_number();
        $institutions = Intitution::active()->get();
        $incomeTypes = IncomeType::all();
        $incomeMethods = IncomeMethod::all();
        $incomeCategories = IncomeCategory::all();
        $data = Income::with(['items.category', 'media', 'method'])->find($id);
        return view($this->vp . '.show', compact('data'));
    }

    /**
     * Function to build html for detail payment in given invoice id
     * @param int income_id
     * 
     * @return JsonResponse
     */
    public function appendPaymentDetail(Request $request)
    {
        $income_id = $request->income_id;
        $element = $this->service->generate_payment_detail($income_id);
        return $this->success_response('Success', ['tr' => $element['rows'], 'detail' => $element['data']]);
    }

    /**
     * Function to handle payment in given invoice id
     * @param string payment_amount
     * @param int income_id
     * @param blob proof_of_payment
     * @param string transaction_date
     * 
     * @return JsonResponse
     */
    public function pay(Request $request)
    {
        DB::beginTransaction();
        try {
            $proof = $request->proof_of_payment;
            if (!$proof) {
                DB::rollBack();
                return $this->error_response(__('view.proof_payment_required'));
            }
            if (count(array_filter($proof)) == 0) {
                DB::rollBack();
                return $this->error_response(__('view.proof_payment_required'));
            }

            $this->service->pay_invoice($request);

            DB::commit();
            return $this->success_response(__('view.payment_success'), $request->income_id);
        } catch (\Throwable $th) {
            DB::rollBack();
            setup_log('error payment', ['data' => $th]);
            return $this->error_response($th->getMessage());
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Income  $income
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
