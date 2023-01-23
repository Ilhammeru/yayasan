<!-- Alternative Sidebar -->
<div id="sidebar-alt">
    <!-- Wrapper for scrolling functionality -->
    <div id="sidebar-alt-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Chat -->
            <!-- Chat demo functionality initialized in js/app.js -> chatUi() -->
            <a href="page_ready_chat.html" class="sidebar-title">
                <i class="gi gi-comments pull-right"></i> <strong>Chat</strong>UI
            </a>

            <!-- Chat Talk -->
            <div class="chat-talk display-none">
                <!-- Chat Info -->
                <div class="chat-talk-info sidebar-section">
                    <button id="chat-talk-close-btn" class="btn btn-xs btn-default pull-right">
                        <i class="fa fa-times"></i>
                    </button>
                    <img src="{{ asset('assets/img/placeholders/avatars/avatar5.jpg') }}" alt="user-avatar" class="img-circle pull-left">
                    {{ auth()->user()->username }}
                </div>
                <!-- END Chat Info -->

                <!-- Chat Messages -->
                <ul class="chat-talk-messages">
                    <li class="text-center"><small>Yesterday, 18:35</small></li>
                    <li class="chat-talk-msg animation-slideRight">Hey admin?</li>
                    <li class="chat-talk-msg animation-slideRight">How are you?</li>
                    <li class="text-center"><small>Today, 7:10</small></li>
                    <li class="chat-talk-msg chat-talk-msg-highlight themed-border animation-slideLeft">I'm fine, thanks!</li>
                </ul>
                <!-- END Chat Messages -->

                <!-- Chat Input -->
                <form action="index.html" method="post" id="sidebar-chat-form" class="chat-form">
                    <input type="text" id="sidebar-chat-message" name="sidebar-chat-message" class="form-control form-control-borderless" placeholder="Type a message..">
                </form>
                <!-- END Chat Input -->
            </div>
            <!--  END Chat Talk -->
            <!-- END Chat -->

            <!-- Activity -->
            <a href="javascript:void(0)" class="sidebar-title">
                <i class="fa fa-globe pull-right"></i> <strong>Activity</strong>UI
            </a>
            <div class="sidebar-section">
                <div class="alert alert-danger alert-alt">
                    <small>just now</small><br>
                    <i class="fa fa-thumbs-up fa-fw"></i> Upgraded to Pro plan
                </div>
                <div class="alert alert-info alert-alt">
                    <small>2 hours ago</small><br>
                    <i class="gi gi-coins fa-fw"></i> You had a new sale!
                </div>
                <div class="alert alert-success alert-alt">
                    <small>3 hours ago</small><br>
                    <i class="fa fa-plus fa-fw"></i> <a href="page_ready_user_profile.html"><strong>John Doe</strong></a> would like to become friends!<br>
                    <a href="javascript:void(0)" class="btn btn-xs btn-primary"><i class="fa fa-check"></i> Accept</a>
                    <a href="javascript:void(0)" class="btn btn-xs btn-default"><i class="fa fa-times"></i> Ignore</a>
                </div>
                <div class="alert alert-warning alert-alt">
                    <small>2 days ago</small><br>
                    Running low on space<br><strong>18GB in use</strong> 2GB left<br>
                    <a href="page_ready_pricing_tables.html" class="btn btn-xs btn-primary"><i class="fa fa-arrow-up"></i> Upgrade Plan</a>
                </div>
            </div>
            <!-- END Activity -->

            <!-- Messages -->
            <a href="page_ready_inbox.html" class="sidebar-title">
                <i class="fa fa-envelope pull-right"></i> <strong>Messages</strong>UI (5)
            </a>
            <div class="sidebar-section">
                <div class="alert alert-alt">
                    Debra Stanley<small class="pull-right">just now</small><br>
                    <a href="page_ready_inbox_message.html"><strong>New Follower</strong></a>
                </div>
                <div class="alert alert-alt">
                    Sarah Cole<small class="pull-right">2 min ago</small><br>
                    <a href="page_ready_inbox_message.html"><strong>Your subscription was updated</strong></a>
                </div>
                <div class="alert alert-alt">
                    Bryan Porter<small class="pull-right">10 min ago</small><br>
                    <a href="page_ready_inbox_message.html"><strong>A great opportunity</strong></a>
                </div>
                <div class="alert alert-alt">
                    Jose Duncan<small class="pull-right">30 min ago</small><br>
                    <a href="page_ready_inbox_message.html"><strong>Account Activation</strong></a>
                </div>
                <div class="alert alert-alt">
                    Henry Ellis<small class="pull-right">40 min ago</small><br>
                    <a href="page_ready_inbox_message.html"><strong>You reached 10.000 Followers!</strong></a>
                </div>
            </div>
            <!-- END Messages -->
        </div>
        <!-- END Sidebar Content -->
    </div>
    <!-- END Wrapper for scrolling functionality -->
