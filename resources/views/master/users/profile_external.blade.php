@php
    $image = asset('assets/img/blank.png');
    if ($data->image) {
        $image = asset('storage/user_external/' . $data->image);
    }
@endphp
<div class="text-center user-internal">
    {{-- header --}}
    <div class="header-image">
        <img src="{{ $image }}" alt="user-image">
    </div>
    <div class="header-text">
        <p class="name">{{ $data->name }}</p>
        <p class="nis">( {{ $data->user_type_text }} )</p>
    </div>
    <hr>

</div>

<div class="tab">
    <button class="tablinks active" id="general-tab" type="button" onclick="openTab('general')">General</button>
    <button class="tablinks" id="payment-tab" type="button" onclick="openTab('payment')">Payment</button>
</div>
  
<div id="general" class="tabcontent">
    <table class="table table-vcenter table-borderless">
        <tbody>
            <tr>
                <td>@lang('view.name')</td>
                <td>:</td>
                <td>{{ $data->name }}</td>
            </tr>
            <tr>
                <td>{{ __('view.user_type') }}</td>
                <td>:</td>
                <td>{{ $data->user_type_text }}</td>
            </tr>
            <tr>
                <td>@lang('view.phone')</td>
                <td>:</td>
                <td>{{ $data->phone }}</td>
            </tr>
            <tr>
                <td>@lang('view.address')</td>
                <td>:</td>
                <td>{{ $data->address . ', ' . $data->district->name . ' ' . $data->city->name . ' ' . $data->province->name }}</td>
            </tr>
        </tbody>
    </table>
</div>
  
<div id="payment" class="tabcontent">
    <div class="payment">
        <div class="empty">
            <h3 class="text-center">@lang('view.data_payment_empty')</h3>
        </div>
    </div>
</div>