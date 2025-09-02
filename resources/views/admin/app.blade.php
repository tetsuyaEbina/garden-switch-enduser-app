<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    @include('layouts.meta')
    @include('admin.admin-meta')
    <title>@yield('title', 'Garden Admin')</title>
</head>

<body class="bg-light" style="padding-top: 4.5rem;">
    <div id="app">
        @include('admin.admin-navbar')

        <main class="py-4">
            @yield('content')
        </main>
    </div>
    @stack('scripts')
</body>
</html>
