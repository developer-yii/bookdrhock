@extends('layouts.blank')

@push('extraStyle')
    <!--alerts CSS -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    {{-- <pre>
            @php
                print_r($poll);
            @endphp
        </pre> --}}
    <div class="container">
        <!--row -->
        <div class="row my-5">
            <div class="col-12">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-8 col-xl-6 col-12">
                        <div class="bg-white card poll-view-card p-30 rounded-0 position-relative">
                            <div class="poll-heading">
                                <h1 class="text-center text-capitalize">{{ $poll->title }}</h1>
                                <div class="text-center">{!! $poll->description !!}</div>
                                <hr>
                            </div>
                            <div class="poll-timer text-center">
                                <h5 class="text-uppercase countdown-heading">time left</h5>
                                <div class="clockdiv-container" id="clockdiv"
                                    data-startdatetime="{{ $poll->start_datetime }}"
                                    data-enddatetime="{{ $poll->end_datetime }}">
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
                            <form action="#" method="POST" id="poll-vote-form" class="form-horizontal">
                                <input type="hidden" name="id" id="id" value="{{ $poll->id }}">
                                <input type="hidden" name="vote_schedule" id="vote_schedule"
                                    value="{{ $poll->vote_schedule }}">
                                <div class="poll-options-main text-center mt-5">
                                    @if (isset($poll->option_select) &&
                                        !empty($poll->option_select) &&
                                        count(explode(',', $poll->option_id)) > $poll->option_select)
                                        <p>You can choose {{ convert_number($poll->option_select) }} option</p>
                                    @else
                                        <p>You can choose more than one</p>
                                    @endif
                                    <div class="option-container mt-5">
                                        @php
                                            $pollOptions = [];
                                            $pollOptionId = explode(',', $poll->option_id);
                                            $pollOptionTitle = explode(',', $poll->option_title);
                                            $pollOptionImage = explode(',', $poll->option_image);
                                            for ($i = 0; $i <= count($pollOptionTitle) - 1; $i++) {
                                                $pollOptions[$i]['id'] = $pollOptionId[$i];
                                                $pollOptions[$i]['title'] = $pollOptionTitle[$i];
                                                $pollOptions[$i]['image'] = $pollOptionImage[$i];
                                            }
                                        @endphp
                                        @foreach ($pollOptions as $pollOption)
                                            <div class="card-poll d-flex align-items-center mb-3">
                                                <input type="hidden" class="option_id"
                                                    name="option_id_{{ $loop->iteration }}"
                                                    id="option_id_{{ $loop->iteration }}" value="{{ $pollOption['id'] }}">
                                                <div class="image-div">
                                                    @if ($pollOption['image'] != 'null')
                                                        <img src="{{ $poll->getImagePath($pollOption['image'], $poll->slug, 'poll_options') }}"
                                                            alt="{{ $pollOption['title'] }}" class="w-100">
                                                    @else
                                                        <img src="{{ @asset('assets/images/bodybg.jpg') }}"
                                                            alt="{{ $pollOption['title'] }}" class="w-100">
                                                    @endif
                                                </div>
                                                <div class="title-div w-100 text-left">
                                                    <input type="hidden" name="option_id" class="option_id"
                                                        value="{{ $pollOption['id'] }}">
                                                    <p class="m-0 pl-4">{{ $pollOption['title'] }}</p>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    @if (isset($poll->captcha_type) && !empty($poll->captcha_type) && $poll->captcha_type == 1)
                                        <div class="google-recaptcha-div mt-5">
                                            <div class="form-group">
                                                <input type="hidden" name="enabledgooglecaptcha" id="enabledgooglecaptcha"
                                                    class="enabledgooglecaptcha" value="enabledgooglecaptcha">
                                                <div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_RECAPTCHA_KEY') }}">
                                                </div>
                                                <span class="help-block error-span"></span>
                                            </div>
                                        </div>
                                    @elseif(isset($poll->captcha_type) && !empty($poll->captcha_type) && $poll->captcha_type == 2)
                                        <div class="form-group">
                                            <input type="hidden" name="enabledmathcaptcha" id="enabledmathcaptcha"
                                                class="enabledmathcaptcha" value="enabledmathcaptcha">
                                            <div class="math-recaptcha-div mt-5">
                                                <h5 class="font-weight-bold mt-0">Please answer it</h5>
                                                <div
                                                    class="form-group mb-0 d-flex align-items-baseline justify-content-center">
                                                    <label for="captcha"
                                                        class="col-form-label text-left">{{ app('mathcaptcha')->label() }}
                                                        = </label>
                                                    <div class="input-group-div ml-3">
                                                        {!! app('mathcaptcha')->input(['class' => 'form-control mathcaptcha', 'id' => 'mathcaptcha']) !!}
                                                        <span class="help-block error-span d-none"></span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <button class="btn btn-primary mt-3 submit-voting" id="submit-voting">Vote</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
@endsection

@push('extraScript')
    <!-- Google-recaptcha  -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        // Define variable for route path
        var routes = {
            indexUrl: "{{ route('poll') }}",
            votingUrl: "{{ route('poll.voting') }}"
        }
        var maximumVoteInNumber =
            {{ isset($poll->option_select) && !empty($poll->option_select) && $poll->option_select > 0 ? $poll->option_select : 0 }}
        var maximumVoteInWord =
            "{{ isset($poll->option_select) && !empty($poll->option_select) && $poll->option_select > 0 ? convert_number($poll->option_select) : 0 }}"
    </script>
    <script src="{{ asset('assets/js/admin/poll/poll-view.js') }}" type="text/javascript"></script>
@endpush
