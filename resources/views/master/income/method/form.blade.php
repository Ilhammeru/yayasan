@php
	$d = null;
	if (!empty($data)) {
		$d = $data;
	}
@endphp

<div class="form-group">
	<label class="control-label" for="name">{{ __('view.name') }}</label>
	<input class="form-control" id="name" name="name" placeholder="Tunjangan" value="{{ $d ? $d->name : '' }}">
</div>