</div>
<!-- END Alternative Sidebar -->

<!-- Main Sidebar -->
<div id="sidebar">
    <!-- Wrapper for scrolling functionality -->
    <div id="sidebar-scroll">
        <!-- Sidebar Content -->
        <div class="sidebar-content">
            <!-- Brand -->
            <a href="index.html" class="sidebar-brand">
                <i class="gi gi-flash"></i><span class="sidebar-nav-mini-hide"><strong>Pro</strong>UI</span>
            </a>
            <!-- END Brand -->

            <!-- User Info -->
            <div class="sidebar-section sidebar-user clearfix sidebar-nav-mini-hide">
                <div class="sidebar-user-avatar">
                    <a href="page_ready_user_profile.html">
                        <img src="{{ asset('assets/img/placeholders/avatars/avatar2.jpg') }}" alt="avatar">
                    </a>
                </div>
                <div class="sidebar-user-name">{{ Str::ucfirst(auth()->user()->username) }}</div>
                <div class="sidebar-user-links">
                    <a href="page_ready_user_profile.html" data-toggle="tooltip" data-placement="bottom" title="Profile"><i class="gi gi-user"></i></a>
                    <a href="page_ready_inbox.html" data-toggle="tooltip" data-placement="bottom" title="Messages"><i class="gi gi-envelope"></i></a>
                    <!-- Opens the user settings modal that can be found at the bottom of each page (page_footer.html in PHP version) -->
                    <a href="javascript:void(0)" class="enable-tooltip" data-placement="bottom" title="Settings" onclick="$('#modal-user-settings').modal('show');"><i class="gi gi-cogwheel"></i></a>
                    <a href="login.html" data-toggle="tooltip" data-placement="bottom" title="Logout"><i class="gi gi-exit"></i></a>
                </div>
            </div>
            <!-- END User Info -->

            <!-- Theme Colors -->
            <!-- Change Color Theme functionality can be found in js/app.js - templateOptions() -->
            {{-- <ul class="sidebar-section sidebar-themes clearfix sidebar-nav-mini-hide">
                <!-- You can also add the default color theme
                <li class="active">
                    <a href="javascript:void(0)" class="themed-background-dark-default themed-border-default" data-theme="default" data-toggle="tooltip" title="Default Blue"></a>
                </li>
                -->
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-night themed-border-night" data-theme="css/themes/night.css" data-toggle="tooltip" title="Night"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-amethyst themed-border-amethyst" data-theme="css/themes/amethyst.css" data-toggle="tooltip" title="Amethyst"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-modern themed-border-modern" data-theme="css/themes/modern.css" data-toggle="tooltip" title="Modern"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-autumn themed-border-autumn" data-theme="css/themes/autumn.css" data-toggle="tooltip" title="Autumn"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-flatie themed-border-flatie" data-theme="css/themes/flatie.css" data-toggle="tooltip" title="Flatie"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-spring themed-border-spring" data-theme="css/themes/spring.css" data-toggle="tooltip" title="Spring"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-fancy themed-border-fancy" data-theme="css/themes/fancy.css" data-toggle="tooltip" title="Fancy"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-fire themed-border-fire" data-theme="css/themes/fire.css" data-toggle="tooltip" title="Fire"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-coral themed-border-coral" data-theme="css/themes/coral.css" data-toggle="tooltip" title="Coral"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-lake themed-border-lake" data-theme="css/themes/lake.css" data-toggle="tooltip" title="Lake"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-forest themed-border-forest" data-theme="css/themes/forest.css" data-toggle="tooltip" title="Forest"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-waterlily themed-border-waterlily" data-theme="css/themes/waterlily.css" data-toggle="tooltip" title="Waterlily"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-emerald themed-border-emerald" data-theme="css/themes/emerald.css" data-toggle="tooltip" title="Emerald"></a>
                </li>
                <li>
                    <a href="javascript:void(0)" class="themed-background-dark-blackberry themed-border-blackberry" data-theme="css/themes/blackberry.css" data-toggle="tooltip" title="Blackberry"></a>
                </li>
            </ul> --}}
            <!-- END Theme Colors -->

            <!-- Sidebar Navigation -->
            <ul class="sidebar-nav">
                <li>
                    <a href="{{ route('dashboard') }}"><i class="gi gi-stopwatch sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">Dashboard</span></a>
                </li>

                <!-- begin::master-data -->
                <li class="sidebar-header">
                    <span class="sidebar-header-options clearfix"><a href="javascript:void(0)" data-toggle="tooltip" title="Quick Settings"><i class="gi gi-settings"></i></a><a href="javascript:void(0)" data-toggle="tooltip" title="Create the most amazing pages with the widget kit!"><i class="gi gi-lightbulb"></i></a></span>
                    <span class="sidebar-header-title">Settings</span>
                </li>
                @if (
                    auth()->user()->can('master institution') ||
                    auth()->user()->can('master role') ||
                    auth()->user()->can('master permission') ||
                    auth()->user()->can('master position') ||
                    auth()->user()->can('master employee')
                )
                    <li class="{{ active_sidebar_parent(['intitutions.index', 'intitutions.show', 'roles.index', 'positions.index', 'employees.index', 'permissions.index', 'users.index.internal', 'users.index.external', 'expenses.category.index', 'expenses.method.index', 'expenses.type.index', 'expenses.main.index', 'income.category.index', 'income.type.index', 'income.method.index']) }}">
                        <a href="#" class="sidebar-nav-menu">
                            <i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                            <i class="gi gi-database_lock sidebar-nav-icon"></i>
                            <span class="sidebar-nav-mini-hide">{{ __('view.master_data') }}</span>
                        </a>
                        <ul>
                            @if (auth()->user()->can('master institution'))
                                <li>
                                    <a class="{{ active_sidebar_child(['intitutions.index', 'intitutions.show']) }}" href="{{ route('intitutions.index') }}">{{ __('view.intitutions') }}</a>
                                </li>
                            @endif
                            @if (auth()->user()->can('master role'))
                                <li>
                                    <a class="{{ active_sidebar_child(['roles.index']) }}" href="{{ route('roles.index') }}">{{ __('view.role') }}</a>
                                </li>
                            @endif
                            @if (auth()->user()->can('master permission'))
                                <li>
                                    <a class="{{ active_sidebar_child(['permissions.index']) }}" href="{{ route('permissions.index') }}">{{ __('view.permissions') }}</a>
                                </li>
                            @endif
                            @if (auth()->user()->can('master position'))
                                <li>
                                    <a class="{{ active_sidebar_child(['positions.index']) }}" href="{{ route('positions.index') }}">{{ __('view.position') }}</a>
                                </li>
                            @endif
                            @if (auth()->user()->can('master employee'))
                                <li>
                                    <a class="{{ active_sidebar_child(['employees.index']) }}" href="{{ route('employees.index') }}">Staff</a>
                                </li>
                            @endif
                            <li>
                                <a href="#" class="sidebar-nav-submenu {{ active_sidebar_parent(['users.index.internal', 'users.index.external']) }}"><i class="fa fa-angle-left sidebar-nav-indicator"></i>{{ __('view.users') }}</a>
                                <ul @if(active_sidebar_parent(['users.index.internal']) || active_sidebar_parent(['users.index.external']) == 'active') style="display: block;" @endif>
                                    <li>
                                        <a href="{{ route('users.index.internal').'?t=internal' }}" class="{{ active_sidebar_child(['users.index.internal']) }}">Internal</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('users.index.external').'?t=external' }}" class="{{ active_sidebar_child(['users.index.external']) }}">External</a>
                                    </li>
                                </ul>
                            </li>
    
                            <li>
                                <a href="#" class="sidebar-nav-submenu {{ active_sidebar_parent(['income.category.index', 'income.type.index', 'income.method.index']) }}"><i class="fa fa-angle-left sidebar-nav-indicator"></i>{{ __('view.income') }}</a>
                                <ul @if(active_sidebar_parent(['income.category.index']) == 'active' || active_sidebar_parent(['income.type.index']) == 'active' || active_sidebar_parent(['income.method.index']) == 'active') style="display: block;" @endif>
                                    <li>
                                        <a href="{{ route('income.category.index') }}" class="{{ active_sidebar_child(['income.category.index']) }}">{{ __('view.category') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('income.type.index') }}" class="{{ active_sidebar_child(['income.type.index']) }}">{{ __('view.type') }}</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('income.method.index') }}" class="{{ active_sidebar_child(['income.method.index']) }}">{{ __('view.method') }}</a>
                                    </li>
                                </ul>
                            </li>
    
                            <li>
                                <a href="#" class="sidebar-nav-submenu {{ active_sidebar_parent(['expenses.category.index', 'expenses.method.index', 'expenses.type.index', 'expenses.main.index']) }}"><i class="fa fa-angle-left sidebar-nav-indicator"></i>{{ __('view.expenses') }}</a>
                                <ul @if(active_sidebar_parent(['expenses.category.index', 'expenses.method.index', 'expenses.type.index', 'expenses.main.index']) == 'active') style="display: block;" @endif>
                                    <li>
                                        <a href="{{ route('expenses.main.index') }}" class="{{ active_sidebar_child(['expenses.main.index']) }}">@lang('view.main')</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('expenses.category.index') }}" class="{{ active_sidebar_child(['expenses.category.index']) }}">@lang('view.category')</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('expenses.method.index') }}" class="{{ active_sidebar_child(['expenses.method.index']) }}">@lang('view.method')</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('expenses.type.index') }}" class="{{ active_sidebar_child(['expenses.type.index']) }}">@lang('view.type')</a>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a class="{{ active_sidebar_child([]) }}" href="#">Account</a>
                            </li>
                        </ul>
                    </li>
                @endif
                <!-- end::master-data -->

                <!-- begin::income -->
                @if (auth()->user()->can('income list') || auth()->user()->can('income create') || auth()->user()->can('income edit'))
                    @php
                        $side_institutions = \Illuminate\Support\Facades\Redis::get('institutions');
                        $side_institutions = json_decode($side_institutions, true);
                        $active_sides_ins = generate_active_sidebars($side_institutions);
                    @endphp
                    <li class="{{ active_sidebar_parent($active_sides_ins) }}">
                        {{-- <a href="{{ route('incomes.index') }}" class="{{ active_sidebar_child(['incomes.index', 'incomes.create', 'incomes.show', 'incomes.edit']) }}"><i class="fa fa-money sidebar-nav-icon"></i><span class="sidebar-nav-mini-hide">{{ __('view.income') }}</span></a> --}}
                        <a href="#" class="sidebar-nav-menu">
                            <i class="fa fa-angle-left sidebar-nav-indicator sidebar-nav-mini-hide"></i>
                            <i class="gi gi-money sidebar-nav-icon"></i>
                            <span class="sidebar-nav-mini-hide">{{ __('view.income') }}</span>
                        </a>
                        @if (count($side_institutions) > 0)
                        <ul>
                            @foreach ($side_institutions as $si)
                                <li>
                                    <a class="{{ active_sidebar_child(['incomes.index.' . $si['id']]) }}" href="{{ route('incomes.index.' . $si['id']) }}">{{ $si['name'] }}</a>
                                </li>
                            @endforeach
                        </ul>
                        @endif
                    </li>
                @endif
                <!-- end:income -->

            </ul>
            <!-- END Sidebar Navigation -->
        </div>
        <!-- END Sidebar Content -->
    </div>
    <!-- END Wrapper for scrolling functionality -->
