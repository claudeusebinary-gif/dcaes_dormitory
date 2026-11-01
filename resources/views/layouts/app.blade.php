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
            <link rel="stylesheet" href="{{ asset('css/payments.css') }}?v={{ filemtime(public_path('css/payments.css')) }}">
            <link rel="stylesheet" href="{{ asset('css/animations.css') }}?v={{ filemtime(public_path('css/animations.css')) }}">
             <link rel="stylesheet" href="{{ asset('css/announcement.css') }}?v={{ filemtime(public_path('css/announcement.css')) }}">
              <link rel="stylesheet" href="{{ asset('css/location.css') }}?v={{ filemtime(public_path('css/location.css')) }}">
               <link rel="stylesheet" href="{{ asset('css/settings.css') }}?v={{ filemtime(public_path('css/settings.css')) }}">
                <link rel="stylesheet" href="{{ asset('css/marketplace.css') }}?v={{ filemtime(public_path('css/marketplace.css')) }}">
                  <link rel="stylesheet" href="{{ asset('css/calendar.css') }}?v={{ filemtime(public_path('css/calendar.css')) }}">
                   <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ filemtime(public_path('css/dashboard.css')) }}">
                   <link rel="stylesheet" href="{{ asset('css/tenant.css') }}?v={{ filemtime(public_path('css/tenant.css')) }}">
                  
    @stack('styles')
</head>

<body>
    @include('partials.sidebar')
   @include('partials.logout-modal')
    <main class="main-content">
        <header class="topbar">
            <div class="topbar-text">
                <h1 class="topbar-title">@yield('page_title', trim($__env->yieldContent('title')))</h1>
                <p class="topbar-sub">@yield('page_subtitle')</p>
            </div>

            <div class="topbar-actions">
                @yield('page_action')

                               @if(auth()->user()->isAdmin())
                    @include('partials.admin-notification-bell')
                @else
                    @include('partials.notification-bell')
                @endif
            </div>
        </header>
        @unless (auth()->user()->isAdmin())
            @if ($pendingBooking = auth()->user()->pendingBooking())
                <div class="pending-banner" role="status">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="12" cy="12" r="9" /><path d="M12 7v5l3 2" /></svg>
                    <div>
                        <b>Waiting for admin approval</b>
                        <span>Room {{ $pendingBooking->room_number }}, Bed {{ $pendingBooking->bed_number }} ({{ $pendingBooking->bed_deck }}) is on hold for you. It will show up in your details once the admin approves it.</span>
                    </div>
                                    <script>
                    (function () {
                        var banner = document.querySelector('.pending-banner');
                        var main = document.querySelector('.main-content');
                        if (!banner || !main) return;
                        function set() { main.style.setProperty('--banner-h', (banner.offsetHeight + 16) + 'px'); }
                        set();
                        window.addEventListener('resize', set);
                        window.addEventListener('load', set);
                    })();
                </script>
                </div>
            @endif
        @endunless
        @yield('content')
    </main>
      @include('partials.chatbox')
    <script src="{{ asset('js/sidebar.js') }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
       <script src="{{ asset('js/logout-modal.js') }}"></script>
    @stack('scripts')
</body>

</html>
<script src="{{ asset('js/animations.js') }}"></script>
