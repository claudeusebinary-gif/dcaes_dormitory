@extends('layouts.app')

@section('title', 'Payments')
@section('page_title', 'Payments')
@section('page_subtitle', 'Every tenants dues at a glance — track collections, chase overdue rent, and log payments in a couple of clicks.')

@section('page_action')
    <button type="button" class="addroombtn" id="manualBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
            stroke-linejoin="round" aria-hidden="true">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>Manual record payments
    </button>
@endsection

@section('content')
    @php
        $pill = [
            'paid'    => ['label' => 'PAID',    'box' => '',                                                                'color' => ''],
            'pending' => ['label' => 'PENDING', 'box' => 'border:1px solid #C98A2C; background-color:#FDFBF8; width:110px;', 'color' => '#C98A2C'],
            'overdue' => ['label' => 'OVERDUE', 'box' => 'border:1px solid #A72121; background-color:#FCF8F8; width:110px;', 'color' => '#A72121'],
            'partial' => ['label' => 'PARTIAL', 'box' => 'border:1px solid #1060BC; background-color:#F8FAFD; width:100px;', 'color' => '#1060BC'],
        ];
    @endphp

    <div class="collectedthismonth">
        <div class="collectedthismonth1">
            <div class="collectedbreak">
                <h1 class="collectpay">₱{{ number_format($summary['collected']) }}</h1>
                <div class="boxbreakdowncollected"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <rect x="2" y="6" width="20" height="12" rx="2" />
                        <circle cx="12" cy="12" r="2" />
                        <path d="M6 12h.01M18 12h.01" />
                    </svg></div>
            </div>
            <h1 class="collectedthismonthbreakkssk">COLLECTED THIS MONTH</h1>
        </div>
        <div class="collectedthismonth1">
            <div class="collectedbreak">
                <h1 class="collectpay">₱{{ number_format($summary['pending']) }}</h1>
                <div class="boxbreakdowncollected"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg></div>
            </div>
            <h1 class="collectedthismonthbreakkssk">PENDING DUES</h1>
        </div>
        <div class="collectedthismonth1">
            <div class="collectedbreak">
                <h1 class="collectpay">{{ $summary['overdue'] }}</h1>
                <div class="boxbreakdowncollected"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2" />
                        <circle cx="9" cy="7" r="4" />
                        <path d="M19 8v4M19 16h.01" />
                    </svg></div>
            </div>
            <h1 class="collectedthismonthbreakkssk">OVERDUE TENANTS</h1>
        </div>
        <div class="collectedthismonth1">
            <div class="collectedbreak">
                <h1 class="collectpay">{{ $summary['rate'] }}%</h1>
                <div class="boxbreakdowncollected"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <line x1="19" y1="5" x2="5" y2="19" />
                        <circle cx="6.5" cy="6.5" r="2.5" />
                        <circle cx="17.5" cy="17.5" r="2.5" />
                    </svg></div>
            </div>
            <h1 class="collectedthismonthbreakkssk">COLLECTION RATE</h1>
        </div>
        <div class="collectedthismonth1">
            <div class="collectedbreak">
                <h1 class="collectpay">₱{{ number_format($summary['due7']) }}</h1>
                <div class="boxbreakdowncollected"><svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                        viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" aria-hidden="true">
                        <rect x="3" y="4" width="18" height="18" rx="2" />
                        <path d="M16 2v4M8 2v4M3 10h18" />
                        <path d="M8 14h.01M12 14h.01M16 14h.01M8 18h.01M12 18h.01" />
                    </svg></div>
            </div>
            <h1 class="collectedthismonthbreakkssk">DUE IN NEXT 7 DAYS</h1>
        </div>
    </div>

    <div class="search">
        <div class="search-box">
            <svg class="search-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7" />
                <path d="m20 20-3.5-3.5" />
            </svg>
            <input type="search" id="paySearch" placeholder="Search payments..." autocomplete="off" />
            <span class="search-caret" aria-hidden="true"></span>
        </div>
        <div class="filter-wrap">
            <input type="button" class="filter" id="filterBtn" value="Filter" aria-expanded="false"
                aria-controls="filterPop">

            <div class="filter-pop" id="filterPop" hidden>
                <label for="fRoom">Room</label>
                <select id="fRoom">
                    <option value="">Any</option>
                    @foreach ($cards->pluck('room')->unique()->sort()->values() as $r)
                        <option value="{{ $r }}">Room {{ $r }}</option>
                    @endforeach
                </select>

                <label for="fDue">Due within</label>
                <select id="fDue">
                    <option value="0">Any time</option>
                    <option value="7">Next 7 days</option>
                    <option value="14">Next 14 days</option>
                    <option value="30">Next 30 days</option>
                </select>

                <button type="button" class="filter-reset" id="fReset">Reset</button>
            </div>
        </div>
    </div>

    <div class="filtering payfilterbar" style="width:100%; max-width:none; display:flex; flex-direction:row; align-items:center; gap:10px;">
        <input type="button" class="fiveb active" data-filter="all" value="All tenants" style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="paid" value="Paid" style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="pending" value="Pending" style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="overdue" value="Overdue" style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="partial" value="Partial" style="width:112px; flex:0 0 112px;">
        <div class="ann-pager" id="payPager" style="margin:0 0 0 auto; justify-content:flex-end; flex-wrap:nowrap; white-space:nowrap;"></div>
    </div>

    <div class="tickercollected" id="payList">
        @foreach ($cards as $c)
            @php $p = $pill[$c['status']]; @endphp
            <div class="tickercollected1" data-id="{{ $c['id'] }}" data-status="{{ $c['status'] }}"
                data-room="{{ $c['room'] }}" data-due="{{ $c['due_ts'] }}"
                data-search="{{ strtolower($c['name'] . ' room ' . $c['room'] . ' bed ' . $c['bed_no'] . ' ' . $c['status']) }}">
                <div class="roomjuelmont">
                    <h1>Room {{ $c['room'] }} · {{ $c['bed'] }}</h1>
                    <h2>{{ $c['name'] }}</h2>
                    <span>Monthly rate {{ $c['rent'] }}</span>
                </div>
                <div class="ticket-divider">
                    <hr class="dashed-line">
                </div>
                <h1 class="amountoaudjue">{{ $c['label'] }}</h1>
                <div class="paidinriajuepaid">
                    <div class="paidjuesakapaid">
                        <h1 class="perajue">{{ $c['amount'] }}</h1>
                        <h1 class="duejue">{{ $c['sub'] }}</h1>
                                                @if ($c['submitted'] > 0)
                            <h1 class="duejue" style="color:#1060BC;">Sent ₱{{ number_format($c['submitted'], 2) }} · to check</h1>
                        @endif
                    </div>
                    <div class="paidinria" @if ($p['box']) style="{{ $p['box'] }}" @endif>
                        <h1 class="inriapaidna" @if ($p['color']) style="color:{{ $p['color'] }};" @endif>{{ $p['label'] }}</h1>
                    </div>
                </div>
                <div class="buttonpaidjue">
                   @php $showRecord = $c['can_record'] || count($c['approve_ids']); @endphp
