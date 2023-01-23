<form action="" id="form-send-wallet">
    <div class="form-group">
        <label for="account" class="control-label">@lang('view.account')</label>
        <select name="account" data-placeholder="{{ __('view.search_account') }}" id="account" class="form-control select-chosen">
            <option value=""></option>
            @foreach ($account as $item)
                <option value="{{ $item->id }}">{{ $item->code . ' - ' . $item->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label for="total" class="control-label">@lang('view.total')</label>
        <input type="text" readonly class="form-control" value="Rp. {{ number_format($total, 0, '.', '.') }}">
        <input type="hidden" class="form-control" name="total" value="{{ $total }}">
    </div>
    <div class="form-group">
        <label for="description" class="control-label">@lang('view.description')</label>
        <textarea id="ckeditor_message_send_wallet" name="message"></textarea>
    </div>
    <div class="form-group">
        <label for="file" class="control-label">@lang('view.attachment')</label>
        <input type="file"
            class="filepond"
            name="attachments_send_wallet[]"
            id="filepond-send-wallet"
            multiple
            data-allow-reorder="true"
            data-max-file-size="3MB"
            data-max-files="5">
    </div>

    {{-- hidden form --}}
    @foreach ($ids as $id)
        <input type="hidden" name="wallet_ids[]" value="{{ $id }}">
    @endforeach
    <input type="hidden" name="income_category_id" value="{{ $income_category_id }}">
    <input type="hidden" name="type" value="transfer_fund">
</form>

<script>
    FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize,
        FilePondPluginImageEdit
    );

    // Select the file input and use
    // create() to turn it into a pond
    FilePond.create(
        document.getElementById('filepond-send-wallet')
    );
    FilePond.setOptions({
        server: {
            url: '/users/upload/attachement/send-wallet',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            }
        }
    });

    // filepond event listener
    document.addEventListener('FilePond:addfilestart', (e) => {
        $('#modal-send-wallet .btn-save').prop("disabled", true);
    });
    document.addEventListener('FilePond:processfileprogress', (e) => {
        $('#modal-send-wallet .btn-save').prop("disabled", true);
    });
    document.addEventListener('FilePond:processfile', (e) => {
        $('#modal-send-wallet .btn-save').prop("disabled", false);
    });
    CKEDITOR.replace( 'ckeditor_message_send_wallet' );
</script>