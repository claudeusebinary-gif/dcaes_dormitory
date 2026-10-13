@extends('layouts.app')

@section('title', 'Profile')

@section('content')
    @php $u = auth()->user(); @endphp

    <h1 class="tit">Profile</h1>
    <h1 class="ba">Your info, your room, your bed, and who you're sharing it with.</h1>

    <div class="profilee">
        <div class="logbi">
            <span class="namesa">{{ $u->initial }}</span>
        </div>

        <div class="info">
            <h2 class="fname">{{ $u->display_name }}</h2>

            <div class="info-row">
                <span class="role-pill">{{ ucfirst($u->role) }}</span>
                <span class="room-line">
                    @if ($u->room_number)
                        Room {{ $u->room_number }}
                        @if ($u->bed_number)
                            · Bed {{ $u->bed_number }}@if ($u->bed_deck), {{ ucfirst($u->bed_deck) }} Deck @endif
                        @endif
                    @else
                        No room assigned
                    @endif
                </span>
            </div>

            <div class="info-row contact">
                <span class="contact-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="5" width="18" height="14" rx="2" />
                        <path d="m3 7 9 6 9-6" />
                    </svg>
                    {{ $u->email }}
                </span>

                <span class="contact-item">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path
                            d="M22 16.9v3a2 2 0 0 1-2.2 2 19.8 19.8 0 0 1-8.6-3.1 19.5 19.5 0 0 1-6-6A19.8 19.8 0 0 1 2.1 4.2 2 2 0 0 1 4.1 2h3a2 2 0 0 1 2 1.7c.1 1 .4 1.9.7 2.8a2 2 0 0 1-.5 2.1L8.1 9.9a16 16 0 0 0 6 6l1.3-1.3a2 2 0 0 1 2.1-.4c.9.3 1.8.6 2.8.7a2 2 0 0 1 1.7 2z" />
                    </svg>
                    {{ $u->phone_number ?: 'No phone number' }}
                </span>
            </div>
        </div>

        <div class="status">
            <span class="statusd"></span>
            {{ ucfirst($u->role) === 'Tenant' ? 'Active Tenant' : ucfirst($u->role) }}
        </div>
    </div>
    <div class="lepat">
        <nav class="rights" aria-label="Main navigation">
            <a href="#overview" class="tab active" data-tab="overview">
                <svg viewBox="0 0 24 24">
                    <path d="M15 21v-8a1 1 0 0 0-1-1h-4a1 1 0 0 0-1 1v8" />
                    <path
                        d="M3 10a2 2 0 0 1 .709-1.528l7-5.999a2 2 0 0 1 2.582 0l7 5.999A2 2 0 0 1 21 10v9a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" />
                </svg>
                <h3>Overview</h3>
            </a>
            <a href="#details" class="tab" data-tab="details">
                <svg viewBox="0 0 24 24">
                    <path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2" />
                    <circle cx="12" cy="7" r="4" />
                </svg>
                <h3>My Details</h3>
            </a>
            <a href="#roommates" class="tab" data-tab="roommates">
                <svg viewBox="0 0 24 24">
                    <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                    <path d="M16 3.128a4 4 0 0 1 0 7.744" />
                    <path d="M22 21v-2a4 4 0 0 0-3-3.87" />
                    <circle cx="9" cy="7" r="4" />
                </svg>
                <h3>Roommates</h3>
            </a>
            <a href="#documents" class="tab" data-tab="documents">
                <svg viewBox="0 0 24 24">
                    <path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z" />
                    <path d="M14 2v4a2 2 0 0 0 2 2h4" />
                </svg>
                <h3>Documents</h3>
            </a>
        </nav>
    </div>

    <section class="panel active" id="panel-overview">
        @php
            $myBed = (int) $u->bed_number;
            $myDeck = strtolower($u->bed_deck ?? '');
        @endphp

        <div class="puti">
            <div class="lift">
                <p class="lbl">MY ROOM</p>
                <h2 class="roomname">
                    {{ $u->room_number ? 'Room ' . $u->room_number : 'No room assigned' }}
                </h2>
                <p class="bedshar">Bed Sharing · 6 double-deck beds</p>

                <p class="lbl">MY BED</p>
                <p class="val">
                    @if ($u->bed_number)
                        Bed {{ $u->bed_number }}@if ($u->bed_deck) · {{ ucfirst($u->bed_deck) }} Deck @endif
                    @else
                        No bed assigned
                    @endif
                </p>

                <p class="lbl">ROOM CAPACITY</p>
               <p class="val">{{ 1 + $u->roommates->count() }} of 12 occupied</p>

                <p class="lbl lbl-maroon">BED LAYOUT</p>
            </div>

          @php
    $occupants = collect([$u])->merge($u->roommates)
        ->keyBy(fn ($p) => $p->bed_number . '-' . strtolower($p->bed_deck));
