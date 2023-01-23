@php
    $levels = $classes->levels;
@endphp

<div class="detail-filter-level">
    <div class="item">
        @foreach ($levels as $level)
            @php
                $active = 'btn-default';
                if ($level->id == $level_id) {
                    $active = 'themed-background-default themed-color-white';
                }
            @endphp
            <button class="btn filter-level {{ $active }}"
                id="filter-level-{{ $level->id }}"
                type="button"
                onclick="changeLevel({{ $institution_id }}, {{ $level->id }}, {{ $classes->id }})">
                {{ $level->name }}
            </button>
        @endforeach
    </div>
</div>