/**
 * File with the global, project-related scripts
 */
$('.nav-sidebar').find('li').on('click', function () {
    $.cookie('pageCode', $(this).data('page-code'), { path: '/' });
});

$('#navbar-logo').on('click', function () {
    $.removeCookie('pageCode', { path: '/' });
});