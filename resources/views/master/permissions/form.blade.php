@php
    $d = null;
    $group = null;
    if (!empty($data)) {
        $d = $data;
        $group = $data->permission_group_id;
    }
@endphp

<div class="form-group mb-3">
    <label for="name" class="control-label">{{ __('view.name') }}</label>
    <input type="text" name="name" value="{{ $d ? ucfirst($d->name) : '' }}" placeholder="Master Employee" class="form-control" id="name">
</div>
<div class="form-group mb-3">
    <label for="group" class="control-label">{{ __('view.permission_group') }}</label>
    <select name="group" id="group" class="form-control">
        <option value=""></option>
        @foreach ($groups as $g)
            <option value="{{ $g->id }}" {{ $group == $g->id ? 'selected' : '' }}>{{ ucfirst($g->name) }}</option>
        @endforeach
    </select>
</div>

<script>
    $('#group').chosen({width: '100%'});
</script>