<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', "D'aces Dormitory")</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
    href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@300;400;700&family=Inter:wght@400;600&family=Nunito+Sans:wght@200;300;400;600;700;800;900&display=swap"
    rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}?v={{ filemtime(public_path('css/sidebar.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(public_path('css/home.css')) }}">
     <link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ filemtime(public_path('css/profile.css')) }}">
      <link rel="stylesheet" href="{{ asset('css/dorm.css') }}?v={{ filemtime(public_path('css/dorm.css')) }}">
         <link rel="stylesheet" href="{{ asset('css/logout.css') }}?v={{ filemtime(public_path('css/logout.css')) }}">
           <link rel="stylesheet" href="{{ asset('css/rooms.css') }}?v={{ filemtime(public_path('css/rooms.css')) }}">
    @stack('styles')
</head>

<body>
    @include('partials.sidebar')
   @include('partials.logout-modal')
    <main class="main-content">
        @include('partials.notification-bell')

        @yield('content')
    </main>

    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
       <script src="{{ asset('js/logout-modal.js') }}"></script>
    @stack('scripts')
</body>

</html>
