@extends('layouts.app')

@section('title', 'Home')

@section('content')
@section('page_title', 'Home')
@section('page_subtitle', "Here's what's happening with your dormitory account.")
   <div class="pangbaabbaaba">
<div class="hero">
<div class="hero-text">
        <div class="now">
            <h1 class="tul">•</h1>
            <h1 class="nows">NOW ACCEPTING MOVE-INS FOR THIS TERM</h1>
        </div>


        <h1 class="nowsr">
            <span id="greetWord">Hello</span>, {{ \Illuminate\Support\Str::before(auth()->user()->display_name, ' ') }}
            @if ($reminder)
                <span class="due-chip {{ $reminder['tone'] }}">{{ $reminder['text'] }}</span>
            @endif
        </h1>

        <div class="texss">
            <h1 class="ts">A room, a bed,<br> and a place that <br> feels like <span class="red">yours.</span> </h1>
        </div>
        <div class="tsa">
            <h1 class="tsaa">D'aces Dormitory gives students and young professionals a safe,<br> affordable place to
                stay —
                with real people looking out for you, <br>not just a landlord collecting rent.
            </h1>
        </div>
        <div class="bosk">
            <a href="{{ route('tenant.rooms') }}" class="bosk1">Browse Available Rooms</a>
            <a href="{{ route('tenant.profile') }}" class="bosk12">View Profile</a>
        </div>
      </div> 

<div class="hero-art im">
    <img src="{{ asset('images/house.svg') }}" alt="" class="house">
    <img src="{{ asset('images/house.svg') }}" alt="" class="house1">
    <img src="{{ asset('images/house.svg') }}" alt="" class="house2">
    <img src="{{ asset('images/house.svg') }}" alt="" class="house3">
            <div class="badge-secure">
                <div class="badge-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#672C2C" stroke-width="2.5"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M5 12.5l4.5 4.5L19 7.5" />
                    </svg>
                </div>
                <div class="badge-text">
                    <strong>CCTV Secured</strong>
                    <span>24/7 monitoring</span>
                </div>
            </div>
            <div class="badge-secure badge-booked">
                <div class="badge-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#672C2C" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                        <path d="M13.7 21a2 2 0 0 1-3.4 0" />
                    </svg>
                </div>
                <div class="badge-text">
                    <strong>Room 101 booked</strong>
                    <span>2mins ago</span>
                </div>
            </div>
            <div class="badge-secure badge-payment">
                <div class="badge-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#672C2C" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round">
                        <path d="M13 2L4 14h7l-1 8 9-12h-7l1-8z" />
                    </svg>
                </div><div class="hero-art im">
    <img src="{{ asset('images/house.svg') }}" alt="" class="house">
    <img src="{{ asset('images/house.svg') }}" alt="" class="house1">
    <img src="{{ asset('images/house.svg') }}" alt="" class="house2">
    <img src="{{ asset('images/house.svg') }}" alt="" class="house3">s
                <div class="badge-text">
                    <strong>Fast payment</strong>
                    <span>Pay in seconds</span>
                </div>
            </div>
        </div>
       </div> 
