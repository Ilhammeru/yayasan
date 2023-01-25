<style>
    .table-view-action {
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
</style>

@php
    $homeroom_data = my_homeroom();
@endphp

{{-- filter level and create button --}}
<div class="table-view-action">
    <div class="group">
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
    </div>
    <div style="padding: 0 15px;">
        <button class="btn btn-primary btn-sm"
            type="button"
            onclick="createInvoiceNonPeriod(
                {{ $institution_id }},
                {{ $class_id }},
                {{ $level_id }},
                {{ $income_category }},
                {{ $income_type_id }},
                `{{ $income_category_name }}`
            )">{{ __('view.create') }}</button>
    </div>
</div>
<div class="table-responsive">
    <table class="table" id="table_view">
        <thead>
            <tr>
                @foreach ($table_head as $item)
                    <th>
                        <b><small>{{ $item }}</small></b>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody></tbody>
    </table>
</div>

<script>
    dtIntegration();

    // let columns, dt_route, dt_param;
    dt_route = "{{ $datatable_url }}";
    dt_param = {
        institution_id: `{{ $datatable_param->institution_id }}`,
        class_id: `{{ $datatable_param->class_id }}`,
        level_id: `{{ $datatable_param->level_id }}`,
        income_category: `{{ $datatable_param->income_category }}`,
        income_type_id: `{{ $datatable_param->income_type_id }}`,
        income_category_name: `{{ $datatable_param->income_category_name }}`,
        income_type_period: `{{ $datatable_param->income_type_period }}`,
    }
    columns = [
        {data: 'invoice_number', name: 'invoice_number'},
        {data: 'user_name', name: 'user_name'},
        {data: 'income_method_id', name: 'income_method_id'},
        {data: 'amount', name: 'amount'},
        // {data: 'action', name: 'action', className: 'text-center', orderable: false},
    ];
    dt_table = $('#table_view').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        scrollX: true,
        ajax: {
            url: dt_route,
            data: dt_param
        },
        columns: columns,
        drawCallback: function(settings, json) {
            // tippy('[data-tippy-content]');
        },
        order: [
            [0, 'desc']
        ],
    });
</script>