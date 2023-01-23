<tr class="tr-item" id="tr-item-{{ $len }}" data-key="{{ $len }}">
	<td>
		<select class="form-control income_category_id select-chosen" id="income_category_id_{{ $len }}" name="items[{{ $len }}][income_category_id]" data-placeholder="{{ __('view.search_income_category') }}">
			@foreach ($incomeCategories as $category)
				<option value="{{ $category->id }}" {{ $category->selected ? 'selected' : 'disabled' }}>{{ $category->name }}</option>
			@endforeach
		</select>
	</td>
	<td>
		@if ($is_enable)
			<input type="text" class="form-control select-date" name="items[{{ $len }}][date]" id="invoice_date_{{ $len }}">
		@else
			<div class="input-group">
				<input type="text" readonly class="form-control select-month" name="items[{{ $len }}][month]">
			</div>
		@endif
	</td>
	<td>
		<input class="form-control" type="text" name="items[{{ $len }}][description]" id="description_{{ $len }}" placeholder="{{ __('view.description') }}">
	</td>
	<td>
		<input class="form-control price_item" type="text" id="price_item_{{ $len }}" autocomplete="off" placeholder="{{ __('view.price') }}" oninput="updateTotal(this)" onchange="updateValue(this)">
		<input class="form-control price_item_shadow" type="hidden" name="items[{{ $len }}][price]" id="price_item_{{ $len }}_shadow" placeholder="{{ __('view.price') }}" value="0">
	</td>
	<td class="td-delete td-delete-{{ $len }}">
		<button class="btn btn-danger btn-sm" type="button" onclick="deleteRow({{ $len }})">
			<i class="fa fa-trash"></i>
		</button>
	</td>
</tr>