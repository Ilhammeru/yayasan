const { default: swal } = require("sweetalert");

function openModalWithValue(
    method,
    formId,
    modalId,
    modalLabel,
    textLabel,
    urlReq,
    urlRes = null
) {
    $.ajax({
        type: method,
        url: urlReq,
        beforeSend: function() {
            
        },
        success: function(res) {
            buildModalBodyGeneral(
                textLabel,
                res.url,
                res.view,
                res.method,
                modalLabel,
                formId,
                modalId
            );
        },
        error: function(err) {
            showNotif(true, err);
        }
    })
}

function buildModalBodyGeneral(
    text, url, view, method,
    modalLabel, formId, modalId
) {
    $(`#${modalLabel}`).text(text);
    $(`#${formId}`).attr('action', base_url + url);
    $(`#${formId}`).attr('method', method);
    $(`#${modalId} .modal-body`).html(view);
    $(`#${modalId}`).modal('show');
}

function showNotif(isError, msg) {
    if (isError) {
        let message = msg.responseJSON ? msg.responseJSON.message : msg;
        iziToast.error({
            title: 'Error',
            message: message,
            position: 'topRight',
            timeout: 3000
        })
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
                    showNotif(true, err.responseJSON == undefined ? err.responseText : err.responseJSON);
                }
            })
        }
    });
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

window.openModalWithValue = openModalWithValue;
window.buildModalBodyGeneral = buildModalBodyGeneral;
window.showNotif = showNotif;
window.dtIntegration = dtIntegration;
window.setDataTable = setDataTable;
window.deleteMaster = deleteMaster;
window.handleSidebar = handleSidebar;
window.getWindowWidth = getWindowWidth;