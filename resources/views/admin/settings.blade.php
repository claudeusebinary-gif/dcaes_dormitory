@extends('layouts.app')

@section('title', 'Settings')
@section('page_title', 'Settings')
@section('page_subtitle', 'Manage the dormitory, its rates, and who can access the admin side.')

@section('content')
    @php
        $tabs = [
            'general' => ['General', '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /><polyline points="9 22 9 12 15 12 15 22" />'],
            'room-rent' => ['Room & Rent', '<rect x="3" y="3" width="18" height="18" rx="2" /><line x1="9" y1="3" x2="9" y2="21" />'],
            'payment-methods' => ['Payment Methods', '<rect x="1" y="4" width="22" height="16" rx="2" /><line x1="1" y1="10" x2="23" y2="10" />'],
            'notification' => ['Notification', '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" /><path d="M13.73 21a2 2 0 0 1-3.46 0" />'],
            'staff-access' => ['Staff & Access', '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" /><circle cx="9" cy="7" r="4" /><path d="M22 21v-2a4 4 0 0 0-3-3.87" /><path d="M16 3.13a4 4 0 0 1 0 7.75" />'],
            'security' => ['Security', '<rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />'],
            'history' => ['History', '<circle cx="12" cy="12" r="10" /><polyline points="12 6 12 12 16 14" />'],
        ];
    @endphp

    <div class="st-wrap">

        <aside class="st-side">
            <label class="st-search">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="search" id="stSearch" name="settings_search_q" placeholder="Search settings..."
                    autocomplete="off" readonly onfocus="this.removeAttribute('readonly')">
            </label>

            <nav class="st-nav" id="stNav">
                @foreach ($tabs as $key => [$label, $icon])
                    <button type="button" class="st-item{{ $loop->first ? ' active' : '' }}" data-tab="{{ $key }}">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                            aria-hidden="true">{!! $icon !!}</svg>
                        <span>{{ $label }}</span>
                    </button>
                @endforeach
                <p class="st-none" id="stNone">No settings found.</p>
            </nav>
        </aside>

        <section class="st-main">

            <div class="st-panel active" data-panel="general">
                <h2 class="st-title">General</h2>
                <h1 class="yoursas">Dormitory name, contact details, and basic information.</h1>
          <div class="settingsgeneup">
    <div class="settinggeneral" id="logoBox">
        @if ($profile['logo'])
            <img src="{{ asset($profile['logo']) }}" alt="Logo" style="width:100%;height:100%;object-fit:cover;border-radius:6px;">
        @else
            <svg viewBox="0 0 24 24" width="36" height="36" fill="#fff" aria-hidden="true"><path d="M12 2.5 3 10v11h6.5v-6h5v6H21V10z"/></svg>
        @endif
    </div>
    <div class="uploadsetting">
        <input type="file" id="logoFile" accept="image/png,image/jpeg,image/webp" hidden>
        <button type="button" class="settingupload" id="logoUpload">Upload Logo</button>
        <button type="button" class="settingupload" id="logoRemove" @disabled(! $profile['logo'])>Remove</button>
    </div>
</div>
<p class="req-status" id="logoStatus" hidden></p>

<div class="namedormitoryname1">
    <div class="namedormitoryname">
        <span>DORMITORY NAME</span>
        <input type="text" id="genName" class="dormitoryname" maxlength="100" value="{{ $profile['name'] }}" placeholder="D'aces Dormitory">
    </div>
    <div class="namedormitoryname">
        <span>CONTACT NUMBER</span>
        <input type="text" id="genPhone" class="dormitoryname" maxlength="30" value="{{ $profile['phone'] }}" placeholder="+63 917 127 4567">
    </div>
</div>
<div class="namedormitoryname" style="margin-top: 10px;">
    <span>ADDRESS</span>
    <input type="text" id="genAddress" class="dormitoryname" maxlength="200" value="{{ $profile['address'] }}" placeholder="Pinaod, Bulacan">
</div>
<div class="namedormitoryname" style="margin-top: 10px;">
    <span>EMAIL</span>
    <input type="email" id="genEmail" class="dormitoryname" maxlength="255" value="{{ $profile['email'] }}" placeholder="dacesdormitory@gmail.com">
</div>
                <hr class="padiretso23175927" style="margin-top:30px;">
                <div class="persoge">
                            <h1>Front Desk hour</h1>
                            <span>When tenants can reach the office in person.</span>
                        </div>
          <div class="timehours" id="frontDeskList">
    @foreach (\App\Support\DormStats::frontDesk() as $day => $h)
        <div class="timehours1" data-day="{{ $day }}">
            <h1>{{ $day }}</h1>
            <label class="switch25">
                <input type="checkbox" class="fd-open" @checked($h['open'])>
                <span class="switch25-track" aria-hidden="true"></span>
                <span class="sr-only25">{{ $day }}</span>
            </label>
            <input type="time" class="timesettinsfa fd-from" value="{{ $h['from'] }}">
            <span class="timeto">to</span>
            <input type="time" class="timesettinsfa fd-to" value="{{ $h['to'] }}">
        </div>
    @endforeach
</div>

<hr class="padiretso23175927" style="margin-top:30px;">

@php
    $policies = [
        'terms'   => ['Terms of Service', 'What tenants agree to when they book a room.', 'Write your Terms of Service here.'],
        'privacy' => ['Privacy Policy', 'How tenant information is collected and used.', 'Write your Privacy Policy here.'],
        'faqs'    => ['FAQs', 'Common questions tenants ask the dormitory.', 'Write your FAQs here.'],
        'rules'   => ['House Rules', 'Daily rules every tenant must follow.', 'Write your House Rules here.'],
    ];
@endphp

<div class="persoge">
    <h1>Terms & Policies</h1>
    <span>Click an item to edit what tenants will read.</span>
</div>

<div class="policylistfa" id="policyList">
    @foreach ($policies as $key => [$title, $desc])
        <button type="button" class="policyrowfa" data-legal="{{ $key }}">
            <span>
                <span class="policynamefa">{{ $title }}</span>
                <span class="policydescfa">{{ $desc }}</span>
            </span>
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <polyline points="9 18 15 12 9 6" />
            </svg>
        </button>
    @endforeach
</div>

@include('partials.legal-modal', ['adminEdit' => true])

