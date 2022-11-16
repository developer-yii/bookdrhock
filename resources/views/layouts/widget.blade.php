<?php $version = 2022111601; ?>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="{{ asset('plugins/toast-master/css/jquery.toast.css') }}" rel="stylesheet" type="text/css">
<link href="{{ asset('widget/jquery.fancybox.min.css') }}" rel="stylesheet" type="text/css">
@stack('extraStyle')
<link href="{{ asset('widget/custom_css.css') }}?<?=$version?>" rel="stylesheet" type="text/css">
<script type="text/javascript">
    var $ = jQuery;
</script>
<div class="bg-transparent w-100 d-inline-flex">     
    <div id="preloader">
        <div id="status">
            <div class="spinner-chase">
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
                <div class="chase-dot"></div>
            </div>
        </div>
    </div>
    @yield('content')    
</div>
<script src="{{ asset('plugins/toast-master/js/jquery.toast.js') }}" type="text/javascript"></script>    
<script src="{{ asset('widget/jquery.lazyload.min.js') }}" type="text/javascript"></script>        
<script src="{{ asset('widget/jquery.fancybox.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('widget/moment.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('widget/custom_js.js') }}?<?=$version?>" type="text/javascript"></script>
<script type="text/javascript">
    var recaptcha_key = "<?=(env('GOOGLE_RECAPTCHA_KEY') != "") ? env('GOOGLE_RECAPTCHA_KEY') : ""?>";
    var recaptcha_1="";
    function onloadCallback() {
        if ( $('#g-recaptcha').length ) {    
            recaptcha_1 = grecaptcha.render('g-recaptcha', {
              'sitekey' : recaptcha_key
            });
        }
    }
</script>
<script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit"></script>
@stack('extraScript')