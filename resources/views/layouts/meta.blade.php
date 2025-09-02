<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<!-- CSRF Token -->
<meta name="csrf-token" content="{{ csrf_token() }}">

<title>@yield('title', 'GardenMasterDataManageAPP')</title>

<!-- Bootstrap 5 CSS: CDN with fallback to local -->
<link rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      onerror="this.onerror=null;this.href='{{ asset('bootstrap/css/bootstrap.min.css') }}';">

<!-- Custom CSS -->
<link rel="stylesheet" href="{{ asset('bootstrap/css/sign-in.css') }}">

<!-- Bootstrap 5 JS: CDN with fallback to local -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        onerror="this.onerror=null;this.src='{{ asset('bootstrap/js/bootstrap.bundle.min.js') }}';" defer></script>

<!-- Bootstrap Icon CSS: CDN with fallback to local -->
<link rel="stylesheet" 
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css"
      onerror="this.onerror=null;this.href='{{ asset('bootstrap/css/bootstrap-icon.css') }}';">

<!-- FontAwesome: local only -->
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}">
