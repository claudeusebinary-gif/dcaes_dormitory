@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <h1 class="tit">Home</h1>
    <h1 class="ba">Here's what's happening with your dormitory account.</h1>
    <div class="now">
        <h1 class="tul">•</h1>
        <h1 class="nows">NOW ACCEPTING MOVE-INS FOR THIS TERM</h1>
    </div>
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
    <div class="im"><img src="{{ asset('images/house.svg') }}" alt="" class="house">
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
            </div>
            <div class="badge-text">
                <strong>Fast payment</strong>
                <span>Pay in seconds</span>
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
    <div class="boxes">
        <div class="box1">
            <h1 class="roomp">Room 101</h1>&nbsp;<h1 class="roomp1">— Good For 6</h1>
            <h1 class="roomp2">Double Bed For 2</h1>
            <h1 class="roomp3">₱2,500 / month</h1>
        </div>
        <div class="box2">
            <h1 class="roomp">Room 111</h1>&nbsp;<h1 class="roomp1">— Good For 6</h1>
            <h1 class="roomp22">Double Bed For 2</h1>
            <h1 class="roomp3">₱2,500 / month</h1>
        </div>
    </div>
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
    <div class="wiabut"><input type="button" value="Book Now" class="bababut" href="#"><input type="button" value="View Rooms"
            class="bababut1"></div>
    
 @include('partials.footer')
@endsection