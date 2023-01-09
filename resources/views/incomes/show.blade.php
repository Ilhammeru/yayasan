@extends('layouts.base')

@push('styles')
	<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
	<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond/dist/filepond.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.css') }}">
	<style>
		.detail-wrapper {
			border: 1px solid #f4f4f4;
		}
		.detail-wrapper .detail-header {
			padding: 10px 30px;
			display: flex;
			align-items: center;
			justify-content: space-between;
		}
		.detail-wrapper .detail-header .status-payment {
			margin: 0;
			font-weight: bold;
			font-size: 20px;
		}
		.detail-wrapper .action {
			display: flex;
			align-items: center;
			gap: 25px;
		}
		.block.full {
			margin-bottom: 200px !important;
		}

		@media screen and (min-width: 1600px) {
			.block.full {
				margin: 0 80px;
			}
		}
	</style>
@endpush

@section('content')
	<div class="block full">
		<div class="block-title">
			<h2>{{ __('view.detail_invoice') }} <b style="font-size: 24px;">{{ $data->invoice_number }}</b>	</h2>
            <div style="padding: 0 15px;">
                <a class="btn btn-warning btn-sm" href="{{ route('incomes.index') }}">{{ __('view.back') }}</a>
            </div>
		</div>

		<div class="detail-wrapper">
			<div class="detail-header">
				<p class="status-payment" style="color: {{ $data->payment_status_color }}">
					{{ $data->payment_status_text }}
				</p>

				{{-- action --}}
				<div class="action">
					@include('incomes.components.action_detail')
				</div>
			</div>

			{{-- detail customer --}}
			@include('incomes.components.detail_customer')

			{{-- detail items --}}
			@include('incomes.components.detail_items', ['items' => $data->items])

		</div>

		{{-- transaction section --}}
		<div class="transaction-section"></div>
	</div>


	{{-- modal detail proof of payment --}}
	{{-- modal --}}
    <div class="modal fade" id="modalProofPayment" tabindex="-1" aria-labelledby="modalProofPaymentLabel" aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <form action="" id="form-position">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="modalProofPaymentLabel">{{ __('view.proof_of_payment') }}</h1>
                    </div>
                    <div class="modal-body">
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" id="btn-cancel" onclick="closeModal('modalProofPayment')">Close</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
	<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
	<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
	<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>

	<script src="{{ asset('assets/plugins/filepond/dist/filepond.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js') }}"></script>
	<script src="{{ asset('assets/plugins/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js') }}"></script></script>
		<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>

	<script src="{{ mix('dist/js/mainIncome.js') }}"></script>
	<script>
		appendPaymentDetail("{{ $data->id }}");
		initTransaction("{{ $data->id }}");

		function initTransaction(id) {
		    $.ajax({
		        type: "POST",
		        url: base_url + '/incomes/generate-transaction',
		        data: {
		            income_id: id
		        },
		        success: function(res) {
		            $('.transaction-section').html(res.view);
		            $('.status-payment').html(res.data.status);
		            FilePond.registerPlugin(
		                FilePondPluginImagePreview,
		                FilePondPluginImageExifOrientation,
		                FilePondPluginFileValidateSize,
		                FilePondPluginImageEdit,
		                FilePondPluginFileValidateType
		            );

		            // Select the file input and use
		            // create() to turn it into a pond
		            FilePond.create(
		                document.getElementById('filepond-input'), {
		                    acceptedFileTypes: ['image/png', 'image/jpg', 'image/webp'],
		                }
		            );
		            FilePond.setOptions({
		                server: {
		                    url: '/incomes/upload/proof-of-payment',
		                    headers: {
		                        'X-CSRF-TOKEN': "{{ csrf_token() }}",
		                    }
		                }
		            });
		            // filepond event listener
		            document.addEventListener('FilePond:addfilestart', (e) => {
		                $('#btn-add-payment').prop("disabled", true);
		            });
		            document.addEventListener('FilePond:processfileprogress', (e) => {
		                $('#btn-add-payment').prop("disabled", true);
		            });
		            document.addEventListener('FilePond:processfile', (e) => {
		                $('#btn-add-payment').prop("disabled", false);
		            });

		            $('input[name="transaction_date"]').daterangepicker({
		                singleDatePicker: true,
		                autoApply: true,
		                minDate: "{{ date('Y-m-d') }}",
		                locale: {
		                    format: 'YYYY-MM-DD'
		                }
		            });

		            $( "#accordion" ).accordion({
				      collapsible: true,
				      active: true
				    });
		        }
		    })
		}
	</script>
@endpush