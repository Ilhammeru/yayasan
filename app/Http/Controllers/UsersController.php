<?php

namespace App\Http\Controllers;

use App\Http\Services\UserService;
use App\Http\Services\WalletService;
use App\Models\Account;
use App\Models\Employees;
use App\Models\ExternalUser;
use App\Models\InstitutionClass;
use App\Models\InstitutionClassLevel;
use App\Models\InternalUser;
use App\Models\Intitution;
use App\Models\User;
use App\Models\Wallets;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Yajra\DataTables\Facades\DataTables;

class UsersController extends Controller
{
    public $vp;

    public $uservice;
    private $wallet;

    public function __construct(
        UserService $userService,
        WalletService $wallet,
    ) {
        $this->vp = 'master.users';
        $this->uservice = $userService;
        $this->wallet = $wallet;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->t;
        breadcrumb([
            [
                'name' => __('view.users'),
                'active' => false,
            ],
            [
                'name' => __('view.list'),
                'active' => false,
            ],
        ]);

        return view($this->vp.'.index', compact('type'));
    }

    public function ajax($type)
    {
        $data = $type == 'external' ? $this->tableExternal() : $this->tableInternal();

        return $data;
    }

    public function tableExternal()
    {
        $q = ExternalUser::query();

        if (request()->name) {
            $name = request()->name;
            $q->where('name', 'LIKE', "%$name%");
        }

        if (request()->user_type) {
            if (request()->user_type == 'all') {
                $q->where('user_type', '>', 0);
            } else {
                $q->where('user_type', request()->user_type);
            }
        }

        if (request()->status) {
            if (request()->status == 'all') {
                $q->where('status', '>=', 0);
            } else {
                $q->where('status', request()->status);
            }
        }

        $data = $q->select('*');

        return DataTables::eloquent($data)
            ->editColumn('name', function ($d) {
                return '<a href="#" onclick="showProfile(`external`, '.$d->id.', `'.__('view.detail_profile').'`)">'.ucfirst($d->name).'</a>';
            })
            ->editColumn('status', function ($d) {
                $text = 'inactive';
                $bg = 'warning';
                if ($d->status) {
                    $text = 'active';
                    $bg = 'success';
                }

                return '<span class="label label-'.$bg.'">'.$text.'</span>';
            })
            ->editColumn('user_type', function ($d) {
                $text = 'inactive';
                $bg = 'warning';
                if ($d->user_type == 1) {
                    $text = __('view.public');
                    $bg = 'success';
                } else {
                    $text = __('view.goverment');
                    $bg = 'primary';
                }

                return '<span class="label label-'.$bg.'">'.$text.'</span>';
            })
            ->addColumn('action', function ($d) {
                // $text =
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('.$d->id.', `'.__('view.update_user').'`, `external`)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteItem('.$d->id.', `'.__('view.delete_text').'`, `external`)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="gi gi-bin"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'institution', 'status', 'name', 'user_type'])
            ->toJson();
    }

