@php
    $checked = '';
    $checked_has_class = '';
    $classes = [];
    $id = 0;
    if (!empty($data)) {
        if ($data->status) {
            $checked = 'checked';
        }

        if (count($data->classes) > 0) {
            $checked_has_class = 'checked';
            $classes = $data->classes;
        }

        $id = $data->id;
    }
@endphp

<div class="form-group mb-3">
    <label for="name" class="control-label required">{{ __('view.name') }} <span class="text-danger">*</span></label>
    <input type="text" placeholder="{{ __('view.name') }}" class="form-control form-control-sm" id="name" name="name" value="{{ !empty($data) ? $data->name : '' }}">
    <input type="hidden" name="current_id" value="{{ $id }}" id="current_id">
</div>
<div class="form-group mb-3">
    <label for="has_class" class="control-label">{{ __('view.has_class') }}</label>
    <div class="w-100">
        <label class="switch switch-primary"><input type="checkbox" {{ $checked_has_class }} value="1" name="has_class" onchange="hasClass()"><span></span></label>
    </div>
</div>
{{-- class --}}
<div id="target-class-level">
    <div class="class-container {{ count($classes) > 0 ? '' : 'd-none' }}">
        @if (count($classes) > 0)
            @foreach ($classes as $key => $class)
                <div class="border p-3 mb-3 class-wrapper" id="class-wrapper-{{ $key }}" style="position: relative; width: 100%;">
                    @if ($key == 0)
                        <span class="gi gi-circle_plus text-primary" onclick="appendClass('{{ __('view.class_name') }}', '{{ __('view.class_level') }}')" style="position: absolute; top: -4px; right: -2px; font-size: 18px; cursor: pointer;"></span>
                    @else
                        <span class="gi gi-remove text-danger" onclick="deleteClassRow({{$key}}, {{$id}})" style="position: absolute; top: -4px; right: -2px; font-size: 18px; cursor: pointer;"></span>
                    @endif
                    <div class="row">
                        @if (count($class->levels) == 0)
                            <div class="col-md-6 col-sm-12 level-wrapper-{{ $key }}" data-key="0" id="level-helper-f-0-{{ $key }}">
                                <div class="form-group mb-3">
                                    <label for="class_name" class="control-label">{{ __('view.class_name') }}</label>
                                    <input type="text" name="ins[{{$key}}][class_name]" data-key="{{ $key }}" value="{{ $class->name }}" placeholder="{{ __('view.class_name') }}" class="form-control form-control-sm" id="class_name">
                                    <input type="hidden" name="ins[{{$key}}][class_id]" value="{{ $class->id }}">
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-12" id="level-helper-s-0-{{ $key }}">
                                <div class="form-group" style="margin-bottom: 0;">
                                    <label for="class_level" class="control-label">{{ __('view.class_level') }}</label>
                                    <div class="input-group">
                                        <input type="text" id="class_level" value="" data-key="0" name="ins[{{$key}}][class][0][level]" class="form-control form-control-sm" placeholder="A / B / C / etc" required>
                                        <span class="input-group-addon"><i class="gi gi-plus" onclick="appendLevel({{$key}})" style="cursor: pointer;"></i></span>
                                    </div>
                                </div>
                            </div>
                        @endif
                        @foreach ($class->levels as $k => $level)
                            <div class="col-md-6 col-sm-12 level-wrapper-{{ $key }}" data-key="{{ $k }}" id="level-helper-f-{{ $k }}-{{ $key }}">
                                @if ($k == 0)
                                    <div class="form-group mb-3">
                                        <label for="class_name" class="control-label">{{ __('view.class_name') }}</label>
                                        <input type="text" name="ins[{{$key}}][class_name]" data-key="{{ $key }}" value="{{ $class->name }}" placeholder="{{ __('view.class_name') }}" class="form-control form-control-sm" id="class_name">
                                        <input type="hidden" name="ins[{{$key}}][class_id]" value="{{ $class->id }}">
                                    </div>
                                @endif
                            </div>
                            <div class="col-md-6 col-sm-12" id="level-helper-s-{{ $k }}-{{ $key }}">
                                <div class="form-group" style="margin-bottom: 0;">
                                    @if ($k == 0)
                                        <label for="class_level" class="control-label">{{ __('view.class_level') }}</label>
                                    @endif
                                    <div class="input-group">
                                        <input type="text" id="class_level" value="{{ $level->name }}" data-key="{{ $k }}" name="ins[{{$key}}][class][{{$k}}][level]" class="form-control form-control-sm" placeholder="A / B / C / etc" required>
                                        <input type="hidden" name="ins[{{$key}}][class][{{$k}}][level_id]" value="{{ $level->id }}">
                                        @if ($k == 0)
                                            <span class="input-group-addon"><i class="gi gi-plus" onclick="appendLevel({{$key}})" style="cursor: pointer;"></i></span>
                                        @else
                                            <span class="input-group-addon"><i class="gi gi-remove_2" onclick="deleteLevel({{$k}}, {{$key}}, {{$id}})" style="color: red; cursor: pointer;"></i></span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        <div id="target-class-level-{{ $key }}"></div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="border p-3 mb-3 class-wrapper" style="position: relative; width: 100%;">
                <span class="gi gi-circle_plus text-primary" onclick="appendClass('{{ __('view.class_name') }}', '{{ __('view.class_level') }}')" style="position: absolute; top: -4px; right: -2px; font-size: 18px; cursor: pointer;"></span>
                <div class="row">
                    <div class="col-md-6 col-sm-12 level-wrapper-0" data-key="0" id="level-helper-f-0-0">
                        <div class="form-group mb-3">
                            <label for="class_name" class="control-label">{{ __('view.class_name') }}</label>
                            <input type="text" name="ins[0][class_name]" placeholder="{{ __('view.class_name') }}" class="form-control form-control-sm class-input-0" id="class_name">
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-12">
                        <div class="form-group" style="margin-bottom: 0;">
                            <label for="class_level" class="control-label">{{ __('view.class_level') }}</label>
                            <div class="input-group">
                                <input type="text" id="class_level" name="ins[0][class][0][level]" class="form-control form-control-sm level-input-0" placeholder="A / B / C / etc" required>
                                <span class="input-group-addon"><i class="gi gi-plus" onclick="appendLevel(0)" style="cursor: pointer;"></i></span>
                            </div>
                        </div>
                    </div>
                    <div id="target-class-level-0"></div>
                </div>
            </div>
        @endif
        <div id="target-class"></div>
    </div>
</div>
{{-- end::class --}}

{{-- incomes --}}
<div class="form-group mb-3">
    <label for="income_category_ids" class="control-label">@lang('view.choose_income_category_institution')</label>
    <select name="income_category_ids[]" id="income_category_ids" multiple="true" class="form-control" data-placeholder="{{ __('view.choose_income_category_institution') }}">
        @foreach ($income_categories as $category)
            <option value="{{ $category->id }}" {{ $category->selected ? 'selected' : '' }}>{{ $category->name }}</option>
        @endforeach
    </select>
</div>
{{-- end::incomes --}}
<div class="form-group mb-3">
    <label for="status" class="control-label">{{ __('view.status') }}</label>
    <div class="w-100">
        <label class="switch switch-primary"><input type="checkbox" {{ $checked }} value="1" name="status"><span></span></label>
    </div>
</div>

<script>
    $('#income_category_ids').select2();
</script>