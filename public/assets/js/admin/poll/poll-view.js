$(document).ready(function () {
    if ($('.option-container-details .card-poll').length > 0) {
        $('.option-container-details .card-poll').on('click', function (e) {
            e.preventDefault();
            if (maximumVoteInNumber > 0 && $('.option-container-details .card-poll.selected').length == maximumVoteInNumber && !$(this).hasClass('selected')) {
                if (maximumVoteInNumber == 1) {
                    $('.option-container-details .card-poll').removeClass('selected');
                    $(this).addClass('selected');
                } else {
                    showMessage('error', 'You can choose ' + maximumVoteInWord + ' option only')
                }
            } else {
                $(this).toggleClass('selected');
            }
        })
    }

    if ($('#clockdiv').length > 0) {
        var deadline = headinText = '';
        if (new Date() > new Date($('#clockdiv').data('enddatetime'))) {
            deadline = new Date($('#clockdiv').data('startdatetime')).getTime();
            headinText = "Poll ended";
            $('.poll-options-main').empty().append(
                '<a href="' + routes.homeUrl + '" class="btn btn-primary text-capitalize m-0">Running polls</a>');
        } else if (new Date() > new Date($('#clockdiv').data('startdatetime'))) {
            deadline = new Date($('#clockdiv').data('enddatetime')).getTime();
            headinText = "time left";
        } else {
            deadline = new Date($('#clockdiv').data('startdatetime')).getTime();
            headinText = "comming soon";
            $('.poll-options-main').empty().append(
                '<a href="' + routes.homeUrl + '" class="btn btn-primary text-capitalize m-0">Running polls</a>');
        }
        $('.countdown-heading').text(headinText);
        var x = setInterval(function () {
            var now = new Date().getTime();
            var t = deadline - now;
            var days = Math.floor(t / (1000 * 60 * 60 * 24));
            var hours = Math.floor((t % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((t % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((t % (1000 * 60)) / 1000);

            document.getElementById("day").innerHTML = days.toString().length == 1 ? '0' + days :
                days;
            document.getElementById("hour").innerHTML = hours.toString().length == 1 ? '0' + hours :
                hours;
            document.getElementById("minute").innerHTML = minutes.toString().length == 1 ? '0' +
                minutes :
                minutes;
            document.getElementById("second").innerHTML = (seconds.toString().length == 1) ? '0' +
                seconds :
                seconds;
            if (t < 0) {
                clearInterval(x);
                document.getElementById("day").innerHTML = '00';
                document.getElementById("hour").innerHTML = '00';
                document.getElementById("minute").innerHTML = '00';
                document.getElementById("second").innerHTML = '00';
            }
        }, 1000);
    }

    let formId = '#poll-vote-form',
        formErrorSpanClass = '.error-span',
        vottingBtnId = '#submit-voting';

    $(formId).bind("keypress", function (e) {
        if (e.keyCode == 13) {
            $(vottingBtnId).click();
            return false;
        }
    });

    // Add or Update Poll
    $(vottingBtnId).on('click', function (e) {
        e.preventDefault();
        if ($('.option-container-details .card-poll.selected').length > 0) {
            let formData = new FormData($(formId)['0']);
            let selectOptions = [];
            $(".option-container-details .card-poll.selected").each(function () {
                selectOptions.push($(this).find('input.option_id').val());
            });
            formData.append('selected_options', selectOptions);
            $(formErrorSpanClass).addClass('d-none').text('');
            $(formId).find('input').parents('.form-group').removeClass('has-error');
            $.ajax({
                url: routes.votingUrl,
                method: 'POST',
                data: formData,
                contentType: false,
                processData: false,
                success: function (response) {
                    if (response.response == 'success') {
                        if (response.type && response.type == 'embeded') {
                            document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                        }
                        pollResultRedirect(response.slug);
                    } else if (response.response == 'error') {
                        $(formId)[0].reset();
                        $('.option-container-details .card-poll.selected').removeClass('selected');
                        if (response.type && response.type == 'embeded') {
                            document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                        }
                        showMessage('error', response.message);
                    } else {
                        if (response.type && response.type == 'embeded') {
                            document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                        }
                        showMessage('error', 'something is wrong!');
                    }
                },
                error: function (error) {
                    if (error.responseJSON.errors) {
                        first_input = "";
                        $.each(error.responseJSON.errors, function (key, value) {
                            if (key == 'g-recaptcha-response') {
                                key = 'enabledgooglecaptcha';
                            }
                            if (first_input == "") first_input = key;
                            $('#' + key).parents('.form-group').find(formErrorSpanClass).removeClass('d-none').text(value);
                            $('#' + key).parents('.form-group').addClass('has-error');
                            $('#poll-form').find("#" + first_input).focus();
                        });
                    } else {
                        console.log('Error', error);
                    }
                }
            })
        } else {
            if ($('#page_type').val() && $('#page_type').val() == 'embeded') {
                document.getElementsByClassName('poll-heading')[0].scrollIntoView();
            }
            showMessage('error', 'please select any option')
        }
    })

});
