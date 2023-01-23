<!DOCTYPE html>
<!--[if IE 9]>         <html class="no-js lt-ie10" lang="en"> <![endif]-->
<!--[if gt IE 9]><!--> <html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">

        <title>ProUI - Responsive Bootstrap Admin Template</title>

        <meta name="description" content="ProUI is a Responsive Bootstrap Admin Template created by pixelcave and published on Themeforest.">
        <meta name="author" content="pixelcave">
        <meta name="robots" content="noindex, nofollow">
        <meta name="viewport" content="width=device-width,initial-scale=1.0,user-scalable=0">
        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <!-- Icons -->
        <!-- The following icons can be replaced with your own, they are used by desktop and mobile browsers -->
        <link rel="shortcut icon" href="img/favicon.png">
        <link rel="apple-touch-icon" href="img/icon57.png" sizes="57x57">
        <link rel="apple-touch-icon" href="img/icon72.png" sizes="72x72">
        <link rel="apple-touch-icon" href="img/icon76.png" sizes="76x76">
        <link rel="apple-touch-icon" href="img/icon114.png" sizes="114x114">
        <link rel="apple-touch-icon" href="img/icon120.png" sizes="120x120">
        <link rel="apple-touch-icon" href="img/icon144.png" sizes="144x144">
        <link rel="apple-touch-icon" href="img/icon152.png" sizes="152x152">
        <link rel="apple-touch-icon" href="img/icon180.png" sizes="180x180">
        <!-- END Icons -->

        <!-- Stylesheets -->
        <!-- Bootstrap is included in its original form, unaltered -->
        <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.min.css') }}">

        <!-- Related styles of various icon packs and plugins -->
        <link rel="stylesheet" href="{{ asset('assets/css/plugins.css') }}">

        <!-- The main stylesheet of this template. All Bootstrap overwrites are defined in here -->
        <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">

        {{-- iziToast --}}
        <link rel="stylesheet" href="{{ asset('assets/plugins/izitoast/dist/css/iziToast.min.css') }}">

        <!-- Include a specific file here from css/themes/ folder to alter the default theme of the template -->

        <!-- The themes stylesheet of this template (for using specific theme color in individual elements - must included last) -->
        <link rel="stylesheet" href="{{ asset('assets/css/themes.css') }}">
        <!-- END Stylesheets -->

        <style>
            .dataTables_scrollHeadInner {
                width: 100% !important;
            }

            .dataTables_scrollHeadInner > table {
                width: 100% !important;
            }
            .dataTables_scrollBody {
                width: 100% !important;
            }
            .dataTables_scrollBody > .table {
                width: 100% !important;
            }
            .dataTables_empty {
                text-align: center !important;
            }
            .border {
                border: 1px solid #e6e6e6;
            }
            .p-3 {
                padding: 10px;
            }
            .mb-3 {
                margin-bottom: 10px;
            }
            .w-25 {
                width: 25% !important;
            }
            .w-50 {
                width: 50% !important;
            }
            .w-100 {
                width: 100% !important;
            }
            .d-none {
                display: none;
            }
            .select2-container {
                width: 100% !important;
            }

            .no-border {
                border: 0;
            }

            .themed-color-white {
                color: #fff !important;
            }

            .select2-selection.select2-selection--single {
                height: 35px !important;
            }

            .select2-selection__rendered {
                line-height: 35px !important;
            }

            .select2-container--default .select2-selection--single .select2-selection__arrow {
                height: 35px !important;
            }

            #global-modal .modal-dialog,
            #invoice-modal .modal-dialog {
                width: 100vw;
                max-width: none;
                height: 100%;
                margin: 0;
            }

            #global-modal .modal-dialog .modal-content,
            #invoice-modal .modal-dialog .modal-content {
                height: 100%;
                border: 0;
                border-radius: 0;
                position: relative;
                display: flex;
                flex-direction: column;
                width: 100%;
                pointer-events: auto;
                background-color: #fff;
                background-clip: padding-box;
            }

            #global-modal .modal-header,
            #invoice-modal .modal-header {
                padding: 15px 15px 14px;
                border-bottom: 1px solid #eeeeee;
                border-top-left-radius: 4px;
                border-top-right-radius: 4px;
                background: #fff;
                display: flex;
                align-content: center;
                justify-content: space-between;
            }

            #global-modal .modal-header::before,
            #global-modal .modal-header::after,
            #invoice-modal .modal-header::before,
            #invoice-modal .modal-header::after {
                display: none;
            }

            #global-modal .modal-body,
            #invoice-modal .modal-body {                
                position: relative;
                flex: 1 1 auto;
                padding: 1rem;
                overflow-y: auto;
                background: #fff;
            }
        </style>

        @stack('styles')

        <!-- Modernizr (browser feature detection library) -->
        <script src="{{ asset('assets/js/vendor/modernizr.min.js') }}"></script>
        <script src="{{ asset('assets/js/jquery.js') }}"></script>

        <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </head>
    <body>
        <!-- Page Wrapper -->
        <!-- In the PHP version you can set the following options from inc/config file -->
        <!--
            Available classes:

            'page-loading'      enables page preloader
        -->
        <div id="page-wrapper">
            <!-- Preloader -->
            <!-- Preloader functionality (initialized in js/app.js) - pageLoading() -->
            <!-- Used only if page preloader is enabled from inc/config (PHP version) or the class 'page-loading' is added in #page-wrapper element (HTML version) -->
            <div class="preloader themed-background">
                <h1 class="push-top-bottom text-light text-center"><strong>Pro</strong>UI</h1>
                <div class="inner">
                    <h3 class="text-light visible-lt-ie10"><strong>Loading..</strong></h3>
                    <div class="preloader-spinner hidden-lt-ie10"></div>
                </div>
            </div>
            <!-- END Preloader -->

            <!-- Page Container -->
            <!-- In the PHP version you can set the following options from inc/config file -->
            <!--
                Available #page-container classes:

                '' (None)                                       for a full main and alternative sidebar hidden by default (> 991px)

                'sidebar-visible-lg'                            for a full main sidebar visible by default (> 991px)
                'sidebar-partial'                               for a partial main sidebar which opens on mouse hover, hidden by default (> 991px)
                'sidebar-partial sidebar-visible-lg'            for a partial main sidebar which opens on mouse hover, visible by default (> 991px)
                'sidebar-mini sidebar-visible-lg-mini'          for a mini main sidebar with a flyout menu, enabled by default (> 991px + Best with static layout)
                'sidebar-mini sidebar-visible-lg'               for a mini main sidebar with a flyout menu, disabled by default (> 991px + Best with static layout)

                'sidebar-alt-visible-lg'                        for a full alternative sidebar visible by default (> 991px)
                'sidebar-alt-partial'                           for a partial alternative sidebar which opens on mouse hover, hidden by default (> 991px)
                'sidebar-alt-partial sidebar-alt-visible-lg'    for a partial alternative sidebar which opens on mouse hover, visible by default (> 991px)

                'sidebar-partial sidebar-alt-partial'           for both sidebars partial which open on mouse hover, hidden by default (> 991px)

                'sidebar-no-animations'                         add this as extra for disabling sidebar animations on large screens (> 991px) - Better performance with heavy pages!

                'style-alt'                                     for an alternative main style (without it: the default style)
                'footer-fixed'                                  for a fixed footer (without it: a static footer)

                'disable-menu-autoscroll'                       add this to disable the main menu auto scrolling when opening a submenu

                'header-fixed-top'                              has to be added only if the class 'navbar-fixed-top' was added on header.navbar
                'header-fixed-bottom'                           has to be added only if the class 'navbar-fixed-bottom' was added on header.navbar

                'enable-cookies'                                enables cookies for remembering active color theme when changed from the sidebar links
            -->
            <div id="page-container" class="sidebar-mini sidebar-visible-lg sidebar-no-animations header-fixed-top">
                
                <!-- begin::sidebar -->
                @include('layouts.sidebar')
                <!-- end::sidebar -->

                <!-- Main Container -->
                <div id="main-container">
                    <!-- Header -->
                    @include('layouts.header')
                    <!-- END Header -->

                    <!-- Page content -->
                    <div id="page-content">

                        <!-- begin::content-header -->
                        {{-- <div class="content-header">
                            <div class="header-section">
                                <h1>
                                    <i class="fa fa-table"></i>Datatables<br><small>HTML tables can become fully dynamic with cool features!</small>
                                </h1>
                            </div>
                        </div> --}}
                        <ul class="breadcrumb breadcrumb-top">
                            {!! config('app.breadcrumb') !!}
                        </ul>
                        <!-- end::content-header -->

                        @yield('content')

                        @include('layouts.modal')
                        {{-- modal send-form --}}
                        <div class="modal animation-fadeInQuick"
                            id="modal-send-wallet">
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
                                    <div class="modal-footer">
                                        <button type="button"
                                            class="btn btn-secondary btn-close"
                                            data-bs-dismiss="modal"
                                            onclick="closeModal('modal-send-wallet')">
                                            @lang('view.close')
                                        </button>
                                        <button type="button"
                                            class="btn btn-primary btn-save"
                                            onclick="sendWallet()">
                                            @lang('view.send')
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>
                    <!-- END Page Content -->

                    <!-- Footer -->
                    @include('layouts.footer')
                    <!-- END Footer -->
                </div>
                <!-- END Main Container -->
            </div>
            <!-- END Page Container -->
        </div>
        <!-- END Page Wrapper -->

        <!-- Scroll to top link, initialized in js/app.js - scrollToTop() -->
        <a href="#" id="to-top"><i class="fa fa-angle-double-up"></i></a>

        <!-- jQuery, Bootstrap.js, jQuery plugins and Custom JS code -->
        <script src="{{ asset('assets/js/vendor/bootstrap.min.js') }}"></script>
        <script src="{{ asset('assets/js/plugins.js') }}"></script>
        <script src="{{ asset('assets/js/app.js') }}"></script>
        <script src="{{ asset('assets/plugins/izitoast/dist/js/iziToast.min.js') }}"></script>
        <script src="{{ asset('assets/plugins/sweetalert/dist/sweetalert.min.js') }}"></script>
        <script src="{{ mix('dist/js/master.js') }}"></script>
        <script src="/js/lang.js"></script>

        <!-- Google Maps API Key (you will have to obtain a Google Maps API key to use Google Maps) -->
        <!-- For more info please have a look at https://developers.google.com/maps/documentation/javascript/get-api-key#key -->
        {{-- <script src="https://maps.googleapis.com/maps/api/js?key="></script>
        <script src="js/helpers/gmaps.min.js"></script> --}}

        {{-- custom scripts --}}
        <script src="{{ mix('dist/js/base.js') }}"></script>
        
        <script>
            handleSidebar('init');
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            const base_url = window.location.origin;

            function closeModal(id) {
                $('#' + id).modal('hide');
            }

            function handleSidebar(mode, extra) {
                var page, pageContent, header, footer, sidebar, sScroll, sidebarAlt, sScrollAlt;
                page            = $('#page-container');
                pageContent     = $('#page-content');
                header          = $('header');
                footer          = $('#page-content + footer');

                sidebar         = $('#sidebar');
                sScroll         = $('#sidebar-scroll');

                sidebarAlt      = $('#sidebar-alt');
                sScrollAlt      = $('#sidebar-alt-scroll');
                
                if (mode === 'init') {
                    // Init sidebars scrolling functionality
                    handleSidebar('sidebar-scroll');
                    handleSidebar('sidebar-alt-scroll');

                    // Close the other sidebar if we hover over a partial one
                    // In smaller screens (the same applies to resized browsers) two visible sidebars
                    // could mess up our main content (not enough space), so we hide the other one :-)
                    $('.sidebar-partial #sidebar')
                        .mouseenter(function(){ handleSidebar('close-sidebar-alt'); });
                    $('.sidebar-alt-partial #sidebar-alt')
                        .mouseenter(function(){ handleSidebar('close-sidebar'); });
                } else {
                    var windowW = getWindowWidth();

                    if (mode === 'toggle-sidebar') {
                        if ( windowW > 991) { // Toggle main sidebar in large screens (> 991px)
                            page.toggleClass('sidebar-visible-lg');

                            if (page.hasClass('sidebar-mini')) {
                                page.toggleClass('sidebar-visible-lg-mini');
                            }

                            if (page.hasClass('sidebar-visible-lg')) {
                                handleSidebar('close-sidebar-alt');
                            }

                            // If 'toggle-other' is set, open the alternative sidebar when we close this one
                            if (extra === 'toggle-other') {
                                if (!page.hasClass('sidebar-visible-lg')) {
                                    handleSidebar('open-sidebar-alt');
                                }
                            }
                        } else { // Toggle main sidebar in small screens (< 992px)
                            page.toggleClass('sidebar-visible-xs');

                            if (page.hasClass('sidebar-visible-xs')) {
                                handleSidebar('close-sidebar-alt');
                            }
                        }

                        // Handle main sidebar scrolling functionality
                        handleSidebar('sidebar-scroll');
                    }
                    else if (mode === 'toggle-sidebar-alt') {
                        if ( windowW > 991) { // Toggle alternative sidebar in large screens (> 991px)
                            page.toggleClass('sidebar-alt-visible-lg');

                            if (page.hasClass('sidebar-alt-visible-lg')) {
                                handleSidebar('close-sidebar');
                            }

                            // If 'toggle-other' is set open the main sidebar when we close the alternative
                            if (extra === 'toggle-other') {
                                if (!page.hasClass('sidebar-alt-visible-lg')) {
                                    handleSidebar('open-sidebar');
                                }
                            }
                        } else { // Toggle alternative sidebar in small screens (< 992px)
                            page.toggleClass('sidebar-alt-visible-xs');

                            if (page.hasClass('sidebar-alt-visible-xs')) {
                                handleSidebar('close-sidebar');
                            }
                        }
                    }
                    else if (mode === 'open-sidebar') {
                        if ( windowW > 991) { // Open main sidebar in large screens (> 991px)
                            if (page.hasClass('sidebar-mini')) { page.removeClass('sidebar-visible-lg-mini'); }
                            page.addClass('sidebar-visible-lg');
                        } else { // Open main sidebar in small screens (< 992px)
                            page.addClass('sidebar-visible-xs');
                        }

                        // Close the other sidebar
                        handleSidebar('close-sidebar-alt');
                    }
                    else if (mode === 'open-sidebar-alt') {
                        if ( windowW > 991) { // Open alternative sidebar in large screens (> 991px)
                            page.addClass('sidebar-alt-visible-lg');
                        } else { // Open alternative sidebar in small screens (< 992px)
                            page.addClass('sidebar-alt-visible-xs');
                        }

                        // Close the other sidebar
                        handleSidebar('close-sidebar');
                    }
                    else if (mode === 'close-sidebar') {
                        if ( windowW > 991) { // Close main sidebar in large screens (> 991px)
                            page.removeClass('sidebar-visible-lg');
                            if (page.hasClass('sidebar-mini')) { page.addClass('sidebar-visible-lg-mini'); }
                        } else { // Close main sidebar in small screens (< 992px)
                            page.removeClass('sidebar-visible-xs');
                        }
                    }
                    else if (mode === 'close-sidebar-alt') {
                        if ( windowW > 991) { // Close alternative sidebar in large screens (> 991px)
                            page.removeClass('sidebar-alt-visible-lg');
                        } else { // Close alternative sidebar in small screens (< 992px)
                            page.removeClass('sidebar-alt-visible-xs');
                        }
                    }
                    else if (mode === 'sidebar-scroll') { // Handle main sidebar scrolling
                        if (page.hasClass('sidebar-mini') && page.hasClass('sidebar-visible-lg-mini') && (windowW > 991)) { // Destroy main sidebar scrolling when in mini sidebar mode
                            if (sScroll.length && sScroll.parent('.slimScrollDiv').length) {
                                sScroll
                                    .slimScroll({destroy: true});
                                sScroll
                                    .attr('style', '');
                            }
                        }
                        else if ((page.hasClass('header-fixed-top') || page.hasClass('header-fixed-bottom'))) {
                            var sHeight = $(window).height();

                            if (sScroll.length && (!sScroll.parent('.slimScrollDiv').length)) { // If scrolling does not exist init it..
                                sScroll
                                    .slimScroll({
                                        height: sHeight,
                                        color: '#fff',
                                        size: '3px',
                                        touchScrollStep: 100
                                    });

                                // Handle main sidebar's scrolling functionality on resize or orientation change
                                var sScrollTimeout;

                                $(window).on('resize orientationchange', function(){
                                    clearTimeout(sScrollTimeout);

                                    sScrollTimeout = setTimeout(function(){
                                        handleSidebar('sidebar-scroll');
                                    }, 150);
                                });
                            }
                            else { // ..else resize scrolling height
                                sScroll
                                    .add(sScroll.parent())
                                    .css('height', sHeight);
                            }
                        }
                    }
                    else if (mode === 'sidebar-alt-scroll') { // Init alternative sidebar scrolling
                        if ((page.hasClass('header-fixed-top') || page.hasClass('header-fixed-bottom'))) {
                            var sHeightAlt = $(window).height();

                            if (sScrollAlt.length && (!sScrollAlt.parent('.slimScrollDiv').length)) { // If scrolling does not exist init it..
                                sScrollAlt
                                    .slimScroll({
                                        height: sHeightAlt,
                                        color: '#fff',
                                        size: '3px',
                                        touchScrollStep: 100
                                    });

                                // Resize alternative sidebar scrolling height on window resize or orientation change
                                var sScrollAltTimeout;

                                $(window).on('resize orientationchange', function(){
                                    clearTimeout(sScrollAltTimeout);

                                    sScrollAltTimeout = setTimeout(function(){
                                        handleSidebar('sidebar-alt-scroll');
                                    }, 150);
                                });
                            }
                            else { // ..else resize scrolling height
                                sScrollAlt
                                    .add(sScrollAlt.parent())
                                    .css('height', sHeightAlt);
                            }
                        }
                    }
                }

                return false;
            };

            function getWindowWidth(){
                return window.innerWidth
                        || document.documentElement.clientWidth
                        || document.body.clientWidth;
            };

            function openTab(name) {
                var i, tabcontent, tablinks;
                tabcontent = document.getElementsByClassName("tabcontent");
                for (i = 0; i < tabcontent.length; i++) {
                    tabcontent[i].style.display = "none";
                }
                tablinks = document.getElementsByClassName("tablinks");
                for (i = 0; i < tablinks.length; i++) {
                    tablinks[i].className = tablinks[i].className.replace(" active", "");
                }
                document.getElementById(name).style.display = "block";
                document.getElementById(name + '-tab').className += " active";
            }

            function showNotif(isError, msg) {
                if (isError) {
                    let message = msg.responseJSON ? msg.responseJSON.message : msg;
                    if (typeof message == 'object') {
                        for (let a = 0; a < message.length; a++) {
                            iziToast.error({
                                title: 'Error',
                                message: message[a],
                                position: 'topRight',
                                timeout: 3000
                            })
                        }
                    } else {
                        iziToast.error({
                            title: 'Error',
                            message: message,
                            position: 'topRight',
                            timeout: 3000
                        })
                    }
                } else {
                    iziToast.success({
                        title: 'Success',
                        message: msg,
                        position: 'topRight',
                        timeout: 3000
                    });
                }
            }

            function dtIntegration() {
                $.extend(true, $.fn.dataTable.defaults, {
                    "sDom": "<'row'<'col-sm-6 col-xs-5'l><'col-sm-6 col-xs-7'f>r>t<'row'<'col-sm-5 hidden-xs'i><'col-sm-7 col-xs-12 clearfix'p>>",
                    "sPaginationType": "bootstrap",
                    "oLanguage": {
                        "sLengthMenu": "_MENU_",
                        "sSearch": "<div class=\"input-group\">_INPUT_<span class=\"input-group-addon\"><i class=\"fa fa-search\"></i></span></div>",
                        "sInfo": "<strong>_START_</strong>-<strong>_END_</strong> of <strong>_TOTAL_</strong>",
                        "oPaginate": {
                            "sPrevious": "",
                            "sNext": ""
                        }
                    }
                });
                $.extend($.fn.dataTableExt.oStdClasses, {
                    "sWrapper": "dataTables_wrapper form-inline",
                    "sFilterInput": "form-control",
                    "sLengthSelect": "form-control"
                });
            };

            function setDataTable(tableId, columns, route) {
                let dt = $('#' + tableId).DataTable({
                    processing: true,
                    serverSide: true,
                    responsive: true,
                    scrollX: true,
                    ajax: route,
                    columns: columns,
                    order: [[0, 'desc']]
                });
                return dt;
            }

            function deleteMaster(title, cancelText, confirmText, url, dt) {
                sweetAlert({
                    text: title,
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                    confirmButtonText: confirmText,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            type: 'delete',
                            url: url,
                            success: function(res) {
                                showNotif(false, res.message);
                                dt.ajax.reload();
                            },
                            error: function(err) {
                                showNotif(true, err);
                            }
                        })
                    }
                });
            }
        </script>

        @stack('scripts')
    </body>
</html>