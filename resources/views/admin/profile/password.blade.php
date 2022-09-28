@extends('layouts.admin')

@section('content')
    <!--row -->
    <div class="row bg-title">
        <div class="col-lg-3 col-md-4 col-sm-4 col-xs-12">
            <h4 class="page-title text-capitalize">{{ __('Profile') }}</h4>
        </div>
        <div class="col-lg-9 col-sm-8 col-md-8 col-xs-12">
            <ol class="breadcrumb">
                <li><a href="{{ route('admin') }}">{{ __('Dashboard') }}</a></li>
                <li><a href="{{ route('userProfile') }}">{{ __('Profile') }}</a></li>
                <li class="active">{{ __('Change password') }}</li>
            </ol>
        </div>
        <!-- /.col-lg-12 -->
    </div>
    <!-- /.row -->
    <!-- /row -->
    <div class="row">
        <div class="col-sm-12">
            <div class="white-box">
                <div class="d-flex mb-5 justify-content-between">
                    <h2 class="text-capitalize font-weight-bold m-0">{{ __('Change password') }}</h2>
                </div>
                <div class="consumption-body">
                    <div class="form-container">
                        <form action="#" method="POST" id="passoword-change-form">
                            <div class="form-body">
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="old_password" class="control-label">{{ __('Old Password') }}</label>
                                            <input type="password" id="old_password" class="form-control"
                                                name="old_password" value="{{ old('old_password') }}">
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="password" class="control-label">{{ __('New Passoword') }}</label>
                                            <input type="password" id="password" class="form-control" name="password"
                                                value="{{ old('password') }}">
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <label for="password_confirmation"
                                                class="control-label">{{ __('Confirm Password') }}</label>
                                            <input type="password" id="password_confirmation" class="form-control"
                                                name="password_confirmation" value="{{ old('password_confirmation') }}">
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                </div>
                                <!--/row-->
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group">
                                            <button type="button" class="btn btn-success"
                                                id="update-password">{{ __('Update Password') }}</button>
                                        </div>
                                    </div>
                                </div>
                                <!--row-->
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- /.row -->
@endsection

@push('extraScript')
    <script type="text/javascript">
        // Define variable for route path
        var routes = {
            indexUrl: "{{ route('userProfile.password') }}",
            updateUrl: "{{ route('userProfile.passwordUpdate') }}",
        }
    </script>
    <script src="{{ asset('assets/js/admin/profile-password.js') }}" type="text/javascript"></script>
@endpush
