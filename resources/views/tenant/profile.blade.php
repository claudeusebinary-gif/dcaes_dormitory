@extends('layouts.app')

@section('title', 'Profile')

@section('content')
  @php $u = $profileUser ?? auth()->user(); @endphp
@section('page_title', 'Profile')
@section('page_subtitle', "Your info, your room, your bed, and who you're sharing it with.")
@isset($profileUser)
    @php $fromPayments = request('from') === 'payments'; @endphp
    <a href="{{ $fromPayments ? route('admin.payments') : route('admin.tenants') }}" class="backtenants">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <path d="M19 12H5M12 19l-7-7 7-7" />
        </svg>
        {{ $fromPayments ? 'Back to payments' : 'Back to tenants' }}
    </a>
@endisset
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
        @isset($profileUser)
        <div class="roombedmove adm-stats">
            <div class="roombedmove1">
                <h1>MONTHLY RENT</h1>
                <h2>{{ $stats['rent'] }}</h2>
                <span>{{ $stats['due_text'] }}</span>
            </div>
            <div class="roombedmove1">
                <h1>TOTAL PAID</h1>
                <h2>{{ $stats['total'] }}</h2>
                <span>Since move-in</span>
            </div>
            <div class="roombedmove1">
                <h1>ON-TIME RATE</h1>
                <h2>{{ $stats['ontime'] }}</h2>
                <span>{{ $stats['ontime_sub'] }}</span>
            </div>
            <div class="roombedmove1">
                <h1>OPEN COMPLAINTS</h1>
                <h2>{{ $stats['open'] }}</h2>
                <span>{{ $stats['resolved'] }} resolved this year</span>
            </div>
        </div>
    @endisset
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
                        @isset($profileUser)
                <a href="#payments" class="tab" data-tab="payments">
                    <svg viewBox="0 0 24 24">
                        <rect x="2" y="5" width="20" height="14" rx="2" />
                        <path d="M2 10h20" />
                    </svg>
                    <h3>Payment History</h3>
                </a>
                <a href="#activity" class="tab" data-tab="activity">
                    <svg viewBox="0 0 24 24">
                        <path d="M22 12h-4l-3 9L9 3l-3 9H2" />
                    </svg>
                    <h3>Activity Log</h3>
                </a>
            @endisset
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
         @unless(isset($profileUser))
        <div class="acson">
            <h1 class="stays">Quick Actions<br><span>Things you might need right now</span></h1>
            <div class="fur">
                <div class="acsun" data-contact="maintenance" role="button" tabindex="0">
                    <h1 class="req">Request Maintenance <br><span>Report a repair or issue in your room</span></h1>
                    <div class="maliitb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path
                                d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z" />
                        </svg></div>
                </div>
               <div class="acsun" data-contact="chooser" role="button" tabindex="0">
                    <h1 class="req">Message Dorm Manager <br><span>Ask a question or raise a concern</span></h1>
                    <div class="maliitb"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                            stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg></div>
                </div>
                <div class="acsun" role="button" tabindex="0">
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
        @endunless
        <div class="sicsons">
            <h1 class="stays">Stay Details<br><span>A quick snapshot of your lease</span></h1>
          @php $u = $profileUser ?? auth()->user(); @endphp

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

            <h3 class="ruw5"><span class="lab">Full Name</span><span class="val">{{ $u->full_name }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw5"><span class="lab">Email Address</span><span class="val">{{ $u->email }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw5"><span class="lab">Phone Number</span><span
                    class="val">{{ $u->phone_number ?: 'Not set' }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw5"><span class="lab">Birthdate</span><span class="val">{{ $u->birthday_label }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw5"><span class="lab">Gender</span><span
                    class="val">{{ $u->sex ? ucfirst($u->sex) : 'Not set' }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw5"><span class="lab">Home Address</span><span class="val">{{ $u->address ?: 'Not set' }}</span>
            </h3>
            <hr class="hw5">
        </div>
       <div class="personal emergency">
    @php $ec = $u->emergencyContact; @endphp

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

            <h3 class="ruw5"><span class="lab">Contact Name</span><span class="val">{{ $ec?->name ?: 'Not set' }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw5"><span class="lab">Relationship</span><span
                    class="val">{{ $ec?->relationship ?: 'Not set' }}</span></h3>
            <hr class="hw5">
            <h3 class="ruw5"><span class="lab">Phone Number</span><span class="val">{{ $ec?->phone ?: 'Not set' }}</span>
            </h3>
            <hr class="hw5">
            <h3 class="ruw5"><span class="lab">Address</span><span class="val">{{ $ec?->address ?: 'Not set' }}</span></h3>
            <hr class="hw5">
        </div>
    </section>

    <section class="panel" id="panel-roommates">
       <div class="mates">
    @php
        $u = $profileUser ?? auth()->user();
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
                        @unless (isset($profileUser))
                            <button type="button" class="mensahe" data-id="{{ $p->id }}"
                                data-name="{{ $p->display_name }}" data-room="Room {{ $p->room_number }}"
                                aria-label="Message {{ $p->display_name }}" title="Message {{ $p->display_name }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                    stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                    <path d="M21 15a2 2 0 0 1-2 2H8l-5 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                                </svg>
                            </button>
                        @endunless
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
        @php
    $docs = [
        ['valid_id', 'Valid ID',           $u->valid_id_path,           'Verified'],
        ['lease',    'Lease Agreement',    $u->lease_path,              'Verified'],
        ['barangay', 'Barangay Clearance', $u->barangay_clearance_path, 'Pending Review'],
    ];
@endphp
<div class="pababa">
    @foreach ($docs as [$key, $label, $path, $okText])
        <div class="tulo">
            <div class="lugos"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                <path d="M14 2v6h6" />
                <path d="M8 13h8M8 17h8M8 9h2" />
            </svg></div>
            <h1 class="stays3">{{ $label }}<br>
                @if ($path)
                    <span @if ($key === 'barangay') style="color:#C98A2C;" @endif>{{ $okText }}</span>
                @else
                    <span style="color:#9a9a9a;">Not uploaded</span>
                @endif
            </h1>
            <div class="view" role="button" tabindex="0" data-doc="{{ $label }}"
                data-url="{{ route('tenant.documents.show', $key) }}"><h2>View</h2></div>
        </div>
    @endforeach
</div>

        </div>
        @unless(isset($profileUser))
        <div class="lease1">
<h1 class="stays">Account Security<br><span>Change your password or manage account access.</span></h1>
<input type="button" class="change" value="Change Password"
    onclick="window.location.href='{{ route('tenant.settings') }}#security'">
        </div>
        @endunless
    </section>
    @isset($profileUser)
        <section class="panel" id="panel-payments">
            <div class="adm-box">
                <h1 class="stays">Payment History<br><span>Every payment this tenant has made</span></h1>

                <div class="adm-row adm-head">
                    <div>Period</div><div>Amount</div><div>Method</div><div>Paid on</div><div>Status</div>
                </div>

                @forelse ($payHistory as $h)
                    <div class="adm-row">
                        <div>{{ $h['label'] }}</div>
                        <div>₱{{ number_format($h['paid_amount'], 2) }}</div>
                        <div>{{ $h['method'] }}</div>
                        <div>{{ $h['paid_at_text'] }}</div>
                        <div><div class="anostatbayad"{!! ($h['pay_status'] ?? 'Paid') === 'Partial' ? ' style="background-color:#B9D2F0"' : '' !!}><h1>{{ $h['pay_status'] ?? 'Paid' }}</h1></div></div>
                    </div>
                @empty
                    <p class="bh-empty">No payments yet.</p>
                @endforelse
            </div>
        </section>

        <section class="panel" id="panel-activity">
            <div class="adm-box">
                <h1 class="stays">Activity Log<br><span>The latest things this tenant did</span></h1>

                @forelse ($activity as $e)
                    <div class="adm-act">
                        <i class="adm-dot adm-dot-{{ $e['type'] }}"></i>
                        <div class="adm-act-text">
                            <b>{{ $e['title'] }}</b>
                            @if ($e['detail'])<span>{{ $e['detail'] }}</span>@endif
                        </div>
                        <small title="{{ $e['at']->format('M j, Y g:i A') }}">{{ $e['at']->diffForHumans() }}</small>
                    </div>
                @empty
                    <p class="bh-empty">No activity yet.</p>
                @endforelse
            </div>
        </section>
    @endisset
    @include('partials.footer')
        @unless (isset($profileUser))
    <div class="adminmodal" id="contactModal" role="dialog" aria-modal="true" aria-hidden="true">

        <div class="adminbox" id="ctMessage" hidden>
            <button type="button" class="admin-close" data-ct-back aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <h1 class="sinta21">Send a Message<br><span>Ask the dorm manager something directly</span></h1>
            <div class="msgfield">
                <textarea class="missagekayadmin" id="ctMsgText" data-counter="ctMsgCount" maxlength="1000" rows="4"
                    placeholder="Type your message to the dorm manager..."></textarea>
                <span class="msgcount" id="ctMsgCount">0 / 1000</span>
            </div>
            <input type="button" class="sending" id="ctMsgSend" value="Send Message"
                data-url="{{ route('tenant.messages.send') }}">
            <p class="msg-status" hidden></p>
        </div>

        <div class="adminbox" id="ctComplaint" hidden>
            <button type="button" class="admin-close" data-ct-back aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <h1 class="sinta21">File a Complaint<br><span>Report an issue or concerns</span></h1>

            <select id="ctComplaintType" class="silicta">
                <option value="" disabled selected>Select complaint type</option>
                <option value="plumbing">Plumbing</option>
                <option value="electrical">Electrical</option>
                <option value="room_furniture">Room and furniture</option>
                <option value="cleanliness">Cleanliness</option>
                <option value="pests">Pests</option>
                <option value="noise">Noise and disturbance</option>
                <option value="roommate">Roommate concern</option>
                <option value="security">Security and safety</option>
                <option value="internet_utilities">Internet and utilities</option>
                <option value="billing">Billing and payments</option>
                <option value="staff">Staff or management</option>
                <option value="other">Others</option>
            </select>

            <div class="msgfield">
                <textarea class="missagekayadmin" id="ctComplaintText" data-counter="ctComplaintCount" maxlength="1000"
                    rows="4" placeholder="Describe the problem: what happened, where, and when..."></textarea>
                <span class="msgcount" id="ctComplaintCount">0 / 1000</span>
            </div>
            <input type="button" class="sending" id="ctComplaintSend" value="Send Complaint"
                data-url="{{ route('tenant.complaints.store') }}">
            <p class="msg-status" hidden></p>
        </div>

        <div class="adminbox" id="ctSuggestion" hidden>
            <button type="button" class="admin-close" data-ct-back aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <h1 class="sinta21">Suggestion<br><span>Tell us your suggestion</span></h1>
            <div class="msgfield">
                <textarea class="missagekayadmin" id="ctSuggestionText" data-counter="ctSuggestionCount" maxlength="1000"
                    rows="4" placeholder="Type your suggestion to the dorm manager..."></textarea>
                <span class="msgcount" id="ctSuggestionCount">0 / 1000</span>
            </div>
            <input type="button" class="sending" id="ctSuggestionSend" value="Send Suggestion"
                data-url="{{ route('tenant.suggestions.store') }}">
            <p class="msg-status" hidden></p>
        </div>

        <div class="adminbox" id="ctMaintenance" hidden>
            <button type="button" class="admin-close" data-ct-back aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>
            <h1 class="sinta21">Request Maintenance<br><span>Report a repair or issue in your room</span></h1>

            <select id="ctCategory" class="silicta">
                <option value="" disabled selected>Select a category</option>
                <option value="plumbing">Plumbing (faucet, toilet, drainage)</option>
                <option value="electrical">Electrical (outlet, lighting, wiring)</option>
                <option value="furniture">Furniture / fixtures</option>
                <option value="ventilation">Ventilation</option>
                <option value="pest">Pest control</option>
                <option value="other">Other</option>
            </select>
            <select id="ctUrgency" class="silicta" style="margin-top: 12px;">
                <option value="" disabled selected>Select urgency</option>
                <option value="low">Low - can wait a few days</option>
                <option value="medium">Medium - needs attention this week</option>
                <option value="high">High - urgent, affects daily use</option>
            </select>

            <div class="msgfield">
                <textarea class="missagekayadmin" id="ctDetails" data-counter="ctDetailsCount" maxlength="1000" rows="4"
                    placeholder="e.g. Faucet in the shared bathroom drips occasionally"></textarea>
                <span class="msgcount" id="ctDetailsCount">0 / 1000</span>
            </div>
            <input type="button" class="sending" id="ctMaintSend" value="Submit Request"
                data-url="{{ route('tenant.requests.maintenance') }}">
            <p class="msg-status" hidden></p>
        </div>
<div class="adminbox" id="ctDoc" hidden style="width:760px;">
    <button type="button" class="admin-close" data-ct-back aria-label="Close">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
            <line x1="18" y1="6" x2="6" y2="18" /><line x1="6" y1="6" x2="18" y2="18" />
        </svg>
    </button>
    <h1 class="sinta21" id="ctDocTitle">Document<br><span>Your uploaded file</span></h1>
    <div id="ctDocBody" style="margin-top:16px; max-height:65vh; overflow:auto; text-align:center;"></div>
</div>
        <div class="adminbox" id="ctChooser">
            <button type="button" class="admin-close" data-ct-all aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                    stroke-linejoin="round" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18" />
                    <line x1="6" y1="6" x2="18" y2="18" />
                </svg>
            </button>

            <h1 class="mainhouse12">Main House<br><span>This is the owner's house. What would you like to do?</span></h1>
            <div class="request1">
                <div class="requestbox" data-ct="Message">
                    <div class="elogg">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z" />
                        </svg>
                    </div>
                    <h1 class="sinta">Send a Message<br><span>Ask the owner something directly</span></h1>
                </div>
                <div class="requestbox" data-ct="Complaint">
                    <div class="elogg">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <circle cx="12" cy="12" r="10" />
                            <line x1="12" y1="8" x2="12" y2="12" />
                            <line x1="12" y1="16" x2="12.01" y2="16" />
                        </svg>
                    </div>
                    <h1 class="sinta">File a Complaint<br><span>Report an issue or concerns</span></h1>
                </div>
                <div class="requestbox" data-ct="Suggestion">
                    <div class="elogg">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"
                            stroke-linejoin="round" aria-hidden="true">
                            <path d="M9 18h6" />
                            <path d="M10 22h4" />
                            <path d="M12 2a7 7 0 0 0-4 12.7c.6.5 1 1.2 1 2V17h6v-.3c0-.8.4-1.5 1-2A7 7 0 0 0 12 2z" />
                        </svg>
                    </div>
                    <h1 class="sinta">Suggestion<br><span>Tell us your suggestion</span></h1>
                </div>
            </div>
        </div>
    </div>
    @endunless
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
        <script>
        (function () {
            var modal = document.getElementById('contactModal');
            if (!modal) return;

            var csrf = document.querySelector('meta[name="csrf-token"]').content;
            var views = ['ctChooser', 'ctMessage', 'ctComplaint', 'ctSuggestion', 'ctMaintenance'];
            var fromChooser = false;

            function setStatus(box, text, type) {
                var p = box.querySelector('.msg-status');
                p.hidden = !text;
                p.textContent = text || '';
                p.className = 'msg-status' + (type ? ' ' + type : '');
            }

            function show(id) {
                views.forEach(function (v) {
                    var box = document.getElementById(v);
                    box.hidden = (v !== id);
                    if (box.querySelector('.msg-status')) setStatus(box, '');
                });
                modal.classList.add('is-open');
                modal.setAttribute('aria-hidden', 'false');
                var first = document.getElementById(id).querySelector('textarea, select');
                if (first) setTimeout(function () { first.focus(); }, 50);
            }

            function closeAll() {
                modal.classList.remove('is-open');
                modal.setAttribute('aria-hidden', 'true');
            }

            document.querySelectorAll('[data-contact]').forEach(function (card) {
                function go() {
                    if (card.dataset.contact === 'maintenance') { fromChooser = false; show('ctMaintenance'); }
                    else { fromChooser = true; show('ctChooser'); }
                }
                card.addEventListener('click', go);
                card.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); go(); }
                });
            });

            modal.addEventListener('click', function (e) {
                if (e.target === modal || e.target.closest('[data-ct-all]')) { closeAll(); return; }
                if (e.target.closest('[data-ct-back]')) { fromChooser ? show('ctChooser') : closeAll(); return; }
                var pick = e.target.closest('[data-ct]');
                if (pick) { fromChooser = true; show('ct' + pick.dataset.ct); }
            });
            document.addEventListener('keydown', function (e) {
                if (e.key === 'Escape' && modal.classList.contains('is-open')) closeAll();
            });

            modal.querySelectorAll('textarea[data-counter]').forEach(function (t) {
                var c = document.getElementById(t.dataset.counter);
                t.addEventListener('input', function () {
                    var n = t.value.length;
                    c.textContent = n + ' / 1000';
                    c.classList.toggle('near', n >= 900 && n < 1000);
                    c.classList.toggle('max', n >= 1000);
                });
            });

            function wire(btnId, label, okText, collect, clear) {
                var btn = document.getElementById(btnId);
                var box = btn.closest('.adminbox');

                btn.addEventListener('click', function () {
                    var out = collect();
                    if (out.error) { setStatus(box, out.error, 'error'); return; }

                    btn.disabled = true;
                    btn.value = 'Sending...';
                    setStatus(box, '');

                    fetch(btn.dataset.url, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': csrf
                        },
                        body: JSON.stringify(out.data)
                    }).then(function (res) {
                        return res.json().catch(function () { return {}; }).then(function (data) {
                            if (res.status === 429) throw new Error('You are sending too fast. Please wait a moment.');
                            if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                            if (res.status === 422) {
                                var first = data.errors ? Object.values(data.errors)[0][0] : data.message;
                                throw new Error(first || 'Please check your details and try again.');
                            }
                            if (!res.ok) throw new Error(data.message || 'Could not send. Please try again.');
                        });
                    }).then(function () {
                        clear();
                        setStatus(box, okText, 'ok');
                        setTimeout(closeAll, 1500);
                    }).catch(function (err) {
                        setStatus(box, err instanceof TypeError
                            ? 'No connection. Check your internet and try again.' : err.message, 'error');
                    }).then(function () {
                        btn.disabled = false;
                        btn.value = label;
                    });
                });
            }

            function resetText(id) {
                var t = document.getElementById(id);
                t.value = '';
                t.dispatchEvent(new Event('input'));
            }

            wire('ctMsgSend', 'Send Message', 'Message sent! The dorm manager will get back to you.',
                function () {
                    var text = document.getElementById('ctMsgText').value.trim();
                    return text.length < 2 ? { error: 'Please type your message first.' } : { data: { message: text } };
                },
                function () { resetText('ctMsgText'); });

            wire('ctComplaintSend', 'Send Complaint', 'Complaint sent! We will get back to you soon.',
                function () {
                    var type = document.getElementById('ctComplaintType').value;
                    var text = document.getElementById('ctComplaintText').value.trim();
                    if (!type) return { error: 'Please select a complaint type.' };
                    if (text.length < 5) return { error: 'Please describe your complaint.' };
                    return { data: { type: type, message: text } };
                },
                function () { document.getElementById('ctComplaintType').selectedIndex = 0; resetText('ctComplaintText'); });

            wire('ctSuggestionSend', 'Send Suggestion', 'Thank you! Your suggestion was sent.',
                function () {
                    var text = document.getElementById('ctSuggestionText').value.trim();
                    return text.length < 5 ? { error: 'Please type your suggestion first.' } : { data: { message: text } };
                },
                function () { resetText('ctSuggestionText'); });

            wire('ctMaintSend', 'Submit Request', 'Request sent. Dorm maintenance will follow up.',
                function () {
                    var cat = document.getElementById('ctCategory').value;
                    var urg = document.getElementById('ctUrgency').value;
                    var det = document.getElementById('ctDetails').value.trim();
                    if (!cat) return { error: 'Please select a category.' };
                    if (!urg) return { error: 'Please select an urgency.' };
                    if (det.length < 5) return { error: 'Please describe the issue.' };
                    return { data: { category: cat, urgency: urg, details: det } };
                },
                function () {
                    document.getElementById('ctCategory').selectedIndex = 0;
                    document.getElementById('ctUrgency').selectedIndex = 0;
                    resetText('ctDetails');
                });
                            var docObj = null;
            function openDoc(btn) {
                var body = document.getElementById('ctDocBody');
                fromChooser = false;
                show('ctDoc');
                document.getElementById('ctDocTitle').childNodes[0].nodeValue = btn.dataset.doc;
                body.textContent = 'Loading...';
                fetch(btn.dataset.url).then(function (r) {
                    if (!r.ok) throw new Error('missing');
                    return r.blob();
                }).then(function (b) {
                    if (docObj) URL.revokeObjectURL(docObj);
                    docObj = URL.createObjectURL(b);
                    var el = b.type.indexOf('image/') === 0 ? document.createElement('img') : document.createElement('iframe');
                    if (el.tagName === 'IMG') { el.style.maxWidth = '100%'; }
                    else { el.style.cssText = 'width:100%;height:60vh;border:0;'; }
                    el.src = docObj;
                    body.textContent = '';
                    body.appendChild(el);
                }).catch(function () {
                    body.textContent = 'No file has been uploaded for this document yet.';
                });
            }
            document.querySelectorAll('[data-doc]').forEach(function (b) {
                b.addEventListener('click', function () { openDoc(b); });
                b.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openDoc(b); }
                });
            });
        })();
    </script>
    <script>
        document.querySelectorAll('.mensahe').forEach(function (b) {
            b.addEventListener('click', function () {
                if (!window.DaceChat) return;
                window.DaceChat.open({
                    kind: 'roommate',
                    item_id: b.dataset.id,
                    with_id: b.dataset.id,
                    with_name: b.dataset.name,
                    with_room: b.dataset.room,
                    item_title: 'Roommate',
                    item_price: b.dataset.room,
                    photo: ''
                });
            });
        });
    </script>
@endpush