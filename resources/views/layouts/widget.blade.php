<?php $version = 20221130; ?>
<meta name="robots" content="index"/>
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta http-equiv="Cache-Control" content="no-cache, no-store, must-revalidate"/>
<meta http-equiv="Pragma" content="no-cache"/>
<meta http-equiv="Expires" content="0"/>

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

<link rel='preload' as='style' href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">
<link href="{{ asset('widget/all_min.css') }}?<?=$version?>" rel="stylesheet" type="text/css">
@stack('extraStyle')
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
<script src="{{ asset('widget/all_min_js.js') }}?<?=$version?>" type="text/javascript"></script>
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