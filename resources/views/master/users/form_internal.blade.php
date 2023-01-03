@php
    $d = null;
    $image = null;
    $status = false;
    if (!empty($data)) {
        $d = $data;
        $status = $data->status;
        $image = $data->image;
    }
@endphp

<div class="row">
    <div class="col-md-6">
        <div class="form-group">
            <div class="text-center" style="display: flex; align-items:center; justify-content: center;">
                <div class="preview-image"
                    id="preview-image"
                    style="width: 150px; position:relative; height: 150px; border-radius: 50%; background-image: @if(!$image) url(../assets/img/blank.png); @else url(../storage/user_internal/{{ $image }}); @endif background-size: cover; background-repeat: no-repeat;">
                    @if (!$image)
                        <i class="fa fa-camera" id="icon-action-image" style="position: absolute; bottom: 10px; font-size: 18px; right: 18px; cursor: pointer;" onclick="selectImage()"></i>
                    @else
                        <i class="fa fa-times" id="icon-action-image" style="position: absolute; bottom: 10px; font-size: 18px; right: 18px; cursor: pointer; color: red;" onclick="removePreviewImage('edit')"></i>
                    @endif
                </div>
            </div>
            <input type="file" name="file" onchange="showImage(event)" style="visibility: hidden;" id="user-image" accept=".png,.webp,.jpg">
            <input type="hidden" name="is_delete_image" id="is_delete_image" value="0">
        </div>
        <div class="form-group">
            <label for="name" class="control-label">@lang('view.name')</label>
            <input type="text" placeholder="Siti Aminah" name="name" value="{{ $d ? $d->name : '' }}" class="form-control" id="name">
        </div>
        <div class="form-group">
            <label for="nis" class="control-label">NIS</label>
            <input type="text" placeholder="12345678" class="form-control" name="nis" id="nis" value="{{ $d ? $d->nis : '' }}">
        </div>
        <div class="form-group">
            <label for="phone" class="control-label">@lang('view.phone')</label>
            <input type="text" placeholder="085123123123" name="phone" oninput="regexNumber(this)" value="{{ $d ? $d->phone : '' }}" class="form-control" id="phone">
        </div>
        <div class="form-group">
            <label for="institution_id" class="control-label">@lang('view.intitutions')</label>
            <select name="institution_id" onchange="getClasses(this)" id="institution_id" class="form-control">
                <option value=""></option>
                @foreach ($institutions as $item)
                    <option value="{{ $item->id }}" {{ $item->selected }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="class_id" class="control-label">@lang('view.class')</label>
            <select name="institution_class_id" {{ !$d ? 'disabled' : '' }} onchange="getLevels(this)" id="institution_class_id" class="form-control">
                @if ($d)
                    <option value=""></option>
                    @foreach ($classes as $item)
                        <option value="{{ $item->id }}" {{ $item->selected }}>{{ $item->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group">
            <label for="level_id" class="control-label">@lang('view.level')</label>
            <select name="institution_class_level_id" {{ !$d ? 'disabled' : '' }} id="institution_class_level_id" class="form-control">
                @if ($d)
                    <option value=""></option>
                    @foreach ($levels as $item)
                        <option value="{{ $item->id }}" {{ $item->selected }}>{{ $item->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
    </div>
    <div class="col-md-6">
        <div class="form-group">
            <label for="parent" class="control-label">@lang('view.parent')</label>
            <input type="text" placeholder="Rohani" name="parent" value="{{ $d ? $d->parent_data : '' }}" class="form-control" id="parent">
        </div>
        <div class="form-group">
            <label for="address" class="control-label">@lang('view.address')</label>
            <input type="text" placeholder="Jl. Kemerdekaan 45" name="address" value="{{ $d ? $d->address : '' }}" class="form-control" id="address">
        </div>
        <div class="form-group">
            <label for="province_id" class="control-label">@lang('view.province')</label>
            <select name="province_id" id="province_id" onchange="getCity(this)" class="form-control">
                <option value=""></option>
                @foreach ($provinces as $item)
                    <option value="{{ $item->id }}" {{ $item->selected }}>{{ $item->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="city_id" class="control-label">@lang('view.city')</label>
            <select name="city_id" onchange="getDistrict(this)" {{ !$d ? 'disabled' : '' }} id="city_id" class="form-control">
                @if ($d)
                    <option value=""></option>
                    @foreach ($cities as $item)
                        <option value="{{ $item->id }}" {{ $item->selected }}>{{ $item->name }}</option>
                    @endforeach
                @endif
            </select>
        </div>
        <div class="form-group">
            <label for="district_id" class="control-label">@lang('view.district')</label>
            <select name="district_id" {{ !$d ? 'disabled' : '' }} id="district_id" class="form-control">
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
    </div>
</div>

<script>
    $('#institution_id').chosen({width: '100%'});
    $('#province_id').chosen({width: '100%'});
    @if($d)
        $('#institution_class_id').chosen({width: '100%'});
        $('#institution_class_level_id').chosen({width: '100%'});
        $('#city_id').chosen({width: '100%'});
        $('#district_id').chosen({width: '100%'});
    @endif
</script>