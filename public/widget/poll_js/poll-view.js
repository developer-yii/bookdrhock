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
                    document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                    showMessage('error', 'You can choose ' + maximumVoteInWord + ' option only')
                }
            } else {
                $(this).toggleClass('selected');
            }
        })
    }
    setFancyboxImg();
    function setFancyboxImg(){
        if ($('.imagelight-box').length > 0) {
            var count = 0;
            $(".imagelight-box a").each(function () {
                count++;
                $(this).attr("data-fancybox", "optionimage-" + count);
                $(this).attr("data-caption", $(this).find("img").attr("alt"));
                $(this).attr("title", $(this).find("img").attr("alt"));
            });
            $(".imagelight-box a").fancybox();
            $(document).on('click', '.option-container-details .imagelight-box a', function (e) {
                e.preventDefault();
                $(this).parents('.card-poll').toggleClass('selected');
            })
        }        
    }

    setClockdiv();
    function setClockdiv(){
        if ($('#clockdiv').length > 0) {
            if ($('#clockdiv').data('startdatetime') != null || $('#clockdiv').data('enddatetime') != null) {
                var deadline = deadlineString = headinText = '';
                if (new Date() > new Date($('#clockdiv').data('enddatetime'))) {
                    deadline = new Date($('#clockdiv').data('startdatetime')).getTime();
                    deadlineString = $('#clockdiv').data('startdatetime');
                    headinText = "Poll ended";
                    $('.poll-options-main').empty();
                    /*$('.poll-options-main').empty().append(
                        '<a href="' + routes.homeUrl + '" class="btn btn-primary text-capitalize m-0">Running polls</a>');*/
                } else if (new Date() > new Date($('#clockdiv').data('startdatetime'))) {
                    deadline = new Date($('#clockdiv').data('enddatetime')).getTime();
                    deadlineString = $('#clockdiv').data('enddatetime');
                    headinText = "time left";
                } else {
                    deadline = new Date($('#clockdiv').data('startdatetime')).getTime();
                    deadlineString = $('#clockdiv').data('startdatetime');
                    headinText = "comming soon";
                    $('.poll-options-main').empty();
                    /*$('.poll-options-main').empty().append(
                        '<a href="' + routes.homeUrl + '" class="btn btn-primary text-capitalize m-0">Running polls</a>');*/
                }
                $('.countdown-heading').text(headinText);

                // Have to split time funny for IOS and Safari NAN and timezone bug
                var timeParsed = deadlineString.replace(' ', 'T').split(/[^0-9]/);
                var countDown = new Date(Date.UTC(timeParsed[0], timeParsed[1] - 1, timeParsed[2], timeParsed[3], timeParsed[4], timeParsed[5]));

                var nowtest = new Date();
                var timertest = countDown - nowtest;
                console.log('deadlinestring: ' + deadlineString, 'countDown: ' + countDown, 'timer: ' + timertest);

                var x = setInterval(function () {
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
                }, 1000);
            }
        }
    }

    let formId = '#poll-vote-form',
        formErrorSpanClass = '.error-span',
        vottingBtnId = '#fandomz_submit_voting';

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
                    $('#poll-vote-form')[0].reset();
                    if (response.response == 'success') {
                        document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                        showMessage('success', response.message);
                        pollResultView(response.slug);
                    } else if (response.response == 'votedone') {
                        $(formId)[0].reset();
                        $('.option-container-details .card-poll.selected').removeClass('selected');
                        document.getElementsByClassName('poll-heading')[0].scrollIntoView();
                        showMessage('success', response.message);
                        pollResultView(response.slug);
                    } else {
                        showMessage('error', 'something is wrong!');
                    }
                },
                complete: function () {
                    $(vottingBtnId).removeClass('lodder')
                    $(vottingBtnId).attr('disabled', false);
                },
                error: function (error) {
                    var first_input = "";                    
                    $.each(error.responseJSON.errors, function (key, value) {
                        if (key == 'g-recaptcha-response') {
                            key = 'enabledgooglecaptcha';
                        }
                        if (first_input == "") first_input = key;
                        $('#' + key).parents('.form-group').find(formErrorSpanClass).removeClass('d-none').text(value);
                        $('#' + key).parents('.form-group').addClass('has-error');
                        $(formId).find("#" + first_input).focus();
                    });
                }
            })
        } else {
            showMessage('error', 'please select any option');
        }
    });
    let ResultListID = ".fandomz_result_list";
    $('body').on('click',ResultListID,function(e){
        e.preventDefault();
        var slug = $('#slug').val();
        pollResultView(slug);
    });

    let pollListID = ".fandomz_poll_list";
    $('body').on('click',pollListID,function(e){
        e.preventDefault();
        var slug = $('#slug').val();
        getpollList(slug);
    });

    function pollListView(slug,data) {        
        $('#poll_list_'+slug).show();
        $('#poll_list_'+slug).html(data);

        $('#poll_result_'+slug).hide();
        $('#poll_result_'+slug).html("");

        if ($(".lazyload").length > 0) {
            $("img.lazyload").lazyload();
        }
        setFancyboxImg();
        setClockdiv();
    }
    function pollResultRedirect(slug,data) {
        $('#poll_list_'+slug).hide();
        $('#poll_list_'+slug).html("");
        
        $('#poll_result_'+slug).show();
        $('#poll_result_'+slug).html(data);

        if ($(".lazyload").length > 0) {
            $("img.lazyload").lazyload();
        }
        setFancyboxImg();        
    }
    function pollResultView(slug){
        $.ajax({
            url: routes.resultsUrl+"/"+slug,
            method: 'GET',
            contentType: false,
            processData: false,
            beforeSend: function () {
                $(ResultListID).addClass('lodder')
                $(ResultListID).attr('disabled', true);
            },
            success: function (response) {
                $(ResultListID).removeClass('lodder')
                $(ResultListID).attr('disabled', false);
                pollResultRedirect(slug,response.html);
            },
            error: function (error) {
            }
        });
    }
    function getpollList(slug){
        $.ajax({
            url: routes.indexUrl+"/"+slug,
            method: 'GET',
            contentType: false,
            processData: false,
            beforeSend: function () {
                $(pollListID).addClass('lodder')
                $(pollListID).attr('disabled', true);
            },
            success: function (response) {
                $(pollListID).removeClass('lodder')
                $(pollListID).attr('disabled', false);
                pollListView(slug,response);
            },
            error: function (error) {
            }
        });
    }    
});
