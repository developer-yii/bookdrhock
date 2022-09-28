@extends('layouts.site')

@push('extraStyle')
    {{-- <!--Datatables CSS -->
    <link href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />

    <!--alerts CSS -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css"> --}}
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
                        <div class="bg-white card p-30 rounded-0">
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
                            <div class="poll-options-main text-center mt-5">
                                <p>You can choose more than one</p>
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
                                <div class="google-recaptcha-div">
                                </div>
                                <button class="btn btn-primary mt-5">Vote</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- /.row -->
    </div>
@endsection

@push('extraScript')
    <script>
        $(document).ready(function() {
            if ($('.card-poll').length > 0) {
                $('.card-poll').on('click', function(e) {
                    e.preventDefault();
                    $(this).toggleClass('selected');
                })
            }

            if ($('#clockdiv').length > 0) {
                var deadline = headinText = '';
                if (new Date() > new Date($('#clockdiv').data('startdatetime'))) {
                    deadline = new Date($('#clockdiv').data('enddatetime')).getTime();
                    headinText = "time left";
                } else {
                    deadline = new Date($('#clockdiv').data('startdatetime')).getTime();
                    headinText = "comming soon";
                    $('.poll-options-main').empty().append(
                        '<button class="btn btn-primary m-0">view more</button>');
                }
                $('.countdown-heading').text(headinText);
                var x = setInterval(function() {
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

        });
    </script>
    {{-- <!-- Datatable  -->
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>

    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <script type="text/javascript">
        // Define variable for route path
        var routes = {
            indexUrl: "{{ route('poll') }}",
            addOrUpdateUrl: "{{ route('poll.createorupdate') }}",
            deleteUrl: "{{ route('poll.delete') }}"
        }

        function pollEditRedirect(id) {
            url = "{{ route('poll.editForm', ':id') }}";
            url = url.replace(':id', id);
            window.location.href = url;
        }

        function pollViewRedirect(id) {
            url = "{{ route('poll.editForm', ':id') }}";
            url = url.replace(':id', id);
            window.location.href = url;
        }
    </script> --}}
    {{-- <script src="{{ asset('assets/js/admin/poll/poll.js') }}" type="text/javascript"></script> --}}
@endpush