<div class="buttonparasave5">
    <button type="button" class="buttonparasave1" id="genSave" disabled>Save Changes</button>
    <button type="button" class="buttonparasave2" id="genCancel" disabled>Cancel</button>
</div>
<p class="req-status" id="genStatus" hidden></p>
            </div>

            <div class="st-panel" data-panel="room-rent">
                <h2 class="st-title">Room & Rent</h2>
                <h1 class="yoursas">Default values applied when adding new rooms, plus amenities and floors.</h1>
           <div class="namedormitoryname1">
    <div class="namedormitoryname"><span>DEFAULT CAPACITY (BEDS PER ROOM)</span>
        <input type="number" id="rrCapacity" class="dormitoryname" min="1" max="20" value="{{ $rr['capacity'] }}"></div>
    <div class="namedormitoryname"><span>DEFAULT RATE (PER BED, ₱)</span>
        <input type="number" id="rrRate" class="dormitoryname" min="0" step="50" value="{{ $rr['rate'] }}"></div>
    <div class="namedormitoryname"><span>RENT DUE DAY OF MONTH</span>
        <input type="number" id="rrDue" class="dormitoryname" min="1" max="31" value="{{ $rr['due_day'] }}" placeholder="Blank = move-in day"></div>
</div>
<div class="namedormitoryname1" style="margin-top:-10px;">
    <div class="namedormitoryname"><span>GRACE PERIOD (DAYS)</span>
        <input type="number" id="rrGrace" class="dormitoryname" min="0" max="60" value="{{ $rr['grace'] }}"></div>
</div>
<div class="namedormitoryname1" style="margin-top:-10px;">
    <div class="namedormitoryname"><span>LATE FEE (₱ PER DAY AFTER GRACE)</span>
        <input type="number" id="rrLate" class="dormitoryname" min="0" value="{{ $rr['late_fee'] }}"></div>
</div>
                <div class="automarkcap">
                    <div class="automarkcap1">
                        <h1>Auto-mark rooms "FULL" at capacity</h1>
                    <span>Room status updates automatically when the last bed is assigned</span>
                    </div>
                     <label class="switch25" style="margin-right:20px ;">
               <input type="checkbox" id="rrAuto" @checked($rr['auto_full'])>
                <span class="switch25-track" aria-hidden="true"></span>
                <span class="sr-only25"></span>
            </label>
                </div>
                  <div class="automarkcap">
                    <div class="automarkcap1">
                        <h1>Require security deposit</h1>
                    <span>1 month rent held on move-in</span>
                    </div>
                     <label class="switch25" style="margin-right:20px ;">
               <input type="checkbox" id="rrDeposit" @checked($rr['deposit'])>
                <span class="switch25-track" aria-hidden="true"></span>
                <span class="sr-only25"></span>
            </label>
                </div>
                 <hr class="padiretso23175927" style="margin-top:30px;">
                  <div class="persoge">
                            <h1 style="color:#672c2c;">Room Amenities</h1>
                            <span>These appear as selectable tags when adding or editing a room.</span>
                        </div>
                        <div class="ametiesgeiod">
                           <div class="ametiesgeiod" id="rrAmenList"></div>
                        </div>
                        <div class="addametiesged">
                           <input type="text" id="rrAmenInput" class="addametiesgedinput" maxlength="40" placeholder="Add an amenities, e.g. Fan/Terrace">
<button type="button" id="rrAmenAdd" class="addametiesgedbutton">Add</button>
                        </div>
                        <div class="buttonparasave5" style="margin-top: 15px;">
    <button type="button" class="buttonparasave1" id="rrSave" disabled>Save Changes</button>
    <button type="button" class="buttonparasave2" id="rrCancel" disabled>Cancel</button>
</div>
<p class="req-status" id="rrStatus" hidden></p>
            </div>

            <div class="st-panel" data-panel="payment-methods">
                <h2 class="st-title">Payment Methods</h2>
                <h1 class="yoursas">Accounts and methods tenants can pay with.</h1>
  <div class="persoge25" style="margin:0; margin-top: 20px;">
                    <h1>Saved payment methods</h1>
                    <span>Used for monthly rent and dorm fee charges.</span>
                </div>
                <div class="linkto">
                    <div class="linkto1">
                        <div class="invoss96" id="invoicesHeader" data-filter="all" role="button" tabindex="0"
                            title="Show all invoices">
                            <div class="invoss196">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <rect x="6" y="2" width="12" height="20" rx="2" />
                                    <line x1="12" y1="18" x2="12.01" y2="18" />
                                </svg>
                            </div>
                            <div class="ibosses96">
                                <h1>GCash Wallet <div class="detofault">
                                        <h1>Default</h1>
                                    </div>
                                </h1>
                                <span>Linked to +63 912 93• ••••</span>
                            </div>
                        </div>
                         <label class="switch25" style="margin-right:20px ;">
               <input type="checkbox" class="pm-toggle" data-key="gcash" @checked($pm['gcash'])>
                <span class="switch25-track" aria-hidden="true"></span>
                <span class="sr-only25"></span>
            </label>
                    </div>
                    <div class="linkto1">
                        <div class="invoss96" id="invoicesHeader" data-filter="all" role="button" tabindex="0"
                            title="Show all invoices">
                            <div class="invoss196">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <rect x="3" y="5" width="18" height="14" rx="2" />
                                    <line x1="3" y1="10" x2="21" y2="10" />
                                    <line x1="7" y1="15" x2="11" y2="15" />
                                </svg>
                            </div>
                            <div class="ibosses96">
                                <h1>Debit / Credit</h1>
                                <span>Linked to •••• 4321</span>
                            </div>
                        </div>
                        <label class="switch25" style="margin-right:20px ;">
              <input type="checkbox" class="pm-toggle" data-key="card" @checked($pm['card'])>
                <span class="switch25-track" aria-hidden="true"></span>
                <span class="sr-only25"></span>
            </label>
                    </div>
                    <div class="linkto1">
                        <div class="invoss96" id="invoicesHeader" data-filter="all" role="button" tabindex="0"
                            title="Show all invoices">
                            <div class="invoss196">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                                    aria-hidden="true">
                                    <line x1="3" y1="21" x2="21" y2="21" />
                                    <path d="M5 21V10l7-5 7 5v11" />
                                    <line x1="9" y1="21" x2="9" y2="13" />
                                    <line x1="15" y1="21" x2="15" y2="13" />
                                </svg>
                            </div>
                            <div class="ibosses96">
                                <h1>Bank Transfer</h1>
                                <span>Linked to •••• 4321</span>
                            </div>
                        </div>
                       <label class="switch25" style="margin-right:20px ;">
               <input type="checkbox" class="pm-toggle" data-key="bank" @checked($pm['bank'])>
                <span class="switch25-track" aria-hidden="true"></span>
                <span class="sr-only25"></span>
            </label>
                    </div>
                </div>
               <button type="button" class="addkopay" id="pmAdd"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>Add payment method</button>
                <hr class="padiretso2317592">
         <div class="buttonparasave5" style="margin-top:20px;">
    <button type="button" class="buttonparasave1" id="pmSave" disabled>Save Changes</button>
    <button type="button" class="buttonparasave2" id="pmCancel" disabled>Cancel</button>
