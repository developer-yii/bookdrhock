@extends('layouts.site')
@section('title', $poll->title)
@section('meta_description', str_replace('&nbsp;', ' ', preg_replace( "/\r|\n/", "", strip_tags($poll->description))))

<!--Str::limit(Str::replaceArray('&amp;', [''], Str::replaceArray('&nbsp;', [''], strip_tags($poll->description))), 100)-->


@push('extraStyle')
    <!--alerts CSS -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <!--lightbox CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.css">
@endpush

@section('content')
    <div class="container">
        <!--row -->
        <div class="row my-5">
            <div class="col-12">
                <div class="row align-items-center justify-content-center">
                    <div class="col-md-8 col-xl-6 col-12">
                        <div class="bg-white card poll-view-card rounded-0 position-relative">
                            @if (isset($userrole) && !empty($userrole) && $userrole == 1)
                                <div class="edit-button position-absolute top right">
                                    <a href="{{ route('poll.editForm', $poll->id) }}"
                                        class="btn btn-info waves-effect waves-light">
                                        <span>edit poll</span>
                                        <i class=" ti-pencil-alt"></i>
                                    </a>
                                </div>
                            @endif
                            @php $type = isset($type) && !empty($type) ? $type : 'details' @endphp
                            @include('admin.poll.polldetail', ['type' => $type])
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

    <!-- lightbox  -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/fancybox/3.5.7/jquery.fancybox.min.js"></script>

    <script type="text/javascript">
        // Define variable for route path
        var routes = {
            indexUrl: "{{ route('poll') }}",
            homeUrl: "{{ route('home') }}",
            votingUrl: "{{ route('poll.voting') }}"
        }

        function pollResultRedirect(slug) {
            url = "{{ route('poll.viewResults', ':slug') }}";
            url = url.replace(':slug', slug);
            window.location.href = url;
        }

        var maximumVoteInNumber =
            {{ isset($poll->option_select) && !empty($poll->option_select) && $poll->option_select > 0 ? $poll->option_select : 0 }}
        var maximumVoteInWord =
            "{{ isset($poll->option_select) && !empty($poll->option_select) && $poll->option_select > 0 ? convert_number($poll->option_select) : 0 }}"
    </script>
    <script src="{{ asset('assets/js/admin/poll/poll-view.js') }}?78912" type="text/javascript"></script>
@endpush
