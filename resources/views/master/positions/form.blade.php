@php
    $data = null;
    $role_id = null;
    if (!empty($position)) {
        $data = $position;
        $role_id = $position->role_id;
    }
@endphp

<div class="form-group mb-3">
    <label for="name" class="control-label">{{ __('view.position_name') }}</label>
    <input type="text" name="name" value="{{ $data ? $data->name : '' }}" class="form-control" id="name">
</div>
<div class="form-group mb-3">
    <label for="role" class="control-label col-form-label">{{ __('view.role') }}</label>
    <select id="role" name="role_id" class="select-role form-control" data-placeholder="Choose a Country..">
        @foreach ($roles as $role)
            <option value="{{ $role->id }}" {{ $role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
        @endforeach
    </select>
</div>