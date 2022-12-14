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
    <!-- poll modal content -->
    <div id="poll-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="pollModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content b-none">
                <div class="modal-header bg-inverse b-none rounded-0">
                    <h4 class="modal-title text-white" id="pollModalLabel">{{ __('Add New User') }}</h4>
                </div>
                <div class="modal-body b-all p-4">
                    <div class="poll-information">
                        <input type="hidden" name="poll_id" id="poll_id" class="poll_id">
                        <h2 class="text-center font-weight-bold">Poll Information</h2>
                        <div class="table-responsive">
                            <table class="table poll-info-table my-3 mb-5">
                                <tbody>
                                    <tr>
                                        <th>Title</th>
                                        <td class="text-capitalize font-weight-normal title">test</td>
                                    </tr>
                                    <tr>
                                        <th>Category</th>
                                        <td class="text-capitalize font-weight-normal category_name">test</td>
                                    </tr>
                                    <tr>
                                        <th>Start Datetime</th>
                                        <td class="text-capitalize font-weight-normal start_datetime">test</td>
                                    </tr>
                                    <tr>
                                        <th>End Datetime</th>
                                        <td class="text-capitalize font-weight-normal end_datetime">test</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="poll-options">
                        <h2 class="text-center font-weight-bold">Poll's option</h2>
                        <div class="option-poll-table table-responsive">
                            <table id="poll-option-datatable" class="table table-hover" cellspacing="0" width="100%">
                                <thead>
                                    <tr>
                                        <th>{{ __('ID') }}</th>
                                        <th>{{ __('Image') }}</th>
                                        <th>{{ __('Title') }}</th>
                                        <th>{{ __('Votes') }}</th>
                                        <th>{{ __('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                        <div class="option-poll-add">
                            <form action="#" method="POST" id="poll-option-votechange-form">
                                <input type="hidden" name="id" value="" id="id">
                                <input type="hidden" name="poll_id" value="" id="poll_id">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="title" class="control-label">Title*</label>
                                            <input type="text" name="title" id="title" value=""
                                                class="form-control" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="admin-add-bulk-vote">
                                    <h4 class="font-weight-bold">Add direct vote</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="add_remove" class="control-label">Add or remove*</label>
                                                <select name="add_remove" id="add_remove"
                                                    class="custom-select width-equal col-12">
                                                    <option value="add">Add</option>
                                                    <option value="remove">Remove</option>
                                                </select>
                                                <span class="help-block error-span"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vote" class="control-label">Vote*</label>
                                                <input type="text" name="vote" id="vote" value="0"
                                                    class="form-control">
                                                <span class="help-block error-span"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="admin-add-bulk-vote mt-3 mb-2">
                                    <h4 class="font-weight-bold">Add extra vote when user's add vote</h4>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="add_remove_user" class="control-label">Add or remove*</label>
                                                <select name="add_remove_user" id="add_remove_user"
                                                    class="custom-select width-equal col-12">
                                                    <option value="add">Add</option>
                                                    <option value="remove">Remove</option>
                                                </select>
                                                <span class="help-block error-span"></span>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="vote_user" class="control-label">Vote*</label>
                                                <input type="text" name="vote_user" id="vote_user" value="0"
                                                    class="form-control">
                                                <span class="help-block error-span"></span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 d-flex align-items-center justify-content-between">
                                            <button type="button" class="btn btn-primary"
                                                id="votechange-poll">Submit</button>
                                            <button type="button" class="btn btn-secondary" id="optionlistTable">Back to
                                                list</button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default waves-effect"
                        id="model-cancle-btn">{{ __('Cancel') }}</button>
                </div>
            </div>
            <!-- /.modal-content -->
        </div>
        <!-- /.modal-dialog -->
    </div>
    <!-- /.modal -->
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
            getPollOptionsUrl: "{{ route('poll.options') }}",
            votechangePollOptionsUrl: "{{ route('poll.votechangePollOptions') }}",
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
    <script src="{{ addAdminJsLink('poll/poll.js') }}" type="text/javascript"></script>
@endpush
