<div style="display: flex; flex-wrap: wrap; align-items: center;">
    @if ($data->status == 5)
        @if (auth()->user()->can('edit proposal'))
            <button type="button"
                onclick="updateForm({{ $data->id }}, `{{ __('view.update_proposal') }}`)"
                data-toggle="tooltip"
                title="Edit"
                class="btn btn-primary btn-sm">
                <i class="fa fa-pencil"></i> @lang('view.edit')
            </button>

            <button type="button"
                onclick="publishProposal({{ $data->id }})"
                data-toggle="tooltip"
                title="{{ __('view.publish') }}"
                class="btn themed-background-spring btn-sm themed-color-white">
                <i class="fa fa-check-square-o"></i> @lang('view.publish')
            </button>
        @endif
    @endif

    @if (auth()->user()->can('detail proposal'))
        <button type="button"
            onclick="detailProposal({{ $data->id }}, `{{ __('view.detail_proposal') }}`)"
            data-toggle="tooltip"
            title="{{ __('view.see_proposal') }}"
            class="btn btn-info btn-sm">
            <i class="fa fa-eye"></i> @lang('view.see_proposal')
        </button>
    @endif
        
    @if (auth()->user()->can('approve proposal') && $data->status == 2)
        <a href="{{ asset('storage/' . $data->docs[0]->path) }}"
            target="_blank"
            data-toggle="tooltip"
            title="{{ __('view.see_proposal') }}"
            class="btn btn-info btn-sm">
            <i class="gi gi-paperclip"></i> @lang('view.detail_document')
        </a>
        <button type="button"
            onclick="approveProposal({{ $data->id }})"
            data-toggle="tooltip"
            title="{{ __('view.approve') }}"
            class="btn btn-success btn-sm">
            <i class="fa fa-check"></i> @lang('view.approve')
        </button>
    @endif
        
    @if (auth()->user()->can('cash out proposal budget') && $data->status == 3)
        <button type="button"
            onclick="fundingProposal({{ $data->id }}, `{{ $data->budget_total }}`)"
            data-toggle="tooltip"
            title="{{ __('view.cash_out') }}"
            class="btn btn-success btn-sm">
            <i class="fa fa-check"></i> @lang('view.cash_out')
        </button>
    @endif
</div>