@endphp

<div class="rith">
    @for ($i = 1; $i <= 6; $i++)
        <div class="roomates {{ $myBed === $i ? 'mine' : '' }}">
            <div class="bed-head">
                <svg viewBox="0 0 24 24">
                    <path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8" />
                    <path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4" />
                    <path d="M12 4v6" />
                    <path d="M2 18h20" />
                </svg>
                <span>BED {{ $i }}</span>
            </div>

            @foreach (['upper', 'lower'] as $deck)
                @php
                    $isMe = $myBed === $i && $myDeck === $deck;
                    $p = $occupants->get($i . '-' . $deck);
                @endphp
                <div class="deck-row {{ $isMe ? 'me' : '' }}">
                    <span class="deck-name">{{ strtoupper($deck) }}</span>
                    @if ($isMe)
                        <span class="deck-user">You</span>
                    @elseif ($p)
                        <span class="deck-user">{{ $p->display_name }}</span>
                    @else
                        <span class="deck-user free">Available</span>
                    @endif
                </div>
            @endforeach
        </div>
    @endfor
</div>
        </div>
        <div class="sicsons">
            <h1 class="stays">Stay Details<br><span>A quick snapshot of your lease</span></h1>
            @php $u = auth()->user(); @endphp

            <div class="threbox">
                <div class="bxas">
                    <h1 class="namber">
                        {{ $u->move_in_date_label }}<br>
                        <span>MOVE-IN DATE</span>
                    </h1>
                </div>

                <div class="bxas">
                    <h1 class="namber">
                        {{ $u->monthly_rent_label }}<br>
                        <span>MONTHLY RENT</span>
                    </h1>
                </div>

                <div class="bxas">
                    <h1 class="namber {{ $u->payment_status_class }}">
                        {{ $u->payment_status_label }}<br>
                        <span>PAYMENT STATUS</span>
                    </h1>
                </div>

                <div class="bxas">
                    <h1 class="namber">
                        {{ $u->bed_deck ? ucfirst($u->bed_deck) . ' Deck' : 'Not set' }}<br>
                        <span>BED SLOT</span>
                    </h1>
                </div>
            </div>

        </div>
        <div class="acson">
            <h1 class="stays">Quick Actions<br><span>Things you might need right now</span></h1>
            <div class="fur">
                <div class="acsun">
                    <h1 class="req">Request Maintenance <br><span>Report a repair or issue in your room</span></h1>
                    <div class="maliitb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                        </svg></div>
                </div>
                <div class="acsun">
                    <h1 class="req">Message Dorm Manager <br><span>Ask a question or raise a concern</span></h1>
                    <div class="maliitb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg></div>
                </div>
                <div class="acsun">
                    <h1 class="req">Download Lease Agreement <br><span>Access your digital lease documents</span></h1>
                    <div class="maliitb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4" />
                            <path d="m7 10 5 5 5-5" />
                            <path d="M12 15V3" />
                        </svg></div>
                </div>
            </div>

        </div>
    </section>

    <section class="panel" id="panel-details">
        <div class="personal">
            <h1 class="stays">Personal Information<br><span>Keep this accurate so we can reach you</span></h1>

            <a href="#" class="edit-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
                </svg>
                Edit
            </a>

            <h3 class="ruw"><span class="lab">Full Name</span><span class="val">{{ $u->full_name }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw"><span class="lab">Email Address</span><span class="val">{{ $u->email }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw"><span class="lab">Phone Number</span><span
                    class="val">{{ $u->phone_number ?: 'Not set' }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw"><span class="lab">Birthdate</span><span class="val">{{ $u->birthday_label }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw"><span class="lab">Gender</span><span
                    class="val">{{ $u->sex ? ucfirst($u->sex) : 'Not set' }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw"><span class="lab">Home Address</span><span class="val">{{ $u->address ?: 'Not set' }}</span>
            </h3>
            <hr class="hw5">
        </div>
        <div class="personaleme">@php $u = auth()->user();
        $ec = $u->emergencyContact; @endphp
            <h1 class="stays">Emergency Contact<br><span>Who we call if something comes up</span></h1>

            <a href="#" class="edit-link">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <path d="M12 20h9" />
                    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
                </svg>
                Edit
            </a>

            @php $ec = $u->emergencyContact; @endphp

            <h3 class="ruw"><span class="lab">Contact Name</span><span class="val">{{ $ec?->name ?: 'Not set' }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw"><span class="lab">Relationship</span><span
                    class="val">{{ $ec?->relationship ?: 'Not set' }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw"><span class="lab">Phone Number</span><span class="val">{{ $ec?->phone ?: 'Not set' }}</span>
            </h3>
            <hr class="hw5">
            <h3 class="ruw"><span class="lab">Address</span><span class="val">{{ $ec?->address ?: 'Not set' }}</span></h3>
            <hr class="hw5">
        </div>
    </section>

    <section class="panel" id="panel-roommates">
       <div class="mates">
    @php
        $u = auth()->user();
        $occupants = collect([$u])->merge($u->roommates)
            ->keyBy(fn ($p) => $p->bed_number . '-' . strtolower($p->bed_deck));
    @endphp

    <h1 class="stays">Roomates<br><span>Everyone sharing Room {{ $u->room_number ?: '—' }} with you</span></h1>

    <div class="mate">
        @foreach (range(1, 6) as $bed)
            @foreach (['Lower', 'Upper'] as $deck)
                @php $p = $occupants->get($bed . '-' . strtolower($deck)); @endphp

                @if ($p && $p->id === $u->id)

                    <div class="kasama" style="border:1px solid #672C2C; background-color: #672c2c1f;">
                        <div class="belogs">
                            <span class="namesa">{{ $u->initial }}</span>
                        </div>
                        <h1 class="stays">{{ $u->display_name }} You<br><span>Bed {{ $bed }} · {{ $deck }} Deck</span></h1>
                    </div>

                @elseif ($p)
                    <div class="kasama">
                        <div class="belogs" style="background-color: #ebe1e0; border: none;">
                            <span class="namesa" style="color: #672C2C;">{{ $p->initial }}</span>
                        </div>
                        <h1 class="stays">{{ $p->display_name }}<br><span>Bed {{ $bed }} · {{ $deck }} Deck</span></h1>
                    </div>

                @else
                    <div class="kasama" style="border:2px dotted #2c6739; background-color: #2c67391f;">
                        <div class="belogs" style="background-color: #2c673954; border: none;">
                            <span class="namesa" style="color: #2c6739;"><svg viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <path d="M12 5v14M5 12h14" />
                                </svg></span>
                        </div>
                        <h1 class="stays">Bed {{ $bed }} · {{ $deck }}<br><span style="color:#2c6739;">Available</span></h1>
                    </div>
                @endif
            @endforeach
        @endforeach
    </div>
</div>
    </section>

    <section class="panel" id="panel-documents">
        <div class="lease">
            <h1 class="stays">Documents<br><span>Your submitted requirements and paperwork</span></h1>
            <div class="pababa">
                <div class="tulo">
                    <div class="lugos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
    <path d="M14 2v6h6" />
    <path d="M8 13h8M8 17h8M8 9h2" />
</svg>
</div>
 <h1 class="stays3">Valid ID<br><span>Verified</span></h1>
 <div class="view"><h2>View</h2></div>
                </div>
                <div class="tulo"><div class="lugos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
    <path d="M14 2v6h6" />
    <path d="M8 13h8M8 17h8M8 9h2" />
</svg>
</div>
 <h1 class="stays3">Lease Agreement<br><span>Verified</span></h1>
 <div class="view"><h2>View</h2></div></div>
                <div class="tulo"><div class="lugos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
    <path d="M14 2v6h6" />
    <path d="M8 13h8M8 17h8M8 9h2" />
</svg>
</div>
 <h1 class="stays3">Barangay Clearance<br><span style="color:#C98A2C    ;">Pending Review</span></h1>
 <div class="view"><h2>View</h2></div></div>
            </div>

        </div>
        <div class="lease1">
<h1 class="stays">Account Security<br><span>Change your password or manage account access.</span></h1>
<input type="button" class="change" value="Change Password">
        </div>
    </section>

    @include('partials.footer')
@endsection
@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const tabs = document.querySelectorAll('.tab');
            const panels = document.querySelectorAll('.panel');

            function showTab(name) {
                tabs.forEach(t => t.classList.toggle('active', t.dataset.tab === name));
                panels.forEach(p => p.classList.toggle('active', p.id === 'panel-' + name));
            }

            tabs.forEach(tab => {
                tab.addEventListener('click', e => {
                    e.preventDefault();
                    showTab(tab.dataset.tab);
                    history.replaceState(null, '', '#' + tab.dataset.tab);
                });
            });


            const start = location.hash.replace('#', '');
            if (start && document.getElementById('panel-' + start)) {
                showTab(start);
            }
        });
    </script>
@endpush