<tr class="text-secondary text-muted">
    <th class="text-center">
        <small>#</small>
    </th>
    <th>
        @if (!$without_text)
            <small>{{ __('view.name') }}</small>
        @endif
    </th>
    <th>
        <small>
            @if (!$without_text)
                {{ __('view.user_type') }}
            @endif
        </small>
    </th>
    <th>
        @if (!$without_text)
            <small>@lang('view.phone')</small>
        @endif
    </th>
    <th>
        @if (!$without_text)
            <small>@lang('view.status')</small>
        @endif
    </th>
    <th class="text-center">
        <small>Action</small>
    </th>
</tr>