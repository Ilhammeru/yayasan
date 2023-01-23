@foreach ($income_categories as $category)
    <div class="radio">
        <label for="income_category_id_{{ $category->id }}">
            <input type="radio" id="income_category_id_{{ $category->id }}" {{ $income_category == $category->id ? 'checked' : '' }} name="income_category_id" value="{{ $category->id }}"> {{ $category->name }}
        </label>
    </div>
@endforeach