$(function () {
    // init: side menu for current page
    $('li#menu-companies').addClass('menu-open active');
    $('li#menu-companies').find('.treeview-menu').css('display', 'block');
    $('li#menu-companies').find('.treeview-menu').find('.add-companies a').addClass('sub-menu-active');

    $('#company-form').validationEngine('attach', {
        promptPosition : 'topLeft',
        scroll: false
    });

    // init: show tooltip on hover
    $('[data-toggle="tooltip"]').tooltip({
        container: 'body'
    });

    // show password field only after 'change password' is clicked
    $('#reset-button').click(function (e) {
        $('#reset-field').removeClass('hide');
        $('#show-password-check').removeClass('hide');
        // to always uncheck the checkbox after button click
        $('#show-password').prop('checked', false);
    });

    // toggle password in plaintext if checkbox is selected
    $("#show-password").click(function () {
        $(this).is(":checked") ? $("#password").prop("type", "text") : $("#password").prop("type", "password");
    });
});