@if ($showRecord)
    <button type="button" class="buttonpaidjue1" data-record="{{ $c['id'] }}" data-approve="{{ json_encode($c['approve_ids']) }}">
        <svg
                            xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <rect x="2" y="6" width="20" height="12" rx="2" />
                            <circle cx="12" cy="12" r="2.5" />
                            <path d="M6 9.5v.01M18 14.5v.01" />
                        </svg>Record payment
    </button>
@endif
                    
                    @if ($c['status'] === 'overdue')
                       <button type="button" class="buttonpaidjue2 {{ $showRecord ? '' : 'full' }}"
    data-remind="{{ route('admin.payments.remind', $c['id']) }}"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
                                <path d="M13.7 21a2 2 0 0 1-3.4 0" />
                            </svg>Send reminder</button>
                    @else
                        <button type="button" class="buttonpaidjue2 {{ $showRecord ? '' : 'full' }}" data-href="{{ $c['url'] }}"><svg
                                xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none"
                                stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M3 12a9 9 0 1 0 3-6.7L3 8" />
                                <path d="M3 3v5h5" />
                                <polyline points="12 7 12 12 15 14" />
                            </svg>View history</button>
                    @endif
                </div>
            </div>
        @endforeach
    </div>
    @if ($cards->isEmpty())
        <p class="no-rooms">No tenants with a room yet.</p>
    @endif
    <p class="no-rooms" id="noPay" hidden>No payments match your search.</p>
   
    <div class="bagongannoverlay" id="payModal" hidden data-url="{{ route('admin.payments.store') }}">
        <div class="bagongannmodal" role="dialog" aria-modal="true" aria-labelledby="payTitle">
            <button type="button" class="sarahanann" id="payClose" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M5 5l14 14M19 5L5 19" />
                </svg>
            </button>

            <h2 class="pamagatnamodal" id="payTitle">Record payment</h2>
            <p class="modalsub">Log a rent payment against a tenant's account.</p>

            <label class="labelnaann" for="payTenant">Tenant / Room</label>
            <select class="kahonnaann" id="payTenant">
                <option value="">Select a tenant</option>
            @foreach ($cards->where('can_record', true)->sortBy([['rank', 'asc'], ['name', 'asc']]) as $c)
                    <option value="{{ $c['id'] }}" data-target="{{ $c['target'] }}" data-left="{{ $c['target_left'] }}">
                        {{ $c['name'] }} - Room {{ $c['room'] }}{{ $c['bed_no'] ? ', Bed ' . $c['bed_no'] : '' }}
                    </option>
                @endforeach
            </select>
            <p class="paynote" id="payNote" hidden></p>

            <label class="labelnaann" for="payAmount">Amount paid</label>
            <div class="pesowrap">
                <span>₱</span>
                <input type="number" class="kahonnaann" id="payAmount" min="1" step="0.01" placeholder="2,000">
            </div>

            <label class="labelnaann" for="payDate">Payment date</label>
            <input type="date" class="kahonnaann" id="payDate">

            <p class="annmali" id="payErr" hidden></p>

            <div class="butonsaibaba" style="margin-top:26px">
                <button type="button" class="kanselaann paybtnhalf" id="payCancel">Cancel</button>
                <button type="button" class="ipostann paybtnhalf" id="paySave">Save payment</button>
            </div>
        </div>
    </div>

    <div class="ev-toast" id="payToast"></div>
