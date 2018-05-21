<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }} @yield('title')</title>

    <!-- Styles -->
        <!--Bootstrap -->


    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="//cdn.materialdesignicons.com/2.0.46/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="{{ asset('css/fontawesome-all.css') }}">


    @yield('stylesheets')
</head>
<body>
<img src={{ asset('images/errors/screenshots/logo.png') }} alt="">

        @include('partials._navbar')
        <div id="app-2">
            @include('partials._messages')
            {!! Toaster::toast() !!}
        </div>


        <div class="columns">
            <div class="column is-one-fifth left-panel">
                @yield('panel-left')
            </div>
            <div class="column page-content-middle">
                @yield('content')
            </div>
            <div class="column is-one-fifth right-panel" style="padding: 20px">
                @yield('panel-right')
            </div>

        </div>
</body>
<!-- Scripts -->
<script src="{{ asset('js/app.js') }}"></script>
@yield('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {

        // Get all "navbar-burger" elements
        var $navbarBurgers = Array.prototype.slice.call(document.querySelectorAll('.navbar-burger'), 0);

        // Check if there are any navbar burgers
        if ($navbarBurgers.length > 0) {

            // Add a click event on each of them
            $navbarBurgers.forEach(function ($el) {
                $el.addEventListener('click', function () {

                    // Get the target from the "data-target" attribute
                    var target = $el.dataset.target;
                    var $target = document.getElementById(target);

                    // Toggle the class on both the "navbar-burger" and the "navbar-menu"
                    $el.classList.toggle('is-active');
                    $target.classList.toggle('is-active');

                });
            });
        }

    });

</script>

</html>
