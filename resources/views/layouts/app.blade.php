<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>UFC Fight Night Moscow</title>
    <meta name="description" content="UFC Fight Night Moscow — tickets Sports in Moscow">

    <meta property="og:title" content="UFC Fight Night Moscow">
    <meta property="og:description" content="UFC Fight Night Moscow — tickets Sports in Moscow">
    <meta property="og:image" content="{{ url('/images/image-meta.jpg') }}">
    <meta property="og:image:width" content="400">
    <meta property="og:image:height" content="400">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ url('') }}">

    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="UFC Fight Night Moscow">
    <meta name="twitter:description" content="UFC Fight Night Moscow — tickets Sports in Moscow">
    <meta name="twitter:image" content="{{ url('/images/image-meta.jpg') }}">

    <script language = "JavaScript">
        function preloader() {
            heavyImage = new Image();
            heavyImage.src = "images/Ripple-1.7s-150px.gif";
        }
    </script>
    <link rel="shortcut icon" href="/images/favicon.ico" type="image/x-icon">
    <style>
        html, body {
            overflow: hidden;
            width: 100vw;
            height: 100vh;
        }
        html.hide-spiner,
        html.hide-spiner body {
            overflow: auto;
            width: auto;
            height: auto;
        }
        html.hide-spiner body:before{
            opacity: 0;
            z-index: -1;
            transition: opacity .2s, z-index 0s .2s;
        }
        html.hide-spiner body .preloading,
        html.hide-spiner body .preloading * {
            opacity: 0;
            z-index: -1!important;
            transition: opacity .2s, z-index 0s .2s;
        }
        body:before{
            content:'';
            position: fixed;
            width: 100%;
            height: 100%;
            background: #f7f7f7;
            top: 0;
            left: 0;
            z-index: 15;
            transition: opacity .2s, z-index 0s 0s;
        }
    </style>
    <link rel="stylesheet" href="{{ asset('css/app.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor_components/fullpage.js/dist/fullpage.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor_components/fancybox/dist/jquery.fancybox.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor_components/swiper/dist/css/swiper.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/main.css') }}">
</head>
<body onLoad="javascript:preloader()">
    <div class="preloading" style="position: fixed; width: 100vw; height: 100vh; z-index: 99;">
        <img src="images/Ripple-1.7s-150px.gif" class="load" style="content:''; width: 150px; height: 150px; position: fixed; top: 50%; left: 50%; z-index: 16; transform: translate(-50%, -50%);">
    </div>

    @yield('content')

    <div style="display: none;" id="fancybox-modal" class="modal-message">
        <div class="title"></div>
        <p class="message">Do you really want to finish purchasing tickets?</p>
        <div class="buttons"></div>
    </div>
    <script type="text/javascript" src="{{ asset('assets/vendor_components/jquery/dist/jquery.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor_components/fullpage.js/dist/fullpage.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor_components/d3/d3.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor_components/inputmask/dist/jquery.inputmask.bundle.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor_components/nicescroll/jquery.nicescroll.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor_components/fancybox/dist/jquery.fancybox.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor_components/swiper/dist/js/swiper.min.js') }}"></script>
    <script type="text/javascript" src="{{ asset('js/main.js') }}"></script>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDsiAqp-ZEVgUwVjsbvrGFzNvkNGTf_IQY&libraries=places&callback=initMap&language=en"></script>
</body>
</html>
