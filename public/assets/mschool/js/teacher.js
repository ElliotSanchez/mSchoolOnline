$(function() {
    $('select.teacher-class-switcher').change(function() {
        var url = $(this).data('url');
        var id = $(this).find('option:selected').data('mclassid');
        var host = location.host;

        window.location = 'http://' + host + url + '/' + id;
    });
});