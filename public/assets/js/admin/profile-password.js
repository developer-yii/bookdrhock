$(document).ready(function () {
    let formId = '#passoword-change-form',
        formErrorSpanClass = '.error-span',
        updateBtnId = '#update-password';

    $(formId).bind("keypress", function (e) {
        if (e.keyCode == 13) {
            $(updateBtnId).click();
            return false;
        }
    });

    // Update Profile
    $(updateBtnId).on('click', function (e) {
        e.preventDefault();
        $(formErrorSpanClass).text('');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $.ajax({
            url: routes.updateUrl,
            method: 'POST',
            data: new FormData($(formId)['0']),
            contentType: false,
            processData: false,
            success: function (response) {
                $(formId)[0].reset();
                showMessage('success', response.message);
            },
            error: function (error) {
                $.each(error.responseJSON.errors, function (key, value) {
                    $('#' + key).parents('.form-group').find(formErrorSpanClass).text(value);
                    $('#' + key).parents('.form-group').addClass('has-error');
                });
            }
        })
    })
});
