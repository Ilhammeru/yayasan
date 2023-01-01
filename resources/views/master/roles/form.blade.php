@php
    $d = !empty($data) ? $data : null;
@endphp

<div class="form-group mb-3">
    <label for="name" class="control-label">{{ __('view.role_name') }}</label>
    <input type="text" name="name" {{ $d ? 'readonly' : '' }} value="{{ $d ? $d->name : '' }}" placeholder="{{ __('view.role_name') }}" class="form-control" id="name">
</div>
<div class="form-group mb-3">
    <label for="permissions" class="control-label">{{ __('view.permissions') }}</label>
    @foreach ($permissions as $k => $p)
    <div style="margin: 10px 0; @if($k == 0) margin-top: 0 !important; @endif">
        <p class="group" style="font-weight: bold; font-size: 14px; margin: 5px 0 0 0;">{{ ucfirst($k) }}</p>
        <div class="row">
            @foreach ($p as $key => $item)
            <div class="col-md-3 col-sm-12">
                <label class="checkbox-inline" for="permission_{{ $key }}_{{ $k }}">
                    <input type="checkbox" id="permission_{{ $key }}_{{ $k }}" name="permissions[]" value="option1"> {{ ucfirst($item->name) }}
                </label>
            </div>
            @endforeach
        </div>
    </div>
    @endforeach
</div>