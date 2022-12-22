@extends('layouts.widget')
@section('content')
    <div class="bg-white card poll-view-card rounded-0 position-relative w-100 m-1 poll-view" id="poll_list_{{ $poll->slug }}">
        @php $type = isset($type) && !empty($type) ? $type : 'details' @endphp
        @include('admin.poll_widget.poll_detail', ['type' => $type,'codeblock' => $codeblock])
    </div>
    <div class="result-view bg-white card poll-view-card rounded-0 position-relative w-100 m-1 result-view" id="poll_result_{{ $poll->slug }}"></div>
@endsection
@push('extraScript')    
    <script type="text/javascript">        
        var routes = {
            homeUrl: "{{ route('home') }}",
            indexUrl: "{{ url('pollwidget/getlistHtml') }}",
            votingUrl: "{{ route('pollwidget.votingwidget') }}",
            resultsUrl: "{{ url('pollwidget/getresults') }}",
        }
        $('.result-view').hide();        
        var maximumVoteInNumber =
            {{ isset($poll->option_select) && !empty($poll->option_select) && $poll->option_select > 0 ? $poll->option_select : 0 }}
        var maximumVoteInWord =
            "{{ isset($poll->option_select) && !empty($poll->option_select) && $poll->option_select > 0 ? convert_number($poll->option_select) : 0 }}"
    </script>
    <script src="{{ asset('widget/poll_js/poll-view-min.js?22122022') }}" type="text/javascript"></script>
@endpush
