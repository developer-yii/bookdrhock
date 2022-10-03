jQuery(document).ready(function ($) {
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

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

    $(document).on('click', '.remove-image', function (e) {
        e.preventDefault();
        $(this).parents('.custom-image-upload-container').find('.file-upload-input').replaceWith($(this).parents('.custom-image-upload-container').find('.file-upload-input').clone());
        $(this).parents('.custom-image-upload-container').find('.file-upload-image').attr('src', '');
        $(this).parents('.custom-image-upload-container').find('.file-upload-content').css('display', 'none');
        $(this).parents('.custom-image-upload-container').find('.image-upload-wrap').css('display', 'flex');
        $(this).parents('.custom-image-upload-container').find('.image-upload-wrap .set_image').val('');
    })

    if ($(".latest-polls").length > 0) {
        if ($('.latest-polls').data('count') <= 1) {
            var latestPolls = new Swiper(".latest-polls", {
                spaceBetween: 1,
                slidesPerView: 1,
                centeredSlides: true,
                // loop: true,
                // grabCursor: true,
                navigation: {
                    nextEl: ".latest-polls-slider-custombtn-next",
                    prevEl: ".latest-polls-slider-custombtn-prev"
                },
            });
        } else {
            var latestPolls = new Swiper(".latest-polls", {
                spaceBetween: 1,
                slidesPerView: 2.5,
                centeredSlides: true,
                loop: true,
                grabCursor: true,
                navigation: {
                    nextEl: ".latest-polls-slider-custombtn-next",
                    prevEl: ".latest-polls-slider-custombtn-prev"
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1
                    },
                    500: {
                        slidesPerView: 1.6
                    },
                    992: {
                        slidesPerView: 2.6
                    }
                }
            });
        }
    }

    if ($(".popular-polls").length > 0) {
        if ($('.popular-polls').data('count') <= 1) {
            var popularPolls = new Swiper(".popular-polls", {
                spaceBetween: 1,
                slidesPerView: 1,
                centeredSlides: true,
                // loop: true,
                // grabCursor: true,
                navigation: {
                    nextEl: ".popular-polls-slider-custombtn-next",
                    prevEl: ".popular-polls-slider-custombtn-prev"
                },
            });
        } else {
            var popularPolls = new Swiper(".popular-polls", {
                spaceBetween: 1,
                slidesPerView: 2.5,
                centeredSlides: true,
                loop: true,
                grabCursor: true,
                navigation: {
                    nextEl: ".popular-polls-slider-custombtn-next",
                    prevEl: ".popular-polls-slider-custombtn-prev"
                },
                breakpoints: {
                    0: {
                        slidesPerView: 1
                    },
                    500: {
                        slidesPerView: 1.6
                    },
                    992: {
                        slidesPerView: 2.6
                    }
                }
            });
        }
    }
});

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