    public function tableInternal()
    {
        $data = InternalUser::all();

        return DataTables::of($data)
            ->editColumn('name', function ($d) {
                return '<a href="#" onclick="showProfile(`internal`, '.$d->id.', `'.__('view.detail_profile').'`)">'.ucfirst($d->name).'</a>';
            })
            ->addColumn('institution', function ($d) {
                return $d->institution->name.' ('.$d->class->name.$d->level->name.')';
            })
            ->editColumn('status', function ($d) {
                $text = 'inactive';
                $bg = 'warning';
                if ($d->status) {
                    $text = 'active';
                    $bg = 'success';
                }

                return '<span class="label label-'.$bg.'">'.$text.'</span>';
            })
            ->addColumn('action', function ($d) {
                // $text =
                return '
                <div class="btn-group btn-group-xs">
                    <button type="button" onclick="updateForm('.$d->id.', `'.__('view.update_user').'`, `internal`)" data-toggle="tooltip" title="Edit" class="btn btn-default"><i class="fa fa-pencil"></i></button>
                    <button type="button" onclick="deleteItem('.$d->id.', `'.__('view.delete_text').'`, `internal`)" data-toggle="tooltip" title="Delete" class="btn btn-danger"><i class="gi gi-bin"></i></button>
                </div>
                ';
            })
            ->rawColumns(['action', 'institution', 'status', 'name'])
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($type)
    {
        $data = [];
        $param = [];
        $provinces = \Indonesia::allProvinces();
        if ($type == 'internal') {
            $classes = InstitutionClass::all();
            $levels = InstitutionClassLevel::all();

            $institutions = Intitution::active()->get();

            $view = view($this->vp.'.form_internal', compact('institutions', 'classes', 'levels', 'provinces'))->render();
        } elseif ($type == 'external') {
            $view = view($this->vp.'.form_external', compact('provinces'))->render();
        }

        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'url' => '/users/0/'.$type,
            'method' => 'POST',
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id, $type)
    {
        if ($type == 'internal') {
            $param = [
                'id' => $id,
                'first_record' => true,
                'type' => $type,
            ];
            $data = $this->uservice->get_data($param);
            $view = view($this->vp.'.profile_internal', compact('data'))->render();
        } elseif ($type == 'external') {
            $param = [
                'id' => $id,
                'first_record' => true,
                'type' => $type,
            ];
            $data = $this->uservice->get_data($param);
            $view = view($this->vp.'.profile_external', compact('data'))->render();
        }

        return response()->json([
            'message' => 'Success',
            'view' => $view,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, $type)
    {
        $data = [];
        $param = ['id' => $id, 'first_record' => true, 'type' => $type];
        $data = $this->uservice->get_data($param);

        // get all address component
        $province = \Indonesia::findProvince($data->province_id, ['cities', 'districts']);
        $cities = $province->cities;
        $districts = $province->districts;
        $provinces = \Indonesia::allProvinces();
        $provinces = collect($provinces)->map(function ($item) use ($province) {
            $item['selected'] = '';
            if ($item->id == $province->id) {
                $item['selected'] = 'selected';
            }

            return $item;
        })->all();
        $cities = collect($cities)->map(function ($item) use ($data) {
            $item['selected'] = '';
            if ($item->id == $data->city_id) {
                $item['selected'] = 'selected';
            }

            return $item;
        })->all();
        $districts = collect($districts)->map(function ($item) use ($data) {
            $item['selected'] = '';
            if ($item->id == $data->district_id) {
                $item['selected'] = 'selected';
            }

            return $item;
        })->all();

        if ($type == 'internal') {
            $ins_id = $data->institution->id;

            $classes = InstitutionClass::where('intitution_id', $ins_id)->get();
            $classes = collect($classes)->map(function ($item) use ($data) {
                $item['selected'] = '';
                if ($data->class->id == $item->id) {
                    $item['selected'] = 'selected';
                }

                return $item;
            })->all();

            $class_id = $data->institution_class_id;
            $levels = InstitutionClassLevel::where('institution_class_id', $class_id)->get();
            $levels = collect($levels)->map(function ($item) use ($data) {
                $item['selected'] = '';
                if ($data->level->id == $item->id) {
                    $item['selected'] = 'selected';
                }

                return $item;
            })->all();

            $institutions = Intitution::active()->get();
            $institutions = collect($institutions)->map(function ($item) use ($data) {
                $item['selected'] = '';
                if ($data->institution->id == $item->id) {
                    $item['selected'] = 'selected';
                }

                return $item;
            })->all();

            $view = view($this->vp.'.form_internal', compact('data', 'institutions', 'classes', 'levels', 'provinces', 'cities', 'districts'))->render();
        } elseif ($type == 'external') {
            $view = view($this->vp.'.form_external', compact('data', 'provinces', 'cities', 'districts'))->render();
        }

        return response()->json([
            'message' => 'Success',
            'view' => $view,
            'url' => '/users/'.$id.'/'.$type,
            'method' => 'POST',
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id, $type)
    {
        DB::beginTransaction();
        try {
            if ($type == 'internal') {
                $this->uservice->updateInternal($request, $id);
            } elseif ($type == 'external') {
                $this->uservice->updateExternal($request, $id);
            }

            DB::commit();

            return response()->json(['message' => 'Success update user']);
        } catch (\Throwable $th) {
            setup_log('error update usser',['file' => $th->getFile(), 'message' => $th->getMessage(), 'line' => $th->getLine()]);
            DB::rollBack();

            return response()->json(['message' => 'Failed to update data'], 500);
        }
    }

    /**
     * Function to generate class based on given institution_id
     *
     * @param int institution_id
     * @return JsonResponses
     */
    public function getClass(Request $request)
    {
        $id = $request->institution_id;
        $classes = InstitutionClass::where('intitution_id', $id)->get();

        return response()->json(['message' => 'Success', 'data' => $classes]);
    }

    /**
     * Function to generate level based on given class_id
     *
     * @param int class_id
     * @return JsonResponses
     */
    public function getLevel(Request $request)
    {
        $id = $request->class_id;
        $classes = InstitutionClassLevel::where('institution_class_id', $id)->get();

        return response()->json(['message' => 'Success', 'data' => $classes]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id, $type)
    {
        if ($type == 'internal') {
            $data = InternalUser::find($id);
            $data->delete();
        }

        return response()->json(['message' => 'Success delete user']);
    }

    /**
     * Function to update saldo user
     * 
     * @return JsonResponse
     */
    public function updateSaldo()
    {
        $saldo = get_user_saldo();
        $view = view($this->vp . '.user_wallet', compact('saldo'))->render();
        return $this->render_response($view);
    }

    /**
     * Function to render detail wallet view
     * @param int user_id
     * 
     * @return Renderable
     */
    public function detailWallet($user_id)
    {
        $data = $this->wallet->detail_wallet($user_id);
        
        $param_category_id = 0;
        if (count($data) > 0) {
            $keys = array_keys($data);
            $key_1 = $keys[0];
            $param_category_id = $data[$key_1]['income_category_id'];
        }

        $view = view($this->vp . '.wallet', compact('data', 'param_category_id'))->render();

        return $this->render_response($view);
    }

    /**
     * FUnction to render datatable for selected wallet category
     * @param int wallet_id
     */
    public function walletAjax()
    {
        $income_category_id = request()->income_category_id;
        $data = $this->wallet->detail_wallet_by_category($income_category_id);

        return DataTables::of($data)
            ->addColumn('checkbox', function($d) {
                return '<input type="checkbox" class="check-wallet-item" value="'. $d->id .'" id="check-wallet-'. $d->id .'" name="wallet_ids[]" />';
            })
            ->addColumn('invoice_number', function($d) {
                return $d->payment->invoice_number;
            })
            ->addColumn('user', function($d) {
                $payment = $d->payment;
                $user = $payment->userData();
                $res = '';
                if ($user) {
                    $res = $user->name;
                }
                return $res;
            })
            ->addColumn('amount', function($d) {
                return 'Rp. ' . number_format($d->debit, 0, '.', '.');
            })
            ->rawColumns(['invoice_number', 'user', 'amount', 'checkbox'])
            ->make(true);
    }

    /**
     * Function to render sending wallet form
     * @param string ids
     * @param int income_category_id
     * 
     * @return Renderable
     */
    public function formSendWallet(Request $request)
    {
        $ids = json_decode($request->ids, true);
        $income_category_id = $request->income_category_id;
        $data = [];
        for ($a = 0; $a < count($ids); $a++) {
            $data[] = $this->wallet->detail_wallet_by_id($ids[$a]);
        }
        $total = collect($data)->pluck('total_amount')->sum();
        /** 
         * get treasure account
         * Find the position who has permission to 'foundation finance'
         * and get the account
         */
        $role = Role::findByName('bendahara yayasan');
        if ($role->hasPermissionTo('foundation finance')) {
            $account_role = DB::table('model_has_roles')
                ->select('model_id')
                ->where('role_id', $role->id)
                ->get();
            $treasurer = [];
            if (count($account_role) > 0) {
                foreach ($account_role as $k => $ar) {
                    $users = User::find($ar->model_id);
                    $treasurer[] = Employees::where('user_id', $users->id)
                        ->first();
                }
            }
        }
        $view = view($this->vp . '.form_send_wallet', compact('ids', 'total', 'treasurer', 'income_category_id'))->render();

        return $this->render_response($view);
    }

    /**
     * Function to render sending wallet form
     * @param string ids
     * @param int income_category_id
     * 
     * @return Renderable
     */
    public function formTransferFund(Request $request)
    {
        $ids = json_decode($request->ids, true);
        $income_category_id = $request->income_category_id;
        $data = [];
        for ($a = 0; $a < count($ids); $a++) {
            $data[] = $this->wallet->detail_wallet_by_id($ids[$a]);
        }
        $total = collect($data)->pluck('total_amount')->sum();
        /** 
         * get available account
         */
        $account = Account::get();

        $view = view($this->vp . '.form_transfer_fund', compact('ids', 'total', 'account', 'income_category_id'))->render();

        return $this->render_response($view);
    }

    /**
     * When request has 'type' key
     * Then
     * Function to send wallet / payment by income category to treasurer
     * Deduct the wallet ballance of the sender
     * top up the wallet ballance of treasurer
     * 
     * If Not
     * Send to account
     * @param array attachments_send_wallet
     * @param string message
     * @param float total
     * @param int treasurer
     * @param int account
     * @param string type
     * @param array wallet_ids
     * @param int income_category_id
     */
    public function sendWallet(Request $request)
    {
        DB::beginTransaction();
        try {
            $rules = [
                'total' => 'required',
                'attachments_send_wallet' => 'required',
            ];
            if (!$request->has('type')) {
                $rules['treasurer'] = 'required';
            } else {
                $rules['account'] = 'required';
            }
            $request->validate($rules);
            $income_category_id = $request->income_category_id;

            if (!$request->has('type')) {
                $this->wallet->send_to_treasurer($request);
            } else {
                $this->wallet->send_to_account($request);
            }

            $wallet_category_amount = $this->wallet->amount_wallet_per_category($income_category_id);
            
            DB::commit();
            return $this->success_response(__('view.success_send_wallet'), ['wallet_category_amount' => $wallet_category_amount]);
        } catch (\Throwable $th) {
            DB::rollBack();
            setup_log('error send walelt', ['file' => $th->getFile(), 'message' => $th->getMessage(), 'line' => $th->getLine()]);

            return $this->error_response($th->getMessage());
        }
    }

    /**
     * Function to store image to temporary folder
     */
    public function uploadAttachment(Request $request)
    {
        if ($request->hasFile('attachments_send_wallet')) {
            $file = $request->file('attachments_send_wallet');
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
}
