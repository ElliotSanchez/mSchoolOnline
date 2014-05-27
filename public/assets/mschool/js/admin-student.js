$(document).ready(function () {

    var passwordToggle = function (e) {
        $('.password-reveal').toggle();
        $('.password-hide').toggle();
        $('.hidden-password').toggle();
        $('.visible-password').toggle();
        e.stopPropagation();
        return false;
    };

    $('.password-reveal').click(passwordToggle);
    $('.password-hide').click(passwordToggle);

});