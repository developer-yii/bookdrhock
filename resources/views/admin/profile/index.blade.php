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
                <li class="active">{{ __('Profile') }}</li>
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
                    <h2 class="text-capitalize font-weight-bold m-0">{{ __('Profile') }}</h2>
                </div>
                <div class="consumption-body">
                    <div class="form-container">
                        <form action="#" method="POST" id="profile-update-form">
                            <div class="form-body">
                                <input type="hidden" name="user_id" id="user_id">
                                <input type="hidden" name="user_profile_id" id="user_profile_id">
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="first_name" class="control-label">{{ __('First Name') }}</label>
                                            <input type="text" id="first_name" class="form-control" name="first_name"
                                                value="{{ old('first_name') }}">
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="last_name" class="control-label">{{ __('Last Name') }}</label>
                                            <input type="text" id="last_name" class="form-control" name="last_name"
                                                value="{{ old('last_name') }}">
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                </div>
                                <!--/row-->
                                <!--row-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email" class="control-label">{{ __('Email') }}</label>
                                            <input type="text" id="email" class="form-control" name="email"
                                                value="{{ old('email') }}">
                                            <span class="help-block error-span"></span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone" class="control-label">{{ __('Phone') }}</label>
                                            <input type="text" id="phone" class="form-control" name="phone"
                                                value="{{ old('phone') }}">
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
                                                id="update-profile">{{ __('Update Profile') }}</button>
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
            indexUrl: "{{ route('userProfile') }}",
            updateUrl: "{{ route('userProfile.update') }}",
        }
    </script>
    <script src="{{ addAdminJsLink('profile.js') }}" type="text/javascript"></script>
@endpush
