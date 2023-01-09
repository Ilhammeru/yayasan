<tr class="tr-item" id="tr-item-{{ $len }}">
	<td>
		<select class="form-control income_category_id" id="income_category_id_{{ $len }}" name="items[{{ $len }}][income_category_id]" data-placeholder="{{ __('view.search_income_category') }}">
			@foreach ($incomeCategories as $category)
				<option value="{{ $category->id }}">{{ $category->name }}</option>
			@endforeach
		</select>
	</td>
	<td>
		<input class="form-control" type="text" name="items[{{ $len }}][description]" id="description_{{ $len }}" placeholder="{{ __('view.description') }}">
	</td>
	<td>
		<input class="form-control price_item" type="text" name="items[{{ $len }}][price]" id="price_item_{{ $len }}" placeholder="{{ __('view.price') }}" oninput="updateTotal()" value="0">
	</td>
	<td>
		<button class="btn btn-danger btn-sm" type="button" onclick="deleteRow({{ $len }})">
			<i class="fa fa-trash"></i>
		</button>
	</td>
</tr>