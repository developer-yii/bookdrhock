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

    <link rel="icon" type="image/jpg" sizes="16x16" href="{{ asset('assets/images/favicon.jpg') }}">
    <title>{{ config('app.name', 'Fandomz') }}</title>

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
    <link rel="stylesheet" href="{{ asset('assets/css/custom.css') }}?123" type="text/css">
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
<![endif]-->
</head>

<body class="bg-transparent w-100 d-inline-flex">
    @yield('content')
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
    <!-- lazyload -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.lazyload/1.9.1/jquery.lazyload.min.js"></script>
    <!--Main custom JS -->
    <script src="{{ asset('assets/js/main.js') }}?{{ cacheclear() }}" type="text/javascript"></script>
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
    @if (session('flash-poll-votedone'))
        <script type="text/javascript">
            $(document).ready(function() {
                let hours = $('#vote_schedule').val()
                if (hours == '')
                    hours = '12';
                console.log('test', hours);
                showMessage('success', 'You Have Completed Your Votes, vote again in ' + hours + ' hours');
            });
        </script>
    @endif
    @stack('extraScript')
</body>

</html>
