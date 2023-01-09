<style>
	.transaction-section {
		margin-top: 10px;
		border: 1px solid #f4f4f4;
	}
	.transaction-section .transaction-header {
		padding: 10px 30px;
	}
	.transaction-body {
		padding: 10px 30px;
	}
	.transaction-header .title {
		font-size: 14px;
		font-weight: bold;
		margin: 0;
	}
	.form-control {
		text-align: right;
	}
	#accordion .ui-accordion-content {
		height: 300px;
	}
</style>

<div class="transaction-header">
	<p class="title">{{ __('view.transaction') }}</p>
</div>	
<div class="transaction-body">
	<form id="form-payment">
		<input type="hidden" name="income_id" value="{{ $data->id }}">
		<div class="row">
			<div class="col-md-6 col-sm-12">
				<div class="form-group" id="form-group-payment-amount">
					<label class="control-label" for="payment_amount">{{ __('view.payment_amount') }} <span class="text-danger">*</span></label>
					<input class="form-control" type="text" name="payment_amount" {{ $data->full_payment_only ? 'readonly' : '' }} id="payment_amount" value="{{ number_format($data->remaining_amount, 0,'.',',') }}" oninput="validatePaymentAmount(this, `{{ $data->remaining_amount }}`)" onfocus="normalizeValue(this)" onfocusout="changeToThousand(this)">
					<span class="help-block"></span>
				</div>
			</div>
			<div class="col-md-6 col-sm-12">
				<div class="form-group">
					<label class="control-label" for="payment_amount">{{ __('view.transaction_date') }} <span class="text-danger">*</span></label>
					<input class="form-control" type="text" name="transaction_date" id="transaction_date" value="{{ date('Y-m-d') }}">
				</div>
			</div>
		</div>

		<div class="row">
			<div class="col-md-6">
				<div id="accordion">
				  	<h3>{{ __('view.proof_of_payment') }}</h3>
				  	<div>
				  		<div class="form-group">
						    <input type="file"
			                    class="filepond"
			                    name="proof_of_payment[]"
			                    id="filepond-input"
			                    multiple
			                    data-allow-reorder="true"
			                    data-max-file-size="3MB"
			                    data-max-files="1">
				  		</div>
				  	</div>
				</div>
			</div>
			<div class="col-md-6">
				<button class="btn btn-sm btn-primary" style="width: 100%;" type="button" id="btn-add-payment" onclick="pay({{ $data->id }})">{{ __('view.pay') }}</button>
			</div>
		</div>
	</form>
</div>