@endsection

@push('scripts')
    <script>
        (function () {
            const csrf = document.querySelector('meta[name="csrf-token"]').content;
            const $ = id => document.getElementById(id);

            const cards = Array.from(document.querySelectorAll('#payList .tickercollected1'));
            const search = $('paySearch'), tabs = document.querySelectorAll('.fiveb');
            const fBtn = $('filterBtn'), fPop = $('filterPop'), fRoom = $('fRoom'), fDue = $('fDue');
            const none = $('noPay'), pager = $('payPager');
            const PER_PAGE = 6;
            let status = 'all', page = 1;

            let tt;
            function toast(msg, bad) {
                const t = $('payToast');
                t.textContent = msg;
                t.className = 'ev-toast is-show' + (bad ? ' is-bad' : '');
                clearTimeout(tt);
                tt = setTimeout(() => { t.className = 'ev-toast'; }, 2600);
            }

            const startOfToday = (() => { const d = new Date(); d.setHours(0, 0, 0, 0); return Math.floor(d / 1000); })();

            function matches(c) {
                if (status !== 'all' && c.dataset.status !== status) return false;

                const words = search.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
                if (!words.every(w => c.dataset.search.includes(w))) return false;

                if (fRoom.value && c.dataset.room !== fRoom.value) return false;

                const days = +fDue.value;
                if (days) {
                    const due = +c.dataset.due;
                    if (c.dataset.status === 'paid' || due < startOfToday || due > startOfToday + days * 86400) return false;
                }
                return true;
            }

            function renderPager(pages, total, from, shown) {
                pager.innerHTML = '';
                if (pages <= 1) return;

                function btn(label, target, disabled, current) {
                    const b = document.createElement('button');
                    b.type = 'button';
                    b.textContent = label;
                    if (current) b.className = 'current';
                    b.disabled = !!disabled;
                    b.addEventListener('click', () => { page = target; apply(true); });
                    pager.appendChild(b);
                }

                btn('‹ Prev', page - 1, page === 1, false);
                for (let p = 1; p <= pages; p++) btn(String(p), p, false, p === page);
                btn('Next ›', page + 1, page === pages, false);

                const info = document.createElement('span');
                info.textContent = 'Showing ' + (from + 1) + '–' + (from + shown) + ' of ' + total;
                pager.appendChild(info);
            }

            function apply(keepPage) {
                if (!keepPage) page = 1;

                const list = cards.filter(matches);
                const pages = Math.max(1, Math.ceil(list.length / PER_PAGE));
                if (page > pages) page = pages;
                const from = (page - 1) * PER_PAGE;

                cards.forEach(c => {
                    const i = list.indexOf(c);
                    c.classList.toggle('is-hidden', !(i >= from && i < from + PER_PAGE));
                });

                none.hidden = list.length > 0 || !cards.length;

                const active = (fRoom.value ? 1 : 0) + (+fDue.value ? 1 : 0);
                fBtn.value = active ? 'Filter (' + active + ')' : 'Filter';

                renderPager(pages, list.length, from, Math.min(PER_PAGE, list.length - from));
            }

            search.addEventListener('input', () => apply());
            tabs.forEach(t => t.addEventListener('click', () => {
                status = t.dataset.filter;
                tabs.forEach(x => x.classList.toggle('active', x === t));
                apply();
            }));

            function setPanel(open) { fPop.hidden = !open; fBtn.setAttribute('aria-expanded', String(open)); }
            fBtn.addEventListener('click', e => { e.stopPropagation(); setPanel(fPop.hidden); });
            fPop.addEventListener('click', e => e.stopPropagation());
            document.addEventListener('click', () => setPanel(false));
            [fRoom, fDue].forEach(el => el.addEventListener('input', () => apply()));
            $('fReset').addEventListener('click', () => { fRoom.value = ''; fDue.value = '0'; apply(); });

            apply();

            const modal = $('payModal'), sel = $('payTenant'), amt = $('payAmount'), date = $('payDate');
            const note = $('payNote'), err = $('payErr'), save = $('paySave');

            function today() {
                const d = new Date();
                return d.getFullYear() + '-' + String(d.getMonth() + 1).padStart(2, '0') + '-' + String(d.getDate()).padStart(2, '0');
            }
            function showErr(m) { err.hidden = !m; err.textContent = m || ''; }

            function sync(autofill) {
                const o = sel.selectedOptions[0];
                if (!o || !o.value) { note.hidden = true; return; }
                note.hidden = false;
                note.textContent = 'This payment goes to ' + o.dataset.target + ' · balance ₱' +
                    Number(o.dataset.left).toLocaleString('en-PH', { maximumFractionDigits: 2 });
                if (autofill) amt.value = +o.dataset.left;
            }

            function openModal(id) {
                showErr('');
                sel.value = id ? String(id) : '';
                date.value = today();
                date.max = today();
                amt.value = '';
                sync(true);
                modal.hidden = false;
                (id ? amt : sel).focus();
            }
            function closeModal() { modal.hidden = true; }

            sel.addEventListener('change', () => { showErr(''); sync(true); });
            $('manualBtn').addEventListener('click', () => openModal(''));
                       const APPROVE_URL = "{{ route('admin.approvals.approve', ['id' => '__ID__']) }}";

            document.querySelectorAll('[data-record]').forEach(b => b.addEventListener('click', async () => {
                const ids = JSON.parse(b.dataset.approve || '[]');
                if (!ids.length) return openModal(b.dataset.record);   

                b.disabled = true;
                try {
                    for (const id of ids) {
                        const res  = await fetch(APPROVE_URL.replace('__ID__', id), {
                            method: 'POST',
                            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
                        });
                        const data = await res.json().catch(() => ({}));
                        if (!res.ok) throw new Error(data.message || 'Could not record the payment.');
                    }
                    toast('Payment recorded just now.');
                    setTimeout(() => location.reload(), 900);
                } catch (e) {
                    b.disabled = false;
                    toast(e.message, true);
                }
            }));
            $('payClose').addEventListener('click', closeModal);
            $('payCancel').addEventListener('click', closeModal);
            modal.addEventListener('click', e => { if (e.target === modal) closeModal(); });
            document.addEventListener('keydown', e => { if (e.key === 'Escape' && !modal.hidden) closeModal(); });

            save.addEventListener('click', async () => {
                if (!sel.value) return showErr('Choose a tenant.');
                const amount = parseFloat(amt.value);
                if (isNaN(amount) || amount <= 0) return showErr('Enter the amount paid.');
                if (!date.value) return showErr('Pick the payment date.');

                const label = save.textContent;
                save.disabled = true;
                save.textContent = 'Saving...';
                showErr('');

                try {
                    const res = await fetch(modal.dataset.url, {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                        body: JSON.stringify({ tenant_id: +sel.value, amount: amount, paid_on: date.value })
                    });
                    const data = await res.json().catch(() => ({}));

                    if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                    if (res.status === 422 && data.errors) throw new Error(Object.values(data.errors)[0][0]);
                    if (!res.ok) throw new Error(data.message || 'Could not save the payment. Please try again.');

                    closeModal();
                    toast(data.message || 'Payment recorded.');
                    setTimeout(() => location.reload(), 900);
                } catch (e) {
                    showErr(e instanceof TypeError ? 'No connection. Check your internet and try again.' : e.message);
                    save.disabled = false;
                    save.textContent = label;
                }
            });

            document.querySelectorAll('[data-href]').forEach(b => b.addEventListener('click', () => { location.href = b.dataset.href; }));

            document.querySelectorAll('[data-remind]').forEach(b => b.addEventListener('click', async () => {
                if (b.disabled) return;
                const text = b.lastChild;
                const old = text.textContent;
                b.disabled = true;
                text.textContent = 'Sending...';

                try {
                    const res = await fetch(b.dataset.remind, {
                        method: 'POST',
                        headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf }
                    });
                    const data = await res.json().catch(() => ({}));
                    if (!res.ok) throw new Error(data.message || 'Could not send the reminder.');

                    text.textContent = 'Reminder sent';
                    toast(data.message || 'Reminder sent.');
                } catch (e) {
                    text.textContent = old;
                    b.disabled = false;
                    toast(e instanceof TypeError ? 'No connection. Check your internet and try again.' : e.message, true);
                }
            }));
        })();
    </script>
@endpush