<h1 class="ov">Overview</h1>
        <hr class="hw">
        <div class="threw">
            <div class="bxsa">
                <h1 class="numberf">{{ $stats['rooms'] }}</h1>
                <h1 class="bs">Total Rooms</h1>
            </div>
            <div class="bxsa">
                <h1 class="numberf">{{ $stats['bed_spaces'] }}</h1>
                <h1 class="bs">Total Bed Spaces</h1>
            </div>
            <div class="bxsa">
                <h1 class="numberf">{{ $stats['vacancies'] }}</h1>
                <h1 class="bs">Current Vacancies</h1>
            </div>
        </div>
        <h1 class="osas">Take your pick</h1>
        <h1 class="osass">A few of the rooms open right now.</h1>
        <h1 class="osasss">Real rooms, real availability — updated as soon as someone books or moves out.</h1>
        <hr class="hw1">
        @include('partials.featured-rooms', ['featured' => $featured])
        <div class="bgg">
            <h1 class="da">THE PROCESS</h1>
            <p class="prom">From browsing to move-in, in three </p>
            <p class="prom1">steps.</p>
            <div class="haba">
                <div class="lagayan">
                    <div class="bilug">
                        <h1 class="wan">1</h1>
                    </div>
                    <h1 class="brow">Browse Rooms</h1>
                    <p class="fil">Filter by floor, room type, and budget <br>
                        until you find the bed that fits your <br>
                        life.
                    </p>
                </div>
                <div class="lagayan">
                    <div class="bilug">
                        <h1 class="wan">2</h1>
                    </div>
                    <h1 class="brow">Book Online</h1>
                    <p class="fil">Reserve your spot and pay securely <br>
                        — get a confirmed receipt the <br>
                        moment it clears.

                    </p>
                </div>
                <div class="lagayan">
                    <div class="bilug">
                        <h1 class="wan">3</h1>
                    </div>
                    <h1 class="brow">Move In</h1>
                    <p class="fil">Bring your ID and receipt on move-in <br>
                        day. Your bed, your key, your new <br>
                        home.

                    </p>
                </div>

            </div>

        </div>
    </div>

    <h1 class="ov2">Overview</h1>
    <hr class="hw2">
    <div class="sixb">
        <div class="sixbs">
            <div class="liit">
                <svg viewBox="0 0 24 24">
                    <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                </svg>
            </div>
            <h1 class="mee">A Landlord Who Answers</h1>
            <p class="meas">Message the admin directly through the app for
                repairs, questions, or just to say the faucet's still
                dripping.</p>
        </div>

        <div class="sixbs">
            <div class="liit">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z" />
                </svg>
            </div>
            <h1 class="mee">CCTV Security</h1>
            <p class="meas">Monitored entrances and common areas, day and
                night, so you can focus on your day instead of
                watching your back.</p>
        </div>

        <div class="sixbs">
            <div class="liit">
                <svg viewBox="0 0 24 24">
                    <path
                        d="M4 14a1 1 0 0 1-.78-1.63l9.9-10.2a.5.5 0 0 1 .86.46l-1.92 6.02A1 1 0 0 0 13 10h7a1 1 0 0 1 .78 1.63l-9.9 10.2a.5.5 0 0 1-.86-.46l1.92-6.02A1 1 0 0 0 11 14z" />
                </svg>
            </div>
            <h1 class="mee">24/7 Water &amp; Electricity</h1>
            <p class="meas">Consistent utilities, all day, every day. We handle
                the outages before you even notice one.</p>
        </div>

        <div class="sixbs">
            <div class="liit">
                <svg viewBox="0 0 24 24">
                    <rect width="20" height="14" x="2" y="5" rx="2" />
                    <line x1="2" x2="22" y1="10" y2="10" />
                </svg>
            </div>
            <h1 class="mee">Online Payment</h1>
            <p class="meas">Pay your rent directly through the app via GCash,
                Maya, or card.</p>
        </div>

        <div class="sixbs">
            <div class="liit">
                <svg viewBox="0 0 24 24">
                    <path d="M12 7v14" />
                    <path
                        d="M3 18a1 1 0 0 1-1-1V4a1 1 0 0 1 1-1h5a4 4 0 0 1 4 4 4 4 0 0 1 4-4h5a1 1 0 0 1 1 1v13a1 1 0 0 1-1 1h-6a3 3 0 0 0-3 3 3 3 0 0 0-3-3z" />
                </svg>
            </div>
            <h1 class="mee">Visitor Logbook</h1>
            <p class="meas">Register guests digitally so front desk approval
                is instant.</p>
        </div>

        <div class="sixbs">
            <div class="liit">
                <svg viewBox="0 0 24 24">
                    <rect width="14" height="20" x="5" y="2" rx="2" ry="2" />
                    <path d="M12 18h.01" />
                </svg>
            </div>
            <h1 class="mee">Mobile App</h1>
            <p class="meas">Manage bookings and payments on the go with
                our upcoming app</p>
        </div>
    </div>
    @php
        $frontDesk = \App\Support\DormStats::frontDesk();
        $dowMap = ['Sunday' => 0, 'Monday' => 1, 'Tuesday' => 2, 'Wednesday' => 3, 'Thursday' => 4, 'Friday' => 5, 'Saturday' => 6];
       @endphp
    <div class="home-hrs-section">
        <h1 class="ov2">Open Hours</h1>
        <hr class="hw2">
        <p class="home-hrs-subtitle">Visit our front desk for inquiries, payments, and room viewings.</p>

        <div class="home-hrs-card">
            <div class="home-hrs-top">
                <div class="home-hrs-clock">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="9"></circle>
                        <path d="M12 7v5l3 2"></path>
                    </svg>
                </div>
                <div>
                    <p class="home-hrs-label">Front Desk</p>
                    <p class="home-hrs-status" id="homeHrsStatus">Checking…</p>
                </div>
                <span class="home-hrs-pill" id="homeHrsPill" hidden></span>
            </div>

            <ul class="home-hrs-list">
                @foreach ($frontDesk as $day => $h)
                    <li class="home-hrs-row{{ $h['open'] ? '' : ' closed' }}" data-dow="{{ $dowMap[$day] }}"
                        data-open="{{ $h['open'] ? 1 : 0 }}" data-from="{{ $h['from'] }}" data-to="{{ $h['to'] }}">
                        <span class="home-hrs-day">{{ $day }}</span>
                        <span class="home-hrs-time">
                            @if ($h['open'])
                                {{ \App\Support\DormStats::formatTime($h['from']) }} –
                                {{ \App\Support\DormStats::formatTime($h['to']) }}
                            @else
                                Closed
                            @endif
                        </span>
                    </li>
                @endforeach
            </ul>
        </div>
    </div>

    <div class="wiabut">
        <input type="button" value="Book Now" class="bababut" onclick="window.location.href='{{ route('tenant.rooms') }}'">
        <input type="button" value="View Rooms" class="bababut1"
            onclick="window.location.href='{{ route('tenant.rooms') }}'">
    </div>

    @include('partials.footer')

    @push('scripts')
        <script>
            (function () {
                var rows = [].slice.call(document.querySelectorAll('.home-hrs-row'));
                if (!rows.length) return;

                var names = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
                var now = new Date(), dow = now.getDay(), mins = now.getHours() * 60 + now.getMinutes();
                var byDow = {};
                rows.forEach(function (r) { byDow[r.dataset.dow] = r; });

                function toMin(t) { var p = t.split(':'); return (+p[0]) * 60 + (+p[1]); }
                function fmt(t) { var p = t.split(':'), h = +p[0], ap = h >= 12 ? 'PM' : 'AM'; h = h % 12 || 12; return h + ':' + p[1] + ' ' + ap; }

                var today = byDow[dow], status = '', open = false;
                if (today) today.classList.add('today');

                if (today && today.dataset.open === '1') {
                    var f = toMin(today.dataset.from), t = toMin(today.dataset.to);
                    if (mins >= f && mins < t) { open = true; status = 'Open now · closes at ' + fmt(today.dataset.to); }
                    else if (mins < f) { status = 'Opens today at ' + fmt(today.dataset.from); }
                }
                if (!status) {
                    for (var i = 1; i <= 7; i++) {
                        var d = (dow + i) % 7, r = byDow[d];
                        if (r && r.dataset.open === '1') {
                            status = 'Closed now · opens ' + (i === 1 ? 'tomorrow' : names[d]) + ' at ' + fmt(r.dataset.from);
                            break;
                        }
                    }
                }
                document.getElementById('homeHrsStatus').textContent = status || 'Currently closed';
                var pill = document.getElementById('homeHrsPill');
                pill.hidden = false;
                pill.textContent = open ? 'Open now' : 'Closed';
                pill.classList.toggle('is-open', open);
            })();
        </script>
        <script>
            (function () {
                var els = [].slice.call(document.querySelectorAll('.threw .numberf'));
                if (!els.length) return;

                var reduce = window.matchMedia && matchMedia('(prefers-reduced-motion: reduce)').matches;
                if (reduce) return;


                els.forEach(function (el) {
                    var n = parseInt(el.textContent.replace(/[^0-9]/g, ''), 10);
                    if (isNaN(n)) return;
                    el.dataset.target = n;
                    el.textContent = '0';
                });

                function run(el) {
                    var target = +el.dataset.target;
                    if (isNaN(target)) return;
                    var dur = 1400, start = null;
                    function step(ts) {
                        if (start === null) start = ts;
                        var p = Math.min((ts - start) / dur, 1);
                        var eased = 1 - Math.pow(1 - p, 3);
                        el.textContent = Math.round(target * eased);
                        if (p < 1) requestAnimationFrame(step);
                        else el.textContent = target;
                    }
                    requestAnimationFrame(step);
                }

                if ('IntersectionObserver' in window) {
                    var io = new IntersectionObserver(function (entries) {
                        entries.forEach(function (e) {
                            if (e.isIntersecting) { run(e.target); io.unobserve(e.target); }
                        });
                    }, { threshold: 0.4 });
                    els.forEach(function (el) { io.observe(el); });
                } else {
                    els.forEach(run);
                }
            })();
        </script>
    @endpush
    @push('scripts')
        <script>
            (function () {
                var h = new Date().getHours();
                var w = h < 12 ? 'Good morning' : (h < 18 ? 'Good afternoon' : 'Good evening');
                var el = document.getElementById('greetWord');
                if (el) el.textContent = w;
            })();
        </script>
    @endpush
    @if (!auth()->user()->room_number && !auth()->user()->isGuest())
        <style>
            .ob-overlay {
                position: fixed;
                inset: 0;
                z-index: 9999;
                display: flex;
                align-items: center;
                justify-content: center;
                background: rgba(0, 0, 0, .5);
                padding: 20px;
            }

            .ob-overlay[hidden] {
                display: none;
            }

            .ob-box {
                position: relative;
                width: 100%;
                max-width: 420px;
                text-align: center;
                background: #fff;
                border-radius: 0 20px 20px 20px;
                padding: 40px 30px 26px;
                box-shadow: 0 20px 60px rgba(0, 0, 0, .28);
                font-family: 'Nunito Sans', sans-serif;
                overflow: hidden;
                animation: obPop .25s ease-out;
            }

            .ob-box::before {
                content: "";
                position: absolute;
                top: 0;
                left: 0;
                width: 46px;
                height: 46px;
                background: #672c2c;
                clip-path: polygon(0 0, 100% 0, 0 100%);
            }

            .ob-skip {
                position: absolute;
                top: 14px;
                right: 18px;
                background: none;
                border: 0;
                font: 700 .85rem 'Nunito Sans', sans-serif;
                color: #0000009a;
                cursor: pointer;
            }

            .ob-skip:hover {
                color: #672c2c;
            }

            .ob-num {
                width: 54px;
                height: 54px;
                border-radius: 50%;
                margin: 0 auto 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: #672c2c1f;
                color: #672c2c;
                font-size: 1.4rem;
                font-weight: 800;
            }

            .ob-step {
                margin: 0 0 4px;
                font-size: .75rem;
                font-weight: 800;
                letter-spacing: .08em;
                color: #672c2c99;
            }

            .ob-box h2 {
                margin: 0 0 8px;
                font-size: 1.2rem;
                font-weight: 800;
                color: #000;
            }

            .ob-box p {
                margin: 0 0 18px;
                font-size: .92rem;
                font-weight: 600;
                color: #0000009a;
                line-height: 1.55;
                min-height: 70px;
            }

            .ob-dots {
                display: flex;
                justify-content: center;
                gap: 7px;
                margin-bottom: 20px;
            }

            .ob-dots i {
                width: 8px;
                height: 8px;
                border-radius: 50%;
                background: #672c2c26;
                transition: all .2s ease;
            }

            .ob-dots i.on {
                width: 22px;
                border-radius: 9999px;
                background: #672c2c;
            }

            .ob-actions {
                display: flex;
                gap: 10px;
            }

            .ob-actions button {
                flex: 1;
                height: 46px;
                border-radius: 9999px;
                cursor: pointer;
                font: 700 .95rem 'Nunito Sans', sans-serif;
                border: 1px solid #672c2c;
            }

            .ob-back {
                background: transparent;
                color: #672c2c;
            }

            .ob-back:hover {
                background: #672c2c14;
            }

            .ob-back[hidden] {
                display: none;
            }

            .ob-next {
                background: #672c2c;
                color: #fff;
            }

            .ob-next:hover {
                background: transparent;
                color: #672c2c;
            }
       .ob-go {
        display: flex; align-items: center; justify-content: center;
        box-sizing: border-box; width: 100%; height: 46px; margin: 0 0 10px;
        border-radius: 9999px; border: 1px solid #672c2c;
        background: #672c2c1a; color: #672c2c;
        font: 800 .95rem/1 'Nunito Sans', sans-serif;
        text-decoration: none; cursor: pointer; transition: all .15s ease;
    }
    .ob-go:hover { background: #672c2c; color: #fff; }
    .ob-go[hidden] { display: none; }
            @keyframes obPop {
                from {
                    transform: scale(.94);
                    opacity: 0;
                }

                to {
                    transform: scale(1);
                    opacity: 1;
                }
            }
        </style>

        <div class="ob-overlay" id="obModal" hidden>
            <div class="ob-box" role="dialog" aria-modal="true" aria-labelledby="obTitle">
                <button type="button" class="ob-skip" id="obSkip">Skip</button>
                <div class="ob-num" id="obNum">1</div>
                <p class="ob-step" id="obStep"></p>
                <h2 id="obTitle"></h2>
                <p id="obText"></p>
                <div class="ob-dots" id="obDots"></div>
                        <a class="ob-go" id="obGo" href="#" hidden></a>
                <div class="ob-actions">
                    <button type="button" class="ob-back" id="obBack">Back</button>
                    <button type="button" class="ob-next" id="obNext">Next</button>
                </div>
            </div>
        </div>

        <script>
    (function () {
        var uid = '{{ auth()->id() }}';
        var KEY = 'onboard_seen_' + uid;
        var STEP_KEY = 'onboard_step_' + uid;
        try { if (localStorage.getItem(KEY)) return; } catch (e) {}

        var steps = [
            { t: "Welcome to D'aces!", p: "Here's a quick guide to getting your bed, from browsing rooms to paying your first rent. It only takes a minute." },
            { t: "Browse the rooms", p: "Open Rooms to see every room. Green beds are available, and amber or red beds are booked or taken. Tap View room details to see photos, price and amenities.", url: @json(route('tenant.rooms')), go: "Go to Rooms" },
            { t: "Book your bed", p: "Pick a bed and a deck, fill in your details, add one valid ID with its photo, and agree to the house rules. Your bed is held for you right away.", url: @json(route('tenant.rooms')), go: "Book a bed" },
            { t: "Wait for admin approval", p: "The admin checks your application. You'll get a notification in the bell when you're approved or declined. While you wait, keep your Profile up to date.", url: @json(route('tenant.profile')), go: "Open my Profile" },
            { t: "Pay rent and stay updated", p: "Once approved, use Payments to send your rent. The admin confirms it and your invoice turns paid.", url: @json(route('tenant.payments')), go: "Go to Payments" }
        ];

        var i = 0;
        try { i = Math.min(Math.max(parseInt(localStorage.getItem(STEP_KEY), 10) || 0, 0), steps.length - 1); } catch (e) {}

        var modal = document.getElementById('obModal');
        var num   = document.getElementById('obNum');
        var stepE = document.getElementById('obStep');
        var title = document.getElementById('obTitle');
        var text  = document.getElementById('obText');
        var dots  = document.getElementById('obDots');
        var back  = document.getElementById('obBack');
        var next  = document.getElementById('obNext');
        var go    = document.getElementById('obGo');

        steps.forEach(function () { dots.appendChild(document.createElement('i')); });

        function save(n) { try { localStorage.setItem(STEP_KEY, String(n)); } catch (e) {} }

        function render() {
            num.textContent = i + 1;
            stepE.textContent = 'STEP ' + (i + 1) + ' OF ' + steps.length;
            title.textContent = steps[i].t;
            text.textContent = steps[i].p;
            Array.prototype.forEach.call(dots.children, function (d, n) { d.className = n === i ? 'on' : ''; });
            back.hidden = i === 0;
            next.textContent = i === steps.length - 1 ? 'Done' : 'Next';
            if (steps[i].url) {
                go.hidden = false;
                go.textContent = steps[i].go + ' →';
                go.href = steps[i].url;
            } else {
                go.hidden = true;
            }
        }

        function finish() {
            try { localStorage.setItem(KEY, '1'); localStorage.removeItem(STEP_KEY); } catch (e) {}
            modal.hidden = true;
        }

        go.addEventListener('click', function () { save(Math.min(i + 1, steps.length - 1)); });
        next.addEventListener('click', function () {
            if (i === steps.length - 1) { finish(); return; }
            i++; save(i); render();
        });
        back.addEventListener('click', function () { if (i > 0) { i--; save(i); render(); } });
        document.getElementById('obSkip').addEventListener('click', finish);
        document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) finish(); });

        render();
        modal.hidden = false;
    })();
</script>
    @endif
@endsection