@php
    $class = $data->classes[0];
    if (count($class->levels) > 0) {
        $level = $class->levels[0];
    }
@endphp

<style>
    .detail-filter-level .item {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 10px;
    }
    .detail-filter-level .title {
        text-align: center;
        font-weight: bolder;
        font-size: 16px;
    }
    .detail-filter-level {
        margin-bottom: 15px;
    }
    .empty-data-img {
        width: 200px;
        height: auto;
    }
</style>

@php
    $all_gender = 0;
    $male = 0;
    $female = 0;
    if (count($genders) > 0) {
        $male = isset($genders['P']) ? $genders['P'] : 0;
        $female = isset($genders['L']) ? $genders['L'] : 0;
        $all_gender = $male + $female;
    }
@endphp

@if (!$selected_level)
    <div class="text-center">
        <img src="{{ asset('assets/img/empty_data.jpg') }}" class="empty-data-img" alt="">
        <p style="margin: 0;">@lang("view.level_doesn't_exist")</p>
        <p>@lang('view.click_to_add_level', ['ins_id' => $data->id])</p>
    </div>
@else
    @if (!$is_update)
        @include('master.intitutions.components.detail_institution_level', ['classes' => $class, 'level_id' => $level_id, 'institution_id' => $institution_id])
    @endif
    <div @if(!$is_update)class="block border"@endif id="target-detail-table-data">
        <table class="table table-borderless">
            <tbody>
                <tr>
                    <td colspan="3" class="text-center">
                        <b>@lang('view.detail_selected_class', ['class' => 1, 'level' => 'b'])</b>
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%;">@lang('view.homeroom_teacher')</td>
                    <td style="width: 1%;">:</td>
                    <td id="target-homeroom-teacher">
                        @if ($selected_level->homeroom_teacher)
                            <b>{{ $selected_level->homeroomTeacher->name }}</b> <a style="cursor: pointer;" onclick="chooseHomeroomTeacher({{ $class_id }}, {{ $level_id }}, {{ $institution_id }})">@lang('view.change_homeroom') <i class="fa fa-share"></i></a> 
                        @else
                            <i>@lang('view.doest_have_homeroom_teacher', ['class_id' => $class_id, 'level_id' => $level_id, 'institution_id' => $institution_id])</i>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="width: 20%;">@lang('view.number_of_student')</td>
                    <td style="width: 1%;">:</td>
                    <td>
                        @if (count($genders) > 0)
                            <b>{{ $all_gender }} @lang('view.students'), {{ $female }} @lang('view.male') @lang('view.and') {{ $male }} @lang('view.female')</b>
                        @else
                            <b>0</b>
                        @endif
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
@endif