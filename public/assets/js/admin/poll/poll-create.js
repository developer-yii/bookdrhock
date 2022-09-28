$(document).ready(function () {

    if ($(".datetimepicker-custom").length > 0) {
        var todayDate = new Date();
        $('#start_datetime').datetimepicker({
            defaultDate: todayDate,
            format: 'DD-MM-YYYY hh:mm a',
        });
        $('#end_datetime').datetimepicker({
            minDate: todayDate,
            format: 'DD-MM-YYYY hh:mm a',
            useCurrent: false
        });
        $("#start_datetime").on("dp.change", function (e) {
            $('#end_datetime').data("DateTimePicker").minDate(e.date);
        });
        $("#end_datetime").on("dp.change", function (e) {
            $('#start_datetime').data("DateTimePicker").maxDate(e.date);
        });
    }

    if ($("#description").length > 0) {
        tinymce.init({
            selector: "textarea#description",
            theme: "modern",
            height: 200,
            menubar: false,
            statusbar: false,
            plugins: [
                "advlist autolink link lists charmap hr anchor pagebreak spellchecker", "searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime nonbreaking"
            ]
        });
    }

    if ($("#popular_poll").length > 0) {
        // Switchery
        new Switchery($('#popular_poll')[0], $('#popular_poll').data());
    }

    if ($("#popular_poll").length > 0) {
        // Basic
        $('.dropify').dropify();
    }

    if ($("input[name = 'option_select']").length > 0) {
        $("input[name='option_select']").TouchSpin({
            min: 0,
            max: 1000000000,
        });
    }

    function sameHeightBoxes() {
        $(".options-container .option-card").each(function () {
            $(this).removeAttr("style")
        });

        var maxHeight = Math.max.apply(null, $(".options-container .option-card").map(function () {
            return $(this).outerHeight();
        }).get());

        $(".options-container .option-card").each(function () {
            $(this).css('height', maxHeight + 'px')
        })
    }

    sameHeightBoxes();
    $(window).on('resize', function () {
        sameHeightBoxes();
    })

    if ($('.options-container').length > 0) {
        let count = 0;
        if ($('.options-container .option-card').length > 2) {
            count = ($('.options-container .option-card').length - 1);
        } else {
            count = 0;
        }
        $('.options-container .plus-btn').on('click', function (e) {
            e.preventDefault();
            count++;
            let html = '<div class="option-card">' +
                '<div class="row h-100">' +
                '<div class="option-card-form">' +
                '<div class="col-md-12">' +
                '<div class="form-group mb-3">' +
                '<div class="custom-image-upload-container">' +
                '<div class="image-upload-wrap">' +
                '<input class="form-control option_image file-upload-input" type="file" id="option_image_' + count + '" name="option[' + count + '][image]">' +
                '<div class="drag-text-message">' +
                '<i class="icon-cloud-upload"></i>' +
                '<p>Drag and drop a file or select add Image</p>' +
                '</div>' +
                '</div>' +
                '<div class="file-upload-content">' +
                '<img class="file-upload-image" src="" alt="upload image" />' +
                '<i class="fa fa-times-circle remove-image"></i>' +
                '</div>' +
                '</div>' +
                '<span class="help-block error-span"></span>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-12">' +
                '<div class="form-group mb-0">' +
                '<input type="text" id="option_title_' + count + '" name="option[' + count + '][title]" class="form-control option_title" value="" placeholder="Option Title*"> ' +
                '<span class="help-block error-span"></span>' +
                '</div>' +
                '</div>' +
                '</div>' +
                '<div class="col-md-12 align-self-end">' +
                '<button type="button" class="btn btn-danger w-100 delete-btn" id="delete-btn_' + count + '">Delete</button>' +
                '</div>' +
                '</div>' +
                '</div>';
            $(html).insertBefore($(this));
            $('.dropify').dropify();
            sameHeightBoxes();
            $('.delete-btn').prop('disabled', false);
        })
    }

    $(document).on('click', '.delete-btn', function () {
        let clickObject = $(this);
        let exisingId = $(this).parents('.option-card').find('.option_id').val();
        swal({
                title: "Are you sure?",
                text: "You will not be able to recover this poll option data!",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Yes, delete it!",
                closeOnConfirm: false
            },
            function () {
                $(clickObject).parents('.option-card').remove();
                if ($('.options-container .option-card').length < 3) {
                    $('.delete-btn').prop('disabled', true);
                }
                swal.close();
                if ($('.remove-options-ids-container').length > 0 && exisingId) {
                    $('.remove-options-ids-container').append('<input type="hidden" class="removed_options" name="removed_options[]" id="removed_options" value="' + exisingId + '">')
                }
            }
        );
    })

    let formId = '#poll-form',
        formErrorSpanClass = '.error-span',
        pollFormActionInput = '#poll_form_action',
        addOrUpdateBtnId = '#addorupdate-poll';

    $(formId).bind("keypress", function (e) {
        if (e.keyCode == 13) {
            $(addOrUpdateBtnId).click();
            return false;
        }
    });

    // Add or Update Poll
    $(addOrUpdateBtnId).on('click', function (e) {
        e.preventDefault();
        var formData = new FormData($(formId)['0']);
        formData.append('description', tinymce.get("description").getContent());
        $(formErrorSpanClass).text('');
        $(formId).find('input').parents('.form-group').removeClass('has-error');
        $(formId).find('select').parents('.form-group').removeClass('has-error');
        $.ajax({
            url: routes.addOrUpdateUrl,
            method: 'POST',
            data: formData,
            contentType: false,
            processData: false,
            success: function (response) {
                $(formId)[0].reset();
                pollEditRedirect(response.data.id)
            },
            error: function (error) {
                if (error.responseJSON.errors) {
                    $.each(error.responseJSON.errors, function (key, value) {
                        if (key.includes("option.")) {
                            let main_key = key.split('.')[2];
                            let currunt_row = parseInt(key.split('.')[1]);
                            if (currunt_row == '0') {
                                divId = '#option_' + main_key;
                            } else {
                                divId = '#option_' + main_key + '_' + currunt_row;
                            }
                            $('.options-container .option-card').find(divId).parents('.form-group').find(formErrorSpanClass).text(value);
                            $('.options-container .option-card').find(divId).parents('.form-group').addClass('has-error');
                        } else {
                            $('#' + key).parents('.form-group').find(formErrorSpanClass).text(value);
                            $('#' + key).parents('.form-group').addClass('has-error');
                        }
                        sameHeightBoxes()
                    });
                } else {
                    console.log('Error', error);
                }
            }
        })
    })
});
