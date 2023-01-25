
<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="{{ asset('assets/plugins/filepond/dist/filepond.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.css') }}"> 

{{-- @if ($param_category_id != 0) --}}
    <div class="row" id="wallet-tab-active">
        @foreach ($data as $key => $item)
            <div class="col-md-{{ $item['col'] }} col-sm-12">
                <a class="widget border widget-hover-effect1 widget-wallet"
                    id="widget-wallet-{{ $item['income_category_id'] }}"
                    onclick="reloadWalletDetail({{ $item['income_category_id'] }})">
                    <div class="widget-simple">
                        <div class="widget-icon pull-left themed-background-autumn animation-fadeIn">
                            <i class="gi gi-parents"></i>
                        </div>
                        <h3 class="widget-content text-right animation-pullDown">
                            <span id="amount_wallet_tab_{{ $item['income_category_id'] }}">{{ $item['amount'] }}</span><br>
                            <small>
                                <b>{{ $key }}</b>
                            </small>
                        </h3>
                    </div>
                </a>
            </div>
        @endforeach
    </div>

    <div class="block full">
        <div class="table-responsive">
            <table class="table" id="table-wallet">
                <thead>
                    <tr>
                        @foreach ($headers as $item)
                            @if ($item == 'checkbox')
                                <th>
                                    <input type="checkbox" id="check-all-wallet" onchange="chooseAllWallet(this)">
                                </th>
                            @else
                                <th>
                                    <small><b>{{ $item }}</b></small>
                                </th>
                            @endif
                        @endforeach
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
    
    <script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="{{ asset('assets/plugins/filepond/dist/filepond.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-exif-orientation/dist/filepond-plugin-image-exif-orientation.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-file-validate-size/dist/filepond-plugin-file-validate-size.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.min.js') }}"></script>
    <script src="{{ asset('assets/plugins/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js') }}"></script>
    <script src="https://cdn.ckeditor.com/4.20.1/standard/ckeditor.js"></script>
    <script>
        dtIntegration();
        reloadWalletDetail("{{ $param_category_id }}");
    </script>
{{-- @endif --}}