</div>
<p class="req-status" id="pmStatus" hidden></p>
            </div>

           <div class="st-panel" data-panel="notification">
    <h2 class="st-title">Notification</h2>
    <h1 class="yoursas">Choose exactly which alerts go out and through which channel.</h1>

    @php
        $prefs = \App\Support\DormStats::notificationPrefs();
        $channels = [
            'email' => ['Email', '<rect x="2" y="4" width="20" height="16" rx="2"/><path d="M2 7l10 6 10-6"/>'],
            'sms'   => ['SMS',   '<path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/>'],
            'push'  => ['Push',  '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9"/><path d="M13.73 21a2 2 0 0 1-3.46 0"/>'],
        ];
        $groups = [
            'Tenant-facing' => [
                'rent_due'           => ['Rent due reminder', 'Sent 3 days before the due date'],
                'overdue_notice'     => ['Overdue payment notice', 'Sent the day after rent is late'],
                'announcement'       => ['New announcement posted', 'Whenever admin posts a board notice'],
                'maintenance_status' => ['Maintenance status update', 'When their request changes status'],
            ],
            'Admin-facing' => [
                'new_request'     => ['New maintenance request', 'Instant alert when a tenant submits one'],
                'overdue_digest'  => ['Overdue tenant digest', 'Daily summary of unpaid tenants'],
                'new_application' => ['New tenant application', 'When someone applies to move in'],
                'weekly_summary'  => ['Weekly summary', 'Every Monday: rooms, payments and complaints'],
            ],
        ];
    @endphp

    <div class="nt-card" id="ntCard">
        <div class="nt-head">
            <span>Notification</span>
            @foreach ($channels as $ch => [$label, $icon])
                <button type="button" class="nt-col" data-col="{{ $ch }}" title="Switch {{ $label }} on or off for every alert">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                        stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">{!! $icon !!}</svg>
                    <b>{{ $label }}</b>
                </button>
            @endforeach
        </div>

        @foreach ($groups as $groupName => $items)
            <div class="nt-group">{{ $groupName }}</div>
            @foreach ($items as $key => [$title, $desc])
                <div class="nt-row">
                    <div>
                        <p class="nt-name">{{ $title }}</p>
                        <span class="nt-desc">{{ $desc }}</span>
                    </div>
                    @foreach ($channels as $ch => $meta)
                        <div class="nt-cell">
                            <label class="nt-check">
                                <input type="checkbox" data-key="{{ $key }}" data-ch="{{ $ch }}"
                                    aria-label="{{ $title }} by {{ $meta[0] }}" @checked($prefs[$key][$ch])>
                                <span></span>
                            </label>
                        </div>
                    @endforeach
                </div>
            @endforeach
        @endforeach

        <div class="nt-foot">
            <span id="ntCount"></span>
            <span>Click a channel name above to switch it on or off for every alert.</span>
        </div>
    </div>

    <div class="buttonparasave5" style="margin-top:20px;">
        <button type="button" class="buttonparasave1" id="ntSave" disabled>Save Changes</button>
        <button type="button" class="buttonparasave2" id="ntCancel" disabled>Cancel</button>
    </div>
    <p class="req-status" id="ntStatus" hidden></p>
</div>

            <div class="st-panel" data-panel="staff-access">
                <h2 class="st-title">Staff & Access</h2>
                <h1 class="yoursas">Who can sign in to the admin side and what they can do.</h1>
                @php $roleLabels = ['owner' => 'OWNER / ADMIN', 'front_desk' => 'FRONT DESK', 'maintenance' => 'MAINTENANCE']; @endphp
<div class="staffacesshaba"><h1>NAME</h1><h1>ROLE</h1><h1>EMAIL</h1></div>
<hr class="padiretso23175927" style="margin-top:15px;">

@foreach ($staff as $m)
    <div class="profileacces">
        <div class="profileacces12">
            <div class="profileacces1"><h1>{{ strtoupper(mb_substr($m->full_name, 0, 1)) }}</h1></div>
            <h1>{{ $m->full_name }}</h1>
        </div>
        <div class="oblongset"><h1>{{ $roleLabels[$m->staff_role] ?? 'STAFF' }}</h1></div>
        <h1>{{ $m->email }}</h1>
        @if ($m->id === auth()->id())
            <h1>You</h1>
        @else
            <div class="deletesakaeditsettins">
                <button type="button" class="deletesakaeditsettins2 staff-edit" title="Edit"
                    data-id="{{ $m->id }}" data-name="{{ $m->full_name }}" data-email="{{ $m->email }}" data-role="{{ $m->staff_role }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L8 18l-4 1 1-4Z"/></svg>
                </button>
                <button type="button" class="deletesakaeditsettins2 staff-del" title="Remove"
                    data-id="{{ $m->id }}" data-name="{{ $m->full_name }}">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14H6L5 6"/><path d="M10 11v6"/><path d="M14 11v6"/><path d="M9 6V4h6v2"/></svg>
                </button>
                @if ($m->staff_role !== 'owner')
<button type="button" class="deletesakaeditsettins2 staff-owner" title="Make owner"
    data-id="{{ $m->id }}" data-name="{{ $m->full_name }}">
    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M2 8l5 4 5-8 5 8 5-4-2 12H4z"/></svg>
</button>
@endif
            </div>
        @endif
    </div>
    <hr class="padiretso23175927" style="margin-top:13px;">
@endforeach

