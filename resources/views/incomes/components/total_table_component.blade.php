<tr>
	<td style="text-align: right;">Total</td>
	<td style="text-align: right;" id="total-item-td">
		{{ number_format($data->total_amount, 0, ',', '.') }}
	</td>
</tr>
@if (count($payments) > 0)
	@foreach ($payments as $payment)
		<tr>
			<td style="text-align: right; color: lightblue;">
				{{ __('view.payment_in') }} {{ date('Y-m-d', strtotime($payment->payment_time)) }}
				<span style="margin-left: 5px; cursor: pointer;" onclick="openProofofPayment({{ $payment->id }})"><i class="fa fa-file-image-o text-danger"></i></span>
			</td>
			<td style="text-align: right; color: lightblue;" id="total-item-td">
				{{ number_format($payment->amount, 0, ",", ".") }}
			</td>
		</tr>
	@endforeach
@endif
<tr>
	<td style="text-align: right; font-size: 16px">{{ __('view.remaining_bill') }}</td>
	<td style="text-align: right;" id="remaining-bill-td">
		{{ number_format($data->remaining_amount, 0, ',', '.') }}
	</td>
</tr>