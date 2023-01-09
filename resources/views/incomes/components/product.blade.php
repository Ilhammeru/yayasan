<style>
	.th-item,
	.th-desc,
	.th-price {
		width: cacl(100%/3);
	}
	.table-item thead tr {
		background: #f2f5f9;
	}
	.table-item thead tr th small {
		font-weight: bold;
	}
</style>

<div class="table-responsive">
	<table class="table table-item row-border">
		<thead>
			<tr>
				<th class="th-item">
					<small>Item</small>
				</th>
				<th class="th-desc">
					<small>{{ __('view.description') }}</small>
				</th>
				<th class="th-price">
					<small>{{ __('view.price') }}</small>
				</th>
				<th class="th-additional d-none">
				</th>
			</tr>
		</thead>
		<tbody>
			<tr class="tr-item" id="tr-item-0">
				<td>
					<select class="form-control income_category_id" id="income_category_id_0" name="items[0][income_category_id]" data-placeholder="{{ __('view.search_income_category') }}">
						@foreach ($incomeCategories as $category)
							<option value="{{ $category->id }}">{{ $category->name }}</option>
						@endforeach
					</select>
				</td>
				<td>
					<input class="form-control" type="text" name="items[0][description]" id="description_0" placeholder="{{ __('view.description') }}">
				</td>
				<td>
					<input class="form-control price_item" type="text" name="items[0][price]" id="price_item_0" placeholder="{{ __('view.price') }}" oninput="updateTotal()" value="0" onchange="updateValue(this)">
				</td>
				<td class="td-delete-0 d-none">
					<button class="btn btn-danger btn-sm" type="button" onclick="deleteRow(0)"><i class="fa fa-trash"></i></button>
				</td>
			</tr>
		</tbody>
	</table>
</div>

<div class="row">
	<div class="col-md-3">
		<button class="btn btn-primary" type="button" id="btn-add-rows" onclick="addItem()">{{ __('view.add_item') }}</button>
	</div>
</div>

{{-- item footer --}}
@include('incomes.components.item_footer')

<script>
	let incomeCategories = $('.income_category_id');

	setTimeout(() => {
		for (let aa = 0; aa < incomeCategories.length; aa++) {
		    let catId = incomeCategories[aa].id;
		    console.log('catId', catId);
		}
	}, 500);
</script>