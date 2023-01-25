<style>
    .custom-col {
        padding: 0 5px;
    }
    .custom-row {
        padding: 0 10px;
    }
</style>

@php
    $homeroom_data = \Illuminate\Support\Facades\Redis::get('user_homeroom_data');
    $homeroom_data = json_decode($homeroom_data, true);
@endphp

<div class="row custom-row">
    @foreach ($classes as $k => $class)
        @if (auth()->user()->hasRole('kepala yayasan'))
            <div class="col-md-3 custom-col">
                <label class="mb-3" for="class_filter_{{ $class->id }}">
                    <input type="radio"
                        id="class_filter_{{ $class->id }}"
                        {{ $class_id == $class->id ? 'checked' : '' }}
                        name="class_id"
                        value="{{ $class->id }}">
                    
                    {{ $class->name }}
                </label>
            </div>
        @else
            @if (auth()->user()->hasRole('wali kelas'))
                @if ($homeroom_data)
                    @if ($class->id == $homeroom_data['class_id'])
                        <div class="col-md-3 custom-col">
                            <label class="mb-3" for="class_filter_{{ $class->id }}">
                                <input type="radio"
                                    id="class_filter_{{ $class->id }}"
                                    {{ $class_id == $class->id ? 'checked' : '' }}
                                    name="class_id"
                                    value="{{ $class->id }}">
                                
                                {{ $class->name }}
                            </label>
                        </div>
                    @endif
                @endif
            @else
                <div class="col-md-3 custom-col">
                    <label class="mb-3" for="class_filter_{{ $class->id }}">
                        <input type="radio"
                            id="class_filter_{{ $class->id }}"
                            {{ $class_id == $class->id ? 'checked' : '' }}
                            name="class_id"
                            value="{{ $class->id }}">
                        
                        {{ $class->name }}
                    </label>
                </div>
            @endif
        @endif
    @endforeach
</div>