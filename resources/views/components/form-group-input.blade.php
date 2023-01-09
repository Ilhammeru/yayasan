<div class="form-group {{ $additional_class }}">
    <label
        for="{{ $id }}"
        class="control-label">
        {{ $label }} 
        @if ($required)
            <span class="text-danger">*</span>
        @endif
    </label>
    <input
        class="form-control"
        id="{{ $id }}"
        name="{{ $name }}"
        type="text"
        value="{{ $val }}"
        placeholder="{{ $placeholder }}"
    />
</div>