<button type="button" class="requestmember" id="staffInviteBtn">
    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#555555" stroke-width="2" stroke-linecap="round"><path d="M12 5V19"/><path d="M5 12H19"/></svg>Invite request Member
</button>

            </div>

            <div class="st-panel" data-panel="security">
                <h2 class="st-title">Security</h2>
                <h1 class="yoursas">Password, sessions, and account protection.</h1>
  <div class="persoge25" style="margin:0; margin-top: 20px;">
        <h1>Change password</h1>
    </div>
    <div class="pirsname29">
        <div class="pirsname244">
            <h1>ENTER CURRENT PASSWORD</h1>
            <input type="password" id="pwCurrent" class="laglaggayan5" placeholder="Current password" autocomplete="none">
        </div>
    </div>
    <div class="pirsname29">
        <div class="pirsname24">
            <h1>ENTER NEW PASSWORD</h1>
            <input type="password" id="pwNew" class="laglaggayan5" placeholder="New password" autocomplete="new-password">
        </div>
        <div class="pirsname324">
            <h1>CONFIRM NEW PASSWORD</h1>
            <input type="password" id="pwConfirm" class="laglaggayan5" placeholder="Confirm new password" autocomplete="new-password">
        </div>
    </div>
       <div class="buttonparasave5" style="margin-top: 25px">
        <button type="button" class="buttonparasave15" id="pwSave"
            data-url="{{ route('admin.settings.password') }}">Update password</button>
        <button type="button" class="buttonparasave2" id="pwCancel">Cancel</button>
    </div>
    <p class="req-status" id="pwStatus" hidden></p>

       <hr class="padiretso23175927" style="margin-top:30px;">
    <div class="persoge25" style="margin-top:20px;">
        <h1>Active sessions</h1>
        <span>Devices currently signed in to your account.</span>
    </div>
    <div id="sessionList">
        @foreach ($sessions as $s)
            <div class="session-row" @if ($s->current) data-current="1" @endif>
                <div class="pantayinnnn">
                    <div class="persoge25" style="margin-top:15px;">
                        <span class="spanish">{{ $s->device }}@if ($s->current) · <span style="font-size:.9rem; color: #255538;">This device</span>@endif</span>
                        <span>{{ $s->ip }} · {{ $s->current ? 'Active now' : 'Last active ' . $s->last->diffForHumans() }}</span>
                    </div>
                    @unless ($s->current)
                        <h1 class="logallses" role="button" tabindex="0" style="cursor:pointer" data-session-logout
                            data-url="{{ route('admin.settings.sessions.destroy', $s->id) }}">Log out</h1>
                    @endunless
                </div>
                <hr class="padiretso23175927" style="margin-top:15px;">
            </div>
        @endforeach
    </div>
  
    <p class="req-status" id="sessStatus" hidden></p>

   
    <div class="automarkcap">
        <div class="automarkcap1">
            <h1>Two-factor authentication</h1>
            <span>Not available yet. It needs changes to the login flow.</span>
        </div>
        <label class="switch25" style="margin-right:20px; opacity:.5;">
            <input type="checkbox" disabled>
            <span class="switch25-track" aria-hidden="true"></span>
            <span class="sr-only25"></span>
        </label>
    </div>
    <div class="automarkcap">
        <div class="automarkcap1">
            <h1>Log out after inactivity</h1>
            <span>Signs admins out after the idle time you choose.</span>
        </div>
        <select id="secMinutes" class="laglaggayan2" style="margin-right:20px; width:140px;">
            @foreach ([15, 30, 60, 120] as $m)
                <option value="{{ $m }}" @selected($sec['idle_minutes'] === $m)>{{ $m }} minutes</option>
            @endforeach
        </select>
        <label class="switch25" style="margin-right:20px;">
            <input type="checkbox" id="secIdle" @checked($sec['idle_logout'])>
            <span class="switch25-track" aria-hidden="true"></span>
            <span class="sr-only25"></span>
        </label>
    </div>
    <div class="buttonparasave5" style="margin-top:20px;">
        <button type="button" class="buttonparasave1" id="secSave" disabled>Save Changes</button>
        <button type="button" class="buttonparasave2" id="secCancel" disabled>Cancel</button>
    </div>
        <p class="req-status" id="secStatus" hidden></p>
    </div>

    <div class="st-panel" data-panel="history">
        <h2 class="st-title">History</h2>
        <h1 class="yoursas">Everything admins did across the dormitory system.</h1>

        <div style="margin-top:20px; overflow-x:auto;">
           <table class="hist-table">
                <thead>
                    <tr style="text-align:left; border-bottom:1px solid #ddd;">
                        <th style="padding:8px;">When</th>
                        <th style="padding:8px;">Admin</th>
                        <th style="padding:8px;">Section</th>
                        <th style="padding:8px;">Action</th>
                        <th style="padding:8px;">Details</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($history as $h)
                        <tr style="border-bottom:1px solid #eee; vertical-align:top;">
                            <td style="padding:8px; white-space:nowrap;">
                                {{ $h->created_at->format('M d, Y g:i A') }}<br>
                                <small>{{ $h->created_at->diffForHumans() }}</small>
                            </td>
                            <td style="padding:8px;">{{ $h->admin_name }}<br><small>{{ $h->ip }}</small></td>
                            <td style="padding:8px;">{{ ucwords(str_replace('-', ' ', $h->section)) }}</td>
                            <td style="padding:8px;">{{ $h->action }}</td>
                            <td style="padding:8px;">
                                @foreach (($h->changes ?? []) as $field => $c)
                                  <div class="hist-msg">
                                        <strong>{{ $field }}:</strong>
                                        {{ is_array($c['from'] ?? null) ? json_encode($c['from']) : ($c['from'] ?? '—') }}
                                        →
                                        {{ is_array($c['to'] ?? null) ? json_encode($c['to']) : ($c['to'] ?? '—') }}
                                    </div>
                                @endforeach
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" style="padding:16px; text-align:center; color:#888;">No activity yet.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    
        </section>
    </div>

    <script>
      document.addEventListener('DOMContentLoaded', function () {
    var histMsgs = document.querySelectorAll('.hist-msg');
    function checkLong(el) {
        if (el.classList.contains('open')) return;
        var long = el.scrollHeight > el.clientHeight + 1;
        el.classList.toggle('is-long', long);
        el.title = long ? 'Click to read more' : '';
    }
    Array.prototype.forEach.call(histMsgs, function (el) {
        el.addEventListener('click', function () {
            if (!el.classList.contains('is-long')) return;
            var open = el.classList.toggle('open');
            el.title = open ? 'Click to collapse' : 'Click to read more';
        });
        if (window.ResizeObserver) {
            new ResizeObserver(function () { checkLong(el); }).observe(el);
        } else {
            checkLong(el);
        }
    });
            var items = Array.prototype.slice.call(document.querySelectorAll('.st-item'));
            var panels = Array.prototype.slice.call(document.querySelectorAll('.st-panel'));
            var search = document.getElementById('stSearch');
            var none = document.getElementById('stNone');

            function show(key, updateHash, animate) {
                if (!items.some(function (i) { return i.dataset.tab === key; })) key = 'general';
                items.forEach(function (i) { i.classList.toggle('active', i.dataset.tab === key); });
                panels.forEach(function (p) {
                    var on = p.dataset.panel === key;
                    p.classList.toggle('active', on);
                    p.classList.remove('pop');
                    if (on && animate) { void p.offsetWidth; p.classList.add('pop'); }
                });
                if (updateHash && history.replaceState) history.replaceState(null, '', '#' + key);
            }

            items.forEach(function (i) {
                i.addEventListener('click', function () { show(i.dataset.tab, true, true); });
            });

            var index = {};
            panels.forEach(function (p) {
                index[p.dataset.panel] = p.textContent.toLowerCase().replace(/\s+/g, ' ');
            });

            search.addEventListener('input', function () {
                var q = search.value.trim().toLowerCase().replace(/\s+/g, ' ');
                var shown = 0, first = null, labelHit = null, activeKey = null;

                items.forEach(function (i) {
                    var key = i.dataset.tab;
                    var inLabel = i.textContent.toLowerCase().indexOf(q) !== -1;
                    var match = !q || inLabel || index[key].indexOf(q) !== -1;
                    i.style.display = match ? '' : 'none';
                    if (match) { shown++; if (!first) first = key; }
                    if (q && inLabel && !labelHit) labelHit = key;
                    if (i.classList.contains('active') && match) activeKey = key;
                });
                none.style.display = shown ? 'none' : 'block';
                if (!q || !shown) return;

                show(labelHit || activeKey || first, true, true);
            });

            window.addEventListener('hashchange', function () {
                show(location.hash.replace('#', ''), false, true);
            });

                                show(location.hash.replace('#', '') || 'general', false);

            var genPanel = document.querySelector('[data-panel="general"]');
           var genInputs = Array.prototype.slice.call(genPanel.querySelectorAll('input:not([type="file"])'));
            var genSave = document.getElementById('genSave');
            var genCancel = document.getElementById('genCancel');
            var genStatus = document.getElementById('genStatus');

            function genRead() {
                return genInputs.map(function (i) { return i.type === 'checkbox' ? i.checked : i.value; });
            }
            var genSaved = genRead();

            function genRefresh() {
                var now = genRead();
                var changed = now.some(function (v, n) { return v !== genSaved[n]; });
                genSave.disabled = !changed;
                genCancel.disabled = !changed;
            }

            function genSay(text, kind) {
                genStatus.hidden = !text;
                genStatus.textContent = text || '';
                genStatus.className = 'req-status' + (kind ? ' ' + kind : '');
            }

            genInputs.forEach(function (i) {
                i.addEventListener('input', function () { genSay(''); genRefresh(); });
                i.addEventListener('change', function () { genSay(''); genRefresh(); });
            });

            genCancel.addEventListener('click', function () {
                genInputs.forEach(function (i, n) {
                    if (i.type === 'checkbox') i.checked = genSaved[n]; else i.value = genSaved[n];
                });
                genSay('');
                genRefresh();
            });

          genSave.addEventListener('click', function () {
    if (genSave.disabled) return;

    var val = function (id) { return document.getElementById(id).value.trim(); };
    var headers = {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    };
    function send(url, body) {
        return fetch(url, { method: 'PUT', headers: headers, body: JSON.stringify(body) })
            .then(function (r) { return r.json().catch(function () { return {}; }).then(function (d) { return { ok: r.ok, d: d }; }); });
    }
    function errMsg(d) { return d.errors ? Object.values(d.errors)[0][0] : (d.message || 'Could not save.'); }

    var hours = {};
    document.querySelectorAll('#frontDeskList .timehours1').forEach(function (row) {
        hours[row.dataset.day] = {
            open: row.querySelector('.fd-open').checked,
            from: row.querySelector('.fd-from').value || '08:00',
            to:   row.querySelector('.fd-to').value || '17:00'
        };
    });

    genSave.disabled = true;
    send('{{ route('admin.settings.general') }}', {
        name: val('genName'), phone: val('genPhone'), address: val('genAddress'), email: val('genEmail')
    }).then(function (res) {
        if (!res.ok) { genSay(errMsg(res.d), 'error'); genRefresh(); return; }
        return send('{{ route('admin.settings.front-desk') }}', { hours: hours }).then(function (res2) {
            if (!res2.ok) { genSay(errMsg(res2.d), 'error'); genRefresh(); return; }
            genSaved = genRead();
            var t = document.querySelector('.sb-title');
            if (t) t.textContent = val('genName');
            genSay('Saved. Dormitory details and front desk hours are now live.', 'ok');
            genRefresh();
        });
    }).catch(function () { genSay('Network error. Please try again.', 'error'); genRefresh(); });
});

            genRefresh();
            var ntPanel  = document.querySelector('[data-panel="notification"]');
var ntBoxes  = Array.prototype.slice.call(ntPanel.querySelectorAll('.nt-check input'));
var ntSave   = document.getElementById('ntSave');
var ntCancel = document.getElementById('ntCancel');
var ntStatus = document.getElementById('ntStatus');
var ntCount  = document.getElementById('ntCount');

function ntRead() {
    var o = {};
    ntBoxes.forEach(function (b) {
        (o[b.dataset.key] = o[b.dataset.key] || {})[b.dataset.ch] = b.checked;
    });
    return o;
}
var ntSaved = JSON.stringify(ntRead());

function ntSay(text, kind) {
    ntStatus.hidden = !text;
    ntStatus.textContent = text || '';
    ntStatus.className = 'req-status' + (kind ? ' ' + kind : '');
}

function ntRefresh() {
    var changed = JSON.stringify(ntRead()) !== ntSaved;
    ntSave.disabled = !changed;
    ntCancel.disabled = !changed;
    var on = ntBoxes.filter(function (b) { return b.checked; }).length;
    ntCount.textContent = on + ' of ' + ntBoxes.length + ' alerts on';
}

ntBoxes.forEach(function (b) {
    b.addEventListener('change', function () { ntSay(''); ntRefresh(); });
});

ntPanel.querySelectorAll('.nt-col').forEach(function (btn) {
    btn.addEventListener('click', function () {
        var col = ntBoxes.filter(function (b) { return b.dataset.ch === btn.dataset.col; });
        var allOn = col.every(function (b) { return b.checked; });
        col.forEach(function (b) { b.checked = !allOn; });
        ntSay('');
        ntRefresh();
    });
});

ntCancel.addEventListener('click', function () {
    var saved = JSON.parse(ntSaved);
    ntBoxes.forEach(function (b) { b.checked = saved[b.dataset.key][b.dataset.ch]; });
    ntSay('');
    ntRefresh();
});

ntSave.addEventListener('click', function () {
    if (ntSave.disabled) return;
    ntSave.disabled = true;
    fetch('{{ route('admin.settings.notifications') }}', {
        method: 'PUT',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ prefs: ntRead() })
    })
    .then(function (r) { return r.json().then(function (d) { return { ok: r.ok, d: d }; }); })
    .then(function (res) {
        if (!res.ok) { ntSay('Could not save notification settings.', 'error'); ntRefresh(); return; }
        ntSaved = JSON.stringify(ntRead());
        ntSay('Notification settings saved.', 'ok');
        ntRefresh();
    })
    .catch(function () { ntSay('Network error. Please try again.', 'error'); ntRefresh(); });
});

