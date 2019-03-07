<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>SISTEMA TALLER</title>

        <!-- Styles -->
        <link href="{{ asset('css/bootstrap.min.css') }}" rel="stylesheet">
        <link href="{{ asset('css/font-awesome.min.css') }}" rel="stylesheet">

        <link rel="shortcut icon" href="{{ asset('img/favicon/favi.ico') }}" type="image/x-icon">
        <link rel="icon" href="{{ asset('img/favicon/favi.ico') }}" type="image/x-icon">

        <link rel="icon" type="image/png" href="{{ asset('img/bus-home.png') }}" />

    </head>
    <body class="hold-transition login-page" onload="dontBack();" style="padding-top: 170px;">
        <div class="panel panel-default col-md-6 col-lg-6 col-md-offset-3" >

            <div class="panel-body">
                @yield('content')
            </div>

        </div>
        <!-- Scripts -->
        <script src="{{ asset('js/libs/jquery-2.1.1.min.js') }}"></script>
        <script src="{{ asset('js/bootstrap/bootstrap.min.js') }}"></script>
        <script type="text/javascript">
        function dontBack() {
            window.location.hash = "";
            window.location.hash = "Again-No-back-button" //chrome
            window.onhashchange = function () {
                window.location.hash = "";
            }
        }
        ;
        </script>
    </body>
</html>