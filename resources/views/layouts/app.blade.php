<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <meta name="language" content="{{ config('app.locale') }}">
    <title>@yield('title', 'TERA DPS Server')</title>
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre.min.css" crossorigin="anonymous">
    <link rel="stylesheet" href="https://unpkg.com/spectre.css/dist/spectre-icons.min.css" crossorigin="anonymous">
    <link href="{{ asset('css/app.css?v=2018051300') }}" rel="stylesheet" type="text/css">
    <!--[if IE]>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" crossorigin="anonymous">
    <![endif]-->
    @yield('meta')
</head>
<body>
<main>
    @yield('content')
</main>
<footer role="contentinfo" style="margin-top: 20px; font-size: 80%;">
    <a href="https://github.com/kronthto/tera-dpsmeter-server" title="TERA DPS-Meter Server source code">source on GitHub</a>
</footer>
<!--[if !IE]> -->
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" crossorigin="anonymous">
<!-- <![endif]-->
</body>
</html>
