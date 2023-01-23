<?php

namespace App\Http\Controllers;

use App\Http\Requests\IncomeRequest;
use App\Http\Services\IncomeService;
use App\Http\Services\UserService;
use App\Http\Services\WalletService;
use App\Models\Employees;
use App\Models\Income;
use App\Models\IncomeCategory;
use App\Models\IncomeMethod;
use App\Models\IncomePayment;
use App\Models\IncomeType;
use App\Models\InstitutionClass;
use App\Models\InternalUser;
use App\Models\Intitution;
use App\Models\Payments;
use App\Models\Position;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redis;
use Illuminate\Support\Facades\Route;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
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
    ) {
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
        $url = request()->url();
        $url = explode('/', $url);
        $hashid = array_pop($url);
        $institution_id = Hashids::decode($hashid)[0];
        $reload = request()->r;
        breadcrumb([
            [
                'name' => __('view.income'),
                'active' => false,
            ],
            [
                'name' => __('view.list'),
                'active' => false,
            ],
        ]);
        $income_categories = IncomeCategory::all();
        $data = Intitution::with(['incomeCategories.category.type', 'classes.levels'])
            ->where('id', $institution_id)
            ->first();
        $classes = $data->classes;
        /**
         * Create default param and apply to frontend
         */
        $selected_incomes = $data->incomeCategories;
        $selected_income = null;
        $income_type_period = null;
        $income_category = null;
        $income_category_name = null;
        $income_type_id = null;
        $class_id = 0;
        $level_id = 0;
        $param = [];
        $levels = [];
        if (count($selected_incomes) > 0) {
            $selected_income = $selected_incomes[0];
            $income_type_period = $selected_income->category->type->period;
            $income_type_id = $selected_income->category->type->id;
            $income_category_name = $selected_income->category->name;
            $income_category = $selected_income->category->id;
            
            if (count($classes) > 0) {
                $class_level = $this->service->get_exist_class($classes);
                $class_id = $class_level['class_id'];
                $level_id = $class_level['level_id'];
                $levels = $class_level['levels'];
                $param = [
                    'institution_id' => $institution_id,
                    'class_id' => $class_level['class_id'],
                    'level_id' => $class_level['level_id'],
                    'levels' => $class_level['levels']
                ];
            }

        }

        /**
         * Return empty-data page if selected institution doesn't have income categories
         * 
         */
        if (count($selected_incomes) == 0) {
            return view($this->vp . '.empty_data');
        }

        /**
         * Render view based on income_type_period
         * and apply default param that has been created above
         * 
         */
        $param_view = [
            'institution_id' => $institution_id,
            'class_id' => $class_id,
            'level_id' => $level_id,
            'income_category' => $income_category,
            'income_type_id' => $income_type_id,
            'income_category_name' => $income_category_name,
            'income_type_period' => $income_type_period,
        ];
        if ($income_type_period != 1 || $income_type_period != 5) {
            $view = $this->reloadPeriodView($param_view);
        } else {
            $view = $this->reloadTableView($param_view);
        }

        return view($this->vp.'.index', compact(
            'income_categories',
            'income_category',
            'class_id',
            'view',
            'classes',
            'institution_id',
        ));
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

        // filter institution
        if (request()->institution_id) {
            $q->where('institution_id', request()->institution_id);
        }

        // filter class
        if (request()->class_id) {
            $class_id = request()->class_id;
            $q->where('user_type', 1);
            $q->whereHas('internal', function (Builder $query) use ($class_id) {
                $query->where('institution_class_id', $class_id);
            });
        }

        // $data = $q->get();
        $data = [];

        return DataTables::of($data)
            ->addColumn('user', function ($d) {
                return '<a href="#">'.$d->assignUser()->name.'</a>';
            })
            ->editColumn('invoice_number', function ($d) {
                return '<a href="'.route('incomes.show', $d->id).'" id="index-invoice-number-'.$d->id.'">'.$d->invoice_number.'</a>';
            })
            ->editColumn('payment_status', function ($d) {
                return '<span class="label" style="background: '.$d->payment_status_color.';">'.$d->payment_status_text.'</span>';
            })
            ->addColumn('remaining_bill', function ($d) {
                return 'Rp.'.number_format($d->remaining_amount, 0, '.', ',');
            })
            ->editColumn('total_amount', function ($d) {
                return 'Rp.'.number_format($d->total_amount, 0, '.', ',');
            })
            ->editColumn('transaction_start_date', function ($d) {
                $date = date('d F Y', strtotime($d->transaction_start_date));
                if (config('app.locale') === 'in') {
                    $date = generate_indo_date($d->transaction_start_date);
                }

                return $date;
            })
            ->editColumn('due_date', function ($d) {
                $date = date('d F Y', strtotime($d->due_date));
                if (config('app.locale') === 'in') {
                    $date = generate_indo_date($d->due_date);
                }

                return $date;
            })
            ->addColumn('tippy_content', function ($d) {
                $items = $d->items;
                $all_items = [];
                foreach ($items as $item) {
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
                        '.$all_items[$a].'
                        </div>
                    ';
                }

                return $element;
            })
            ->rawColumns(['user', 'payment_status', 'remaining_bill', 'total_amount', 'transaction_start_date', 'due_date', 'invoice_number', 'tippy_content'])
            ->make(true);
    }

    public function generateData(Request $request)
    {
        $institution_id = Hashids::decode($request->institution_id);
        $income_category = Intitution::with(['incomeCategories'])->where('id', $institution_id)->first();
        return response()->json($income_category);
    }

    /**
     * Function to generate transaction view based on payment status
     * Show transaction form if unpaid / partially paid
     * And show transaction recap if paid
     *
     * @param int income_id
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
     * @param int institution_id
     * @param int class_id
     * @param int level_id
     * @param int user_id
     * @param string month
     * @param int income_category_id
     * @param int income_type_id
     * @param int user_type --> 1 is internal 2 is external
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $institution_id = $request->institution_id;
        $class_id = $request->class_id;
        $level_id = $request->level_id;
        $user_id = $request->user_id;
        $month = $request->month;
        $selected_month = add_zero($month) . '-' . date('Y');
        $income_category_id = $request->income_category_id;
        $income_type_id = $request->income_type_id;
        $user_type = $request->user_type;
        
        $users = $this->service->get_all_user(['class_id' => $class_id, 'level_id' => $level_id]);
        $institutions = Intitution::active()->get();
        $incomeCategories = IncomeCategory::all();
        $incomeTypes = IncomeType::all();
        $enable_all = false;
        /**
         * Modify some collections -> add 'selected' key
         * if id or type in collection match with given request
         * set 'selected' key to TRUE
         * Default is FALSE
         * 
         */
        $users = collect($users)->map(function ($item) use ($user_id, $user_type) {
            $user_type = $user_type == 1 ? 'internal' : 'external';
            $item['selected'] = false;
            if (
                $user_id == $item->id &&
                $user_type == $item->type
                ) {
                    $item['selected'] = true;
                }
                
            return $item;
        })->all();
        $institutions = collect($institutions)->map(function ($item) use ($institution_id) {
            $item['selected'] = false;
            if ($item->id == $institution_id) {
                $item['selected'] = true;
            }
            
            return $item;
        })->all();
        $incomeCategories = collect($incomeCategories)->map(function ($item) use ($income_category_id) {
            $item['selected'] = false;

            if ($item->id == $income_category_id) {
                $item['selected'] = true;
            }
            
            return $item;
        })->all();
        $incomeTypes = collect($incomeTypes)->map(function ($item) use ($income_type_id) {
            $item['selected'] = false;

            if ($item->id == $income_type_id) {
                $item['selected'] = true;
            }
            
            return $item;
        })->all();
        
        $incomeMethods = IncomeMethod::all();
        $suggest_number = generate_invoice_number();

        /**
         * This condition came from function reloadTableView and reloadPeriodView
         * Render if render_create_income session is FALSE
         * Redirect if render_create_income session is TRUE
         */
        if (session('render_create_income')) {
            $view_path = $this->vp . '.create';
        } else {
            $view_path = $this->vp . '.components.main_content.invoice';
        }

        $view = view($view_path, compact(
                'users','suggest_number',
                'institutions', 'incomeTypes',
                'incomeMethods', 'incomeCategories',
                'income_category_id', 'selected_month',
                'class_id', 'level_id', 'enable_all',
            ));
        return $this->render_response($view->render());
    }

    /**
     * Function to render invoice view for non period type
     * @param int institution_id
     * @param int class_id
     * @param int level_id
     * @param int income_category_id
     * @param int income_type_id
     * @return Renderable
     */
    public function invoiceNonPeriod(Request $request)
    {
        $institution_id = $request->institution_id;
        $class_id = $request->class_id;
        $level_id = $request->level_id;
        $income_category_id = $request->income_category_id;
        $income_type_id = $request->income_type_id;
        $users = $this->service->get_all_user(['class_id' => $class_id, 'level_id' => $level_id]);
        $institutions = Intitution::active()->get();
        $incomeCategories = IncomeCategory::all();
        $incomeTypes = IncomeType::all();
        $selected_month = null;
        $enable_all = true;
        /**
         * Modify some collections -> add 'selected' key
         * if id or type in collection match with given request
         * set 'selected' key to TRUE
         * Default is FALSE
         * 
         */
        $incomeCategories = collect($incomeCategories)->map(function ($item) use ($income_category_id) {
            $item['selected'] = false;

            if ($item->id == $income_category_id) {
                $item['selected'] = true;
            }
            
            return $item;
        })->all();
        $incomeTypes = collect($incomeTypes)->map(function ($item) use ($income_type_id) {
            $item['selected'] = false;

            if ($item->id == $income_type_id) {
                $item['selected'] = true;
            }
            
            return $item;
        })->all();
        $institutions = collect($institutions)->map(function ($item) use ($institution_id) {
            $item['selected'] = false;
            if ($item->id == $institution_id) {
                $item['selected'] = true;
            }
            
            return $item;
        })->all();
        
        $incomeMethods = IncomeMethod::all();
        $suggest_number = generate_invoice_number();

        $view = view($this->vp . '.components.main_content.invoice', compact(
                'users','suggest_number',
                'institutions', 'incomeTypes',
                'incomeMethods', 'incomeCategories',
                'income_category_id', 'selected_month',
                'class_id', 'level_id', 'enable_all'
            ))->render();

        return $this->render_response($view);
    }

    /**
     * Function to store image to temporary folder
     */
    public function uploadAttachment(Request $request)
    {
        if ($request->hasFile('attachments')) {
            $file = $request->file('attachments');
            $folder = uniqid().'-'.now()->timestamp;
            $ext = $file[0]->getClientOriginalExtension();
            $filename = 'temporary-'.now()->timestamp.'.'.$ext;
            $file[0]->storeAs('tmp/'.$folder, $filename, 'public');

            return $folder.'@@'.$filename.'@@'.'.'.$ext;
        }

        return '';
    }

    /**
     * Function to delete image and temporary folder
     *
     * This function run when user remove file during the upload action
     */
    public function deleteAttachment(Request $request)
    {
        $content = $request->getContent();
        if ($content) {
            $folder = '';
            $exp = explode('@@', (string) $content);
            $folder = str_replace(' ', '', $exp[0]);
            $link = public_path('storage/tmp/'.$folder.'/'.$exp[1]);
            unlink($link);
            rmdir(public_path('storage/tmp/'.$folder));
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
            $folder = uniqid().'-'.now()->timestamp;
            $ext = $file[0]->getClientOriginalExtension();
            $filename = 'temporary-'.now()->timestamp.'.'.$ext;
            $file[0]->storeAs('tmp/'.$folder, $filename, 'public');

            return $folder.'@@'.$filename.'@@'.'.'.$ext;
        }

        return '';
    }

    /**
     * Function to delete image and temporary folder
     *
     * This function run when user remove file during the upload action
     */
    public function deleteProof(Request $request)
    {
        $content = $request->getContent();
        if ($content) {
            $folder = '';
            $exp = explode('@@', (string) $content);
            $folder = str_replace(' ', '', $exp[0]);
            $link = public_path('storage/tmp/'.$folder.'/'.$exp[1]);
            unlink($link);
            rmdir(public_path('storage/tmp/'.$folder));
        }

        return '';
    }

    /**
     * Function to generate item row
     *
     * @param int len
     * @param int income_category_id --> This can be 0
     * @return JsonResponse
     */
    public function buildItemRow(Request $request)
    {
        $len = $request->len;
        $income_category_id = $request->income_category_id;
        $is_enable = $request->is_enable;
        $incomeCategories = IncomeCategory::all();
        $incomeCategories = collect($incomeCategories)->map(function ($item) use ($income_category_id) {
            $item['selected'] = false;
            if ($item->id == $income_category_id) {
                $item['selected'] = true;
            }

            return $item;
        })->all();
        $view = view($this->vp.'.components.item_row', compact('len', 'incomeCategories', 'is_enable'))->render();

        return $this->render_response($view);
    }

    /**
     * Function to get detail user
     *
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
        $view = view($this->vp.'.components.user_detail', compact('detail'))->render();

        // create select option if type is internal
        $institutions = Intitution::active()->get();
        $institutions = collect($institutions)->map(function ($item) use ($detail, $type) {
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
     *
     * @param string invoice_number
     * @return JsonResponse
     */
    public function checkInvoiceNumber(Request $request)
    {
        $number = $request->invoice_number;
        $data = Payments::select('id')->where('invoice_number', $number)->count();
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
     *
     * @param int income_payment_id
     * @return JsonResponse
     */
    public function proofOfPayment(Request $request)
    {
        $id = $request->income_payment_id;
        $data = IncomePayment::find($id);
        $images = $data->media;
        $view = view($this->vp.'.components.proof_payment', compact('images'))->render();

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
        $data = Payments::with([
            'docs',
            'incomeMethod:id,name',
            'incomeCategory:id,name'
        ])
            ->find($id);
        $user_data = $data->userData();

        $print_mode = false;
        if (request()->print) {
            $print_mode = true;
            return view($this->vp.'.show', compact('data', 'user_data', 'print_mode'));
        }

        $view = view($this->vp.'.show', compact('data', 'user_data', 'print_mode'))->render();
        
        return $this->render_response($view);
    }

    /**
     * Function to build html for detail payment in given invoice id
     *
     * @param int income_id
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
     *
     * @param int amount_total
     * @param array attachments
     * @param int class_id
     * @param int income_method_id
     * @param int income_type_id
     * @param int institution_id
     * @param string invoice_number
     * @param array items
     * @param int level_id
     * @param int remaining_bill
     * @param string transaction_start_date
     * @param string user
     * 
     * @return JsonResponse
     */
    public function pay(IncomeRequest $request)
    {
        DB::beginTransaction();
        try {
            // validate month
            $items = $request->items;
            $items = array_values($items);
            $months = collect($items)->pluck('month')->unique()->all();
            $prices = collect($items)->pluck('price')->all();
            if (count($months) < count($items) && count($items) > 1) {
                DB::rollBack();
                return $this->error_response(__('view.month_cannot_be_same'));
            }
            for ($a = 0; $a < count($prices); $a++) {
                if ($prices[$a] == 0) {
                    DB::rollBack();
                    return $this->error_response(__('view.price_cannot_null'));
                }
            }

            $pay = $this->service->pay_invoice($request);
            if ($pay['error']) {
                DB::rollBack();
                return $this->error_response($pay['message']);
            }

            $income_category = IncomeCategory::select('name')
                ->where('id', $request->items[0]['income_category_id'])
                ->first();
            $income_category_name = $income_category->name;
            DB::commit();

            /**
             * generate params to reload element
             * Then run reloadPeriodView() function to generate view based on this param
             */
            $period = IncomeType::select('period')
                ->find($request->income_type_id);
            $period = $period->period;
            $param = [
                'class_id' => $request->class_id,
                'level_id' => $request->level_id,
                'institution_id' => $request->institution_id,
                'income_category' => $request->items[0]['income_category_id'],
                'income_type_id' => $request->income_type_id,
                'income_category_name' => $income_category_name,
                'income_type_period' => $period,
            ];
            $view = $this->reloadPeriodView($param);

            return $this->success_response(
                __('view.payment_success'),
                [
                    'view' => $view,
                    'param' => $param
                ]
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            setup_log('error payment', ['data' => $th->getMessage(), 'line' => $th->getLine(), 'file' => $th->getFile()]);

            return $this->error_response($th->getMessage());
        }
    }

    /**
     * Function to pay invoice from non period type
     * @param int amount_total
     * @param array attachments
     * @param int class_id
     * @param int income_method_id
     * @param int income_type_id
     * @param int institution_id
     * @param string invoice_number
     * @param array items
     * @param int level_id
     * @param int remaining_bill
     * @param string transaction_start_date
     * @param string user
     * 
     * @return JsonResponse
     */
    public function payNonPeriod(Request $request)
    {
        // return $this->success_response('oke', $request->all());
        DB::beginTransaction();
        try {
            // validate month
            $items = $request->items;
            $items = array_values($items);
            $prices = collect($items)->pluck('price')->all();
            for ($a = 0; $a < count($prices); $a++) {
                if ($prices[$a] == 0) {
                    DB::rollBack();
                    return $this->error_response(__('view.price_cannot_null'));
                }
            }

            $pay = $this->service->pay_invoice($request);
            if ($pay['error']) {
                DB::rollBack();
                return $this->error_response($pay['message']);
            }

            $income_category = IncomeCategory::select('name')
                ->where('id', $request->items[0]['income_category_id'])
                ->first();
            $income_category_name = $income_category->name;
            DB::commit();

            /**
             * generate params to reload element
             * Then run reloadPeriodView() function to generate view based on this param
             */
            $period = IncomeType::select('period')
                ->find($request->income_type_id);
            $period = $period->period;
            $param = [
                'class_id' => $request->class_id,
                'level_id' => $request->level_id,
                'institution_id' => $request->institution_id,
                'income_category' => $request->items[0]['income_category_id'],
                'income_type_id' => $request->income_type_id,
                'income_category_name' => $income_category_name,
                'income_type_period' => $period,
            ];
            
            if ($period == 1 || $period == 5) {
                $view = $this->reloadTableView($param);
            } else {
                $view = $this->reloadPeriodView($param);
            }

            return $this->success_response(
                __('view.payment_success'),
                [
                    'view' => $view,
                    'param' => $param
                ]
            );
        } catch (\Throwable $th) {
            DB::rollBack();
            setup_log('error payment', ['data' => $th->getMessage(), 'line' => $th->getLine(), 'file' => $th->getFile()]);

            return $this->error_response($th->getMessage());
        }
    }

    public function changeMonthlyIncomceByLevel(Request $request)
    {
        $institution_id = $request->institution_id;
        $level_id = $request->level_id;
        $class_id = $request->class_id;
        $income_category = $request->income_category_id;
        $income_type_id = $request->income_type_id;
        $income_category_name = $request->income_category_name;
        $income_type_period = $request->income_type_period;
        $param = [
            'institution_id' => $institution_id,
            'class_id' => $class_id,
            'level_id' => $level_id,
            'income_category' => $income_category,
            'income_type_id' => $income_type_id,
            'income_category_name' => $income_category_name,
            'income_type_period' => $income_type_period,
        ];

        if ($income_type_period != 1) {
            $view = $this->reloadPeriodView($param);
        } else {
            $view = $this->reloadTableView($param);
        }

        return $this->render_response($view);
    }

    /**
     * Reload data and Generate monthly view
     * @param mix param
     * @param int institution_id
     * @param int class_id
     * @param int level_id
     * @param int income_category
     * @param int income_type_id
     * @param string income_category_name
     * @param string income_type_period
     * @return Renderable
     */
    public function reloadPeriodView($param)
    {
        $institution_id = $param['institution_id'];
        $class_id = $param['class_id'];
        $level_id = $param['level_id'];
        $income_category = $param['income_category'];
        $income_type_id = $param['income_type_id'];
        $income_category_name = $param['income_category_name'];
        $income_type_period = $param['income_type_period'];
        $levels = $this->service->get_all_levels_of_given_class($institution_id, $class_id);

        $students = InternalUser::with(['payments.docs'])
            ->where('institution_id', $param['institution_id'])
            ->where('institution_class_id', $param['class_id'])
            ->where('institution_class_level_id', $param['level_id'])
            ->active()
            ->get();
        $monthly_payments = $this->service->get_period_payments($students, $income_type_period, $income_category);
        $calendar = $monthly_payments['calendar'];
        $data_user = $monthly_payments['users'];

        // set session to manipulate 'create.blade.php' view
        session(['render_create_income' => false]);

        $view = view($this->vp . '.components.main_content.income_period', compact(
                'calendar',
                'data_user',
                'institution_id',
                'class_id',
                'level_id',
                'income_category',
                'income_type_id',
                'income_category_name',
                'levels',
                'income_type_period',
            ))
            ->render();

        return $view;
    }

    /**
     * Reload data and Generate table view
     * @param mix param
     * @param int institution_id
     * @param int class_id
     * @param int level_id
     * @param int income_category
     * @param int income_type_id
     * @param string income_category_name
     * @param string income_type_period
     * @return Renderable
     */
    public function reloadTableView($param)
    {
        $datatable_url = '/incomes/data/ajax';
        $datatable_param = (object) $param;
        $table_head = [
            __('view.invoice'),
            __('view.name'),
            __('view.payment_method'),
            __('view.amount')
        ];

        $institution_id = $param['institution_id'];
        $class_id = $param['class_id'];
        $level_id = $param['level_id'];
        $income_category = $param['income_category'];
        $income_type_id = $param['income_type_id'];
        $income_category_name = $param['income_category_name'];
        $income_type_period = $param['income_type_period'];
        $levels = $this->service->get_all_levels_of_given_class($institution_id, $class_id);

        // set session to manipulate 'create.blade.php' view
        session(['render_create_income' => true]);

        $view = view($this->vp . '.components.main_content.income_table_view', compact(
            'datatable_url',
            'datatable_param',
            'table_head',
            'levels',
            'class_id',
            'level_id',
            'institution_id',
            'income_category',
            'income_type_id',
            'income_type_period',
            'income_category_name',
        ))->render();

        return $view;
    }

    public function datatable()
    {
        $institution_id = request()->institution_id;
        $class_id = request()->class_id;
        $level_id = request()->level_id;
        $income_category = request()->income_category;
        $income_type_id = request()->income_type_id;
        $income_category_name = request()->income_category_name;
        $income_type_period = request()->income_type_period;

        $data = Payments::where('income_category_id', $income_category)
            ->where('institution_id', $institution_id)
            ->where('institution_class_id', $class_id)
            ->where('institution_class_level_id', $level_id)
            ->get();

        return DataTables::of($data)
            ->editColumn('invoice_number', function($d) {
                return '<a onclick="detailPaidInvoice('. $d->id .')">'. $d->invoice_number .'</a>';
            })
            ->addColumn('attachment', function($d) {
                $docs = $d->docs;
                $res = '-';
                if (count($docs) > 0) {
                    $res = __('view.view_attachment');
                }
                return $res;
            })
            ->addColumn('user_name', function($d) {
                $user = $d->userData();
                $name = ucfirst($user->name);
                return $name;
            })
            ->editColumn('income_method_id', function($d) {
                return strtoupper($d->incomeMethod->name);
            })
            ->editColumn('amount', function($d) {
                return 'Rp. ' . number_format($d->amount, 0, '.', '.');
            })
            ->rawColumns(['attachment', 'user_name', 'amount', 'invoice_number'])
            ->make(true);
    }

    public function filterIncome(Request $request)
    {
        $class_id = $request->class_id;
        $institution_id = $request->institution_id;
        $income_category_id = $request->income_category_id;

        // get income type period
        $income_category = IncomeCategory::with('type')
            ->find($income_category_id);
        $period = $income_category->type->period;

        /**
         * Generate a view based on period
         */
        $class = InstitutionClass::with('levels')
            ->where('id', $class_id)
            ->first();
        $levels = $class->levels;
        $level_id = 0;
        if (count($levels) > 0) {
            $level_id = $levels[0]->id;
        }

        $param = [
            'institution_id' => $institution_id,
            'class_id' => $class_id,
            'level_id' => $level_id,
            'income_category' => $income_category_id,
            'income_type_id' => $income_category->type->id,
            'income_category_name' => $income_category->name,
            'levels' => $levels,
            'income_type_period' => $period,
        ];

        if ($period == 1 || $period == 5) {
            $view = $this->reloadTableView($param);
        } else {
            $view = $this->reloadPeriodView($param);
        }

        return $this->render_response($view);
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
