@php
    $d = !empty($data) ? $data : null;
@endphp

<div class="form-group mb-3">
    <label for="name" class="control-label">{{ __('view.role_name') }}</label>
    <input type="text" name="name" {{ $d ? 'readonly' : '' }} value="{{ $d ? $d->name : '' }}" placeholder="{{ __('view.role_name') }}" class="form-control" id="name">
</div>