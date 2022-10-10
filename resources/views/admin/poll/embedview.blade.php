@extends('layouts.blank')

@push('extraStyle')
    <!--alerts CSS -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    {{-- <div class="container embeded-container">
        <!--row -->
        <div class="row my-5">
            <div class="col-12">
            </div>
        </div>
        <!-- /.row -->
    </div> --}}
    <div class="bg-white card poll-view-card rounded-0 position-relative w-100 m-1">
        @php $type = isset($type) && !empty($type) ? $type : 'details' @endphp
        @include('admin.poll.polldetail', ['type' => $type])
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

        function pollResultRedirect(slug) {
            url = "{{ route('poll.embedViewResults', ':slug') }}";
            url = url.replace(':slug', slug);
            window.location.href = url;
        }

        var maximumVoteInNumber =
            {{ isset($poll[0]->option_select) && !empty($poll[0]->option_select) && $poll[0]->option_select > 0 ? $poll[0]->option_select : 0 }}
        var maximumVoteInWord =
            "{{ isset($poll[0]->option_select) && !empty($poll[0]->option_select) && $poll[0]->option_select > 0 ? convert_number($poll[0]->option_select) : 0 }}"
    </script>
    <script src="{{ asset('assets/js/admin/poll/poll-view.js') }}" type="text/javascript"></script>
@endpush
