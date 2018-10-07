<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Тестовое задание')</title>

    <link rel="stylesheet" href="{!! mix('build/css/app.css') !!}">
</head>
<body>
    @include('partials._navbar')

    @yield('body')

    <script type="text/javascript" src="{!! mix('build/js/app.js') !!}"></script>
</body>