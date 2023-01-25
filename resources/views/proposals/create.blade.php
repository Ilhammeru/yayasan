@php
    $data = null;
    $status = null;
    $is_edit = false;
    $docs_path = null;
    if (!empty($proposal)) {
        $data = $proposal;
        if ($data->status != 5) {
            $status = 1;
        }
        $is_edit = true;
    }
@endphp

<form action="" id="form-proposal">
    <div class="form-group">
        <label for="title" class="control-label">@lang('view.title') <span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="{{ __('view.title') }}" id="title" name="title" value="{{ $data ? $data->title : '' }}">
    </div>
    <div class="form-group">
        <label for="event_date" class="control-label">@lang('view.event_date') <span class="text-danger">*</span></label>
        <input type="text" class="form-control select-date" id="event_date" name="event_date" value="{{ $data ? date('Y-m-d H:i', strtotime($data->event_date . ' ' . $data->event_time)) : '' }}">
    </div>
    <div class="form-group">
        <label for="budget_total" class="control-label">@lang('view.budget') <span class="text-danger">*</span></label>
        <input type="text" class="form-control" placeholder="0" value="{{ $data ? number_format($data->budget_total, 0, ',', ',') : '' }}" oninput="regexNumber(this)" onchange="updateValue(this)" id="budget_total" name="budget_total" value="{{ $data ? $data->budget_total : '' }}">
    </div>
    <div class="form-group">
        <label for="pic" class="control-label">@lang('view.pic') <span class="text-danger">*</span></label>
        <select name="pic" data-placeholde="{{ __('view.select_pic') }}" id="pic" class="form-control select-chosen">
            @foreach ($employees as $employee)
                <option value="{{ $employee->id }}" {{ $employee->selected }}>{{ $employee->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="description" class="control-label">@lang('view.description') <span class="text-danger">*</span></label>
        <textarea id="ckeditor_description_proposal" name="message">

            {!! $data ? $data->description : '' !!}
        </textarea>
    </div>
    <div class="form-group">
        <label for="file"
            data-value="`{{ $docs_path }}`"
            id="label-file"
            class="control-label">
            @lang('view.attachment') <span class="text-danger">*</span>
        </label>
        <input type="file"
            class="filepond"
            name="attachments_proposal[]"
            id="filepond-proposal"
            accept=".pdf"
            multiple
            data-allow-reorder="true"
            data-max-file-size="3MB"
            data-max-files="1">
    </div>
    <div class="form-group">
        <label for="status" class="control-label">@lang('view.status')</label>
        <div>
            <label class="switch switch-primary"><input type="checkbox" name="status" value="2" id="status-proposal-form"><span></span></label>
            <span id="text-status-proposal">@lang('view.draft')</span>
        </div>
        <span class="help-block d-none" id="helper-status-proposal">
            @lang('view.cannot_edit_after_publish')
        </span>
    </div>
</form>

<script>
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize,
        FilePondPluginImageEdit,
        FilePondPluginFileValidateType
    );

    // Select the file input and use
    // create() to turn it into a pond
    pond = FilePond.create(
        document.getElementById('filepond-proposal')
    );

    existingFile = "{{ $docs_path }}"
    if (existingFile) {
        split = existingFile.split(',');
        for (let aa = 0; aa < split.length; aa++) {
            pond.addFile(split[aa]);
        }
    }

    FilePond.setOptions({
        server: {
            url: '/proposals/upload-attachments',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            }
        },
        acceptedFileTypes: [
            'application/pdf',
            'application/docx',
        ],
        fileValidateTypeLabelExpectedTypesMap: {
            'application/pdf': '.pdf',
            'application/docx': '.docx',
        }
    });

    // filepond event listener
    document.addEventListener('FilePond:addfilestart', (e) => {
        $('#global-modal .btn-save').prop("disabled", true);
    });
    document.addEventListener('FilePond:processfileprogress', (e) => {
        $('#global-modal .btn-save').prop("disabled", true);
    });
    document.addEventListener('FilePond:processfile', (e) => {
        $('#global-modal .btn-save').prop("disabled", false);
    });
    CKEDITOR.replace( 'ckeditor_description_proposal' );

    $('#status-proposal-form').on('change', function(e) {
        e.preventDefault();
        
        let checked = $(this).prop('checked');
        if (checked) {
            $('#text-status-proposal').text(i18n.view.publish);
            $('#helper-status-proposal').removeClass('d-none');
        } else {
            $('#helper-status-proposal').addClass('d-none');
            $('#text-status-proposal').text(i18n.view.draft);
        }
    })
</script>