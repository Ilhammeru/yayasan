<?php

namespace App\Http\Services;

use App\Models\ExternalUser;
use App\Models\Income;
use App\Models\IncomeItem;
use App\Models\IncomeMedia;
use App\Models\IncomePayment;
use App\Models\IncomePaymentMedia;
use App\Models\InternalUser;
use Auth;
use Illuminate\Support\Facades\Storage;

class IncomeService {
	/**
	 * Function to get all user type
	 * 
	 * @return void
	 */
	public function get_all_user()
	{
		$internal = InternalUser::active()->get();
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
        	for($a = 0; $a < count($items); $a++) {
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
        		for($b = 0; $b < count($file); $b++) {
	        		$exp = explode('@@', $file[$b]);
		            $folder = str_replace(' ', '', $exp[0]);
		            $link = public_path('storage/tmp/' . $folder . '/' . $exp[1]);

		            Storage::copy('public/tmp/' . $folder . '/' . $exp[1], 'public/invoice/' . $exp[1]);

		            unlink($link);
		            rmdir(public_path('storage/tmp/' . $folder));

		            // save to database
		            $model_media = new IncomeMedia();
		            $model_media->income_id = $model->id;
		            $model_media->path = 'invoice/' . $exp[1];
		            $model_media->save();
        		}
        	}
        }

        return $model;
	}

	/**
	 * Function to get all total and payment if exist
	 * @param int it
	 * 
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
			'data' => $data
		];
	}

	/**
     * Function to handle payment in given invoice id
     * @param string payment_amount
     * @param int income_id
     * @param blob proof_of_payment
     * @param string transaction_date
     * 
     * @return void
     */
	public function pay_invoice($request)
	{
		$model_payment = new IncomePayment();
		$model_payment->income_id = $request->income_id;
		$model_payment->amount = str_replace(',','',$request->payment_amount);
		$model_payment->account_id = 0;
		$model_payment->payment_time = date('Y-m-d H:i:s', strtotime($request->transaction_date));
		$model_payment->save();
		if ($request->has('proof_of_payment')) {
			$file = $request->proof_of_payment;
    		for($b = 0; $b < count($file); $b++) {
        		$exp = explode('@@', $file[$b]);
	            $folder = str_replace(' ', '', $exp[0]);
	            $link = public_path('storage/tmp/' . $folder . '/' . $exp[1]);

	            Storage::copy('public/tmp/' . $folder . '/' . $exp[1], 'public/invoice/proof/' . $exp[1]);

	            unlink($link);
	            rmdir(public_path('storage/tmp/' . $folder));

	            $media = new IncomePaymentMedia();
	            $media->income_payment_id = $model_payment->id;
	            $media->path = '/invoice/proof/' . $exp[1];
	            $media->save();
    		}
		}

		// updata payment status if trnsaction is complete
		$data = Income::find($request->income_id);
		if ($data->payment_is_complete) {
			$data->payment_status = 1;
			$data->save();
		} else if ($data->payment_is_partial) {
			$data->payment_status = 2;
			$data->save();
		}
	}
}