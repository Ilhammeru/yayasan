<?php

namespace App\Http\Services;

use App\Models\Proposal;
use App\Models\ProposalDoc;
use App\Models\ProposalLog;
use Illuminate\Support\Facades\Storage;

class ProposalService {
    /**
     * Function to generate prpoposal list based on auth and role
     */
    public function list()
    {
        $auth = auth()->user();
        
    }

    public function store($request)
    {
        $status = 5;
        if ($request->has('status')) {
            $status = $request->status;
        }
        $model = new Proposal();
        $model->title = $request->title;
        $model->event_date = date('Y-m-d', strtotime($request->event_date));
        $model->event_time = date('H:i', strtotime($request->event_date));
        $model->pic = $request->pic;
        $model->pic_user_type = 2;
        $model->description = $request->message;
        $model->budget_total = str_replace(',','',$request->budget_total);
        $model->status = $status;
        $model->save();

        // upload photo
        if ($request->has('attachments_proposal')) {
            $file = $request->attachments_proposal;
            for ($b = 0; $b < count($file); $b++) {
                $exp = explode('@@', $file[$b]);
                $folder = str_replace(' ', '', $exp[0]);
                $link = public_path('storage/tmp/'.$folder.'/'.$exp[1]);

                Storage::copy('public/tmp/'.$folder.'/'.$exp[1], 'public/proposal/'.$exp[1]);

                unlink($link);
                rmdir(public_path('storage/tmp/'.$folder));

                // save to database
                $model_media = new ProposalDoc();
                $model_media->proposal_id = $model->id;
                $model_media->path = 'proposal/'.$exp[1];
                $model_media->save();
            }
        }

        $this->create_log($model->id, $status);
    }

    public function update($request, $id) {
        $status = 5;
        if ($request->has('status')) {
            $status = $request->status;
        }
        $model = Proposal::find($id);
        $current_docs = $model->docs;

        $model->title = $request->title;
        $model->event_date = date('Y-m-d', strtotime($request->event_date));
        $model->event_time = date('H:i', strtotime($request->event_date));
        $model->pic = $request->pic;
        $model->pic_user_type = 2;
        $model->description = $request->message;
        $model->budget_total = str_replace(',','',$request->budget_total);
        $model->status = $status;
        $model->save();

        // delete current docs
        foreach ($current_docs as $doc) {
            $unlink_path = public_path('storage/' . $doc->path);
            unlink($unlink_path);

            $doc->delete();
        }

        // upload photo
        if ($request->has('attachments_proposal')) {
            $file = $request->attachments_proposal;
            for ($b = 0; $b < count($file); $b++) {
                $exp = explode('@@', $file[$b]);
                $folder = str_replace(' ', '', $exp[0]);
                $link = public_path('storage/tmp/'.$folder.'/'.$exp[1]);

                Storage::copy('public/tmp/'.$folder.'/'.$exp[1], 'public/proposal/'.$exp[1]);

                unlink($link);
                rmdir(public_path('storage/tmp/'.$folder));

                // save to database
                $model_media = new ProposalDoc();
                $model_media->proposal_id = $model->id;
                $model_media->path = 'proposal/'.$exp[1];
                $model_media->save();
            }
        }
    }

    /**
     * Function to approve proposal
     * @param int id
     * 
     * @return void
     */
    public function approve($id)
    {
        $data = Proposal::find($id);
        $data->status = Proposal::APPROVE_WAIT_BUDGET;
        $data->approved_by = auth()->id();
        $data->approve_at = date('Y-m-d H:i:s');
        $data->save();

        $this->create_log($id, Proposal::APPROVE_WAIT_BUDGET);
    }

    /**
     * Function to funding proposal
     * @param int id
     * @param int amount
     * @param int account
     * @param array attachments_funding
     * @param string message
     */
    public function funding($request, $id)
    {
        $mail = new EmailService();
        $data = Proposal::find($id);
        $data->status = Proposal::APPROVE;
        $data->approved_budget = str_replace(',','',$request->amount);
        $data->funding_by = auth()->id();
        $data->funding_at = date('Y-m-d H:i:s');
        $data->save();

        $pic = $data->picRaw();

        $account_service = new AccountService();
        $wallet_service = new WalletService();

        /**
         * Deduct amount from account
         * add to account_transaction table
         * add income_category_id with 0 value
         */
        $account_service->deductAccount(
            $request->account,
            $request->amount,
            'Pencairan dana proposal',
            $request->attachments_proposal,
        );

         /**
          * Add amount to pic wallet
          * Add with income_category_id is 0 and source_id is 0
          */
        $wallet_service->topup(
            $data->pic,
            $request->amount,
            0,
            0,
            'InternalUser',
            $data->id,
        );

        /**
         * Send email confirmation
         */
        if (!empty($pic->email)) {
            $mail->send($pic, 'approved_proposal');
        }

        $this->create_log($id, Proposal::APPROVE);
    }

    /**
     * Function to create proposal logs
     * @param int proposal_id
     * @param int status
     * 
     * @return void
     */
    public function create_log($proposal_id, $status)
    {
        $model = new ProposalLog();
        $model->proposal_id = $proposal_id;
        $model->status = $status;
        $model->description = $this->generate_log_text($status);
        $model->save();

        $wallet = new WalletService();
        
    }

    /**
     * Function to generate text description based on status
     * @param int status
     * 
     * @return string
     */
    public function generate_log_text($status)
    {
        switch ($status) {
            case '1':
                $text = 'Proposal sudah di setujui dan dana sudah dicairkan';
                break;

            case '2':
                $text = 'Proposal dalam proses pengajuan dan menuggu persetujuan';
                break;

            case '3':
                $text = 'Proposal sudah di setujui dan menunggu pencairan dana';
                break;
            
            case '4':
                $text = 'Proposal tidak disetujui';
                break;

            case '5':
                $text = 'Proposal masih dalam draft dan belum di publish untuk di ajukan';
                break;
            
            default:
                $text = '';
                break;
        }

        return $text;
    }

}