<style>
    .custom-col {
        padding: 0 5px;
    }
    .custom-row {
        padding: 0 10px;
    }
</style>
<div class="row custom-row">
    @foreach ($classes as $k => $class)
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
    @endforeach
</div>