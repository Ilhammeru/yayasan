<style>
	.table-item-footer tbody tr td {
		font-weight: bold;
	}
	#accordion .message-body {
		height: 400px;
	}
</style>

<div class="row">
	<div class="col-md-6">
		<div id="accordion">
		 	<h3>{{ __('view.message') }}</h3>
		  	<div class="message-body">
			   	<textarea id="ckeditor_message" name="message"></textarea>
		  	</div>
		  	{{-- <h3>{{ __('view.attachment') }}</h3>
		  	<div>
			    <input type="file"
                    class="filepond"
                    name="attachments[]"
                    id="filepond-input"
                    multiple
                    data-allow-reorder="true"
                    data-max-file-size="3MB"
                    data-max-files="5">
		  	</div> --}}
		</div>
	</div>
	<div class="col-md-6">
		<div class="responsive">
			<table class="table table-item-footer">
				<tbody>
					<tr>
						<td style="text-align: right;">Total</td>
						<td style="text-align: right;" id="total-item">
							<input type="text" name="amount_total" id="amount_total" style="border: none; text-align: right;" value="0">
						</td>
					</tr>
					<tr>
						<td style="text-align: right;">{{ __('view.remaining_bill') }}</td>
						<td style="text-align: right;">
							<input type="text" name="remaining_bill" id="remaining_bill" value="0" style="border: none; text-align: right;">
						</td>
					</tr>
					<tr>
						<td colspan="2">
							<button class="btn btn-primary text-center" id="btn-save-invoice" style="width: 100%;" type="button" onclick="saveItem()">{{ __('view.save') }}</button>
						</td>
					</tr>
				</tbody>
			</table>
		</div>
	</div>
</div>

<script src="{{ asset('assets/plugins/filepond/dist/filepond.min.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js') }}"></script>
<script src="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js') }}"></script>
<script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
<script>
	$( function() {
	    $( "#accordion" ).accordion({
	      collapsible: true,
	      active: false
	    });
	  } );

	FilePond.registerPlugin(
        FilePondPluginImagePreview,
        FilePondPluginImageExifOrientation,
        FilePondPluginFileValidateSize,
        FilePondPluginImageEdit
    );

    // Select the file input and use
    // create() to turn it into a pond
    FilePond.create(
        document.getElementById('filepond-input')
    );
    FilePond.setOptions({
        server: {
            url: '/incomes/upload/attachement',
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}",
            }
        }
    });
    // filepond event listener
    document.addEventListener('FilePond:addfilestart', (e) => {
        $('#btn-save-invoice').prop("disabled", true);
    });
    document.addEventListener('FilePond:processfileprogress', (e) => {
        $('#btn-save-invoice').prop("disabled", true);
    });
    document.addEventListener('FilePond:processfile', (e) => {
        $('#btn-save-invoice').prop("disabled", false);
    });
    CKEDITOR.replace( 'ckeditor_message' );
</script>