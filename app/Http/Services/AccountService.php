<?php

namespace App\Http\Services;

use App\Models\AccountTransaction;
use App\Models\AccountTransactionDocs;
use Illuminate\Support\Facades\Storage;

class AccountService {
    public function send_to_account(
        $account_id,
        $amount,
        $income_category_id,
        $message,
        $docs,
    )
    {
        $status = 1; // success
        if ($account_id == 2) {
            $status = 2; // pending
        }
        
        $model = new AccountTransaction();
        $model->status = $status;
        $model->account_id = $account_id;
        $model->debit = $amount;
        $model->description = $message;
        $model->source_id = $income_category_id;
        $model->save();

        // save attachments
        $this->store_attachments($docs, $model->id);
    }

    public function deductAccount(
        $account_id,
        $amount,
        $message,
        $docs,
    )
    {
        $model = new AccountTransaction();
        $model->status = AccountTransaction::SUCCESS;
        $model->account_id = $account_id;
        $model->credit = str_replace(',','',$amount);
        $model->description = $message;
        $model->source_id = 0;
        $model->save();

        // save attachments
        // save attachments
        $this->store_attachments($docs, $model->id);
    }

    public function store_attachments($docs, $transaction_id)
    {
        for ($b = 0; $b < count($docs); $b++) {
            $exp = explode('@@', $docs[$b]);
            $folder = str_replace(' ', '', $exp[0]);
            $link = public_path('storage/tmp/'.$folder.'/'.$exp[1]);

            Storage::copy('public/tmp/'.$folder.'/'.$exp[1], 'public/account_transaction/'.$exp[1]);

            if (file_exists($link)) {
                unlink($link);
                rmdir(public_path('storage/tmp/'.$folder));
            }

            $path = 'account_transaction/'.$exp[1];
            $model_media = new AccountTransactionDocs();
            $model_media->account_transaction_id = $transaction_id;
            $model_media->path = $path;
            $model_media->save();
        }
    }
}