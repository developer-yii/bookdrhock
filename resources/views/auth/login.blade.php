@extends('layouts.auth')

@section('content')
    <section id="wrapper" class="login-register">
        <div class="login-box mt-0">
            <div class="white-box mb-0">
                @if (session('status'))
                    <div class="alert alert-success" role="alert">
                        {{ session('status') }}
                    </div>
                @endif
                <form class="form-horizontal form-material" id="loginform" method="POST" action="{{ route('login') }}">
                    @csrf
                    <h3 class="box-title m-b-20">Sign In</h3>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input id="email" type="text" class="form-control mb-2 @error('email') is-invalid @enderror"
                                name="email" placeholder="Username" value="{{ old('email') }}" autocomplete="off"
                                autofocus>
                            @error('email')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="password" type="password"
                                class="form-control mb-2 @error('password') is-invalid @enderror" name="password"
                                placeholder="Password" autocomplete="off">
                            @error('password')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <div class="col-md-12">
                            <div class="checkbox checkbox-primary pull-left p-t-0">
                                <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                    {{ old('remember') ? 'checked' : '' }}>
                                <label class="form-check-label" for="remember">
                                    {{ __('Remember Me') }}
                                </label>
                            </div>
                            @if (Route::has('password.request'))
                                <a class="text-dark pull-right" href="{{ route('password.request') }}">
                                    {!! __('<i
                                    class="fa fa-lock m-r-5"></i> Forgot pwd?') !!}
                                </a>
                            @endif
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20 mb-0">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit">{{ __('Log In') }}</button>
                        </div>
                    </div>
                    @if (Route::has('register'))
                        <div class="form-group m-b-0">
                            <div class="col-sm-12 text-center">
                                <p>{{ __('Don\'t have an account?') }}
                                    <a class="text-primary m-l-5"
                                        href="{{ route('register') }}"><b>{{ __('Sign Up') }}</b></a>
                                </p>
                            </div>
                        </div>
                    @endif
                </form>
            </div>
        </div>
    </section>
@endsection
