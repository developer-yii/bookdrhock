window.addEventListener("pageshow", function (event) {
    var historyTraversal = event.persisted || (typeof window.performance != "undefined" && window.performance.navigation.type === 2);
    if (historyTraversal) {
        window.location.reload();
    }
});

$(document).ready(function () {
    if ($('#poll-vote-form').length > 0) {
        $('#poll-vote-form')[0].reset();
    }
    if ($('.option-container-details .card-poll').length > 0) {
        $('.option-container-details .card-poll').on('click', function (e) {
            e.preventDefault();
            if (maximumVoteInNumber > 0 && $('.option-container-details .card-poll.selected').length == maximumVoteInNumber && !$(this).hasClass('selected')) {
                if (maximumVoteInNumber == 1) {
                    $('.option-container-details .card-poll').removeClass('selected');
                    $(this).addClass('selected');
                } else {
                    if ($('#page_type').val() && $('#page_type').val() == 'embeded') {
                        document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                        // showMessageBottom('error', 'You can choose ' + maximumVoteInWord + ' option only');
                    }
                    showMessage('error', 'You can choose ' + maximumVoteInWord + ' option only')
                }
            } else {
                $(this).toggleClass('selected');
            }
        })
    }

    if ($('.imagelight-box').length > 0) {
        // assign captions and title from alt-attributes of images:
        var count = 0;
        $(".imagelight-box a").each(function () {
            count++;
            // add all to same gallery
            $(this).attr("data-fancybox", "optionimage-" + count);
            $(this).attr("data-caption", $(this).find("img").attr("alt"));
            $(this).attr("title", $(this).find("img").attr("alt"));
        });
        // start fancybox:
        $(".imagelight-box a").fancybox();
        $(document).on('click', '.option-container-details .imagelight-box a', function (e) {
            e.preventDefault();
            $(this).parents('.card-poll').toggleClass('selected');
        })
    }

    if ($('#clockdiv').length > 0) {
        if ($('#clockdiv').attr('data-startdatetime') != null || $('#clockdiv').attr('data-enddatetime') != null) {                
                var startdatetimeObj = $('#clockdiv').attr('data-startdatetime');
                var startdatetimeObjTmp = startdatetimeObj.replace(' ', 'T').split(/[^0-9]/);
                var startdatetime = new Date(Date.UTC(startdatetimeObjTmp[0], startdatetimeObjTmp[1] - 1, startdatetimeObjTmp[2], startdatetimeObjTmp[3], startdatetimeObjTmp[4], startdatetimeObjTmp[5]));

                var enddatetimeObj = $('#clockdiv').attr('data-enddatetime');
                var enddatetimeObjTmp = enddatetimeObj.replace(' ', 'T').split(/[^0-9]/);
                var enddatetime = new Date(Date.UTC(enddatetimeObjTmp[0], enddatetimeObjTmp[1] - 1, enddatetimeObjTmp[2], enddatetimeObjTmp[3], enddatetimeObjTmp[4], enddatetimeObjTmp[5]));

                var deadline = deadlineString = headinText = '';
                if (new Date() > enddatetime) {
                    deadline = new Date(startdatetime).getTime();
                    deadlineString = startdatetime;
                    headinText = "Poll ended";                    
                    $('.poll-options-main').empty().append('<a href="' + routes.homeUrl + '" class="btn btn-primary text-capitalize m-0">Running polls</a>');
                } else if (new Date() > startdatetime) {
                    deadline = new Date(enddatetime).getTime();
                    deadlineString = enddatetime;
                    headinText = "time left";
                } else {
                    deadline = new Date(startdatetime).getTime();
                    deadlineString = startdatetime;
                    headinText = "comming soon";
                    $('.poll-options-main').empty().append('<a href="' + routes.homeUrl + '" class="btn btn-primary text-capitalize m-0">Running polls</a>');
                }
                $('.countdown-heading').text(headinText);
                
                var countDown = deadlineString;
                var nowtest = new Date();
                var timertest = countDown - nowtest;

                var x = setInterval(function () {
                    if ($('#day').length > 0) {
                        var now = new Date();
                        var t = countDown - now;
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
                    }
                }, 1000);
            }
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
            $(vottingBtnId).attr('disabled', true);
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
                beforeSend: function () {
                    $(vottingBtnId).addClass('lodder')
                    $(vottingBtnId).attr('disabled', true);
                },
                success: function (response) {
                    if (response.response == 'success') {
                        if (response.type && response.type == 'embeded') {
                            document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                        }
                        showMessage('success', response.message);
                        pollResultRedirect(response.slug,response.html);
                    } else if (response.response == 'votedone') {
                        $(formId)[0].reset();
                        $('.option-container-details .card-poll.selected').removeClass('selected');
                        if (response.type && response.type == 'embeded') {
                            document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                            // showMessageBottom('error', response.message);
                        } else {
                            // showMessage('error', response.message);
                        }
                        showMessage('success', response.message);
                        pollResultRedirect(response.slug,response.html);
                    } else {
                        if (response.type && response.type == 'embeded') {
                            // document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                            showMessageBottom('error', 'something is wrong!');
                        } else {
                            showMessage('error', 'something is wrong!');
                        }
                    }
                },
                complete: function () {
                    $(vottingBtnId).removeClass('lodder')
                    $(vottingBtnId).attr('disabled', false);
                },
                error: function (error) {
                    if (error.responseJSON.errors) {
                        first_input = "";
                        $.each(error.responseJSON.errors, function (key, value) {
                            if (key == 'g-recaptcha-response') {
                                key = 'enabledgooglecaptcha';
                            }
                            if (key == 'h-captcha-response') {
                                key = 'enabledhcaptcha';
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
                showMessageBottom('error', 'please select any option');
            } else {
                showMessage('error', 'please select any option')
            }
        }
    })

});
