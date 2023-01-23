<select name="homeroom" id="homeroom" class="form-control" data-placeholder="{{ __('view.select_homeroom') }}">
    <option value=""></option>
    @foreach ($data as $d)
        <option value="{{ $d->id }}">{{ $d->name }}</option>
    @endforeach
</select>
<input type="hidden" name="class_id" value="{{ $class_id }}">
<input type="hidden" name="level_id" value="{{ $level_id }}">
<input type="hidden" name="institution_id" value="{{ $institution_id }}">