jQuery(document).ready(function () {
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
                    $('.poll-options-main').empty();
                } else if (new Date() > startdatetime) {
                    deadline = new Date(enddatetime).getTime();
                    deadlineString = enddatetime;
                    headinText = "time left";
                } else {
                    deadline = new Date(startdatetime).getTime();
                    deadlineString = startdatetime;
                    headinText = "comming soon";
                    $('.poll-options-main').empty();
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
                        $('.fandomz-poll-widget').find('#second').html();
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
       
    $('.fandomz-poll-widget').on('keypress',"input",function(e){        
        if (e.keyCode == 13) {            
            $('.fandomz-poll-widget #fandomz_submit_voting').click();
            return false;
        }
        return true;
    });    
    // Add or Update Poll
    $('.fandomz-poll-widget').on('click',vottingBtnId,function(e){    
        e.preventDefault();
        if ($('.fandomz-poll-widget .option-container-details .card-poll.selected').length > 0) {            
            let formData = new FormData($(formId)['0']);
            let selectOptions = [];
            $(".fandomz-poll-widget .option-container-details .card-poll.selected").each(function () {
                selectOptions.push($(this).find('input.option_id').val());
            });
            formData.append('selected_options', selectOptions);
            $(formErrorSpanClass).addClass('d-none').text('');
            $(formId).find('input').parents('.form-group').removeClass('has-error');            
            if(checkVoteLimit()){
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
                            pushDate();
                            showMessage('success', response.message);
                            $('.poll-heading')[0].scrollIntoView();
                            reinitPreloader();
                            pollResultView(response.slug);
                        } else if (response.response == 'votedone') {
                            $(formId)[0].reset();
                            $('.fandomz-poll-widget .option-container-details .card-poll.selected').removeClass('selected');
                            showMessage('success', response.message);
                            $('.poll-heading')[0].scrollIntoView();
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
                        if(error.responseJSON.errors){
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
                        if(error.responseJSON.message){
                            showMessage('error', error.responseJSON.message);
                        }
                    }
                });
            } else {
                var hour = $('.fandomz-poll-widget #vote_schedule').val();
                var slug = $('.fandomz-poll-widget #slug').val();                
                showMessage('success', 'You\'ve completed your vote, vote again in '+hour+' hours');                
                $('.poll-heading')[0].scrollIntoView();
                reinitPreloader();
                pollResultView(slug);
            }
        } else {
            showMessage('error', 'please select any option');
        }
    });
    
    let ResultListID = ".fandomz_result_list";
    $('.fandomz-poll-widget').on('click',ResultListID,function(e){
        e.preventDefault();
        var slug = $('#slug').val();
        $('.poll-heading')[0].scrollIntoView();
        reinitPreloader();
        pollResultView(slug);
    });

    let pollListID = ".fandomz_poll_list";
    $('.fandomz-poll-widget').on('click',pollListID,function(e){
        e.preventDefault();
        var slug = $('#slug').val();
        $('.poll-heading')[0].scrollIntoView();
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

    /*setTimeout(function () {
        console.log('Now date-time:'+moment().format());
        checkVoteLimit();
    },1000);*/

    var widget_slug = $(".fandomz-poll-widget").attr('data-slug');
    widget_slug = "widget_"+widget_slug;
    
    function checkVoteLimit(){
        var hour = $('.fandomz-poll-widget  #vote_schedule').val();        
        var limit = $('.fandomz-poll-widget #vote_add').val();
        
        hour = (typeof hour !== 'undefined' && hour)? parseInt(hour):24;
        limit = (typeof limit !== 'undefined' && limit)? parseInt(limit):1;

        var dateSubtract = moment().subtract({hours:Math.abs(hour)});
        var currentDate = moment();
        var dateArr = new Array();

        var cookie = readCookie(widget_slug);
        var total = 0;
        if(typeof cookie !== 'undefined' && cookie && cookie.length){
            var cookieArr = cookie.split(',');
            $.each(cookieArr, function(key, date){
                var dateObj = moment(date);
                if(dateObj >= dateSubtract){
                    dateArr.push(date);
                    total++;
                }
            });
        }
        console.log(total);
        return (total < limit)? true : false;
    }
    function pushDate(){        
        var lastDateRemove = moment().subtract({hours:24});// old date remove in cookie

        var dateArr = new Array();
        var cookie = readCookie(widget_slug);
        if(typeof cookie !== 'undefined' && cookie){
            var cookieArr = cookie.split(',');
            $.each(cookieArr, function(key, date){
                var dateObj = moment(date);
                if(dateObj >= lastDateRemove){
                    dateArr.push(date);
                }    
            });
        }
        dateArr.push(moment().format());//push current datetime
        createCookie(widget_slug,dateArr);
    }
    function createCookie(name,value) {
        var date = new Date();            
            date.setTime(date.getTime()+(24*60*60*1000));
        var expires_time = date.toGMTString();
        var expires = "; expires="+expires_time;
        document.cookie = name+"="+value+expires+"; path=/";
    }
    function readCookie(name) {
        var keyValue = document.cookie.match('(^|;) ?' + name + '=([^;]*)(;|$)');
        return keyValue ? keyValue[2] : null;        
    }
});