
@extends('layout.bootstrap')

<body>
    <div style="margin-left: auto; margin-right: auto; text-align: center">
        @yield('title')
    </div>

    @yield('content')

</body>

<style>
    body {
        margin: 25px !important;
    }
</style>
