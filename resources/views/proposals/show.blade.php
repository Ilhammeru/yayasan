<table class="table">
    <tbody>
        <tr>
            <td style="width: 10%;">@lang('view.title')</td>
            <td>:</td>
            <td>{{ $data->title }}</td>
        </tr>
        <tr>
            <td style="width: 10%;">@lang('view.event_date')</td>
            <td>:</td>
            <td>{{ $data->event_date_text }}</td>
        </tr>
        <tr>
            <td style="width: 10%;">@lang('view.pic')</td>
            <td>:</td>
            <td>{{ $data->pic_data }}</td>
        </tr>
        <tr>
            <td style="width: 10%;">@lang('view.budget')</td>
            <td>:</td>
            <td>{{ $data->total_text }}</td>
        </tr>
        <tr>
            <td style="width: 10%;">@lang('view.status')</td>
            <td>:</td>
            <td>{!! $data->status_text !!}</td>
        </tr>
        <tr>
            <td style="width: 10%;">@lang('view.status')</td>
            <td>:</td>
            <td>
                <div>
                    @foreach ($docs as $key => $doc)
                        @php
                            $mime = mime_content_type($doc->real_path);
                        @endphp

                        @if ($mime == 'application/pdf')
                            <a href="{{ asset('storage/' . $doc->path) }}" target="_blank">@lang('view.detail_document') {{ $key + 1 }}</a>
                        @else
                            <a href="{{ route('proposals.download.document', $doc->id) }}">@lang('view.detail_document') {{ $key + 1 }}</a>
                        @endif
                    @endforeach
                </div>
            </td>
        </tr>
    </tbody>
</table>