</div>
<!-- END Main Sidebar -->

<!-- User Settings, modal which opens from Settings link (found in top right user menu) and the Cog link (found in sidebar user info) -->
<div id="modal-user-settings" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header text-center">
                <h2 class="modal-title"><i class="fa fa-pencil"></i> Settings</h2>
            </div>
            <!-- END Modal Header -->

            <!-- Modal Body -->
            <div class="modal-body">
                <form action="index.html" method="post" enctype="multipart/form-data" class="form-horizontal form-bordered" onsubmit="return false;">
                    <fieldset>
                        <legend>Vital Info</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label">Username</label>
                            <div class="col-md-8">
                                <p class="form-control-static">Admin</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-settings-email">Email</label>
                            <div class="col-md-8">
                                <input type="email" id="user-settings-email" name="user-settings-email" class="form-control" value="admin@example.com">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-settings-notifications">Email Notifications</label>
                            <div class="col-md-8">
                                <label class="switch switch-primary">
                                    <input type="checkbox" id="user-settings-notifications" name="user-settings-notifications" value="1" checked>
                                    <span></span>
                                </label>
                            </div>
                        </div>
                    </fieldset>
                    <fieldset>
                        <legend>Password Update</legend>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-settings-password">New Password</label>
                            <div class="col-md-8">
                                <input type="password" id="user-settings-password" name="user-settings-password" class="form-control" placeholder="Please choose a complex one..">
                            </div>
                        </div>
                        <div class="form-group">
                            <label class="col-md-4 control-label" for="user-settings-repassword">Confirm New Password</label>
                            <div class="col-md-8">
                                <input type="password" id="user-settings-repassword" name="user-settings-repassword" class="form-control" placeholder="..and confirm it!">
                            </div>
                        </div>
                    </fieldset>
                    <div class="form-group form-actions">
                        <div class="col-xs-12 text-right">
                            <button type="button" class="btn btn-sm btn-default" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-sm btn-primary">Save Changes</button>
                        </div>
                    </div>
                </form>
            </div>
            <!-- END Modal Body -->
        </div>
    </div>
</div>
<!-- END User Settings -->