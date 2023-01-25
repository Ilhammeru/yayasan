<?php

namespace App\Http\Services;

use App\Models\Employees;
use App\Models\IncomeCategory;
use App\Models\Position;
use App\Models\WalletDocs;
use App\Models\Wallets;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class WalletService {
    private $account_service;

    public function __construct()
    {
        $this->account_service = new AccountService();
    }

    /**
     * Function to topup wallet
     */
    public function topup(
        $user_id,
        $amount,
        $income_category_id,
        $source_id,
        $model_class,
        $proposal_id = null,
    )
    {
        // get income category name
        if ($income_category_id == 0) {
            $text = 'Pencairan dana proposal';
        } else {
            $income_category = IncomeCategory::select('name')
                ->find($income_category_id);
            $income_category_name = $income_category->name;
            $text = __('view.received_money_from', ['name' => $income_category_name]);
        }

        $model = new Wallets();
        $model->model = $model_class;
        $model->user_id = $user_id;
        $model->debit = str_replace(',','',$amount);
        $model->source_id = $source_id;
        $model->source_text = $text;
        $model->income_category_id = $income_category_id;
        if ($proposal_id) {
            $model->proposal_id = $proposal_id;
        }
        $model->save();

        return $model;
    }

    /**
     * Function to deduct wallet
     */
    public function deduct(
        $user_id,
        $amount,
        $income_category_id,
        $source_id,
        $model_class,
        $message = null
    )
    {
        // get income category name
        $income_category = IncomeCategory::select('name')
            ->find($income_category_id);
        $income_category_name = $income_category->name;
        $text = __('view.send_money_to', ['name' => $income_category_name]);

        $model = new Wallets();
        $model->model = $model_class;
        $model->user_id = $user_id;
        $model->credit = $amount;
        $model->source_id = $source_id;
        $model->source_text = $text;
        if ($message) {
            $model->source_text = $message;
        }
        $model->income_category_id = $income_category_id;
        $model->save();
    }

    /**
     * Deduct the wallet ballance of the sender
     * top up the wallet ballance of treasurer
     * @param array attachments_send_wallet
     * @param string message
     * @param float total
     * @param int treasurer
     * @param array wallet_ids
     * @param int income_category_id
     */
    public function send_to_treasurer($request)
    {
        $message = $request->message;
        $total = $request->total;
        $treasurer = $request->treasurer;
        $wallet_ids = $request->wallet_ids;
        $model_class = 'InternalUser';

        // topup treasure and deduct sender
        $topup_ids = [];
        for ($a = 0; $a < count($wallet_ids); $a++) {
            $income_category = Wallets::select('income_category_id', 'user_id', 'source_id', 'debit', 'credit')
                ->find($wallet_ids[$a]);
            $topup = $this->topup(
                $treasurer,
                $income_category->debit,
                $income_category->income_category_id,
                $income_category->source_id,
                $model_class
            );
            $topup_ids[] = $topup->id;

            $this->deduct(
                $income_category->user_id,
                $income_category->debit,
                $income_category->income_category_id,
                $income_category->source_id,
                $model_class,
                $message,
            );

            /**
             * Update 'is_out' and 'out' column in current wallet
             */
            $current = Wallets::find($wallet_ids[$a]);
            $current->is_out = true;
            $current->out = $income_category->debit;
            $current->save();
        }

        // save attachments
        $file = $request->attachments_send_wallet;
        $files = [];
        for ($b = 0; $b < count($file); $b++) {
            $exp = explode('@@', $file[$b]);
            $folder = str_replace(' ', '', $exp[0]);
            $link = public_path('storage/tmp/'.$folder.'/'.$exp[1]);

            Storage::copy('public/tmp/'.$folder.'/'.$exp[1], 'public/invoice/'.$exp[1]);

            unlink($link);
            rmdir(public_path('storage/tmp/'.$folder));

            $files[] = [
                'path' => 'wallet/'.$exp[1],
            ];
        }

        for ($c = 0; $c < count($topup_ids); $c++) {
            $topop_id = $topup_ids[$c];
            $files = collect($files)->map(function ($item) use ($topop_id) {
                $item['wallet_id'] = $topop_id;
                
                return $item;
            })->all();
        }
        WalletDocs::insert($files);
    }

    /**
     * Deduct the wallet ballance of the sender
     * Add saldo to account
     * @param array attachments_send_wallet
     * @param string message
     * @param float total
     * @param int account
     * @param array wallet_ids
     * @param int income_category_id
     */
    public function send_to_account($request)
    {
        $account_id = $request->account;
        $wallet_ids = $request->wallet_ids;
        $message = $request->message;
        $files = $request->attachments_send_wallet;

        for ($a = 0; $a < count($wallet_ids); $a++) {
            // deduct wallet
            $income_category = Wallets::select('income_category_id', 'user_id', 'source_id', 'debit', 'credit')
                ->find($wallet_ids[$a]);
            $model_class = 'InternalUser';
            $this->deduct(
                $income_category->user_id,
                $income_category->debit,
                $income_category->income_category_id,
                $income_category->source_id,
                $model_class,
                $message,
            );

            // send to account
            $this->account_service->send_to_account(
                $account_id,
                $income_category->debit,
                $income_category->income_category_id,
                $message,
                $files,
            );

            /**
             * Update 'is_out' and 'out' column in current wallet
             */
            $current = Wallets::find($wallet_ids[$a]);
            $current->is_out = true;
            $current->out = $income_category->debit;
            $current->save();
        }
    }

    /**
     * Function to get current user saldo
     * @param int user_id
     * @param string type
     * 
     * @return string
     */
    public function wallet_saldo(
        $user_id,
        $type,
    )
    {
        if ($type == 'internal') {
            $model_class = 'InternalUser';
        } else {
            $model_class = 'ExternalUser';
        }
        /**
         * TODO: For now set static to internal user only (Employee)
         */
        $model_class = 'InternalUser';
        $data = Wallets::select('debit', 'credit')
            ->where('model', $model_class)
            ->where('user_id', $user_id)
            ->get();
        $debit = collect($data)->pluck('debit')->sum();
        $credit = collect($data)->pluck('credit')->sum();
        $saldo = $debit - $credit;

        return 'Rp. ' . number_format($saldo, 0, '.', '.');
    }

    /**
     * Function to get detail all wallet and group by income_category_id
     * @param int user_id
     * 
     * @return array
     */
    public function detail_wallet($user_id = null)
    {
        if (!$user_id) {
            $user_id = auth()->id();
        }
        $employee = Employees::select('id')->where('user_id', $user_id)
            ->first();
        $employee_id = $employee->id;
        $data = Wallets::where('user_id', $employee_id)
            ->get();
        $groups = collect($data)->groupBy('income_category_id')->all();
        
        $col = 12;
        if (count($groups) == 2) {
            $col = 6;
        } else if (count($groups) == 3) {
            $col = 4;
        } else if (count($groups) == 4) {
            $col = 3;
        } else if (count($groups) == 5) {
            $col = 4;
        } else if (count($groups) == 6) {
            $col = 2;
        }

        $data = [];
        foreach ($groups as $key => $group) {
            $debits = collect($group)->pluck('debit')->sum();
            $credits = collect($group)
                ->where('is_out', 0)
                ->pluck('credit')->sum();
            $total = $debits - $credits;
            if ($key != 0) {
                $category = IncomeCategory::select('id', 'name')
                    ->find($key);
                $data[$category->name] = [
                    'id' => $key,
                    'amount' => 'Rp. ' . number_format($total, 0, '.', '.'),
                    'col' => $col,
                    'income_category_id' => $key,
                ];
            } else {
                $data['Proposal'] = [
                    'id' => $key,
                    'amount' => 'Rp. ' . number_format($total, 0, '.', '.'),
                    'col' => $col,
                    'income_category_id' => $key,
                ];
            }
        }

        return $data;
    }

    /**
     * Get all wallet by income_category_id
     * @param int income_category_id
     * 
     * @return Collection
     */
    public function detail_wallet_by_category($income_category_id, $first = false)
    {
        $data = Wallets::with(['payment'])
            ->where('income_category_id', $income_category_id)
            ->isNotOut()
            ->myWallet()
            ->get();
        
        return $data;
    }

    /**
     * Get detail wallet by id
     * @param int int
     * 
     * @return Collection
     */
    public function detail_wallet_by_id($id)
    {
        $data = Wallets::with(['payment:id,amount,user_id,invoice_number'])
            ->find($id);
        $debit = $data->debit;
        $credit = $data->credit;
        $payment = $data->payment;
        $data->total_amount = $debit - $credit;
        $user = $payment->userData();
        $data->user_data = $user;
        return $data;
    }

    /**
     * Function to get current wallet amount based on given category_id and user auth
     * 
     * @param int category_id
     */
    public function amount_wallet_per_category($category_id)
    {
        $data = Wallets::select('debit', 'credit')
            ->where('income_category_id', $category_id)
            ->myWallet()
            ->get();
        
        $debit = collect($data)->pluck('debit')->sum();
        $credit = collect($data)->pluck('credit')->sum();

        $total = $debit - $credit;

        return $total;
    }

    /**
     * Function to render table header of proposal data
     * 
     * @return array
     */
    public function proposalHeader()
    {
        return [
            '#',
            __('view.proposal'),
            __('view.request_budget'),
            __('view.approved_at'),
            __('view.approved_budget'),
        ];
    }

     /**
     * Function to render table header of wallet transction data
     * 
     * @return array
     */
    public function walletTransactionHeader()
    {
        return [
            'checkbox',
            __('view.invoice'),
            __('view.user'),
            __('view.amount'),
        ];
    }
}