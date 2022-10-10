<div class="poll-heading">
    <h1 class="text-center text-capitalize">{{ $poll[0]->title }}</h1>
    <div class="text-center">{!! $poll[0]->description !!}</div>
    <hr>
</div>
@if (isset($type) && !empty($type) && $type == 'details')
    <div class="poll-timer text-center">
        <h5 class="text-uppercase countdown-heading">time left</h5>
        <div class="clockdiv-container" id="clockdiv"
            data-startdatetime="{{ isset($poll[0]->start_datetime) && !empty($poll[0]->start_datetime) ? $poll[0]->start_datetime : 'null' }}"
            data-enddatetime="{{ isset($poll[0]->end_datetime) && !empty($poll[0]->end_datetime) ? $poll[0]->end_datetime : 'null' }}">
            <div class="time-box bg-success text-light font-bold">
                <span class="days" id="day">00</span>
                <div>Days</div>
            </div>
            <div class="time-box bg-success text-light font-bold">
                <span class="hours" id="hour">00</span>
                <div>Hrs</div>
            </div>
            <div class="time-box bg-success text-light font-bold">
                <span class="minutes" id="minute">00</span>
                <div>Mins</div>
            </div>
            <div class="time-box bg-success text-light font-bold">
                <span class="seconds" id="second">00</span>
                <div>Secs</div>
            </div>
        </div>
    </div>
@endif
@if (isset($type) && !empty($type) && $type == 'details')
    <form action="#" method="POST" id="poll-vote-form" class="form-horizontal">
        @csrf
        <input type="hidden" name="id" id="id" value="{{ $poll[0]->id }}">
        <input type="hidden" name="slug" id="slug" value="{{ $poll[0]->slug }}">
        <input type="hidden" name="vote_add" id="vote_add" value="{{ $poll[0]->vote_add }}">
        <input type="hidden" name="vote_schedule" id="vote_schedule" value="{{ $poll[0]->vote_schedule }}">
        <input type="hidden" name="page_type" id="page_type"
            value="{{ request()->routeIs('poll.embedView') ? 'embeded' : 'normal' }}">
@endif
<div class="poll-options-main text-center  @if (isset($type) && !empty($type) && $type == 'details') mt-5 @endif">
    @if (isset($type) && !empty($type) && $type == 'details')
        @if (isset($poll[0]->option_select) && !empty($poll[0]->option_select) && count($poll) > $poll[0]->option_select)
            <p>You can choose {{ convert_number($poll[0]->option_select) }} option</p>
        @else
            <p>You can choose more than one</p>
        @endif
    @endif
    <div class="option-container @if (isset($type) && !empty($type) && $type == 'details') option-container-details @endif mt-5">
        @foreach ($poll as $pollOption)
            <div class="card-poll d-flex align-items-center mb-3">
                <input type="hidden" class="option_id" name="option_id_{{ $loop->iteration }}"
                    id="option_id_{{ $loop->iteration }}" value="{{ $pollOption->option_id }}">
                <div class="image-div">
                    @if (isset($pollOption->option_image) && !empty($pollOption->option_image))
                        <img src="{{ $pollOption->getImagePath($pollOption->option_image, $poll[0]->slug, 'poll_options') }}"
                            alt="{{ $pollOption->option_title }}" class="w-100">
                    @else
                        <img src="{{ @asset('assets/images/bodybg.jpg') }}" alt="{{ $pollOption->option_title }}"
                            class="w-100">
                    @endif
                </div>
                <div class="title-div w-100 text-left">
                    <input type="hidden" name="option_id" class="option_id" value="{{ $pollOption->option_id }}">
                    <p class="m-0 pl-4">{{ $pollOption->option_title }}</p>
                </div>
                @if (isset($type) && !empty($type) && $type == 'results')
                    <div class="total-votebox position-absolute">
                        <img src="{{ @asset('assets/images/voting-box.png') }}" alt="Voting Box" class="w-100">
                        <p>{{ strlen($pollOption->votes) > 1 ? $pollOption->votes : '0' . $pollOption->votes }}
                        </p>
                    </div>
                @endif
            </div>
        @endforeach
    </div>
    @if (isset($type) && !empty($type) && $type == 'details')
        @if (isset($poll[0]->captcha_type) && !empty($poll[0]->captcha_type) && $poll[0]->captcha_type == 1)
            <div class="google-recaptcha-div mt-5">
                <div class="form-group">
                    <input type="hidden" name="enabledgooglecaptcha" id="enabledgooglecaptcha"
                        class="enabledgooglecaptcha" value="enabledgooglecaptcha">
                    <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}">
                    </div>
                    <span class="help-block error-span"></span>
                </div>
            </div>
        @elseif(isset($poll[0]->captcha_type) && !empty($poll[0]->captcha_type) && $poll[0]->captcha_type == 2)
            <div class="form-group">
                <input type="hidden" name="enabledmathcaptcha" id="enabledmathcaptcha" class="enabledmathcaptcha"
                    value="enabledmathcaptcha">
                <div class="math-recaptcha-div mt-5">
                    <h5 class="font-weight-bold mt-0">Please answer it</h5>
                    <div class="form-group mb-0 d-flex align-items-baseline justify-content-center">
                        <label for="captcha" class="col-form-label text-left">{{ app('mathcaptcha')->label() }}
                            = </label>
                        <div class="input-group-div ml-3">
                            {!! app('mathcaptcha')->input(['class' => 'form-control mathcaptcha', 'id' => 'mathcaptcha']) !!}
                            <span class="help-block error-span d-none"></span>
                        </div>
                    </div>
                </div>
            </div>
        @endif
        <div class="btn-container mt-3 d-flex align-items-center justify-content-between">
            <button class="btn btn-primary submit-voting" id="submit-voting">Vote</button>
            <a href="{{ request()->routeIs('poll.embedView') ? route('poll.embedViewResults', $poll[0]->slug) : route('poll.viewResults', $poll[0]->slug) }}"
                class="btn btn-success">Go to result page</a>
        </div>
    @endif
</div>
@if (isset($type) && !empty($type) && $type == 'details')
    </form>
@endif
@if (isset($type) && !empty($type) && $type == 'results')
    <div class="card-bottom mt-4">
        <a href="{{ request()->routeIs('poll.embedViewResults') ? route('poll.embedView', $poll[0]->slug) : route('poll.view', $poll[0]->slug) }}"
            class="btn btn-primary">Go to poll page</a>
    </div>
@endif
