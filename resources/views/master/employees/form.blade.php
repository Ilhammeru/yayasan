@php
    $status = 1;
    $inst = null;
    $post = null;
    $prov = null;
    $user = null;
    if (!empty($employee)) {
        $status = $employee->status;
        $inst = $employee->institution_id;
        $post = $employee->position_id;
        $prov = $employee->province_id;
        if ($employee->user) {
            $user = $employee->user;
        }
    }
@endphp

@if (count($institutions) == 0 || count($positions) == 0)
    <div class="alert alert-warning">
        @if (count($institutions) == 0 && count($positions) == 0)
            {{ __('view.cannot_continue_bcs_pos_and_inst') }}
        @elseif(count($positions) == 0)
            {{ __('view.cannot_continue_bcs_position') }}
        @elseif (count($institutions))
            {{ __('view.cannot_continue_bcs_institution') }}
        @endif
    </div>
@endif

<p class="title" style="text-align:center; font-size: 22px; margin: 0; padding: 0;">General</p>

<hr style="padding: 5px; margin: 0;">

<div class="row">
    <div class="col-md-6">
        <div class="block no-border">
            <div class="form-group">
                <label class="control-label" for="name">{{ __('view.employee_name') }} <span class="text-danger">*</span></label>
                <input type="text" id="name" value="{{ !empty($employee) ? $employee->name : '' }}" name="name" class="form-control" placeholder="Ki Hajar Dewantoro">
            </div>
            <div class="form-group">
                <label class="control-label" for="email">{{ __('view.email') }} <span class="text-danger">*</span></label>
                <input type="email" id="email" value="{{ !empty($employee) ? $employee->email : '' }}" name="email" class="form-control" placeholder="admin@gmail.com">
            </div>
            <div class="form-group">
                <label class="control-label" for="nip">NIP <span class="text-danger">*</span></label>
                <input type="text" id="nip" name="nip" class="form-control" value="{{ !empty($employee) ? $employee->nip : '' }}">
            </div>
            <div class="form-group">
                <label class="control-label" for="phone">{{ __('view.phone') }} <span class="text-danger">*</span>  </label>
                <input type="text" id="phone" oninput="regexNumber(this)" name="phone" value="{{ !empty($employee) ? $employee->phone : '' }}" class="form-control" placeholder="081234578">
            </div>
            <div class="form-group">
                <label class="control-label" for="account_number">{{ __('view.account_number') }} <span class="text-danger">*</span></label>
                <input type="text" id="account_number" oninput="regexNumber(this)" name="account_number" value="{{ !empty($employee) ? $employee->account_number : '' }}" class="form-control" placeholder="12345567">
            </div>
            <div class="form-group">
                <label for="institution_id" class="control-label">{{ __('view.intitutions') }} <span class="text-danger">*</span></label>
                <select id="institution_id" name="institution_id" class="select-chosen form-control" style="width: 100%;" data-placeholder="Choose one..">
                    <option value=""></option>
                    @foreach ($institutions as $item)
                        <option value="{{ $item->id }}" {{ $inst == $item->id ? 'selected' : '' }}>{{ $item->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="position_id" class="control-label">{{ __('view.position') }} <span class="text-danger">*</span></label>
                <select id="position_id" name="position_id" class="select-chosen form-control" style="width: 100%;" data-placeholder="Choose one..">
                    <option value=""></option>
                    @foreach ($positions as $pos)
                        <option value="{{ $pos->id }}" {{ $post == $pos->id ? 'selected' : '' }}>{{ $pos->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="block no-border">
            <div class="form-group">
                <label for="address" class="control-label">{{ __('view.address') }} <span class="text-danger">*</span></label>
                <input type="text" name="address" value="{{ !empty($employee) ? $employee->address : '' }}" placeholder="Jl. Merdeka No 45" class="form-control" id="address">
            </div>
            <div class="form-group">
                <label for="province_id" class="control-label">{{ __('view.province') }} <span class="text-danger">*</span></label>
                <select id="province_id" onchange="getCity(this)" name="province_id" class="select-chosen form-control" style="width: 100%;" data-placeholder="Choose one..">
                    <option value=""></option>
                    @foreach ($provinces as $province)
                        <option value="{{ $province->id }}" {{ $prov == $province->id ? 'selected' : '' }}>{{ $province->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label for="city_id" class="control-label">{{ __('view.city') }} <span class="text-danger">*</span></label>
                <select id="city_id" onchange="getDistrict(this)" disabled name="city_id" class="select-chosen form-control" style="width: 100%;" data-placeholder="Choose one..">
                </select>
            </div>
            <div class="form-group">
                <label for="district_id" class="control-label">{{ __('view.district') }} <span class="text-danger">*</span></label>
                <select id="district_id" disabled name="district_id" class="select-chosen form-control" style="width: 100%;" data-placeholder="Choose one..">
                </select>
            </div>
            <div class="form-group">
                <label for="status" class="control-label">{{ __('view.status') }}</label>
                <br>
                <label class="switch switch-primary"><input type="checkbox" id="status" {{ $status == 1 ? 'checked' : '' }}><span></span></label>
            </div>
        </div>
    </div>
</div>

<hr style="padding: 5px; margin: 0;">

<p class="title" style="text-align:center; font-size: 22px; margin: 0; padding: 0;">User</p>

<hr style="padding: 5px; margin: 0;">

<div class="row">
    <div class="col-md-6">
        <div class="block no-border">
            <div class="form-group">
                <label for="username" class="control-label">Username</label>
                <input type="text" value="{{ !empty($user) ? $user->username : '' }}" class="form-control" id="username" name="username" placeholder="johndoe">
            </div>
            <div class="form-group">
                <label for="password" class="control-label">Password</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="******">
                <input type="hidden" name="from_edit" value="{{ !empty($user) ? 1 : 0 }}">
                @if ($user)
                    <span class="help-block">{{ __('view.password_hint') }}</span>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
    $('#province_id').chosen({width: "100%"});
    $('#institution_id').chosen({width: "100%"});
    $('#position_id').chosen({width: "100%"});

    @if (count($institutions) == 0 || count($positions) == 0)
        disableButton('btn-save');
    @endif

    @if(!empty($employee))
        getCity("{{ $employee->province_id }}", "{{ $employee->city_id }}");
        $('#city_id').prop('disabled', false);
        getDistrict("{{ $employee->city_id }}", "{{ $employee->district_id }}");
        $('#district_id').prop('disabled', false);
    @endif
</script>