<style>
    .box-pay {
        width: 25px;
        height: 25px;
        border-radius: 8px;
        justify-content: center;
        display: flex;
        align-items: center;
        cursor: pointer;
        margin: 0 auto;
    }
    .empty-jpg {
        width: 150px;
        height: auto;
    }

    /* .table-day thead tr th:nth-of-type(1),
    .table-day tbody tr td:nth-of-type(1) {
        display: block;
        position: fixed;
        z-index: 6;
        width: 100px;
        height: auto;
        background-color: rgb(255,255,255);
    }

    .table-day th:nth-of-type(2),
    .table-day td:nth-of-type(2) {
        padding-left: 210px;
    } */

    .table-day .name {
        width: 80%;
    }

    .table-month .name {
        width: 90%;
    }

    #table-spp .name {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        margin: 0;
    }

    .table-responsive:has(#table-spp) {
        overflow-y: auto;
        height: 500px;
        margin-top: 20px;
    }

    .table-month thead {
        position: sticky;
        background: #fff;
        top: -1px;
    }
</style>

@php
    $homeroom_data = my_homeroom();
@endphp

{{-- filter level --}}
@if (count($levels) > 0)
    @foreach ($levels as $level)
        @if (auth()->user()->hasRole('kepala yayasan') || auth()->user()->hasRole('bendahara yayasan'))
            <div class="btn-group btn-group-md" data-toggle="buttons">
                <button class="btn btn-filter-level-income {{ $level->id == $level_id ? 'themed-background-default themed-color-white' : 'btn-default' }}"
                    id="btn-filter-level-{{ $level->id }}"
                    onclick="changeIncomeByLevel(
                        {{ $level->id }},
                        {{ $class_id }},
                        {{ $institution_id }},
                        {{ $income_category }},
                        {{ $income_type_id }},
                        {{ $income_type_period }},
                        `{{ $income_category_name }}`,
                    )">
                    {{ $level->name}}
                </button>
            </div>
        @else
            @if (auth()->user()->hasRole('wali kelas'))
                @if ($homeroom_data)
                    @if ($level->id == $homeroom_data['level_id'])
                        <div class="btn-group btn-group-md" data-toggle="buttons">
                            <button class="btn btn-filter-level-income {{ $level->id == $level_id ? 'themed-background-default themed-color-white' : 'btn-default' }}"
                                id="btn-filter-level-{{ $level->id }}"
                                onclick="changeIncomeByLevel(
                                    {{ $level->id }},
                                    {{ $class_id }},
                                    {{ $institution_id }},
                                    {{ $income_category }},
                                    {{ $income_type_id }},
                                    {{ $income_type_period }},
                                    `{{ $income_category_name }}`,
                                )">
                                {{ $level->name}}
                            </button>
                        </div>
                    @endif
                @endif
            @else
                <div class="btn-group btn-group-md" data-toggle="buttons">
                    <button class="btn btn-filter-level-income {{ $level->id == $level_id ? 'themed-background-default themed-color-white' : 'btn-default' }}"
                        id="btn-filter-level-{{ $level->id }}"
                        onclick="changeIncomeByLevel(
                            {{ $level->id }},
                            {{ $class_id }},
                            {{ $institution_id }},
                            {{ $income_category }},
                            {{ $income_type_id }},
                            {{ $income_type_period }},
                            `{{ $income_category_name }}`,
                        )">
                        {{ $level->name}}
                    </button>
                </div>
            @endif
        @endif
    @endforeach
@endif
@if (count($data_user) == 0)
    <div style="display: flex; align-items: center; justify-content: center;">
        <img src="{{ asset('assets/img/empty_data.jpg') }}" class="empty-jpg" alt="">
    </div>
    <div class="text-center">
        <p>@lang('view.empty_students')</p>
    </div>
@else
    @php
        $tableClass = '';
        if ($income_type_period == 30) {
            $tableClass = 'table-day';
        } else if ($income_type_period == 12) {
            $tableClass = 'table-month';
        }
    @endphp
    <div class="table-responsive">
        <table class="table {{ $tableClass }}" id="table-spp">
            <thead>
                <tr>
                    <th style="width: 13%;">
                        <b><small>@lang('view.name')</small></b>
                    </th>
                    @foreach ($calendar as $cal)
                        <th class="text-center">
                            <b>
                                <small>{{ $cal['month'] }}</small>
                            </b>
                        </th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach ($data_user as $user)
                    <tr>
                        <td style="width: 13%;"><p class="name">{{ $user['name'] }}</p></td>
                        @foreach ($user['list_payments'] as $key => $list)
                            <td class="text-center">
                                @if ($list['paid'])
                                    <div class="themed-background-spring box-pay"
                                        onclick="detailPaidInvoice(
                                            {{ $list['payment_id'] }},
                                        )">
                                        <i class="fa fa-check"></i>
                                    </div>
                                @else
                                    <div class="themed-background-fire box-pay"
                                        onclick="detailInvoiceMonthly(
                                            {{ $institution_id }},
                                            {{ $class_id }},
                                            {{ $level_id }},
                                            {{ $user->id }},
                                            {{ $key + 1 }},
                                            {{ $income_category }},
                                            {{ $income_type_id }},
                                            `{{ $income_category_name }}`
                                        )">
                                        <i class="fa fa-times"></i>
                                    </div>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endif