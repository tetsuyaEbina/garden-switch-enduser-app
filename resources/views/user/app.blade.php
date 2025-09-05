<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @include('layouts.meta')
    @include('user.user-meta')
    @stack('styles')
    <title>@yield('title', 'SwitchApp')</title>
</head>
<body class="bg-light">
  <div id="app">
    @include('user.user-navbar')

    <main class="app-main">
      @yield('content')
    </main>
  </div>
  @stack('scripts')
</body>
</html>
