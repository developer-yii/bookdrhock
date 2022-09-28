@extends('layouts.auth')

@section('content')
    <section id="wrapper" class="welcome">
        <div class="welcome-box">
            <header class="header">
                <div class="container">
                    <div class="row align-items-center">
                        <div class="col-4">
                            <a class="logo d-flex align-items-center" href="{{ route('home') }}">
                                <b><img src="{{ asset('assets/images/logo-dark.png') }}" width="40px"
                                        alt="home" /></b>
                                <span class="hidden-xs">
                                    <img src="{{ asset('assets/images/text-dark.png') }}" width="135px" alt="home" />
                                </span>
                            </a>
                        </div>
                        <div class="col-8 text-right">
                            @if (Route::has('login'))
                                <div class="top-right links">
                                    @auth
                                        <a href="{{ route('admin') }}">{{ __('Dashboard') }}</a>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                             document.getElementById('logout-form').submit();">
                                            {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                            @csrf
                                        </form>
                                    @else
                                        <a href="{{ route('login') }}">{{ __('Login') }}</a>

                                        @if (Route::has('register'))
                                            <a href="{{ route('register') }}">{{ __('Register') }}</a>
                                        @endif
                                    @endauth
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </header>
            <div class="box-content">
                <div class="text-center">
                    <h1>{{ __('Welcome') }}</h1>
                    <h2 class="text-uppercase">{{ __('Welcome To our Application') }}</h2>
                </div>
            </div>
            <footer class="footer text-center">
                <p class="font-weight-normal mb-0">{{ date('Y') }} {{ __('© Bookdrhock by') }} <a
                        href="https://amcodr.com/" target="_blank">{{ __('Amcodr IT Solutions') }}</a>.</p>
            </footer>
        </div>
    </section>
@endsection
