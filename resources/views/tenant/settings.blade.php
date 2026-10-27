@extends('layouts.app')

@section('title', 'Settings')

@section('content')
    @php
        $u = auth()->user();

        $fields = [$u->full_name, $u->email, $u->phone_number, $u->birthday, $u->sex, $u->address, $u->room_number, $u->move_in_date];
        $filled = count(array_filter($fields, fn($v) => filled($v)));
        $percent = (int) round($filled / count($fields) * 100);

        $tabs = [
            'general' => ['General', '<path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z" /><polyline points="9 22 9 12 15 12 15 22" />'],
            'room-info' => ['Room Info', '<rect x="3" y="3" width="18" height="18" rx="2" /><line x1="9" y1="3" x2="9" y2="21" />'],
            'payment' => ['Payment', '<rect x="1" y="4" width="22" height="16" rx="2" /><line x1="1" y1="10" x2="23" y2="10" />'],
            'notifications' => ['Notifications', '<path d="M18 8A6 6 0 0 0 6 8c0 7-3 9-3 9h18s-3-2-3-9" /><path d="M13.73 21a2 2 0 0 1-3.46 0" />'],
            'billing' => ['Billing', '<path d="M4 2v20l2-1 2 1 2-1 2 1 2-1 2 1 2-1 2 1V2l-2 1-2-1-2 1-2-1-2 1-2-1-2 1z" /><line x1="8" y1="9" x2="16" y2="9" /><line x1="8" y1="13" x2="14" y2="13" />'],
            'security' => ['Security', '<rect x="3" y="11" width="18" height="11" rx="2" /><path d="M7 11V7a5 5 0 0 1 10 0v4" />'],
        ];
    @endphp

    <h1 class="tit">Settings</h1>
    <h1 class="ba">Manage your account and keep it secure.</h1>

    <div class="st-wrap">

        <aside class="st-side">
            <label class="st-search">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                    <circle cx="11" cy="11" r="8" />
                    <line x1="21" y1="21" x2="16.65" y2="16.65" />
                </svg>
                <input type="text" id="stSearch" placeholder="Search settings..." autocomplete="off">
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

            <div class="st-complete">
                <p class="st-complete-label">Profile completeness</p>
                <p class="st-complete-text">{{ $percent }}% complete</p>
                <div class="st-bar">
                    <div class="st-bar-fill" style="width: {{ $percent }}%"></div>
                </div>
            </div>
        </aside>

        <section class="st-main">

            <div class="st-panel active" data-panel="general">
                @php
                    $u = auth()->user();
                    $parts = preg_split('/\s+/', trim($u->full_name ?? ''));
                    $lastName = count($parts) > 1 ? array_pop($parts) : '';
                    $firstName = implode(' ', $parts);
                    $ec = $u->emergencyContact;
                @endphp

                <h2 class="st-title">General</h2>
                <h1 class="yoursas">Your personal details and how the dorm office reaches you.</h1>

                @if (session('status'))
                    <p style="color:#16a34a;margin:8px 0;">{{ session('status') }}</p>
                @endif
                @if ($errors->any())
                    <div style="color:#dc2626;margin:8px 0;">
                        @foreach ($errors->all() as $error)
                        <p>{{ $error }}</p> @endforeach
                    </div>
                @endif

                <div class="general">
                    <div class="invoss93" id="invoicesHeader" data-filter="all" role="button" tabindex="0"
                        title="Show all invoices">
                        <div class="invoss193">
                            <h1>{{ $u->initial }}</h1>
                        </div>
                        <div class="ibosses93">
                            <h1 class="washi">{{ $u->display_name }}</h1>
                            <span>Tenant since {{ ($u->move_in_date ?? $u->created_at)->format('F Y') }}</span>
                        </div>
                    </div>
                    <h1 class="changephoto" id="editGeneral">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                            stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                            <path d="M12 20h9" />
                            <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
                        </svg>Edit General
                    </h1>
                </div>

                <form method="POST" id="generalForm" action="{{ route('tenant.settings.general') }}">
                    @csrf
                    @method('PUT')
                    <fieldset id="generalFields" disabled>
                        <div class="persoge">
                            <h1>Personal Information</h1>
                            <span>This is how you appear to dorm staff and other tenants.</span>
                        </div>

                        <div class="pirsname29">
                            <div class="pirsname">
                                <h1>FIRST NAME</h1>
                                <input type="text" id="firstName" name="first_name" class="laglaggayan"
                                    value="{{ old('first_name', $firstName) }}">
                            </div>
                            <div class="pirsname3">
                                <h1>LAST NAME</h1>
                                <input type="text" name="last_name" class="laglaggayan"
                                    value="{{ old('last_name', $lastName) }}">
                            </div>
                            <div class="pirsname4">
                                <h1>CIVIL STATUS</h1>
                                <select name="civil_status" class="laglaggayan">
                                    <option value="">Select</option>
                                    @foreach (['Single', 'Married', 'Widowed', 'Separated'] as $opt)
                                        <option value="{{ $opt }}" @selected(old('civil_status', $u->civil_status) === $opt)>
                                            {{ $opt }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="pirsname29">
                            <div class="pirsname">
                                <h1>EMAIL ADDRESS</h1>
                                <input type="email" name="email" class="laglaggayan" value="{{ old('email', $u->email) }}">
                            </div>
                            <div class="pirsname3">
                                <h1>PHONE NUMBER</h1>
                                <input type="text" name="phone_number" class="laglaggayan"
                                    value="{{ old('phone_number', $u->phone_number) }}">
                            </div>
                            <div class="pirsname4">
                                <h1>GENDER</h1>
                                <select name="sex" class="laglaggayan">
                                    <option value="">Select</option>
                                    <option value="male" @selected(old('sex', strtolower($u->sex ?? '')) === 'male')>Male
                                    </option>
                                    <option value="female" @selected(old('sex', strtolower($u->sex ?? '')) === 'female')>
                                        Female</option>
                                </select>
                            </div>
                        </div>

                        <div class="pirsname29">
                            <div class="pirsname24">
                                <h1>DATE OF BIRTH</h1>
                                <input type="date" name="birthday" class="laglaggayan"
                                    value="{{ old('birthday', optional($u->birthday)->format('Y-m-d')) }}">
                            </div>
                            <div class="pirsname324">
                                <h1>OCCUPATION / SCHOOL</h1>
                                <input type="text" name="occupation" class="laglaggayan"
                                    value="{{ old('occupation', $u->occupation) }}">
                            </div>
                        </div>

                        <div class="persoge25">
                            <h1>Emergency Contact</h1>
                            <span>We'll only reach out to this person in case of an emergency.</span>
                        </div>

                        <div class="pirsname29">
                            <div class="pirsname24">
                                <h1>FULL NAME</h1>
                                <input type="text" name="ec_name" class="laglaggayan"
                                    value="{{ old('ec_name', $ec->name ?? '') }}">
                            </div>
                            <div class="pirsname324">
                                <h1>RELATIONSHIP</h1>
                                <input type="text" name="ec_relationship" class="laglaggayan"
                                    value="{{ old('ec_relationship', $ec->relationship ?? '') }}">
                            </div>
                        </div>

                        <div class="pirsname29">
                            <div class="pirsname244">
                                <h1>PHONE NUMBER</h1>
                                <input type="text" name="ec_phone" class="laglaggayan"
                                    value="{{ old('ec_phone', $ec->phone ?? '') }}">
                            </div>
                        </div>
                    </fieldset>
                    <div class="buttonparasave" id="saveBar" style="display:none">
                        <button type="submit" class="buttonparasave1">Save Changes</button>
                        <button type="button" class="buttonparasave2" id="cancelEdit">Cancel</button>
                    </div>
                </form>
            </div>

            <div class="st-panel" data-panel="room-info">
                <h2 class="st-title">Room Info</h2>
                <h1 class="yoursas">Your assigned room, bed number, and roommate details.</h1>
                @php $u = auth()->user(); @endphp
                <div class="roombedmove">
                    <div class="roombedmove1">
                        <h1>ROOM NUMBER</h1>
                        <h2>{{ $u->room_number ?: 'No room yet' }}</h2>
                        <span>{{ $myRoom->sharing_type ?? 'Book a room to get started' }}</span>
                    </div>
                    <div class="roombedmove1">
                        <h1>BED ASSIGNMENT</h1>
                        <h2>{{ $u->bed_number ? 'Bed ' . $u->bed_number : '—' }}</h2>
                        <span>{{ $u->bed_deck ? $u->bed_deck . ' Bunk' : '' }}</span>
                    </div>
                    <div class="roombedmove1">
                        <h1>MOVE-IN DATE</h1>
                        <h2>{{ $u->move_in_date ? \Illuminate\Support\Carbon::parse($u->move_in_date)->format('M j, Y') : '—' }}
                        </h2>
                        <span>Lease renews annually</span>
                    </div>
                </div>
                <div class="persoge25" style="margin:0; margin-top: 20px;">
                    <h1>Maintenance request</h1>
                    <span>Report an issue in your and dorm maintenance will follow up.</span>
                </div>
                <div class="pirsname29">
                    <div class="pirsname24">
                        <h1>ISSUE CATEGORY</h1>
                        <select id="mCategory" class="laglaggayan2">
                            <option value="">Select a category</option>
                            <option value="plumbing">Plumbing (faucet, toilet, drainage)</option>
                            <option value="electrical">Electrical (outlet, lighting, wiring)</option>
                            <option value="furniture">Furniture / fixtures</option>
                            <option value="ventilation">Ventilation</option>
                            <option value="pest">Pest control</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                    <div class="pirsname324">
                        <h1>URGENCY</h1>
                        <select id="mUrgency" class="laglaggayan2">
                            <option value="">Select urgency</option>
                            <option value="low">Low - can wait a few days</option>
                            <option value="medium">Medium - needs attention this week</option>
                            <option value="high">High - urgent, affects daily use</option>
                        </select>
                    </div>
                </div>

                <div class="pirsname29">
                    <div class="pirsname2443">
                        <h1>DESCRIBE THE ISSUE</h1>
                        <textarea id="mDetails" class="laglaggayan22" maxlength="1000"
                            placeholder="e.g. Faucet in the shared bathroom drips occasionally"></textarea>
                    </div>
                </div>

                <button type="button" class="submitmain" id="mSubmit"
                    data-url="{{ route('tenant.requests.maintenance') }}">Submit maintenance request</button>
                <p class="req-status" id="mStatus" hidden></p>
                <hr class="padiretso231759">
                <div class="persoge25" style="margin:0; margin-top: 20px;">
                    <h1>Request room transfer</h1>
                    <span>Ask to be moved to a different room or bed. Subject to availability and dorm management
                        approval.</span>
                </div>
                <div class="pirsname29">
                    <div class="pirsname24">
                        <h1>PREFERRED ROOM</h1>
                        <select name="preferred_room" id="trRoom" class="laglaggayan2">
                            <option value="">Select a room</option>
                            @foreach ($transferOptions as $roomNo => $info)
                                <option value="{{ $roomNo }}">Room {{ $roomNo }} · {{ $info['free'] }} available</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="pirsname324">
                        <h1>PREFERRED BED AND DECK</h1>
                        <select name="preferred_bed" id="trBed" class="laglaggayan2" disabled>
                            <option value="">Select a room first</option>
                        </select>
                    </div>
                </div>

                <div class="pirsname29">
                    <div class="pirsname2442">
                        <h1>REASON FOR TRANSFER</h1>
                        <select name="reason" id="trReason" class="laglaggayan2">
                            <option value="">Select a reason</option>
                            <option value="roommate_conflict">Roommate conflict</option>
                            <option value="noise">Noise / Environment</option>
                            <option value="entrance">Closer to entrance</option>
                            <option value="medical">Medical / accessibility need</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                <div class="pirsname29">
                    <div class="pirsname2443">
                        <h1>ADDITIONAL DETAILS (OPTIONAL)</h1>
                        <textarea id="trDetails" class="laglaggayan22" maxlength="1000"
                            placeholder="Anything dorm management should know"></textarea>
                    </div>
                </div>

                <button type="button" class="submitmain" id="trSubmit"
                    data-url="{{ route('tenant.requests.transfer') }}">Submit transfer request</button>
                <p class="req-status" id="trStatus" hidden></p>
            </div>

            <div class="st-panel" data-panel="payment">
                <h2 class="st-title">Payment</h2>
                <h1 class="yoursas">Manage your payment methods and billing preferences.</h1>
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
                        <h1 class="manageee">Manage</h1>
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
                        <h1 class="manageee">Manage</h1>
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
                        <h1 class="manageee">Manage</h1>
                    </div>
                </div>
                <button class="addkopay"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <line x1="12" y1="5" x2="12" y2="19" />
                        <line x1="5" y1="12" x2="19" y2="12" />
                    </svg>Add payment method</button>
                <hr class="padiretso2317592">
                <div class="persoge25" style="margin:0; margin-top: 20px;">
                    <h1>Auto-pay</h1>
                    <span>Automatically charge your default method on the due date.</span>
                </div>
                <div class="autopayline">
                    <div class="persoge25" style="margin:0;">
                        <h1>Enable auto-pay for monthly rent</h1>
                        <span>Charges your default method 3 days before due date</span>
                    </div>

                    <label class="switch25">
                        <input type="checkbox" id="autopaySetting" checked>
                        <span class="switch25-track" aria-hidden="true"></span>
                        <span class="sr-only25">Enable auto-pay for monthly rent</span>
                    </label>
                </div>
                <div class="buttonparasave5">
                    <button type="button" class="buttonparasave1" id="paySave" disabled>Save Changes</button>
                    <button type="button" class="buttonparasave2" id="payCancel" disabled>Cancel</button>
                </div>
                <p class="req-status" id="payStatus" hidden></p>
            </div>

            <div class="st-panel" data-panel="notifications">
                <h2 class="st-title">Notifications</h2>
                <h1 class="yoursas">Choose what updates you receive and how.</h1>

            </div>

            <div class="st-panel" data-panel="billing">
                <div class="st-panel" data-panel="billing">
                    <h2 class="st-title">Billing</h2>
                    <h1 class="yoursas">Track your payments, inquiries and account activity.</h1>

                    @php
                        $bill = $billing;
                        $history = $bill['history'] ?? [];
                    @endphp

                    <div class="roombedmove">
                        <div class="roombedmove1">
                            <h1>MONTHLY RENT</h1>
                            <h2>{{ $bill['rent_text'] }}</h2>
                            <span>{{ $bill['due_day_text'] ? 'Due on the ' . $bill['due_day_text'] . ' of each month' : 'Book a room to get started' }}</span>
                        </div>
                        <div class="roombedmove1">
                            <h1>OUTSTANDING BALANCE</h1>
                            <h2>{{ $bill['balance_text'] }}</h2>
                            <span>{{ $bill['balance_sub'] }}</span>
                        </div>
                        <div class="roombedmove1">
                            <h1>NEXT DUE DATE</h1>
                            <h2>{{ $bill['next_due_text'] }}</h2>
                            <span>{{ $bill['next_due_sub'] }}</span>
                        </div>
                    </div>

                    <div class="persoge25" style="margin:0; margin-top: 20px;">
                        <h1>Billing history</h1>
                    </div>
                    <div class="periodbalancestatus">
                        <h1 class="periodbalancestatus1">Period</h1>
                        <h1 class="periodbalancestatus1">Balance</h1>
                        <h1 class="periodbalancestatus1">Status</h1>
                        <h1 class="periodbalancestatus1 receiptcell"></h1>
                    </div>
                    <hr class="padiretso23175927">

                    <div id="billHistory">
                        @forelse ($history as $h)
                                            <div class="bh-row">
                                                <div class="periodbalancestatus">
                                                    <h1 class="periodbalancestatus12">{{ $h['label'] }}</h1>
                                                    <h1 class="periodbalancestatus12">₱{{ number_format($h['paid_amount'], 2) }}</h1>
                                                    <h1 class="periodbalancestatus12">
                                                        <div class="anostatbayad">
                                                            <h1>Paid</h1>
                                                        </div>
                                                    </h1>
                                                    <h1 class="periodbalancestatus12 receiptcell" role="button" tabindex="0" data-receipt="{{ json_encode([
                                'ref' => $h['ref'],
                                'period' => $h['label'],
                                'amount' => '₱' . number_format($h['paid_amount'], 2),
                                'method' => $h['method'],
                                'paid' => $h['paid_at_text'],
                            ]) }}">View receipt</h1>
                                                </div>
                                                <hr class="padiretso23175927">
                                            </div>
                        @empty
                            <p class="bh-empty">No payments yet. Your paid invoices will show up here.</p>
                        @endforelse
                    </div>
                    <div class="ann-pager" id="billPager"></div>

                    <div class="persoge25" style="margin-top:20px;">
                        <h1>Billing contact</h1>
                        <span>Where invoices and receipt are sent.</span>
                    </div>

                    <h1 class="bemail-label">Billing email</h1>
                    <div class="bemail" id="bEmailBox">
                        <span class="bemail-text" id="bEmailText">{{ $u->billing_email ?: $u->email }}</span>
                        <input type="email" id="bEmailInput" class="bemail-input" maxlength="255" autocomplete="email"
                            value="{{ $u->billing_email ?: $u->email }}" hidden>
                        <button type="button" class="bemail-edit" id="bEmailEdit">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                                <path d="M12 20h9" />
                                <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
                            </svg>Edit
                        </button>
                    </div>
                    <p class="req-status" id="bStatus" hidden></p>

                    <div class="buttonparasave5">
                        <button type="button" class="buttonparasave1" id="bSave" disabled
                            data-url="{{ route('tenant.settings.billing-email') }}">Save Changes</button>
                        <button type="button" class="buttonparasave2" id="bCancel" disabled>Cancel</button>
                    </div>
                </div>
            </div>

            <div class="st-panel" data-panel="security">
                <h2 class="st-title">Security</h2>
                <h1 class="yoursas">Keep your admin account safe.</h1>
            </div>

        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var items = Array.prototype.slice.call(document.querySelectorAll('.st-item'));
            var panels = Array.prototype.slice.call(document.querySelectorAll('.st-panel'));
            var search = document.getElementById('stSearch');
            var none = document.getElementById('stNone');
            var ALIAS = { payments: 'payment' };

            function show(key, updateHash) {
                if (!items.some(function (i) { return i.dataset.tab === key; })) key = 'general';
                items.forEach(function (i) { i.classList.toggle('active', i.dataset.tab === key); });
                panels.forEach(function (p) { p.classList.toggle('active', p.dataset.panel === key); });
                if (updateHash && history.replaceState) history.replaceState(null, '', '#' + key);
            }

            items.forEach(function (i) {
                i.addEventListener('click', function () { show(i.dataset.tab, true); });
            });

            search.addEventListener('input', function () {
                var q = search.value.trim().toLowerCase(), shown = 0;
                items.forEach(function (i) {
                    var match = i.textContent.toLowerCase().indexOf(q) !== -1;
                    i.style.display = match ? '' : 'none';
                    if (match) shown++;
                });
                none.style.display = shown ? 'none' : 'block';
            });

            window.addEventListener('hashchange', function () {
                var h = location.hash.replace('#', '');
                show(ALIAS[h] || h, false);
            });

            var start = location.hash.replace('#', '');
            show(ALIAS[start] || start || 'general', false);
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var fields = document.getElementById('generalFields');
            var bar = document.getElementById('saveBar');
            var form = document.getElementById('generalForm');

            function setEditing(on) {
                fields.disabled = !on;
                bar.style.display = on ? '' : 'none';
                if (on) document.getElementById('firstName').focus();
            }

            document.getElementById('editGeneral').addEventListener('click', function () { setEditing(true); });
            document.getElementById('cancelEdit').addEventListener('click', function () { form.reset(); setEditing(false); });

            @if ($errors->any())
                setEditing(true);
            @endif
    });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var data = @json($transferOptions);
            var room = document.getElementById('trRoom');
            var bed = document.getElementById('trBed');

            room.addEventListener('change', function () {
                bed.innerHTML = '';
                var info = data[room.value];

                if (!info) {
                    bed.disabled = true;
                    bed.innerHTML = '<option value="">Select a room first</option>';
                    return;
                }

                bed.disabled = false;
                bed.insertAdjacentHTML('beforeend', '<option value="">Select a bed and deck</option>');
                info.beds.forEach(function (b) {
                    var o = document.createElement('option');
                    o.value = b.value;
                    o.textContent = b.label;
                    bed.appendChild(o);
                });
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var csrf = document.querySelector('meta[name="csrf-token"]').content;

            function wire(btnId, statusId, getBody, onSuccess) {
                var btn = document.getElementById(btnId);
                var status = document.getElementById(statusId);

                function say(text, kind) {
                    status.hidden = !text;
                    status.textContent = text || '';
                    status.className = 'req-status' + (kind ? ' ' + kind : '');
                }

                btn.addEventListener('click', async function () {
                    if (btn.disabled) return;
                    var label = btn.textContent;
                    btn.disabled = true;
                    btn.textContent = 'Sending...';
                    say('');

                    try {
                        var res = await fetch(btn.dataset.url, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': csrf
                            },
                            body: JSON.stringify(getBody())
                        });
                        var data = await res.json().catch(function () { return {}; });

                        if (res.status === 429) throw new Error('You are sending too fast. Please wait a moment.');
                        if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                        if (res.status === 422 && data.errors) throw new Error(Object.values(data.errors)[0][0]);
                        if (!res.ok) throw new Error(data.message || 'Could not send your request. Please try again.');

                        say(data.message, 'ok');
                        onSuccess();
                    } catch (err) {
                        say(err instanceof TypeError ? 'No connection. Check your internet and try again.' : err.message, 'error');
                    } finally {
                        btn.disabled = false;
                        btn.textContent = label;
                    }
                });
            }

            var $ = function (id) { return document.getElementById(id); };

            wire('mSubmit', 'mStatus',
                function () {
                    return { category: $('mCategory').value, urgency: $('mUrgency').value, details: $('mDetails').value.trim() };
                },
                function () { $('mCategory').value = ''; $('mUrgency').value = ''; $('mDetails').value = ''; }
            );

            wire('trSubmit', 'trStatus',
                function () {
                    return { room: $('trRoom').value, bed: $('trBed').value, reason: $('trReason').value, details: $('trDetails').value.trim() };
                },
                function () {
                    $('trRoom').value = ''; $('trRoom').dispatchEvent(new Event('change'));
                    $('trReason').value = ''; $('trDetails').value = '';
                }
            );
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {


            var toggle = document.getElementById('autopaySetting');
            var saveBtn = document.getElementById('paySave');
            var cancel = document.getElementById('payCancel');
            var status = document.getElementById('payStatus');
            var saved = toggle.checked;

            function refresh() {
                var changed = toggle.checked !== saved;
                saveBtn.disabled = !changed;
                cancel.disabled = !changed;
            }
            refresh();

            toggle.addEventListener('change', function () { status.hidden = true; refresh(); });

            cancel.addEventListener('click', function () {
                toggle.checked = saved;
                refresh();
            });

            saveBtn.addEventListener('click', function () {
                if (saveBtn.disabled) return;
                saved = toggle.checked;
                refresh();
                status.textContent = 'Payment settings saved.';
                status.className = 'req-status ok';
                status.hidden = false;
            });


            var overlay = document.createElement('div');
            overlay.style.cssText = 'display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);align-items:center;justify-content:center;padding:20px;';
            overlay.innerHTML =
                '<div style="background:#fff;border-radius:12px;padding:30px;width:100%;max-width:360px;text-align:center;font-family:Nunito Sans,sans-serif;box-shadow:0 10px 40px rgba(0,0,0,.25)">' +
                '<h2 id="naTitle" style="margin:0 0 8px;font-size:1.2rem;font-weight:800;color:#000"></h2>' +
                '<p id="naMsg" style="margin:0 0 20px;font-size:.95rem;font-weight:600;color:#00000099;line-height:1.4"></p>' +
                '<button type="button" id="naClose" style="background:#672C2C;color:#fff;border:0;border-radius:6px;padding:10px 28px;font-size:.95rem;font-weight:800;cursor:pointer">Okay</button>' +
                '</div>';
            document.body.appendChild(overlay);

            function showNA(title, msg) {
                overlay.querySelector('#naTitle').textContent = title;
                overlay.querySelector('#naMsg').textContent = msg;
                overlay.style.display = 'flex';
            }
            function closeNA() { overlay.style.display = 'none'; }
            overlay.addEventListener('click', function (e) { if (e.target === overlay) closeNA(); });
            overlay.querySelector('#naClose').addEventListener('click', closeNA);
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeNA(); });


            document.querySelectorAll('.linkto1[data-unavailable]').forEach(function (row) {
                row.style.cursor = 'pointer';
                row.addEventListener('click', function () {
                    var name = row.querySelector('.ibosses96 h1').textContent.trim();
                    showNA('Not available yet', name + ' is not available right now. Please use GCash for now.');
                });
            });


            document.querySelector('.addkopay').addEventListener('click', function () {
                showNA('Cannot add right now', "You can't add a payment method right now. Please try again later.");
            });
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {


            var PER_PAGE = 3;
            var rows = Array.prototype.slice.call(document.querySelectorAll('#billHistory .bh-row'));
            var pager = document.getElementById('billPager');
            var page = 1;

            function renderHistory() {
                var pages = Math.max(1, Math.ceil(rows.length / PER_PAGE));
                if (page > pages) page = pages;
                var from = (page - 1) * PER_PAGE;

                rows.forEach(function (r, i) { r.hidden = !(i >= from && i < from + PER_PAGE); });

                pager.innerHTML = '';
                if (pages <= 1) return;

                function btn(label, target, disabled, current) {
                    var b = document.createElement('button');
                    b.type = 'button';
                    b.textContent = label;
                    if (current) b.className = 'current';
                    b.disabled = !!disabled;
                    b.addEventListener('click', function () { page = target; renderHistory(); });
                    pager.appendChild(b);
                }

                btn('‹ Prev', page - 1, page === 1, false);
                for (var p = 1; p <= pages; p++) btn(String(p), p, false, p === page);
                btn('Next ›', page + 1, page === pages, false);

                var shown = Math.min(PER_PAGE, rows.length - from);
                var info = document.createElement('span');
                info.textContent = 'Showing ' + (from + 1) + '–' + (from + shown) + ' of ' + rows.length;
                pager.appendChild(info);
            }
            renderHistory();


            var rOverlay = document.createElement('div');
            rOverlay.style.cssText = 'display:none;position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.45);align-items:center;justify-content:center;padding:20px;';
            rOverlay.innerHTML =
                '<div style="background:#fff;border-radius:12px;padding:30px;width:100%;max-width:380px;font-family:Nunito Sans,sans-serif;box-shadow:0 10px 40px rgba(0,0,0,.25)">' +
                '<h2 style="margin:0 0 4px;font-size:1.2rem;font-weight:800;color:#000;text-align:center">Payment receipt</h2>' +
                '<p id="rcRef" style="margin:0 0 18px;font-size:.85rem;font-weight:700;color:#672C2C;text-align:center"></p>' +
                '<div id="rcBody"></div>' +
                '<div style="text-align:center;margin-top:22px"><button type="button" id="rcClose" style="background:#672C2C;color:#fff;border:0;border-radius:6px;padding:10px 28px;font-size:.95rem;font-weight:800;cursor:pointer">Close</button></div>' +
                '</div>';
            document.body.appendChild(rOverlay);

            function openReceipt(d) {
                rOverlay.querySelector('#rcRef').textContent = d.ref;
                var body = rOverlay.querySelector('#rcBody');
                body.innerHTML = '';
                [['Period', d.period], ['Amount paid', d.amount], ['Method', d.method], ['Paid on', d.paid]].forEach(function (r) {
                    var line = document.createElement('div');
                    line.style.cssText = 'display:flex;justify-content:space-between;gap:16px;padding:9px 0;border-bottom:1px solid #00000014;font-size:.9rem;font-weight:700;';
                    var a = document.createElement('span'); a.style.color = '#00000085'; a.textContent = r[0];
                    var b = document.createElement('span'); b.style.color = '#000'; b.textContent = r[1];
                    line.appendChild(a); line.appendChild(b);
                    body.appendChild(line);
                });
                rOverlay.style.display = 'flex';
            }
            function closeReceipt() { rOverlay.style.display = 'none'; }
            rOverlay.addEventListener('click', function (e) { if (e.target === rOverlay) closeReceipt(); });
            rOverlay.querySelector('#rcClose').addEventListener('click', closeReceipt);
            document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeReceipt(); });

            document.querySelectorAll('#billHistory [data-receipt]').forEach(function (cell) {
                function open() { openReceipt(JSON.parse(cell.dataset.receipt)); }
                cell.addEventListener('click', open);
                cell.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); open(); }
                });
            });


            var box = document.getElementById('bEmailBox');
            var text = document.getElementById('bEmailText');
            var input = document.getElementById('bEmailInput');
            var editBtn = document.getElementById('bEmailEdit');
            var save = document.getElementById('bSave');
            var cancel = document.getElementById('bCancel');
            var status = document.getElementById('bStatus');
            var csrf = document.querySelector('meta[name="csrf-token"]').content;

            var saved = input.value;
            var editing = false;

            function validEmail(v) { return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(v); }

            function say(msg, kind) {
                status.hidden = !msg;
                status.textContent = msg || '';
                status.className = 'req-status' + (kind ? ' ' + kind : '');
            }

            function refresh() {
                var v = input.value.trim();
                save.disabled = !(editing && v !== saved && validEmail(v));
                cancel.disabled = !editing;
            }

            function setEditing(on) {
                editing = on;
                box.classList.toggle('editing', on);
                text.hidden = on;
                input.hidden = !on;
                editBtn.hidden = on;
                if (on) { input.value = saved; input.focus(); input.select(); }
                refresh();
            }

            editBtn.addEventListener('click', function () { say(''); setEditing(true); });
            cancel.addEventListener('click', function () { if (!cancel.disabled) { say(''); setEditing(false); } });
            input.addEventListener('input', function () { say(''); refresh(); });
            input.addEventListener('keydown', function (e) {
                if (e.key === 'Enter') { e.preventDefault(); if (!save.disabled) save.click(); }
                if (e.key === 'Escape') { cancel.click(); }
            });

            save.addEventListener('click', async function () {
                if (save.disabled) return;
                var label = save.textContent;
                save.disabled = true; cancel.disabled = true;
                save.textContent = 'Saving...';
                say('');

                try {
                    var res = await fetch(save.dataset.url, {
                        method: 'PUT',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ billing_email: input.value.trim() })
                    });
                    var data = await res.json().catch(function () { return {}; });

                    if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                    if (res.status === 422 && data.errors) throw new Error(Object.values(data.errors)[0][0]);
                    if (!res.ok) throw new Error(data.message || 'Could not save. Please try again.');

                    saved = data.email;
                    text.textContent = saved;
                    setEditing(false);
                    say(data.message, 'ok');
                } catch (err) {
                    say(err instanceof TypeError ? 'No connection. Check your internet and try again.' : err.message, 'error');
                    refresh();
                } finally {
                    save.textContent = label;
                    refresh();
                }
            });

            refresh();
        });
    </script>
@endsection