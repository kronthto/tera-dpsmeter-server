<!DOCTYPE html>
<html lang="{{ config('app.locale') }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,minimum-scale=1,initial-scale=1">
    <meta name="language" content="{{ config('app.locale') }}">
    <title>TERA DPS Server</title>
    <!--[if IE]>
    <link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" crossorigin="anonymous">
    <![endif]-->
</head>
<body>
<div class="container">
    @yield('content')
</div>
<!--[if !IE]> -->
<link href="https://fonts.googleapis.com/css?family=Roboto" rel="stylesheet" crossorigin="anonymous">
<!-- <![endif]-->
</body>
</html>
