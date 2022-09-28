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
                <form class="form-horizontal form-material" method="POST" action="{{ route('password.email') }}">
                    @csrf
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <h3 class="mt-0">{{ __('Recover Password') }}</h3>
                            <p class="text-muted">Enter your Email and instructions will be sent to you! </p>
                        </div>
                    </div>
                    <div class="form-group ">
                        <div class="col-xs-12">
                            <input id="email" type="text" class="form-control @error('email') is-invalid @enderror"
                                name="email" placeholder="Email" value="{{ old('email') }}" autocomplete="email"
                                autofocus>

                            @error('email')
                                <span class="invalid-feedback text-danger" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group text-center m-t-20 mb-0">
                        <div class="col-xs-12">
                            <button class="btn btn-primary btn-lg btn-block text-uppercase waves-effect waves-light"
                                type="submit">{{ __('Reset') }}</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@endsection
