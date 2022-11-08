<?php $version = 20221108; ?>
<!DOCTYPE html>
<html lang="en">
<head>    
    <title>{{ config('app.name', 'Fandomz') }}</title>    
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/bootstrap-extension/css/bootstrap-extension.css') }}" rel="stylesheet"
        type="text/css">    
    <link href="{{ asset('plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet" type="text/css">    
    <link href="{{ asset('assets/css/animate.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('plugins/sweetalert/sweetalert.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('widget/jquery.fancybox.min.css') }}" rel="stylesheet" type="text/css">
    @stack('extraStyle')
    <link href="{{ asset('assets/css/style.css') }}" rel="stylesheet" type="text/css">    
    <link href="{{ asset('assets/css/custom.css') }}?<?=$version?>" rel="stylesheet" type="text/css">
</head>

<body class="bg-transparent w-100 d-inline-flex">
    @yield('content')    
    <script src="{{ asset('plugins/jquery/dist/jquery.min.js') }}" type="text/javascript"></script>    
    <script src="{{ asset('assets/js/popper.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('assets/js/bootstrap.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('plugins/bootstrap-extension/js/bootstrap-extension.min.js') }}" type="text/javascript"></script>    
    <script src="{{ asset('plugins/sidebar-nav/dist/sidebar-nav.min.js') }}" type="text/javascript"></script>    
    <script src="{{ asset('assets/js/jquery.slimscroll.js') }}" type="text/javascript"></script>    
    {{-- <script src="{{ asset('assets/js/custom.min.js') }}" type="text/javascript"></script>     --}}
    <script src="{{ asset('plugins/toast-master/js/jquery.toast.js') }}" type="text/javascript"></script>    
    <script src="{{ asset('widget/jquery.lazyload.min.js') }}" type="text/javascript"></script>        
    <script src="{{ asset('assets/js/main.js') }}?<?=$version?>" type="text/javascript"></script>
    <script src="{{ asset('plugins/sweetalert/sweetalert.min.js') }}" type="text/javascript"></script>
    <script src="{{ asset('widget/jquery.fancybox.min.js') }}" type="text/javascript"></script>
    
    <script src='https://www.google.com/recaptcha/api.js'></script>
    
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