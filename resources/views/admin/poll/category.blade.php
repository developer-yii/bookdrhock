@extends('layouts.admin')

@push('extraStyle')
    <link href="{{ asset('plugins/datatables/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css" />
    <!--alerts CSS -->
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.css') }}" rel="stylesheet" type="text/css">
@endpush

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title text-capitalize">{{ __('Categories') }}</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                <li class="active">{{ __('Categories') }}</li>
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
                    <h2 class="text-capitalize font-weight-bold m-0">{{ __('All Categories') }}</h2>
                    <button type="button" class="btn btn-info waves-effect waves-light" id="create-category">
                        <span>{{ __('Add New') }} </span>
                        <i class="fa fa-plus-circle m-l-5"></i>
                    </button>
                </div>
                <div class="table-responsive">
                    <table id="category-datatable" class="table table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('Name') }}</th>
                                <th>{{ __('Slug') }}</th>
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
    <!-- category modal content -->
    <div id="category-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="categoryModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-none">
                <div class="modal-header bg-inverse b-none rounded-0">
                    <h4 class="modal-title text-white" id="categoryModalLabel">{{ __('Add New Category') }}</h4>
                </div>
                <div class="modal-body b-all p-4">
                    <form action="#" method="POST" id="category-form">
                        <div class="form-body">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="category_form_action" id="category_form_action">
                            <!--row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="name" class="control-label">{{ __('Name*') }}</label>
                                        <input type="text" id="name" name="name" class="form-control"
                                            value="{{ old('name') }}">
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                            </div>
                            <!--row-->
                            <!--row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="slug" class="control-label">{{ __('Permalink') }}</label>
                                        <div class="slug-group d-flex align-items-center">
                                            <p class="m-0 pr-2">{{ URL::to('/') }}/category/</p>
                                            <input type="text" id="slug" name="slug" class="form-control"
                                                value="{{ old('slug') }}">
                                        </div>
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                            </div>
                            <!--row-->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary"
                        id="addorupdate-category">{{ __('Add Category') }}</button>
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
    <script src="{{ asset('plugins/datatables/jquery.dataTables.min.js') }}" type="text/javascript"></script>
    <!-- Sweet-Alert  -->
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/bootstrap-switch/bootstrap-switch.min.js') }}" type="text/javascript"></script>
    <script type="text/javascript">
        // Define variable for route path
        var routes = {
            indexUrl: "{{ route('category') }}",
            addOrUpdateUrl: "{{ route('category.createorupdate') }}",
            getSlugUrl: "{{ route('category.slug') }}",
            deleteUrl: "{{ route('category.delete') }}"
        }
    </script>
    <script src="{{ addAdminJsLink('poll/poll-category.js') }}" type="text/javascript"></script>
@endpush
