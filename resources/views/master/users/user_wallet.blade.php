@if ($saldo == 'Rp. 0')
    <a href="javascript:void(0)"
        id="user-saldo">
        {{ $saldo }}
    </a>
@else
    @if (auth()->user()->can('foundation finance'))
        <a href="javascript:void(0)"
            id="user-saldo"
            onclick="openGlobalModal(
                `/users/wallet/detail/{{ auth()->id() }}`,
                null,
                'openTransferFoundForm()',
                true,
                `{{ __('view.detail_wallet') }}`,
                `{{ __('view.close') }}`,
                `{{ __('view.transfer_fund') }}`
            )">
            {{ $saldo }}
        </a>
    @else
        <a href="javascript:void(0)"
            id="user-saldo"
            onclick="openGlobalModal(
                `/users/wallet/detail/{{ auth()->id() }}`,
                null,
                'openSendingWalletForm()',
                true,
                `{{ __('view.detail_wallet') }}`,
                `{{ __('view.close') }}`,
                `{{ __('view.send') }}`
            )">
            {{ $saldo }}
        </a>
    @endif
@endif