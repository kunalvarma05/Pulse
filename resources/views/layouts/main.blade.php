<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="site-url" content="{{ url('/') }}" />
    <meta name="site-logo" content="{{ asset('images/logo-white.png') }}" />
    <title>Pulse - @yield('pageTitle', 'Multiple Clouds. One Platform.')</title>
    <link href='https://fonts.googleapis.com/css?family=Roboto:400,700,300' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    @yield('body')

    <script src="{{ asset('js/main.js') }}" type="text/javascript" charset="utf-8"></script>
</body>
</html>