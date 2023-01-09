<div class="btn-group">
	<a href="javascript:void(0)" data-toggle="dropdown" class="btn btn-alt btn-primary dropdown-toggle">{{ __('view.action') }} <span class="caret"></span></a>
    <ul class="dropdown-menu dropdown-custom text-left">
        <li class="dropdown-header">Header</li>
        <li>
            <a href="javascript:void(0)"><i class="fa fa-envelope pull-right"></i>{{ __('view.send_email') }}</a>
            <a href="javascript:void(0); window.print"><i class="fa fa-print pull-right"></i>{{ __('view.print') }}</a>
        </li>
    </ul>
</div>

<div class="btn-group">
    <a href="javascript:void(0)" data-toggle="dropdown" class="dropdown-toggle"><i class="fa fa-ellipsis-v"></i></a>
    <ul class="dropdown-menu text-left">
        <li><a href="javascript:void(0)">{{ __('view.edit') }}</a></li>
        <li><a href="javascript:void(0)">{{ __('view.delete') }}</a></li>
    </ul>
</div>