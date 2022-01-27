<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta charset="UTF-8">
    <meta name="description" content="">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title')</title>

    <!-- Favicon  -->
    <link rel="icon" href="favicon.ico">

    <!-- Core Style CSS -->
    <link rel="stylesheet" href="{{ asset('css/core-style.css') }}">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">

    <!-- Responsive CSS -->
    <link href="{{ asset('css/responsive.css') }}" rel="stylesheet">

</head>
<body>
<!-- Header Area Start -->
<header class="header-area">
    <div class="top-header">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <!-- Breaking News Area -->
                @isset($lastPosts)
                    @include('partials.blocks.public-header-breking-news', compact('lastPosts'))
                @endisset
            <!-- Stock News Area -->
                @include('partials.blocks.public-header-stocks')
            </div>
        </div>
    </div>
    <!-- Middle Header Area -->
    <div class="middle-header">
        <div class="container h-100">
            <div class="row h-100 align-items-center">
                <!-- Logo Area -->
                <div class="col-12 col-md-4">
                    <div class="logo-area">
                        <a href="{{ route('home') }}"><img src="img/logo.png" alt="logo"></a>
                    </div>
                </div>
                <!-- Header Advert Area -->
            </div>
        </div>
    </div>
</header>
<!-- Header Area End -->

<main class="py-4">
    @yield('content')
</main>

<!-- Footer Area Start -->
<footer class="footer-area bg-img background-overlay">

    <!-- Bottom Footer Area -->
    <div class="bottom-footer-area">
        <div class="container h-100">
            <div class="row h-100 align-items-center justify-content-center">
                <div class="col-12">
                    <div class="copywrite-text">
                        <p><!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                            &copy;<script>document.write(new Date().getFullYear());</script> @lang('main.all_rights_received') | @lang('main.template_made_with_by') <a href="https://colorlib.com" target="_blank">Colorlib</a>
                            <!-- Link back to Colorlib can't be removed. Template is licensed under CC BY 3.0. -->
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- Footer Area End -->

<!-- jQuery (Necessary for All JavaScript Plugins) -->
<script src="{{ asset('js/jquery/jquery-2.2.4.min.js') }}"></script>
<!-- Popper js -->
<script src="{{ asset('js/popper.min.js') }}"></script>
<!-- Bootstrap js -->
<script src="{{ asset('js/bootstrap.min.js') }}"></script>
<!-- Plugins js -->
<script src="{{ asset('js/plugins.js') }}"></script>
<!-- Active js -->
<script src="{{ asset('js/active.js') }}"></script>

</body>

</html>