ntRefresh();
        });
    </script>
    <style>
.sf-overlay{position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);display:flex;align-items:center;justify-content:center;padding:20px}
.sf-overlay[hidden]{display:none}
.sf-box{background:#fff;border-radius:12px;padding:28px;width:100%;max-width:440px;font-family:'Nunito Sans',sans-serif;box-shadow:0 10px 40px rgba(0,0,0,.25)}
.sf-box h2{margin:0 0 6px;font-family:'Playfair Display',serif;color:#672c2c;font-size:1.4rem}
.sf-sub{margin:0 0 16px;font-size:.85rem;font-weight:600;color:#000000a4}
.sf-box label{display:block;margin-bottom:12px;font-size:.75rem;font-weight:800;color:#000000c4}
.sf-box input,.sf-box select{display:block;width:100%;box-sizing:border-box;margin-top:5px;height:42px;padding:0 12px;border:1px solid #00000030;border-radius:6px;font:inherit;font-size:.9rem}
.sf-actions{display:flex;gap:10px;margin-top:18px}
.sf-small{max-width:360px;text-align:center}
.sf-small .sf-actions{justify-content:center}
</style>

<div id="staffModal" class="sf-overlay" hidden>
    <div class="sf-box" role="dialog" aria-modal="true" aria-labelledby="sfTitle">
        <h2 id="sfTitle">Invite team member</h2>
        <p class="sf-sub">They sign in at the normal login page with this email and password.</p>
        <label>FULL NAME<input type="text" id="sfName" maxlength="100"></label>
        <label>EMAIL<input type="email" id="sfEmail"></label>
        <label>ROLE
            <select id="sfRole">
                <option value="front_desk">Front Desk</option>
                <option value="maintenance">Maintenance</option>
                <option value="owner">Owner / Admin</option>
            </select>
        </label>
        <label>PASSWORD<input type="text" id="sfPass" autocomplete="off"></label>
        <p class="req-status" id="sfStatus" hidden></p>
        <div class="sf-actions">
            <button type="button" class="buttonparasave1" id="sfSave">Save</button>
            <button type="button" class="buttonparasave2" id="sfCancel">Cancel</button>
        </div>
    </div>
</div>
<div id="cfModal" class="sf-overlay" hidden>
    <div class="sf-box sf-small" role="dialog" aria-modal="true">
        <h2 id="cfTitle">Are you sure?</h2>
        <p class="sf-sub" id="cfText"></p>
        <div class="sf-actions">
            <button type="button" class="buttonparasave1" id="cfYes">Yes</button>
            <button type="button" class="buttonparasave2" id="cfNo">Cancel</button>
        </div>
    </div>
</div>
<div id="pmNA" class="sf-overlay" hidden>
    <div class="sf-box sf-small" role="dialog" aria-modal="true">
        <h2>Not available yet</h2>
        <p class="sf-sub">Adding a new payment method isn't available right now. Please try again later.</p>
        <div class="sf-actions"><button type="button" class="buttonparasave1" id="pmNAok">Okay</button></div>
    </div>
</div>

<script>
(function () {
    var $ = function (id) { return document.getElementById(id); };
    var csrf = document.querySelector('meta[name="csrf-token"]').content;

    function api(url, method, body) {
        return fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
            body: body ? JSON.stringify(body) : undefined
        }).then(function (r) {
            return r.json().catch(function () { return {}; }).then(function (d) { return { ok: r.ok, d: d }; });
        });
    }
    function firstError(d) { return d.errors ? Object.values(d.errors)[0][0] : (d.message || 'Something went wrong.'); }
    function say(el, text, kind) { el.hidden = !text; el.textContent = text || ''; el.className = 'req-status' + (kind ? ' ' + kind : ''); }
var logoFile = $('logoFile'), logoBox = $('logoBox'), logoRemove = $('logoRemove');
var HOUSE = '<svg viewBox="0 0 24 24" width="36" height="36" fill="#fff" aria-hidden="true"><path d="M12 2.5 3 10v11h6.5v-6h5v6H21V10z"/></svg>';

function showLogo(url) {
    logoBox.innerHTML = '';
    if (url) {
        var img = document.createElement('img');
        img.src = url; img.alt = 'Logo';
        img.style.cssText = 'width:100%;height:100%;object-fit:cover;border-radius:6px;';
        logoBox.appendChild(img);
    } else {
        logoBox.innerHTML = HOUSE;
    }
    logoRemove.disabled = !url;
}
$('logoUpload').addEventListener('click', function () { logoFile.click(); });
logoFile.addEventListener('change', function () {
    var f = logoFile.files[0];
    if (!f) return;
    if (f.size > 2 * 1024 * 1024) { say($('logoStatus'), 'Logo must be 2 MB or smaller.', 'error'); logoFile.value = ''; return; }
    var fd = new FormData(); fd.append('logo', f);
    fetch('{{ route('admin.settings.logo') }}', {
        method: 'POST',
        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
        body: fd
    }).then(function (r) {
        return r.json().catch(function () { return {}; }).then(function (d) { return { ok: r.ok, d: d }; });
    }).then(function (res) {
        logoFile.value = '';
        if (!res.ok) { say($('logoStatus'), firstError(res.d), 'error'); return; }
        showLogo(res.d.url);
        document.querySelectorAll('.sb-logo').forEach(function (el) { if (el.tagName === 'IMG') el.src = res.d.url; });
        say($('logoStatus'), 'Logo updated.', 'ok');
    }).catch(function () { say($('logoStatus'), 'Network error. Please try again.', 'error'); });
});
logoRemove.addEventListener('click', function () {
    if (logoRemove.disabled) return;
    api('{{ route('admin.settings.logo.remove') }}', 'DELETE').then(function (res) {
        if (!res.ok) { say($('logoStatus'), firstError(res.d), 'error'); return; }
        showLogo(null);
        say($('logoStatus'), 'Logo removed. Reload to see the default icon in the sidebar.', 'ok');
    });
});
(function () {
    var f = [$('pwCurrent'), $('pwNew'), $('pwConfirm')];
    function clear() { f.forEach(function (i) { i.value = ''; }); }
    $('pwCancel').addEventListener('click', function () { clear(); say($('pwStatus'), ''); });
    $('pwSave').addEventListener('click', function () {
        var btn = this; btn.disabled = true; say($('pwStatus'), '');
        api(btn.dataset.url, 'PUT', {
            current_password: f[0].value,
            password: f[1].value,
            password_confirmation: f[2].value
        }).then(function (res) {
            btn.disabled = false;
            if (!res.ok) { say($('pwStatus'), firstError(res.d), 'error'); return; }
            clear();
            say($('pwStatus'), res.d.message, 'ok');
            document.querySelectorAll('#sessionList .session-row:not([data-current])').forEach(function (r) { r.remove(); });
        }).catch(function () { btn.disabled = false; say($('pwStatus'), 'Network error. Please try again.', 'error'); });
    });
})();

function bindSession(btn) {
    function go() {
        if (!confirm('Log out this device?')) return;
        api(btn.dataset.url, 'DELETE').then(function (res) {
            if (!res.ok) { say($('sessStatus'), firstError(res.d), 'error'); return; }
            btn.closest('.session-row').remove();
            say($('sessStatus'), res.d.message, 'ok');
        });
    }
    btn.addEventListener('click', go);
    btn.addEventListener('keydown', function (e) { if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); go(); } });
}
document.querySelectorAll('[data-session-logout]').forEach(bindSession);


function secRead() { return { idle_logout: $('secIdle').checked, idle_minutes: parseInt($('secMinutes').value, 10) }; }
var secSaved = JSON.stringify(secRead());
function secRefresh() {
    var changed = JSON.stringify(secRead()) !== secSaved;
    $('secSave').disabled = !changed; $('secCancel').disabled = !changed;
}
['secIdle', 'secMinutes'].forEach(function (id) {
    $(id).addEventListener('change', function () { say($('secStatus'), ''); secRefresh(); });
});
$('secCancel').addEventListener('click', function () {
    var s = JSON.parse(secSaved);
    $('secIdle').checked = s.idle_logout; $('secMinutes').value = s.idle_minutes;
    say($('secStatus'), ''); secRefresh();
});
$('secSave').addEventListener('click', function () {
    $('secSave').disabled = true;
    api('{{ route('admin.settings.security') }}', 'PUT', secRead()).then(function (res) {
        if (!res.ok) { say($('secStatus'), firstError(res.d), 'error'); secRefresh(); return; }
        secSaved = JSON.stringify(secRead());
        say($('secStatus'), 'Saved. The inactivity timer applies to all admin accounts.', 'ok');
        secRefresh();
    }).catch(function () { say($('secStatus'), 'Network error. Please try again.', 'error'); secRefresh(); });
});
secRefresh();
    var staffUrl = '{{ route('admin.settings.staff.store') }}';
    var modal = $('staffModal'), editId = null;

    function openStaff(m) {
        editId = m ? m.id : null;
        $('sfTitle').textContent = m ? 'Edit team member' : 'Invite team member';
        $('sfName').value = m ? m.name : '';
        $('sfEmail').value = m ? m.email : '';
        $('sfRole').value = m ? m.role : 'front_desk';
        $('sfPass').value = '';
        $('sfPass').placeholder = m ? 'Leave blank to keep the current password' : 'At least 8 characters';
        say($('sfStatus'), '');
        modal.hidden = false;
        $('sfName').focus();
    }
    function closeStaff() { modal.hidden = true; }

    $('staffInviteBtn').addEventListener('click', function () { openStaff(null); });
    $('sfCancel').addEventListener('click', closeStaff);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeStaff(); });

    document.querySelectorAll('.staff-edit').forEach(function (b) {
        b.addEventListener('click', function () {
            openStaff({ id: b.dataset.id, name: b.dataset.name, email: b.dataset.email, role: b.dataset.role });
        });
    });
   function ask(title, text, yesLabel, onYes) {
    $('cfTitle').textContent = title;
    $('cfText').textContent = text;
    $('cfYes').textContent = yesLabel;
    $('cfModal').hidden = false;
    $('cfYes').onclick = function () { $('cfModal').hidden = true; onYes(); };
}
$('cfNo').addEventListener('click', function () { $('cfModal').hidden = true; });
$('cfModal').addEventListener('click', function (e) { if (e.target === this) this.hidden = true; });

document.querySelectorAll('.staff-del').forEach(function (b) {
    b.addEventListener('click', function () {
        ask('Remove staff member', 'Remove ' + b.dataset.name + '? They will no longer be able to sign in.', 'Remove', function () {
            api(staffUrl + '/' + b.dataset.id, 'DELETE').then(function (res) {
                if (res.ok) location.reload(); else alert(firstError(res.d));
            });
        });
    });
});

document.querySelectorAll('.staff-owner').forEach(function (b) {
    b.addEventListener('click', function () {
        ask('Make owner', 'Give ' + b.dataset.name + ' full Owner / Admin access?', 'Make owner', function () {
            api(staffUrl + '/' + b.dataset.id + '/owner', 'PUT').then(function (res) {
                if (res.ok) location.reload(); else alert(firstError(res.d));
            });
        });
    });
});
    $('sfSave').addEventListener('click', function () {
        var btn = this; btn.disabled = true;
        var body = { full_name: $('sfName').value.trim(), email: $('sfEmail').value.trim(), staff_role: $('sfRole').value, password: $('sfPass').value };
        api(editId ? staffUrl + '/' + editId : staffUrl, editId ? 'PUT' : 'POST', body).then(function (res) {
            btn.disabled = false;
            if (res.ok) location.reload(); else say($('sfStatus'), firstError(res.d), 'error');
        }).catch(function () { btn.disabled = false; say($('sfStatus'), 'Network error. Please try again.', 'error'); });
    });

  var pmBoxes = [].slice.call(document.querySelectorAll('.pm-toggle'));
function pmRead() { var o = {}; pmBoxes.forEach(function (b) { o[b.dataset.key] = b.checked; }); return o; }
var pmSaved = JSON.stringify(pmRead());
function pmRefresh() {
    var changed = JSON.stringify(pmRead()) !== pmSaved;
    $('pmSave').disabled = !changed; $('pmCancel').disabled = !changed;
}
pmBoxes.forEach(function (b) { b.addEventListener('change', function () { say($('pmStatus'), ''); pmRefresh(); }); });
$('pmCancel').addEventListener('click', function () {
    var s = JSON.parse(pmSaved);
    pmBoxes.forEach(function (b) { b.checked = s[b.dataset.key]; });
    say($('pmStatus'), ''); pmRefresh();
});
$('pmSave').addEventListener('click', function () {
    $('pmSave').disabled = true;
    api('{{ route('admin.settings.payment-methods') }}', 'PUT', { methods: pmRead() }).then(function (res) {
        if (!res.ok) { say($('pmStatus'), firstError(res.d), 'error'); pmRefresh(); return; }
        pmSaved = JSON.stringify(pmRead());
        say($('pmStatus'), 'Saved. Tenants now see these payment methods.', 'ok');
        pmRefresh();
    }).catch(function () { say($('pmStatus'), 'Network error. Please try again.', 'error'); pmRefresh(); });
});
$('pmAdd').addEventListener('click', function () { $('pmNA').hidden = false; });
$('pmNAok').addEventListener('click', function () { $('pmNA').hidden = true; });
pmRefresh();
    var rrSaved = JSON.stringify(@json($rr));
var rrAmen  = @json($rr['amenities']);
var X_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4L20 20"/><path d="M20 4L4 20"/></svg>';

function rrRead() {
    var due = $('rrDue').value.trim();
    return {
        capacity: parseInt($('rrCapacity').value, 10), rate: parseFloat($('rrRate').value),
        due_day: due === '' ? null : parseInt(due, 10),
        grace: parseInt($('rrGrace').value, 10), late_fee: parseFloat($('rrLate').value),
        auto_full: $('rrAuto').checked, deposit: $('rrDeposit').checked, amenities: rrAmen.slice()
    };
}
function rrRefresh() {
    var changed = JSON.stringify(rrRead()) !== rrSaved;
    $('rrSave').disabled = !changed; $('rrCancel').disabled = !changed;
}
function rrRender() {
    var box = $('rrAmenList'); box.innerHTML = '';
    rrAmen.forEach(function (n, i) {
        var d = document.createElement('div'); d.className = 'ametiesgeiod1';
        var h = document.createElement('h1'); h.textContent = n;
        var x = document.createElement('div'); x.className = 'logbisettingsa'; x.style.cursor = 'pointer';
        x.setAttribute('role', 'button'); x.innerHTML = X_SVG;
        x.addEventListener('click', function () { rrAmen.splice(i, 1); rrRender(); rrRefresh(); });
        d.appendChild(h); d.appendChild(x); box.appendChild(d);
    });
}
function rrFill(s) {
    $('rrCapacity').value = s.capacity; $('rrRate').value = s.rate; $('rrDue').value = s.due_day === null ? '' : s.due_day;
    $('rrGrace').value = s.grace; $('rrLate').value = s.late_fee;
    $('rrAuto').checked = s.auto_full; $('rrDeposit').checked = s.deposit;
    rrAmen = s.amenities.slice(); rrRender();
}
['rrCapacity', 'rrRate', 'rrDue', 'rrGrace', 'rrLate', 'rrAuto', 'rrDeposit'].forEach(function (id) {
    $(id).addEventListener('input', function () { say($('rrStatus'), ''); rrRefresh(); });
    $(id).addEventListener('change', function () { say($('rrStatus'), ''); rrRefresh(); });
});
function rrAddAmen() {
    var n = $('rrAmenInput').value.trim().slice(0, 40);
    if (n && !rrAmen.some(function (a) { return a.toLowerCase() === n.toLowerCase(); })) { rrAmen.push(n); rrRender(); rrRefresh(); }
    $('rrAmenInput').value = '';
}
$('rrAmenAdd').addEventListener('click', rrAddAmen);
$('rrAmenInput').addEventListener('keydown', function (e) { if (e.key === 'Enter') { e.preventDefault(); rrAddAmen(); } });
$('rrCancel').addEventListener('click', function () { rrFill(JSON.parse(rrSaved)); say($('rrStatus'), ''); rrRefresh(); });
$('rrSave').addEventListener('click', function () {
    $('rrSave').disabled = true;
    api('{{ route('admin.settings.room-rent') }}', 'PUT', rrRead()).then(function (res) {
        if (!res.ok) { say($('rrStatus'), firstError(res.d), 'error'); rrRefresh(); return; }
        rrSaved = JSON.stringify(rrRead());
        say($('rrStatus'), 'Saved. Rooms and tenant billing now use these values.', 'ok');
        rrRefresh();
    }).catch(function () { say($('rrStatus'), 'Network error. Please try again.', 'error'); rrRefresh(); });
});
rrRender(); rrRefresh();

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape') { modal.hidden = true; $('pmNA').hidden = true; $('cfModal').hidden = true; }
    });
})();
</script>
@endsection