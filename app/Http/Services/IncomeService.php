<?php

namespace App\Http\Services;

use App\Models\Employees;
use App\Models\ExternalUser;
use App\Models\Income;
use App\Models\IncomeItem;
use App\Models\IncomeMedia;
use App\Models\IncomePayment;
use App\Models\IncomePaymentMedia;
use App\Models\InstitutionClass;
use App\Models\InternalUser;
use App\Models\Intitution;
use App\Models\PaymentDocs;
use App\Models\Payments;
use App\Models\Position;
use Auth;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class IncomeService
{
    private $wallet;

    public function __construct(
        WalletService $wallet
    )
    {
        $this->wallet = $wallet;
    }

    /**
     * Function to get all user type
     *
     * @return void
     */
    public function get_all_user($param = [])
    {
        $internal_query = InternalUser::query();
        $internal_query->active();
        if (isset($param['class_id'])) {
            $internal_query->where('institution_class_id', $param['class_id']);
        }
        if (isset($param['level_id'])) {
            $internal_query->where('institution_class_level_id', $param['level_id']);
        }
        $internal = $internal_query->get();
        $external = ExternalUser::active()->get();
        $all = [];
        foreach ($internal as $in) {
            $in['type'] = $in->type;
            $all[] = $in;
        }
        foreach ($external as $ex) {
            $ex['type'] = $ex->type;
            $all[] = $ex;
        }

        return $all;
    }

    /**
     * Function to create new invoice
     */
    public function create_invoice($request)
    {
        $user_req = explode('-', $request->user);
        $user_id = $user_req[0];
        $user_type = $user_req[1];
        $model = new Income();
        $model->invoice_number = $request->invoice_number;
        $model->user_type = $user_type == 'internal' ? 1 : 2;
        $model->user_id = $user_id;
        $model->total_amount = str_replace(',', '', $request->amount_total);
        $model->institution_id = $request->institution_id;
        $model->income_type_id = $request->income_type_id;
        $model->income_method_id = $request->income_method_id;
        $model->transaction_start_date = date('Y-m-d', strtotime($request->transaction_start_date));
        $model->due_date = date('Y-m-d', strtotime($request->due_date));
        $model->created_by = Auth::id();
        if ($request->message != null) {
            $model->message = $request->message;
        }

        if ($model->save()) {
            // save items
            $items = $request->items;
            for ($a = 0; $a < count($items); $a++) {
                $model_item = new IncomeItem();
                $model_item->income_id = $model->id;
                $model_item->income_category_id = $items[$a]['income_category_id'];
                $model_item->description = $items[$a]['description'];
                $model_item->amount = str_replace(',', '', $items[$a]['price']);
                $model_item->save();
            }

            //save media
            if ($request->has('attachments')) {
                $file = $request->attachments;
                for ($b = 0; $b < count($file); $b++) {
                    $exp = explode('@@', $file[$b]);
                    $folder = str_replace(' ', '', $exp[0]);
                    $link = public_path('storage/tmp/'.$folder.'/'.$exp[1]);

                    Storage::copy('public/tmp/'.$folder.'/'.$exp[1], 'public/invoice/'.$exp[1]);

                    unlink($link);
                    rmdir(public_path('storage/tmp/'.$folder));

                    // save to database
                    $model_media = new IncomeMedia();
                    $model_media->income_id = $model->id;
                    $model_media->path = 'invoice/'.$exp[1];
                    $model_media->save();
                }
            }
        }

        return $model;
    }

    /**
     * Function to get all total and payment if exist
     *
     * @param int it
     * @return array
     */
    public function generate_payment_detail($id)
    {
        $data = Income::with(['payments'])
            ->where('id', $id)
            ->first();
        $data->remaining_amount = $data->remaining_amount;
        $data->total = number_format($data->total_amount, 0, '.', ',');
        $payments = $data->payments;

        $rows = view('incomes.components.total_table_component', ['payments' => $payments, 'data' => $data])->render();

        return [
            'rows' => $rows,
            'data' => $data,
        ];
    }

    /**
     * Function to handle payment in given invoice id
     *
     * @param string payment_amount
     * @param int income_id
     * @param blob proof_of_payment
     * @param string transaction_date
     * @return void
     */
    public function pay_invoice($request)
    {
        $model = new Payments();
        /**
         * manipulate invoice group and number
         * If request key 'items' has more than 1
         * Then create a child invoices
         */
        $items = $request->items;
        $items = array_values($items);
        $invoice_number = $request->invoice_number;
        $invoices = [$invoice_number];
        if (count($items) > 1) {
            unset($invoices[0]);
            $invoices = generate_child_invoice($invoice_number, count($items));
        }

        /**
         * Split user id
         */
        $user_request = $request->user;
        $split_user = explode('-', $user_request);
        $user_id = $split_user[0];
        $user_type = $split_user[1] == 'internal' ? 1 : 2;
        if ($user_type == 1) {
            $model_class = 'InternalUser';
        } else {
            $model_class = 'ExternalUser';
        }
        /**
         * TODO: For now set model class static to InternalUser
         */
        $model_class = 'InternalUser';

        // get current class of user who has pay this bills
        $class_id = $request->class_id;
        $class = InstitutionClass::select('name')
            ->where('id', $class_id)
            ->first();
        $class_name = $class->name;

        /**
         * VALIDATION!
         * Check status payment of request month
         * if already paid, then return the error
         */
        for ($yy = 0; $yy < count($items); $yy++) {
            if (!empty($items[$yy]['month'])) {
                $is_paid = $this->this_month_is_paid(
                    $class_id,
                    $request->level_id,
                    $request->institution_id,
                    $items[$yy]['month'],
                    $user_type,
                    $user_id,
                    $items[$yy]['income_category_id']
                );
                $date = '01-'.$items[$yy]['month'];
                if ($is_paid) {
                    $month_paid = generate_indo_month(date('M', strtotime($date)));
                    return [
                        'error' => true,
                        'message' => __('view.month_already_paid', ['month' => $month_paid])
                    ];
                }
            }
        }

        /**
         * get target of payments
         * Payment to whom
         * Payment to whom position
         * TODO: Get the data from database, for now set staticaly
         */
        $target_position = Position::select('id')
            ->where('name', 'tu')
            ->first();
        $target_position = $target_position->id;
        $target_user = Employees::select('id')
            ->where('institution_id', $request->institution_id)
            ->where('position_id', $target_position)
            ->first();
        $ids = [];
        $target_user = $target_user->id;
        for ($a = 0; $a < count($invoices); $a++) {
            $model = new Payments();
            if (count($invoices) > 1) {
                $model->invoice_number_group = $invoice_number;
            }
            $model->invoice_number = $invoices[$a];
            $model->amount = $request->items[$a]['price'];
            $model->payment_date = date('Y-m-d');
            $model->payment_time = date('H:i:s');
            if (!empty($items[$a]['month'])) {
                $model->is_monthly = true;
                $model->monthly = date('m', strtotime('01-' . $request->items[$a]['month']));
            }
            $model->status = 1;
            $model->user_type = $user_type;
            $model->user_id = $user_id;
            $model->income_category_id = $request->items[$a]['income_category_id'];
            $model->income_method_id = $request->income_method_id;
            $model->income_type_id = $request->income_type_id;
            $model->institution_id = $request->institution_id;
            $model->institution_class_id = $request->class_id;
            $model->institution_class_level_id = $request->level_id;
            $model->payment_at_class = $class->name;
            $model->payment_target_position = $target_position;
            $model->payment_target_user = $target_user;
            $model->description = $request->items[$a]['description'];
            $model->save();
            $ids[] = $model->id;
        }

        /**
         * add payment value to target wallet account
         */
        $this->wallet->topup(
            $target_user,
            $request->amount_total,
            $request->items[0]['income_category_id'],
            $model->id,
            $model_class,
        );

        // save attachments
        $file = $request->attachments;
        for ($b = 0; $b < count($file); $b++) {
            $exp = explode('@@', $file[$b]);
            $folder = str_replace(' ', '', $exp[0]);
            $link = public_path('storage/tmp/'.$folder.'/'.$exp[1]);

            Storage::copy('public/tmp/'.$folder.'/'.$exp[1], 'public/invoice/'.$exp[1]);

            unlink($link);
            rmdir(public_path('storage/tmp/'.$folder));

            // save to database
            $model_media = new PaymentDocs();
            $model_media->payment_id = $ids[0];
            $model_media->path = 'invoice/'.$exp[1];
            $model_media->save();
        }

        return [
            'error' => false,
            'data' => $model,
        ];
    }

    /**
     * Function to Generate default filter
     * This execute when user load the page for the first time
     * This function will generate based on users role and users type
     * 
     * @param int institution_id
     * @param collection classes
     * @param collection income_categories
     * 
     * @return array 
     */
    public function generate_default_filter($institution_id, $classes, $income_categories)
    {
        $class = $classes[0]['id'];
        $filter = [
            'status' => 1, // paid,
            'class_id' => $class,
            'institution_id' => $institution_id,
            'transaction_start_date' => date('Y-m-d', strtotime('-1 day')),
            'transaction_end_date' => date('Y-m-d')
        ];
        return $filter;
    }

    public function get_exist_class($classes, $key = 0)
    {
        $param = [];
        $class = $classes[$key];
        if ($class) {
            $levels = $class->levels;
            if (count($levels) == 0) {
                $class = $this->get_exist_class($classes, $key + 1);
            }
            
            $param = [
                'class_id' => $class->id,
                'level_id' => $levels[0]->id,
                'levels' => $levels,
            ];
        }

        return $param;
    }

    /**
     * Function to generate array based on period
     * This array used to loop through table in frontend
     * Result will have 2 kay, users and calendar
     * Calendar is filled based on period
     * User is collection with addition key 'list_payments'
     * @param collection users
     * @param int period
     * @return array
     */
    public function get_period_payments($users, $period = 0, $income_category)
    {
        $max_loop = $period;
        $d1 = date_create(date('Y').'-'.date('m').'-01');
        $d2 = date_create($d1->format('Y-m-t'));
        $last_day = $d2->format('d');
        $month = $d2->format('m');
        $year = $d2->format('Y');
        $first_date = date_create($year.'-'.$month.'-01')->format('Y-m-d');
        $last_date = date_create($year.'-'.$month.'-'.$last_day)->format('Y-m-d');

        $times = [
            $first_date,
            $last_date
        ];

        $all_payments = [];
        $bills = [];
        $users = collect($users)->map(function ($item) use ($all_payments, $times, $max_loop, $income_category) {
            $payments = $item->payments;
            $payments = collect($payments)->whereBetween('payment_date', $times)
                ->where('is_monthly', 1)
                ->whereBetween('monthly', [1,12])
                ->where('income_category_id', $income_category)
                ->values();
            for($x = 0; $x < $max_loop; $x++) {
                if (count($payments) == 0) {
                    $all_payments[$x] = [
                        'id_user' => $item->id,
                        'payment_id' => 0,
                        'month' => $x+1,
                        'paid' => false,
                    ];
                } else {
                    if (isset($payments[$x])) {
                        if ($payments[$x]->monthly == ($x+1)) {
                            $all_payments[$x] = [
                                'id_user' => $item->id,
                                'payment_id' => $payments[$x]->id,
                                'month' => $x+1,
                                'paid' => true,
                            ];
                        } else {
                            $all_payments[$x] = [
                                'id_user' => $item->id,
                                'payment_id' => 0,
                                'month' => $x+1,
                                'paid' => false,
                            ];
                        }
                    } else {
                        $all_payments[$x] = [
                            'id_user' => $item->id,
                            'payment_id' => 0,
                            'month' => $x+1,
                            'paid' => false,
                        ];
                    }
                }
            }
            $item['list_payments'] = $all_payments;

            return $item;
        })->all();
        $bills = [];
        for ($a = 0; $a < $max_loop; $a++) {
            $month_count = $a + 1;
            $bills[$a] = [
                'month' => $month_count,
            ];
        }

        return [
            'calendar' => $bills,
            'users' => $users,
        ];
    }

    /**
     * Function to validate while payment on process
     * To check whether payment has been made or not in the given month / item
     * @return boolean
     */
    public function this_month_is_paid(
        $class_id,
        $level_id,
        $institution_id,
        $month,
        $user_type,
        $user_id,
        $income_category_id
    )
    {
        $res = false;
        if ($user_type == 1) {
            $data = Payments::select('id')
                ->where('user_type', $user_type)
                ->where('monthly', $month)
                ->where('income_category_id', $income_category_id)
                ->where('institution_id', $institution_id)
                ->where('institution_class_id', $class_id)
                ->where('institution_class_level_id', $level_id)
                ->where('user_id', $user_id)
                ->first();
            if ($data) {
                $res = true;
            }
        }

        return $res;
    }

    /**
     * Function to get all levels from given class and institution
     * @param int institution_id
     * @param int class_id
     * 
     * @return array
     */
    public function get_all_levels_of_given_class(
        $institution_id, $class_id
    )
    {
        $institutions = Intitution::with('classes.levels')
            ->where('id', $institution_id)
            ->first();
        $classes = $institutions->classes;
        $levels = [];

        if (count($classes) > 0) {
            $class = collect($classes)->where('id', $class_id)->values();
            $levels = $class[0]->levels;
        }

        return $levels;
    }
}
