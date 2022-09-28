@extends('layouts.auth')

@section('content')
    <section id="wrapper" class="login-register">
        <div class="login-box mt-0">
            <div class="white-box mb-0">
                <form method="POST" action="{{ route('password.update') }}" class="form-horizontal form-material">
                    @csrf
                    <h3 class="box-title m-b-20">{{ __('Reset Password') }}</h3>
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="form-group">
                        <div class="col-xs-12">
                            <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="Email"
                                name="email" value="{{ $email ?? old('email') }}" required autocomplete="email"
                                autofocus>

                            @error('email')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-xs-12">
                            <input id="password" type="password"
                                class="form-control @error('password') is-invalid @enderror" name="password" placeholder="Password" required
                                autocomplete="new-password">

                            @error('password')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>

                    <div class="form-group">

                        <div class="col-xs-12">
                            <input id="password-confirm" type="password" class="form-control" name="password_confirmation" placeholder="Confirm Password"
                                required autocomplete="new-password">
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20 mb-0">
                        <div class="col-xs-12">
                            <button class="btn btn-info btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit">{{ __('Reset Password') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
