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
	.transaction-body .table thead tr {
		background: #f4f4f4;
	}
	.transaction-body .table thead tr th {
		font-size: 12px;
	}
</style>
<div class="transaction-header">
	<p class="title">{{ __('view.transaction') }}</p>
</div>	
<div class="transaction-body">
	<div class="table-responsive">
		<table class="table">
			<thead>
				<tr>
					<th>{{ __('view.date') }}</th>
					<th>{{ __('view.transaction') }}</th>
					<th>{{ __('view.amount') }}</th>
				</tr>
			</thead>
			@php
				$payments = $data->payments;
			@endphp
			<tbody>
				@foreach ($payments as $payment)
					<tr>
						<td>{{ date('Y-m-d', strtotime($payment->payment_time)) }}</td>
						<td>{{ __('view.accept_payment_invoice') }}</td>
						<td>{{ number_format($payment->amount, 0, ',', '.') }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	</div>
</div>