<?php $version = 2022110803; ?>    
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="{{ asset('plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('widget/jquery.fancybox.min.css') }}" rel="stylesheet" type="text/css">
@stack('extraStyle')
<link href="{{ asset('widget/custom_css.css') }}?<?=$version?>" rel="stylesheet" type="text/css">
<div class="bg-transparent w-100 d-inline-flex">
    @yield('content')    
</div>
<script src="{{ asset('plugins/toast-master/js/jquery.toast.js') }}" type="text/javascript"></script>    
<script src="{{ asset('widget/jquery.lazyload.min.js') }}" type="text/javascript"></script>        
<script src="{{ asset('widget/jquery.fancybox.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('widget/custom_js.js') }}" type="text/javascript"></script>

<script src='https://www.google.com/recaptcha/api.js'></script>
@stack('extraScript')