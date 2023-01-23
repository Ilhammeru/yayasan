		<style>
			.invoice-box {
				margin: auto;
				padding: 30px;
				border: 1px solid #eee;
				box-shadow: 0 0 10px rgba(0, 0, 0, 0.15);
				font-size: 16px;
				line-height: 24px;
				font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
				color: #555;
			}

			.invoice-box.print {
				max-width: 800px;
			}

			.invoice-box table {
				width: 100%;
				line-height: inherit;
				text-align: left;
			}

			.invoice-box table td {
				padding: 5px;
				vertical-align: top;
			}

			.invoice-box table tr td:nth-child(2) {
				text-align: right;
			}

			.invoice-box table tr.top table td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.top table td.title {
				font-size: 45px;
				line-height: 45px;
				color: #333;
			}

			.invoice-box table tr.information table td {
				padding-bottom: 40px;
			}

			.invoice-box table tr.heading td {
				background: #eee;
				border-bottom: 1px solid #ddd;
				font-weight: bold;
			}

			.invoice-box table tr.details td {
				padding-bottom: 20px;
			}

			.invoice-box table tr.item td {
				border-bottom: 1px solid #eee;
			}

			.invoice-box table tr.item.last td {
				border-bottom: none;
			}

			.invoice-box table tr.total td:nth-child(2) {
				border-top: 2px solid #eee;
				font-weight: bold;
			}

			@media only screen and (max-width: 600px) {
				.invoice-box table tr.top table td {
					width: 100%;
					display: block;
					text-align: center;
				}

				.invoice-box table tr.information table td {
					width: 100%;
					display: block;
					text-align: center;
				}
			}

			/** RTL **/
			.invoice-box.rtl {
				direction: rtl;
				font-family: Tahoma, 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
			}

			.invoice-box.rtl table {
				text-align: right;
			}

			.invoice-box.rtl table tr td:nth-child(2) {
				text-align: left;
			}

			.stamp {
				position: absolute;
				top: 50px;
				right: 50px;
				transform: rotate(12deg);
				color: #555;
				font-size: 6rem;
				font-weight: 700;
				border: 0.25rem solid #555;
				display: inline-block;
				padding: 1.25rem 3rem;
				text-transform: uppercase;
				border-radius: 1rem;
				font-family: 'Courier';
				-webkit-mask-image: url('/assets/img/grunge.png');
				-webkit-mask-size: 944px 604px;
				mix-blend-mode: multiply;
				opacity: .2;
			}

			.is-approved {
				color: #0A9928;
				border: 0.5rem solid #0A9928;
				-webkit-mask-position: 13rem 6rem;
				transform: rotate(-14deg);
				border-radius: 0;
			} 

			.img-proof {
				width: 150px;
				height: auto;
				cursor: pointer;
				transition: all .5s;
			}

			.img-proof:hover {
				transform: scale(1.1);
			}

			#preview-modal-image .modal-dialog {
				max-width: 800px;
				height: auto;
				margin: 6.75rem;
				margin-right: auto;
				margin-left: auto;
			}
		</style>
		<div class="invoice-box {{ $print_mode ? 'print' : '' }}">
			@if (!$print_mode)
				<span class="stamp is-approved">@lang('view.paid')</span>
			@endif
			<table cellpadding="0" cellspacing="0">
				<tr class="top">
					<td colspan="2">
						<table>
							<tr>
								<td class="title">
									<img src="https://www.sparksuite.com/images/logo.png" alt="logo-perusahaan" style="width: 100%; max-width: 300px" />
								</td>

								<td>
									Invoice #: {{ $data->invoice_number }}<br />
									Created: {{ $data->paid_at }}<br />
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="information">
					<td colspan="2">
						<table>
							<tr>
								<td>
									Nama Perusahaan<br />
									Alamat Perusahaan<br />
									Nomor telfon perusahaan
								</td>

								<td>
									<b>{{ $user_data->name }}</b><br />
									{{ $user_data->phone }} <br />
									{{ $user_data->address }}<br />
									{{ $data->institution->name }} - {{ $data->class->name }} ({{ $data->level->name }})
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr class="heading">
					<td>Payment Method</td>

					<td>Check #</td>
				</tr>

				<tr class="details">
					<td>{{ $data->incomeMethod->name }}</td>

					{{-- <td>1000</td> --}}
				</tr>

				<tr class="heading">
					<td>Item</td>

					<td>Price</td>
				</tr>

				<tr class="item">
					<td>{{ $data->incomeCategory->name }}</td>

					<td>{{ $data->amount_text }}</td>
				</tr>

				{{-- <tr class="item">
					<td>Hosting (3 months)</td>

					<td>$75.00</td>
				</tr>

				<tr class="item last">
					<td>Domain name (1 year)</td>

					<td>$10.00</td>
				</tr> --}}

				<tr class="total">
					<td></td>

					<td>Total: {{ $data->amount_text }}</td>
				</tr>
			</table>

			@if (!$print_mode)
				<div class="attachment">
					<p>@lang('view.attachment')</p>
					@foreach ($data->docs as $img)
						<img src="{{ $img->full_path }}"
							class="img-proof"
							alt=""
							onclick="previewImage(`{{ $img->full_path }}`)">
					@endforeach
				</div>

				{{-- modal previce image --}}
				<div class="modal animation-fadeInQuick"
					id="preview-modal-image"
					tabindex="-1">
					<div class="modal-dialog">
						<div class="modal-content">
							{{-- <div class="modal-header">
								<h5 class="modal-title">Modal title</h5>
								<button type="button"
									class="btn"
									type="button"
									onclick="closeModal('invoice-modal')">
									<i class="fa fa-times"></i>
								</button>
							</div> --}}
							<div class="modal-body">
							</div>
							{{-- <div class="modal-footer">
								<button type="button"
									class="btn btn-secondary btn-close"
									data-bs-dismiss="modal"
									onclick="closeModal('invoice-modal')"></button>
								<button type="button"
									class="btn btn-primary btn-save"></button>
							</div> --}}
						</div>
					</div>
				</div>
			@endif
		</div>

@if ($print_mode)
<script>
	window.print();
</script>
@endif