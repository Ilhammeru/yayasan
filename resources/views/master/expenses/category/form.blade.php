@php
    $d = null;
    if (!empty($data)) {
        $d = $data;
    }
@endphp

<div class="form-group">
    <label for="name" class="control-label">@lang('view.name')</label>
    <input type="text" value="{{ $d ? $d->name : '' }}" placeholder="SPP" class="form-control" name="name" id="name">
</div>