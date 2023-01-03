@php
    $d = null;
    if (!empty($data)) {
        $d = $data;
    }
@endphp

<div class="form-group mb-3">
    <label for="username" class="control-label">{{ __('view.username') }}</label>
    <input type="text" name="username" value="{{ $d ? $d->username : '' }}" class="form-control" id="username">
</div>
<div class="form-group">
    <label for="password" class="control-label">{{ __('view.password') }} <span style="font-size: 10px;">{{ __('view.password_hint') }}</span></label>
    <input type="password" name="password" placeholder="*****" class="form-control" id="password">
</div>