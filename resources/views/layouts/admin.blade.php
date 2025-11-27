<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
    <title>{{ $page_title . ' - ' . config('app.name', 'Event System') }}</title>

    <link rel="stylesheet" href="{{ asset('assets/vendor_components/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/bootstrap-extend.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor_components/zebra_datepicker/zebra_datepicker.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor_components/bootstrap-switch/switch.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/vendor_components/select2/dist/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/master_style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/_all-skins.css') }}">
    <link rel="stylesheet" href="{{ asset('css/admin/style.css') }}">

    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body class="hold-transition skin-black sidebar-mini">
    <div class="wrapper">
        <header class="main-header">
            <nav class="navbar navbar-static-top">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">Toggle navigation</span>
                </a>
            </nav>
        </header>

        <aside class="main-sidebar">
            <section class="sidebar">
                <div class="user-panel">
                    <div class="image">
                        <img src="{{ asset('/images/admin/user_icon.png') }}" class="rounded-circle" alt="admin">
                    </div>
                    <div class="info">
                        <p>admin</p>
                        <a href="{{ url('/auth/logout') }}" class="link" data-toggle="tooltip" title="" data-original-title="Выйти"><i class="ion ion-power"></i></a>
                    </div>
                </div>

                <ul class="sidebar-menu" data-widget="tree">
                    <li class="nav-devider"></li>
                    <li><a href="{!! url('admin') !!}"><i class="fa fa-home"></i> <span>Главная панель</span></a></li>
                    <li><a href="{!! url('admin/events') !!}"><i class="fa fa-star"></i> <span>Cобытия</span></a></li>
                    <li><a href="{!! url('admin/locations') !!}"><i class="fa fa-institution"></i> <span>Локации</span></a></li>
                    <li><a href="{!! url('admin/schemes') !!}"><i class="fa fa-image"></i> <span>Схемы площадок</span></a></li>
                    <li><a href="{!! url('admin/orders') !!}"><i class="fa fa-dollar"></i><span>Заказы</span></a></li>
                    <li><a href="{!! url('admin/participants') !!}"><i class="fa fa-music"></i><span>Участники</span></a></li>
                    <li><a href="{!! url('admin/setting') !!}"><i class="fa fa-cogs"></i><span>Настройки</span></a></li>
                </ul>
            </section>
        </aside>

        <div class="content-wrapper">
            <section class="content-header">
                @if (isset($page_title))
                    <h1>{{ $page_title }}</h1>
                @endif

                @if (!empty($breadcrumbs))
                    <ol class="breadcrumb">
                        @foreach($breadcrumbs as $breadcrumb)
                            <li class="breadcrumb-item{!! empty($breadcrumb['link']) ? ' active' : '' !!}">
                                @if (empty($breadcrumb['link']))
                                    {!! $breadcrumb['title'] !!}
                                @else
                                    <a href="{{ $breadcrumb['link'] }}">{!! $breadcrumb['title'] !!}</a>
                                @endif
                            </li>
                        @endforeach
                    </ol>
                @endif
            </section>

            <section class="content">
                @yield('content')
            </section>
        </div>
    </div>

    <script src="{{ asset('assets/vendor_components/jquery/dist/jquery.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/zebra_datepicker/zebra_datepicker.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/popper/dist/popper.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/bootstrap/dist/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/jquery-slimscroll/jquery.slimscroll.min.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/fastclick/lib/fastclick.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/select2/dist/js/select2.full.js') }}"></script>
    <script src="{{ asset('assets/vendor_components/select2/dist/js/i18n/ru.js') }}"></script>
    <script src="{{ asset('js/admin/template.js') }}"></script>
    <script src="{{ asset('js/admin/demo.js') }}"></script>
    <script type="text/javascript" src="{{ asset('assets/vendor_components/d3/d3.min.js') }}"></script>
    <script src="{{ asset('js/admin/script.js') }}"></script>

</body>
</html>
