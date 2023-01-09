<style>
	.detail-items {
		margin-top: 10px;
		padding: 0 30px;
	}
	.table-detail-item thead tr {
		background : #f4f4f4;
	}
	.table-detail-item thead tr th {
		font-size: 12px;
	}
	.table-detail-item thead tr th:last-child {
		text-align: right;
	}
	.table-detail-item tbody tr td:last-child {
		text-align: right;
	}
	.message-container {
		padding-left: 40px;
	}
	.message-container .title {
		margin: 0;
		font-weight: bold;
		border-bottom: 1px solid #e6e6e6;
	}
	.wrapper-total {
		padding: 0 30px;
	}
	.table-item-footer-detail tbody tr td {
		font-weight: bold;
	}
</style>

<div class="table-responsive detail-items">
	<table class="table table-detail-item">
		<thead>	
			<tr>
				<th>Item</th>
				<th>{{ __('view.description') }}</th>
				<th>{{ __('view.price') }}</th>
			</tr>
		</thead>
		<tbody>
			{{-- TODO: Crate a link in category name --}}
			@foreach ($items as $item)
				<tr>
					<td>{{ $item->category->name }}</td>
					<td></td>
					<td>Rp. {{ number_format($item->amount, 0, '.', ',') }}</td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

{{-- table footer --}}
<div class="row">
	<div class="col-md-6 col-sm-12">
		{{-- Message if any --}}
		@if ($data->message)
		<div class="message-container">
			<p class="title">{{ __('view.message') }}</p>
			{!! $data->message !!}
		</div>
		@endif
	</div>
	<div class="col-md-6 col-sm-12">
		{{-- total --}}
		<div class="table-responsive wrapper-total">
			<table class="table table-item-footer-detail">
				<tbody id="target-payment-detail">
					<tr>
						<td style="text-align: right;">Total</td>
						<td style="text-align: right;" id="total-item-td">
							0
						</td>
					</tr>
					<tr>
						<td style="text-align: right;">{{ __('view.remaining_bill') }}</td>
						<td style="text-align: right;" id="remaining-bill-td">
							0
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>