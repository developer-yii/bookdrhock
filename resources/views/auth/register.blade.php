@extends('layouts.auth')

@section('content')
    <section id="wrapper" class="login-register">
        <div class="login-box">
            <div class="white-box">
                <form class="form-horizontal form-material" id="loginform" method="POST" action="{{ route('register') }}">
                    @csrf
                    <h3 class="box-title m-b-20">{{ __('Sign In') }}</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name"
                                placeholder="Name" value="{{ old('name') }}" autocomplete="off" autofocus>

                            @error('name')
                                <span class="invalid-feedback text-danger text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                                name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="off">

                            @error('email')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password"
                                placeholder="Password" autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation"
                                placeholder="Confirm Password" autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-primary p-t-0">
                                <input id="checkbox-signup" type="checkbox">
                                <label for="checkbox-signup"> I agree to all <a href="#">Terms</a></label>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit">{{ __('Sign Up') }}</button>
                        </div>
                    </div>
                    @if (Route::has('login'))
                        <div class="form-group m-b-0">
                            <div class="col-sm-12 text-center">
                                <p>Already have an account? <a class="text-primary m-l-5"
                                        href="{{ route('login') }}"><b>{{ __('Sign In') }}</b></a></p>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection
