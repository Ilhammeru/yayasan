@php
	$d = null;
	if (!empty($data)) {
		$d = $data;
	}
@endphp

<div class="form-group">
	<label class="control-label" for="name">{{ __('view.name') }} <span class="text-danger">*</span></label>
	<input class="form-control" id="name" name="name" placeholder="Tunjangan" value="{{ $d ? $d->name : '' }}">
</div>
<div class="form-group">
	<label for="income_type_id" class="control-label">@lang('view.method') <span class="text-danger">*</span></label>
	<select name="income_type_id" id="income_type_id" class="form-control" data-placeholder="{{ __('view.search_income_method') }}">
		<option value=""></option>
		@foreach ($types as $type)
			<option value="{{ $type->id }}" {{ $type->selected }}>{{ $type->name }}</option>
		@endforeach
	</select>
</div>

<script>
	$('#income_type_id').chosen({width: '100%'});
</script>