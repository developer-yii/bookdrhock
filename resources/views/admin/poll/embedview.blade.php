@extends('layouts.blank')

@push('extraStyle')
    <!--alerts CSS -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <!--lightbox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
@endpush

@section('content')
    <div class="bg-white card poll-view-card rounded-0 position-relative w-100 m-1 poll-view">
        @php $type = isset($type) && !empty($type) ? $type : 'details' @endphp
        @include('admin.poll.polldetail', ['type' => $type,'codeblock' => $codeblock])
    </div>
    <div class="result-view bg-white card poll-view-card rounded-0 position-relative w-100 m-1 result-view"></div>
@endsection

@push('extraScript')
    <!-- Google-recaptcha  -->
    <script src='https://www.google.com/recaptcha/api.js'></script>

    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>

    <!-- lightbox  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    <script type="text/javascript">
        // Define variable for route path
        var routes = {
            indexUrl: "{{ route('poll') }}",
            homeUrl: "{{ route('home') }}",
            votingUrl: "{{ route('poll.voting') }}"
        }
        $('.result-view').hide();
        function pollResultRedirect(slug,data) {
            $('.poll-view').hide();
            $('.result-view').show();
            $('.result-view').html(data);

            if ($(".lazyload").length > 0) {
                $("img.lazyload").lazyload();
            }
        }

        var maximumVoteInNumber =
            {{ isset($poll->option_select) && !empty($poll->option_select) && $poll->option_select > 0 ? $poll->option_select : 0 }}
        var maximumVoteInWord =
            "{{ isset($poll->option_select) && !empty($poll->option_select) && $poll->option_select > 0 ? convert_number($poll->option_select) : 0 }}"
    </script>
    <script src="{{ addAdminJsLink('poll/poll-view.js') }}" type="text/javascript"></script>
@endpush
