<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('assets/images/favicon.png') }}">
    <title>{{ config('app.name', 'Bookdrhock') }}</title>

    <!-- Bootstrap Core CSS -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/bootstrap-extension/css/bootstrap-extension.css') }}" rel="stylesheet"
        type="text/css">
    <!-- toast CSS -->
    <link href="{{ asset('plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet" type="text/css">
    <!-- animation CSS -->
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet" type="text/css">
    @stack('extraStyle')
    <!-- Custom Theme CSS -->
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>
@php $poll_categories = \App\Model\PollCategory::all(); @endphp

<body class="bg-transparent">
    <!-- Preloader -->
    <div class="preloader">
        <div class="cssload-speeding-wheel"></div>
    </div>

    <header class="site-header">
        <nav class="navbar navbar-expand-lg navbar-light shadow-sm bg-light fixed-top rounded-0 mb-0">
            <div class="container">
                <a class="logo d-flex align-items-center" href="{{ route('home') }}">
                    <img src="{{ asset('assets/images/logo-dark.png') }}" width="200px" alt="home" />
                </a>
                <button class="navbar-toggler navbar-toggler-right border-0" type="button" data-toggle="collapse"
                    data-target="#navbar4">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbar4">
                    <ul class="navbar-nav mr-auto pl-lg-4 font-bold">
                        <li class="nav-item px-lg-2 {{ request()->routeIs('home') ? 'active' : '' }}"> <a
                                class="nav-link" href="{{ route('home') }}"> <span
                                    class="d-inline-block d-lg-none icon-width"><i
                                        class="fas fa-home"></i></span>{{ __('Home') }}</a> </li>
                        @if (isset($poll_categories) && !empty($poll_categories) && count($poll_categories) > 0)
                            <li class="nav-item px-lg-2 {{ request()->routeIs('poll.view') ? 'active' : '' }}">
                                <a class="nav-link" href="#"><span class="d-inline-block d-lg-none icon-width"><i
                                            class="far fa-user"></i></i></span>Polls</a>
                                <ul class="nav nav-second-level collapse">
                                    @foreach ($poll_categories as $poll_category)
                                        <li> <a href="{{ route('poll.getCategoryView', str_replace(' ', '', $poll_category->slug)) }}"
                                                class="text-capitalize">{{ $poll_category->name }}</a> </li>
                                    @endforeach
                                </ul>
                            </li>
                        @endif
                        <li class="nav-item px-lg-2"> <a class="nav-link" href="#"><span
                                    class="d-inline-block d-lg-none icon-width"><i
                                        class="far fa-user"></i></i></span>{{ __('About') }}</a> </li>
                        <li class="nav-item px-lg-2"> <a class="nav-link" href="#"><span
                                    class="d-inline-block d-lg-none icon-width"><i
                                        class="far fa-envelope"></i></span>{{ __('Contact') }}</a> </li>
                    </ul>
                    <ul class="navbar-nav ml-auto mt-3 mt-lg-0">
                        <li class="nav-item"> <a class="nav-link" href="#">
                                <i class="fa fa-twitter"></i><span class="d-lg-none ml-3">{{ __('Twitter') }}</span>
                            </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="#">
                                <i class="fa fa-facebook"></i><span class="d-lg-none ml-3">{{ __('Facebook') }}</span>
                            </a> </li>
                        <li class="nav-item"> <a class="nav-link" href="#">
                                <i class="fa fa-instagram"></i><span
                                    class="d-lg-none ml-3">{{ __('Instagram') }}</span>
                            </a> </li>
                    </ul>
                    @if (Auth::check() && Auth::user()->user_role == 1)
                        <ul class="nav navbar-top-links navbar-right pull-right">
                            <li class="dropdown">
                                <a class="dropdown-toggle profile-pic text-capitalize font-weight-bold"
                                    data-toggle="dropdown" href="#"><img
                                        src="{{ @asset('assets/images/avatar.jpg') }}" alt="avtar" width="40px"
                                        srcset=""></a>
                                <ul class="dropdown-menu dropdown-user animated flipInY">
                                    <li><a href="{{ route('admin') }}"><i class="icon-layers"></i>
                                            {{ __('Dashboard') }}</a></li>
                                    <li><a href="{{ route('userProfile') }}"><i class="ti-user"></i>
                                            {{ __('My Profile') }}</a></li>
                                    <li><a href="{{ route('userProfile.password') }}"><i class="ti-key"></i>
                                            {{ __('Change Password') }}</a></li>
                                    <li>
                                        <a href="{{ route('logout') }}"
                                            onclick="event.preventDefault();
                                                                 document.getElementById('logout-form').submit();">
                                            <i class="fa fa-power-off"></i> {{ __('Logout') }}
                                        </a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST"
                                            class="d-none">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                                <!-- /.dropdown-user -->
                            </li>
                        </ul>
                    @endif
                </div>
            </div>
        </nav>
    </header>

    <main id="main" class="main">
        @yield('content')
    </main>

    <!-- Site footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <div class="footer-logo pb-4">
                        <a class="logo d-flex align-items-center" href="{{ route('home') }}">
                            <img src="{{ @asset('assets/images/logo.png') }}" width="200px" alt="home">
                        </a>
                    </div>
                    <p class="text-justify pr-5">
                        {{ __('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Praesent porta eget purus vitae fringilla. Morbi porta at elit a efficitur. Nam facilisis tellus sed orci finibus maximus.') }}
                    </p>
                </div>

                <div class="col-xs-6 col-md-3">
                    <h6>{{ __('Categories') }}</h6>
                    <ul class="footer-links">
                        @foreach ($poll_categories as $poll_category)
                            <li {{ request()->routeIs('getCategoryView') ? 'active' : '' }}> <a
                                    href="{{ route('poll.getCategoryView', str_replace(' ', '', $poll_category->slug)) }}"
                                    class="text-capitalize">{{ $poll_category->name }}</a> </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-xs-6 col-md-3">
                    <h6>{{ __('Quick Links') }}</h6>
                    <ul class="footer-links">
                        <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">
                                {{ __('Home') }}</a></li>
                        <li><a href="#">About Us</a></li>
                        <li><a href="#">Contact Us</a></li>
                        <li><a href="#">Privacy Policy</a></li>
                    </ul>
                </div>
            </div>
            <hr>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <p class="copyright-text">{{ date('Y') }} {{ __('© Bookdrhock by') }} <a
                            href="https://amcodr.com/" target="_blank">{{ __('Amcodr IT Solutions') }}</a>.</p>
                </div>

                <div class="col-md-4 col-sm-6 col-xs-12">
                    <ul class="social-icons">
                        <li><a class="facebook" href="#"><i class="fa fa-facebook"></i></a></li>
                        <li><a class="twitter" href="#"><i class="fa fa-twitter"></i></a></li>
                        <li><a class="instagram" href="#"><i class="fa fa-instagram"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>

    <!-- jQuery -->
    <script src="{{ asset('plugins/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>
    <!-- Bootstrap Core JavaScript -->
    <script src="{{ asset('assets/js/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/bootstrap-extension/js/bootstrap-extension.min.js') }}" type="text/javascript"></script>
    <!-- Menu Plugin JavaScript -->
    <script src="{{ asset('plugins/sidebar-nav/dist/sidebar-nav.min.js') }}" type="text/javascript"></script>
    <!--slimscroll JavaScript -->
    <script src="{{ asset('assets/js/jquery.slimscroll.js') }}" type="text/javascript"></script>
    <!-- Custom Theme JavaScript -->
    <script src="{{ asset('assets/js/custom.min.js') }}" type="text/javascript"></script>
    <!-- Toast Message JavaScript -->
    <script src="{{ asset('plugins/toast-master/js/jquery.toast.js') }}" type="text/javascript"></script>
    <!--Main custom JS -->
    <script src="{{ asset('assets/js/main.js') }}" type="text/javascript"></script>
    @if (session('flash-login-inactive'))
        <script type="text/javascript">
            $(document).ready(function() {
                showMessage('error', 'Your account was inactivated!')
            });
        </script>
    @endif
    @if (session('flash-poll-voted'))
        <script type="text/javascript">
            $(document).ready(function() {
                showMessage('success', 'Poll voted successfully.');
            });
        </script>
    @endif
    @stack('extraScript')
</body>

</html>
