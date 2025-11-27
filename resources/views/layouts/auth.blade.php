<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ $document_title }}</title>
    <link rel="stylesheet" href="{{ asset('assets/vendor_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/bootstrap-extend.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/master_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/_all-skins.css') }}">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

</head>
<body class="hold-transition login-page">
    @yield('content')
    <script src="{{ asset('assets/vendor_components/jquery/dist/jquery.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/popper/dist/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
</body>
</html>