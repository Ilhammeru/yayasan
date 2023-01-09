<style>
	.detail-customer {
		padding: 10px 30px;
		border-top: 1px solid #f4f4f4;
	}
	.detail-customer .item-detail {
		margin: 20px 0;
	}
	.detail-customer .item-detail .title {
		margin: 0 0 5px 0;
	}
	.detail-customer .item-detail .item {
		display: flex;
		align-items: center;
		gap: 15px;
	}
	.detail-customer .item-detail .item .text {
		margin: 0;
		font-size: 10px;
	}
	.detail-customer .item-detail .name {
		font-size: 18px;
		margin: 0 0 10px 0;
	}
	.detail-customer .item-detail .d {
		padding-left: 10px;
	}
	.detail-customer .item-detail .date {
		color: #000;
		font-weight: bold;
		font-size: 16px;
	}
</style>

<div class="detail-customer">
	<div class="row">
		<div class="col-md-12 col-sm-12">
			<div class="item-detail">
				<p class="title">
					{{ __('view.user') }}
				</p>
				<div class="name">
					<a href="#">{{ $data->assignUser()->name }}</a>
				</div>
				<div class="d">
					@include('incomes.components.user_detail', ['detail' => $data->assignUser()])
				</div>
			</div>

			<div class="item-detail">
				<p class="title">{{ __('view.transaction_date') }}</p>
				<p class="date">{{ date('Y-m-d', strtotime($data->transaction_start_date)) }}</p>
			</div>

			<div class="item-detail">
				<p class="title">{{ __('view.transaction_method') }}</p>
				<span class="method label label-success">
					{{ strtoupper($data->method->name) }}
				</span>
			</div>
		</div>
	</div>
</div>