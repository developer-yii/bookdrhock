jQuery(document).ready(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    if ($(".lazyload").length > 0) {
        $("img.lazyload").lazyload();
    }

    if ($(".datepicker-custom").length > 0) {
        var todayDate = new Date();
        $('.datepicker-custom').datepicker({
            autoclose: true,
            format: 'dd-mm-yyyy',
            startDate: todayDate,
            todayHighlight: true
        });
    }

    $(document).on('change', '.file-upload-input', function (e) {
        e.preventDefault();
        readURL(this);
    });

    if ($(window).width() < 767) {
        if (!$('.sidebar-nav.navbar-collapse.slimscrollsidebar').hasClass('collapse')) {
            setTimeout(function () {
                $('.sidebar-nav.navbar-collapse.slimscrollsidebar').addClass('collapse');
            }, 500);
        }
    }

    if ($(window).width() < 992) {
        if (!$('.navbar-collapse').hasClass('collapse')) {
            setTimeout(function () {
                $('.navbar-collapse').addClass('collapse');
            }, 500);
        }
    }

    $(document).on('click', '.remove-image', function (e) {
        e.preventDefault();
        $(this).parents('.custom-image-upload-container').find('.file-upload-input').replaceWith($(this).parents('.custom-image-upload-container').find('.file-upload-input').clone());
        $(this).parents('.custom-image-upload-container').find('.file-upload-image').attr('src', '');
        $(this).parents('.custom-image-upload-container').find('.file-upload-content').css('display', 'none');
        $(this).parents('.custom-image-upload-container').find('.image-upload-wrap').css('display', 'flex');
        $(this).parents('.custom-image-upload-container').find('.image-upload-wrap .set_image').val('');
    })

    if ($(".equal-height-box").length > 0) {
        sameHeightBoxes();
    }
});

function sameHeightBoxes() {
    $(".equal-height-box").each(function () {
        $(this).removeAttr("style")
    });

    var maxHeight = Math.max.apply(null, $(".equal-height-box").map(function () {
        return $(this).outerHeight();
    }).get());

    $(".equal-height-box").each(function () {
        $(this).css('height', maxHeight + 'px')
    })
}

function readURL(input) {
    var fileTypes = ['jpg', 'jpeg', 'png'];
    if (input.files && input.files[0]) {

        var extension = input.files[0].name.split('.').pop().toLowerCase(), //file extension from input file
            isSuccess = fileTypes.indexOf(extension) > -1; //is extension in acceptable types

        if (isSuccess) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $(input).parents('.custom-image-upload-container').find('.image-upload-wrap').css('display', 'none');
                $(input).parents('.custom-image-upload-container').find('.file-upload-image').attr('src', e.target.result);
                $(input).parents('.custom-image-upload-container').find('.file-upload-content').css('display', 'flex');
            };

            reader.readAsDataURL(input.files[0]);
        } else {
            $(input).parents('.custom-image-upload-container').find('.image-upload-wrap').css('display', 'none');
            $(input).parents('.custom-image-upload-container').find('.file-upload-image').attr('src', '/assets/images/dummy document.png');
            $(input).parents('.custom-image-upload-container').find('.file-upload-content').css('display', 'flex');
        }

    } else {
        removeUpload();
    }
}

$('.image-upload-wrap').bind('dragover', function () {
    $('.image-upload-wrap').addClass('image-dropping');
});
$('.image-upload-wrap').bind('dragleave', function () {
    $('.image-upload-wrap').removeClass('image-dropping');
});

$(window).on('resize', function () {
    if ($(window).width() < 767) {
        if (!$('.sidebar-nav.navbar-collapse.slimscrollsidebar').hasClass('collapse')) {
            setTimeout(function () {
                $('.sidebar-nav.navbar-collapse.slimscrollsidebar').addClass('collapse');
            }, 500);
        }
    }

    if ($(window).width() < 992) {
        if (!$('.navbar-collapse').hasClass('collapse')) {
            setTimeout(function () {
                $('.navbar-collapse').addClass('collapse');
            }, 500);
        }
    }

    if ($(".equal-height-box").length > 0) {
        sameHeightBoxes();
    }

});

function showMessage(type = "info", message = "") {
    $.toast({
        heading: message,
        position: {
            right: 15,
            top: 75
        },
        loaderBg: '#ff6849',
        icon: type,
        hideAfter: 3500,
        stack: 6
    })
}
