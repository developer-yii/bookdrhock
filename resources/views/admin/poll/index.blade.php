@extends('layouts.admin')

@push('extraStyle')
    <!--Datatables CSS -->
    <link href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />

    <!--alerts CSS -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title text-capitalize">{{ __('Polls') }}</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                <li class="active">{{ __('Polls') }}</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="d-flex mb-5 justify-content-between align-items-center">
                    <h2 class="text-capitalize font-weight-bold m-0">{{ __('All Polls') }}</h2>
                    <a href="{{ route('poll.createForm') }}" class="btn btn-info waves-effect waves-light">
                        <span>{{ __('Add New') }} </span>
                        <i class="fa fa-plus-circle m-l-5"></i>
                    </a>
                </div>
                <div class="table-responsive">
                    <table id="poll-datatable" class="table table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Title') }}</th>
                                <th>{{ __('Category') }}</th>
                                <th>{{ __('Start Date') }}</th>
                                <th>{{ __('End Date') }}</th>
                                <th>{{ __('Action') }}</th>
                            </tr>
                        </thead>
                        <tbody></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@push('extraScript')
    <!-- Datatable  -->
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

        function pollViewRedirect(slug) {
            url = "{{ route('poll.view', ':slug') }}";
            url = url.replace(':slug', slug);
            window.open(url, '_blank');
        }
    </script>
    <script src="{{ asset('assets/js/admin/poll/poll.js') }}" type="text/javascript"></script>
@endpush
