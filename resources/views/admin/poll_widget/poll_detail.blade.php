<div class="poll-heading">
    <h1 class="text-center text-capitalize">{{ $poll->title }}</h1>
    <div class="text-center sub-text">{!! $poll->description !!}</div>
    <hr>
</div>
@if (isset($type) &&
    !empty($type) &&
    $type == 'details' &&
    isset($poll->start_datetime) &&
    !empty($poll->start_datetime) &&
    isset($poll->end_datetime) &&
    !empty($poll->end_datetime))
    <div class="poll-timer text-center">
        <h5 class="text-uppercase countdown-heading">time left</h5>
        <div class="clockdiv-container" id="clockdiv"
            data-startdatetime="{{ isset($poll->start_datetime) && !empty($poll->start_datetime) ? $poll->start_datetime : 'null' }}"
            data-enddatetime="{{ isset($poll->end_datetime) && !empty($poll->end_datetime) ? $poll->end_datetime : 'null' }}">
            <div class="time-box bg-success text-light font-bold">
                <span class="days" id="day">00</span>
                <div class="web">Days</div>
                <div class="mob">D</div>
            </div>
            <div class="time-box bg-success text-light font-bold">
                <span class="hours" id="hour">00</span>
                <div class="web">Hrs</div>
                <div class="mob">H</div>
            </div>
            <div class="time-box bg-success text-light font-bold">
                <span class="minutes" id="minute">00</span>
                <div class="web">Mins</div>
                <div class="mob">M</div>
            </div>
            <div class="time-box bg-success text-light font-bold">
                <span class="seconds" id="second">00</span>
                <div class="web">Secs</div>
                <div class="mob">S</div>
            </div>
        </div>
    </div>
@endif
@if (isset($type) && !empty($type) && $type == 'details')
    <form action="#" method="POST" id="poll-vote-form" class="form-horizontal">
        <input type="hidden" name="widget_token" value="{{ addWidgetToken($poll->id) }}">
