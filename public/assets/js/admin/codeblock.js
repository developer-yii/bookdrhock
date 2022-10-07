$(document).ready(function () {

    if ($('.js-switch').length > 0) {
        var elems = Array.prototype.slice.call(document.querySelectorAll('.js-switch'));
        $('.js-switch').each(function () {
            new Switchery($(this)[0], $(this).data());
        });
    }

    let formId = '#job-form',
        addOrUpdateBtnId = '#addorupdate-job';

    $(".setting-switch").change(function (e) {
        let weeklyEmail = false
        let inputId = $(this).attr('id')
        if ($('#' + inputId).is(":checked")) {
            weeklyEmail = true
        } else {
            weeklyEmail = false
        }
        e.preventDefault();
        $.ajax({
            url: routes.addOrUpdateUrl,
            method: 'POST',
            data: {
                id: $(this).parent().attr('data-setting-id'),
                setting: weeklyEmail
            },
            success: function (response) {
                if ("code" in response && response.code == 400) {
                    $.toast({
                        heading: response.message,
                        position: {
                            right: 15,
                            top: 75
                        },
                        loaderBg: '#ff6849',
                        icon: 'error',
                        hideAfter: 3500,
                        stack: 6
                    });
                } else {
                    $.toast({
                        heading: response.message,
                        position: {
                            right: 15,
                            top: 75
                        },
                        loaderBg: '#ff6849',
                        icon: 'success',
                        hideAfter: 3500,
                        stack: 6
                    });
                }
            },
            error: function (error) {
                $.each(error.responseJSON.errors, function (key, value) {
                    $('#' + key).parent().find(formErrorSpanClass).text(value);
                    $('#' + key).parent().addClass('has-error');
                });
            }
        })
    });
});
