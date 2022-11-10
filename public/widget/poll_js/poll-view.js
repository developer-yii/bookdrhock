$(document).ready(function () {
    if ($('.fandomz-poll-widget #poll-vote-form').length > 0) {
        $('.fandomz-poll-widget #poll-vote-form')[0].reset();
    }        

    $('.fandomz-poll-widget').on('click', '.option-container-details .card-poll', function (e) {    
        e.preventDefault();
        if (maximumVoteInNumber > 0 && $('.option-container-details .card-poll.selected').length == maximumVoteInNumber && !$(this).hasClass('selected')) {
            if (maximumVoteInNumber == 1) {
                $('.fandomz-poll-widget .option-container-details .card-poll').removeClass('selected');
                $(this).addClass('selected');
            } else {                
                showMessage('error', 'You can choose ' + maximumVoteInWord + ' option only')
            }
        } else {
            $(this).toggleClass('selected');
        }
    });
    
    $('.fandomz-poll-widget').on('click', '.option-container-details .imagelight-box a', function (e) {
        e.preventDefault();
        $(this).parents('.card-poll').toggleClass('selected');
    });    
    // call auto load
    setTimeout(function () {
        setFancyboxImg();
        setClockdiv();
        setLazyloadImg();
    },1000);
    function setLazyloadImg(){
        if ($(".lazyload").length > 0) {
            $(".lazyload").lazyload({
                 event: "lazyload",
                 effect: "fadeIn",
                 effectspeed: 2000
               })
             .trigger("lazyload");
        }
    }
    function setFancyboxImg(){
        if ($('.fandomz-poll-widget .imagelight-box').length > 0) {
            var count = 0;
            $(".fandomz-poll-widget .imagelight-box a").each(function () {
                count++;
                $(this).attr("data-fancybox", "optionimage-" + count);
                $(this).attr("data-caption", $(this).find("img").attr("alt"));
                $(this).attr("title", $(this).find("img").attr("alt"));
            });
            $(".imagelight-box a").fancybox();
        }        
    }
    function setClockdiv(){
        if ($('#clockdiv').length > 0) {
            if ($('#clockdiv').data('startdatetime') != null || $('#clockdiv').data('enddatetime') != null) {
                var deadline = deadlineString = headinText = '';
                if (new Date() > new Date($('#clockdiv').data('enddatetime'))) {
                    deadline = new Date($('#clockdiv').data('startdatetime')).getTime();
                    deadlineString = $('#clockdiv').data('startdatetime');
                    headinText = "Poll ended";                    
                    $('.poll-options-main').empty();
                } else if (new Date() > new Date($('#clockdiv').data('startdatetime'))) {
                    deadline = new Date($('#clockdiv').data('enddatetime')).getTime();
                    deadlineString = $('#clockdiv').data('enddatetime');
                    headinText = "time left";
                } else {
                    deadline = new Date($('#clockdiv').data('startdatetime')).getTime();
                    deadlineString = $('#clockdiv').data('startdatetime');
                    headinText = "comming soon";
                    $('.poll-options-main').empty();
                }
                $('.countdown-heading').text(headinText);
                var timeParsed = deadlineString.replace(' ', 'T').split(/[^0-9]/);
                var countDown = new Date(Date.UTC(timeParsed[0], timeParsed[1] - 1, timeParsed[2], timeParsed[3], timeParsed[4], timeParsed[5]));

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
    }

    let formId = '.fandomz-poll-widget #poll-vote-form',
        formErrorSpanClass = '.error-span',
        vottingBtnId = '#fandomz_submit_voting';

    $(formId).bind("keypress", function (e) {
        if (e.keyCode == 13) {
            $(vottingBtnId).click();
            return false;
        }
    });
    // Add or Update Poll
    $('.fandomz-poll-widget').on('click',vottingBtnId,function(e){    
        e.preventDefault();
        if ($('.fandomz-poll-widget .option-container-details .card-poll.selected').length > 0) {
            $(vottingBtnId).attr('disabled', true);
            let formData = new FormData($(formId)['0']);
            let selectOptions = [];
            $(".fandomz-poll-widget .option-container-details .card-poll.selected").each(function () {
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
                        showMessage('success', response.message);
                        reinitPreloader();
                        pollResultView(response.slug);
                    } else if (response.response == 'votedone') {
                        $(formId)[0].reset();
                        $('.fandomz-poll-widget .option-container-details .card-poll.selected').removeClass('selected');
                        showMessage('success', response.message);
                        reinitPreloader();
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
                        $('.fandomz-poll-widget #' + key).parents('.form-group').find(formErrorSpanClass).removeClass('d-none').text(value);
                        $('.fandomz-poll-widget #' + key).parents('.form-group').addClass('has-error');
                        $(formId).find("#" + first_input).focus();
                    });
                }
            })
        } else {
            showMessage('error', 'please select any option');
        }
    });
    
    let ResultListID = ".fandomz_result_list";
    $('.fandomz-poll-widget').on('click',ResultListID,function(e){
        e.preventDefault();
        var slug = $('#slug').val();
        //$('.poll-heading')[0].scrollIntoView();
        reinitPreloader();
        pollResultView(slug);
    });

    let pollListID = ".fandomz_poll_list";
    $('.fandomz-poll-widget').on('click',pollListID,function(e){
        e.preventDefault();
        var slug = $('#slug').val();
        //$('.poll-heading')[0].scrollIntoView();
        reinitPreloader();
        getpollList(slug);
    });

    function pollListView(slug,data) {
        $('#poll_list_'+slug).show();
        $('#poll_list_'+slug).html(data);

        $('#poll_result_'+slug).hide();
        $('#poll_result_'+slug).html("");

        setTimeout(function () {            
            if ( $('.fandomz-poll-widget #g-recaptcha').length ) {    
                recaptcha_1 = grecaptcha.render('g-recaptcha', {
                  'sitekey' : recaptcha_key
                });
            }
            setFancyboxImg();
            setClockdiv();
            setLazyloadImg();
        },1000);  
    }
    function pollResultRedirect(slug,data) {
        $('#poll_list_'+slug).hide();
        $('#poll_list_'+slug).html("");
        
        $('#poll_result_'+slug).show();        
        $('#poll_result_'+slug).html(data);

        setTimeout(function () {
            setFancyboxImg();            
            setLazyloadImg();
        },1000);
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
                initPreloader();
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
                initPreloader();
                $(pollListID).removeClass('lodder')
                $(pollListID).attr('disabled', false);
                pollListView(slug,response);
            },
            error: function (error) {
            }
        });
    }        
});
