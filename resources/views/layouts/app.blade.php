<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', $dorm['name'])</title>
<link rel="icon" type="image/svg+xml" href="{{ asset('images/favicon-house.svg') }}?v=2">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inria+Serif:wght@300;400;700&family=Inter:wght@400;600&family=Nunito+Sans:wght@200;300;400;600;700;800;900&family=Playfair+Display:wght@900&display=swap"
        rel="stylesheet">
   
    <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}?v={{ filemtime(public_path('css/sidebar.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}?v={{ filemtime(public_path('css/home.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/profile.css') }}?v={{ filemtime(public_path('css/profile.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/dorm.css') }}?v={{ filemtime(public_path('css/dorm.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/logout.css') }}?v={{ filemtime(public_path('css/logout.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/rooms.css') }}?v={{ filemtime(public_path('css/rooms.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/payments.css') }}?v={{ filemtime(public_path('css/payments.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/animations.css') }}?v={{ filemtime(public_path('css/animations.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/announcement.css') }}?v={{ filemtime(public_path('css/announcement.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/location.css') }}?v={{ filemtime(public_path('css/location.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/settings.css') }}?v={{ filemtime(public_path('css/settings.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/marketplace.css') }}?v={{ filemtime(public_path('css/marketplace.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/calendar.css') }}?v={{ filemtime(public_path('css/calendar.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}?v={{ filemtime(public_path('css/dashboard.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/tenant.css') }}?v={{ filemtime(public_path('css/tenant.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/paymentsadmin.css') }}?v={{ filemtime(public_path('css/paymentsadmin.css')) }}">
    <link rel="stylesheet"
        href="{{ asset('css/complaints.css') }}?v={{ filemtime(public_path('css/complaints.css')) }}">
    <link rel="stylesheet" href="{{ asset('css/approvals.css') }}?v={{ filemtime(public_path('css/approvals.css')) }}">


    @stack('styles')
</head>

<body>
    <script>
        (function () {
            var b = document.body;
            try {
                if (!window.matchMedia('(max-width: 768px)').matches && localStorage.getItem('sbCollapsed') === '1') {
                    b.classList.add('sb-collapsed');
                }
            } catch (e) { }
            
            b.classList.add('sb-no-anim');
            window.addEventListener('load', function () {
                requestAnimationFrame(function () {
                    requestAnimationFrame(function () { b.classList.remove('sb-no-anim'); });
                });
            });
        })();
    </script>
    <div class="page-curtain" id="pageCurtain" aria-hidden="true">
        <div class="pc-label" id="pcLabel">Loading</div>
        <div class="pc-bar"><i></i></div>
    </div>
    <script>
        (function () {
            var curtain = document.getElementById('pageCurtain');
            var label = document.getElementById('pcLabel');
            var SLIDE_MS = 300;  
            var MIN_SHOW = 150;  
            var KEY = 'pageCurtain';
            var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            var busy = false;

            var saved = null;
            try { saved = sessionStorage.getItem(KEY); sessionStorage.removeItem(KEY); } catch (e) { }
            var parts = saved ? saved.split('|') : [];
            if (!reduce && parts.length >= 2 && Date.now() - Number(parts[0]) < 8000) {
                var start = Date.now();
                var lifted = false;
                label.textContent = parts.slice(1).join('|');
                curtain.classList.add('covered');

                var lift = function () {
                    if (lifted) return;
                    lifted = true;
                    curtain.classList.remove('covered');
                    curtain.classList.add('out');
                    setTimeout(function () { curtain.classList.remove('out'); }, SLIDE_MS);
                };
                var scheduleLift = function () {
                    setTimeout(lift, Math.max(0, MIN_SHOW - (Date.now() - start)));
                };

                if (document.readyState === 'complete') scheduleLift();
                else window.addEventListener('load', scheduleLift);
                setTimeout(lift, 6000); // safety: never get stuck
            }

            document.addEventListener('click', function (e) {
                if (reduce || busy || e.defaultPrevented) return;
                if (e.button !== 0 || e.metaKey || e.ctrlKey || e.shiftKey || e.altKey) return;

              var a = e.target.closest('a[href]');
                if (!a || a.id === 'logoutLink') return;

                var href = a.getAttribute('href');
               if (!href || href.charAt(0) === '#' || a.target === '_blank') return;
if (a.hasAttribute('download') || a.hasAttribute('data-no-curtain') || /^(mailto:|tel:|javascript:)/i.test(href)) return;

                var url = new URL(a.href, location.href);
                if (url.origin !== location.origin) return;
                if (url.pathname === location.pathname && url.search === location.search) return; // already here

                e.preventDefault();
                busy = true;

              var inSidebar = a.matches('.sb-link, .sb-subitem');
var span = a.querySelector('.sb-label, span');
var name = (a.dataset.curtain || (inSidebar ? (span ? span.textContent : a.textContent) : 'Loading')).trim();
                label.textContent = name;
                try { sessionStorage.setItem(KEY, Date.now() + '|' + name); } catch (err) { }

                curtain.classList.remove('out', 'covered');
                curtain.classList.add('in');
            setTimeout(function () { window.location.href = a.href; }, 160);
            }, true);
window.curtainGo = function (url, name) {
    name = name || 'Loading';
    if (reduce || busy) { window.location.href = url; return; }
    busy = true;
    label.textContent = name;
    try { sessionStorage.setItem(KEY, Date.now() + '|' + name); } catch (err) { }
    curtain.classList.remove('out', 'covered');
    curtain.classList.add('in');
    setTimeout(function () { window.location.href = url; }, 160);
};
            window.addEventListener('pageshow', function (e) {
                if (e.persisted) {
                    busy = false;
                    curtain.classList.remove('in', 'out', 'covered');
                }
            });
        })();
    </script>
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
                @elseif (!auth()->user()->isGuest())
                    @include('partials.notification-bell')
                @endif
            </div>
        </header>
        @unless (auth()->user()->isAdmin() || auth()->user()->isGuest())
            @if ($pendingBooking = auth()->user()->pendingBooking())
                <div class="pending-banner" role="status">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9" />
                        <path d="M12 7v5l3 2" />
                    </svg>
                    <div>
                        <b>Waiting for admin approval</b>
                        <span>Room {{ $pendingBooking->room_number }}, Bed {{ $pendingBooking->bed_number }}
                            ({{ $pendingBooking->bed_deck }}) is on hold for you. It will show up in your details once the admin
                            approves it.</span>
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
    @unless (auth()->user()->isGuest())
        @include('partials.chatbox')
    @endunless
<script src="{{ asset('js/sidebar.js') }}?v={{ filemtime(public_path('js/sidebar.js')) }}"></script>
    <script src="{{ asset('js/notifications.js') }}"></script>
    <script src="{{ asset('js/logout-modal.js') }}"></script>
    <script>
(function () {
    document.querySelectorAll('.photo-drop').forEach(function (box) {
        var input  = box.querySelector('.photo-drop-input');
        var area   = box.querySelector('.photo-drop-area');
        var img    = box.querySelector('.photo-preview img');
        var nameEl = box.querySelector('.photo-preview-name');
        var sizeEl = box.querySelector('.photo-preview-size');
        var url = null;

        function refresh() {
            if (url) { URL.revokeObjectURL(url); url = null; }
            var f = input.files && input.files[0];
            box.classList.toggle('has-file', !!f);
            if (!f) { img.removeAttribute('src'); return; }
            url = URL.createObjectURL(f);
            img.src = url;
            nameEl.textContent = f.name;
            sizeEl.textContent = (f.size / 1048576).toFixed(2) + ' MB';
        }

        input.addEventListener('change', refresh);
        box.querySelector('.photo-remove').addEventListener('click', function () {
            input.value = '';
            refresh();
        });

        ['dragenter', 'dragover'].forEach(function (ev) {
            area.addEventListener(ev, function (e) { e.preventDefault(); area.classList.add('is-drag'); });
        });
        ['dragleave', 'drop'].forEach(function (ev) {
            area.addEventListener(ev, function () { area.classList.remove('is-drag'); });
        });
        area.addEventListener('drop', function (e) {
            e.preventDefault();
            if (e.dataTransfer.files.length) { input.files = e.dataTransfer.files; refresh(); }
        });
    });
})();
</script>
    @stack('scripts')
</body>

</html>
<script src="{{ asset('js/animations.js') }}?v={{ filemtime(public_path('js/animations.js')) }}"></script>