@endif
<input type="hidden" name="id" id="id" value="{{ $poll['id'] }}">
<input type="hidden" name="slug" id="slug" value="{{ $poll->slug }}">
<input type="hidden" name="vote_add" id="vote_add" value="{{ $poll->vote_add }}">
<input type="hidden" name="vote_schedule" id="vote_schedule" value="{{ $poll->vote_schedule }}">
<input type="hidden" name="page_type" id="page_type" value="embeded">
<div
    class="poll-options-main option-view-{{ $type }} text-center  @if (isset($type) && !empty($type) && $type == 'details') mt-5 @endif">
    @if (isset($type) && !empty($type) && $type == 'details')
        @if (isset($poll->option_select) && !empty($poll->option_select) && count($poll_options) > $poll->option_select)
            <p class="sub-text">You can choose {{ convert_number($poll->option_select) }} option</p>
        @else
            <p class="sub-text">You can choose more than one</p>
        @endif
    @endif
    <div class="option-container @if (isset($type) && !empty($type) && $type == 'details') option-container-details @endif mt-5">
        @foreach ($poll_option_array as $option_id => $option_vote)
            @if ($loop->first && $loop->iteration < 2)
                @if (isset($codeblock) && !empty($codeblock) && !empty($codeblock['abovefirst']))
                    <div class="firstoption-codeblock">
                        {!! $codeblock['abovefirst'] !!}
                    </div>
                @endif
            @endif
            @if ($loop->iteration == round(count($poll_option_array) / 2) && $loop->iteration > 1)
                @if (isset($codeblock) && !empty($codeblock) && !empty($codeblock['abovemiddle']))
                    <div class="firstoption-codeblock">
                        {!! $codeblock['abovemiddle'] !!}
                    </div>
                @endif
            @endif
            @if ($loop->last && $loop->iteration > 1)
                @if (isset($codeblock) && !empty($codeblock) && !empty($codeblock['abovelast']))
                    <div class="lastoption-codeblock">
                        {!! $codeblock['abovelast'] !!}
                    </div>
                @endif
            @endif
            <div class="card-poll d-flex align-items-center mb-3 imagelight-box">
                <input type="hidden" class="option_id" name="option_id_{{ $loop->iteration }}"
                    id="option_id_{{ $loop->iteration }}" value="{{ $poll_options[$option_id]['id'] }}">
                <div class="image-div">
                    @if (isset($poll_options[$option_id]['image']) && !empty($poll_options[$option_id]['image']))
                        <a href="{{ getImagePath($poll_options[$option_id]['image'], $poll->slug, 'poll_options') }}"><img
                                data-original="{{ getImagePath($poll_options[$option_id]['image'], $poll->slug, 'poll_options') }}"
                                alt="{{ $poll_options[$option_id]['title'] }}" class="w-100 lazyload"></a>
                    @else
                        <a href="{{ @asset('assets/images/bodybg.jpg') }}"
                            alt="{{ $poll_options[$option_id]['title'] }}"><img
                                data-original="{{ @asset('assets/images/bodybg.jpg') }}"
                                alt="{{ $poll_options[$option_id]['title'] }}" class="w-100 lazyload"></a>
                    @endif
                </div>
                <div class="title-div w-100 text-left">
                    <input type="hidden" name="option_id" class="option_id"
                        value="{{ $poll_options[$option_id]['id'] }}">
                    <p class="m-0 pl-4">{{ $poll_options[$option_id]['title'] }}</p>
                </div>
                @if (isset($type) && !empty($type) && $type == 'results')
                    <div class="total-votebox position-absolute">
                        <img src="{{ @asset('assets/images/voting-box.png') }}" alt="Voting Box" class="w-100">
                        <p>{{ strlen($option_vote) > 1 ? $option_vote : '0' . $option_vote }}</p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    @if (isset($type) && !empty($type) && $type == 'details')
        @if (isset($poll->captcha_type) && !empty($poll->captcha_type) && $poll->captcha_type == 1)
            @if (!checkBlockedIP())
                <div class="google-recaptcha-div mt-5">
                    <div class="form-group">
                        <input type="hidden" name="enabledgooglecaptcha" id="enabledgooglecaptcha"
                            class="enabledgooglecaptcha" value="enabledgooglecaptcha">
                        <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}" id="g-recaptcha">
                        </div>
                        <span class="help-block error-span"></span>
                    </div>
                </div>
            @endif
        @elseif(isset($poll->captcha_type) && !empty($poll->captcha_type) && $poll->captcha_type == 2)
            <div class="form-group">
                <input type="hidden" name="enabledmathcaptcha" id="enabledmathcaptcha" class="enabledmathcaptcha"
                    value="enabledmathcaptcha">
                <div class="math-recaptcha-div mt-5">
                    <h5 class="font-weight-bold mt-0 text-uppercase">Please answer it - 解决数学问题</h5>
                    <div class="form-group mb-0 d-flex align-items-baseline justify-content-center">
                        @php
                            $first_numb = rand(1, 20);
                            $second_numb = rand(1, 20);
                        @endphp
                        <input type="hidden" name="match_captcha_firstnumb" value="{{ $first_numb }}"
                            id="match_captcha_firstnumb" class="match_captcha_firstnumb" readonly>
                        <input type="hidden" name="match_captcha_secoundnumb" value="{{ $second_numb }}"
                            id="match_captcha_secoundnumb" class="match_captcha_secoundnumb" readonly>
                        <label for="captcha" class="col-form-label text-left">{{ $first_numb }} +
                            {{ $second_numb }}
                            = </label>
                        <div class="input-group-div ml-3">
                            <input type="text" id="mathcaptcha_ctm" name="mathcaptcha_ctm" required="required"
                                value="" class="form-control mathcaptcha_ctm">
                            <span class="help-block error-span d-none"></span>
                        </div>
                    </div>
                </div>
            </div>
        @elseif(isset($poll->captcha_type) && !empty($poll->captcha_type) && $poll->captcha_type == 3)
            @if (!checkBlockedIP())
                <div class="form-group">
                    <input type="hidden" name="enabledhcaptcha" id="enabledhcaptcha" class="enabledhcaptcha"
                        value="enabledhcaptcha">
                    <div class="h-captcha" data-sitekey="{{ env('HCAPTCHA_SITEKEY') }}" id="h-captcha"></div>
                    <span class="help-block error-span"></span>
                </div>
            @endif
        @endif
        <div class="btn-container mt-5 d-flex align-items-center justify-content-center">
            <a href="javascript:void(0)" class="link pr-4 text-capitalize fandomz_result_list">results</a>
            <button type="button" class="btn btn-primary submit-voting btn-lg" id="fandomz_submit_voting">
                <span class="load open"></span><span class="btn-text">Vote</span>
            </button>
        </div>
    @endif
</div>
@if (isset($type) && !empty($type) && $type == 'details')
    </form>
@endif
@if (isset($type) && !empty($type) && $type == 'results' && $poll->status == '1')
    <div class="card-bottom mt-4">
        <a href="javascript:void(0)" class="btn btn-primary fandomz_poll_list">Go to poll page</a>
    </div>
@endif
