@php
    $d = null;
    $status = null;
    $current_image = null;
    $user_type = null;
    if (!empty($data)) {
        $d = $data;
        $status = $data->status;
        $current_image = $data->image;
        $user_type = $data->user_type;
    }
@endphp

<div class="form-group">
    @include('master.users.components.user_image', ['image' => $current_image, 'folder' => 'user_external'])
</div>

<div class="form-group">
    <label class="control-label" for="name">
        {{ __('view.name') }}
        <span class="text-danger">*</span>
    </label>
    <input class="form-control" id="name" type="text" value="{{ $d ? $d->name : '' }}" placeholder="John Doe" name="name">
</div>

<div class="form-group">
    <label for="user_type" class="control-label">@lang('view.user_type') <span class="text-danger">*</span></label>
    <select name="user_type" name="user_type" id="user_type" class="form-control" data-placeholder="{{ __('view.select_user_type') }}">
        <option value=""></option>
        @foreach (\App\Models\ExternalUser::get_user_type() as $item)
            <option value="{{ $item['id'] }}" {{ $user_type == $item['id'] ? 'selected' : '' }}>{{ $item['name'] }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="control-label" for="phone">{{ __('view.phone') }} <span class="text-danger">*</span></label>
    <input class="form-control" name="phone" id="phone" type="text" oninput="regexNumber(this)" placeholder="08999211122" value="{{ $d ? $d->phone : '' }}">
</div>

<div class="form-group">
    <label class="control-label" for="address">{{ __('view.address') }} <span class="text-danger">*</span></label>
    <textarea name="address" class="form-control" cols="1" rows="2">{{ $d ? $d->address : '' }}</textarea>
</div>

<div class="form-group">
    <label class="control-label" for="province">{{ __('view.province') }} <span class="text-danger">*</span></label>
    <select class="form-control" onchange="getCity(this)" id="province" name="province_id" data-placeholder="{{ __('view.select_province') }}">
        <option></option>
        @foreach (  $provinces as $province)
            <option value="{{ $province->id }}" {{ $province->selected }}>{{ $province->name }}</option>
        @endforeach
    </select>
</div>

<div class="form-group">
    <label class="control-label" for="city">{{ __('view.city') }} <span class="text-danger">*</span></label>
    <select class="form-control" onchange="getDistrict(this)" id="city_id" name="city_id" data-placeholder="{{ __('view.select_city') }}" {{ !$d ? 'disabled' : '' }}>
        <option></option>

        @if ($d)
            <option value=""></option>
            @foreach ($cities as $item) 
                <option value="{{ $item->id }}" {{ $item->selected }}>{{ $item->name }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="form-group">
    <label class="control-label" for="district">{{ __('view.district') }} <span class="text-danger">*</span></label>
    <select class="form-control" id="district_id" name="district_id" data-placeholder="{{ __('view.select_district') }}" {{ $d == null ? 'disabled' : '' }}>
        <option></option>

        @if ($d)
            <option value=""></option>
            @foreach ($districts as $item)
                <option value="{{ $item->id }}" {{ $item->selected }}>{{ $item->name }}</option>
            @endforeach
        @endif
    </select>
</div>

<div class="form-group">
    <label for="status" class="control-label">{{ __('view.status') }}</label>
    <div class="w-100">
        <label class="switch switch-primary"><input type="checkbox" {{ $status ? 'checked' : '' }} value="1" name="status" id="status"><span></span></label>
    </div>
</div>

<script>
    $('#user_type').chosen({width: '100%'});
    $('#province').chosen({width: '100%'});
</script>