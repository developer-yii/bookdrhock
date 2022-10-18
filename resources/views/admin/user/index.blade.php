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
            <h4 class="page-title text-capitalize">{{ __('Users') }}</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                <li class="active">{{ __('Users') }}</li>
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
                    <h2 class="text-capitalize font-weight-bold m-0">{{ __('All Users') }}</h2>
                    <button type="button" class="btn btn-info waves-effect waves-light" id="create-user">
                        <span>{{ __('Add New') }} </span>
                        <i class="fa fa-plus-circle m-l-5"></i>
                    </button>
                </div>
                <div class="table-responsive">
                    <table id="user-datatable" class="table table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>{{ __('ID') }}</th>
                                <th>{{ __('First Name') }}</th>
                                <th>{{ __('Last Name') }}</th>
                                <th>{{ __('Email') }}</th>
                                <th>{{ __('Phone') }}</th>
                                <th>{{ __('Role Id') }}</th>
                                <th>{{ __('Role') }}</th>
                                <th>{{ __('Status') }}</th>
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
    <!-- user modal content -->
    <div id="user-modal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content b-none">
                <div class="modal-header bg-inverse b-none rounded-0">
                    <h4 class="modal-title text-white" id="userModalLabel">{{ __('Add New User') }}</h4>
                </div>
                <div class="modal-body b-all p-4">
                    <form action="#" method="POST" id="user-form">
                        <div class="form-body">
                            <input type="hidden" name="id" id="id">
                            <input type="hidden" name="user_profile_id" id="user_profile_id">
                            <input type="hidden" name="user_form_action" id="user_form_action">
                            <!--row-->
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="first_name" class="control-label">{{ __('First Name') }}</label>
                                        <input type="text" id="first_name" name="first_name" class="form-control"
                                            value="{{ old('first_name') }}">
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="last_name" class="control-label">{{ __('Last Name') }}</label>
                                        <input type="text" id="last_name" name="last_name" class="form-control"
                                            value="{{ old('last_name') }}">
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                            </div>
                            <!--row-->
                            <!--row-->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="email" class="control-label">{{ __('Email') }}</label>
                                        <input type="text" id="email" name="email" class="form-control"
                                            value="{{ old('email') }}">
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                            </div>
                            <!--row-->
                            <div class="row">
                                <div class="{{ isset($roles) && !empty($roles) ? 'col-md-6' : 'col-md-12' }}">
                                    <div class="form-group">
                                        <label for="phone" class="control-label">{{ __('Phone') }}</label>
                                        <input type="text" id="phone" name="phone" class="form-control"
                                            value="{{ old('phone') }}">
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                                @if (isset($roles) && !empty($roles))
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role" class="control-label">{{ __('Role') }}</label>
                                            <select name="role" id="role"
                                                class="custom-select width-equal col-12">
                                                <option value="">{{ __('Select Role') }}</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}" class="text-capitalize">
                                                        {{ $role->role }}</option>
                                                @endforeach
                                            </select>
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <!--row-->
                            <div class="row" id="password-form-row">
                                <div class="col-md-6 password-col">
                                    <div class="form-group mb-0">
                                        <label for="password" class="control-label">{{ __('Password') }}</label>
                                        <input type="password" id="password" name="password" class="form-control"
                                            value="{{ old('password') }}">
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 password-col">
                                    <div class="form-group mb-0">
                                        <label for="password_confirmation"
                                            class="control-label">{{ __('Confirm Password') }}</label>
                                        <input type="password" id="password_confirmation" name="password_confirmation"
                                            class="form-control" value="{{ old('password_confirmation') }}">
                                        <span class="help-block error-span"></span>
                                    </div>
                                </div>
                            </div>
                            <!--row-->
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-primary" id="addorupdate-user">{{ __('Add User') }}</button>
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
            indexUrl: "{{ route('user') }}",
            addOrUpdateUrl: "{{ route('user.createorupdate') }}",
            deleteUrl: "{{ route('user.delete') }}",
            updateuserStatusUrl: "{{ route('user.userStatus') }}",
            userProfileUrl: "{{ route('userProfile') }}",
            userProfilePasswordChangeUrl: "{{ route('userProfile.password') }}",
            loginUserId: {{ $login_user_id }}
        }
    </script>
    <script src="{{ addAdminJsLink('user.js') }}" type="text/javascript"></script>
@endpush
