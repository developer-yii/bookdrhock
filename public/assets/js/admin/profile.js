$(document).ready(function () {
    let formId = '#profile-update-form',
        formErrorSpanClass = '.error-span',
        updateBtnId = '#update-profile';

    $(formId).bind("keypress", function (e) {
        if (e.keyCode == 13) {
            $(updateBtnId).click();
            return false;
        }
    });

    // Edit profile data
    function editProfile() {
        $(formId)[0].reset();
        $(formErrorSpanClass).text('');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $.ajax({
            url: routes.indexUrl,
            method: 'GET',
            success: function (response) {
                $.each(response.data, function (key, value) {
                    $('#' + key).val(value);
                })
            },
            error: function (error) {
                console.log('Error', error)
            }
        });
    }

    editProfile();

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
                editProfile();
                showMessage('success', response.message)
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
