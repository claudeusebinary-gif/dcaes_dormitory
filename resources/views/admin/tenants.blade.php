@extends('layouts.app')

@section('title', 'Tenants')
@section('page_title', 'Tenants')
@section('page_subtitle', "Everyone currently staying at D'aces, in one searchable list.")

@section('content')
@php $w = fn ($k) => $total ? round($counts[$k] / $total * 100, 2) : 0; @endphp

<div class="admintetenant">
    <div class="admintena3">
        <h1 id="tnTotal">{{ $total }}</h1>
        <h2>Total Tenants</h2>
        <span id="tnRooms">Across {{ $roomsUsed }} {{ \Illuminate\Support\Str::plural('room', $roomsUsed) }}</span>
    </div>
    <div class="paymentbreakdown">
        <h1>PAYMENTS STATUS BREAKDOWN</h1>
        <div class="breakdown">
            <i class="seg paid" id="seg-paid" style="width: {{ $w('paid') }}%"></i>
            <i class="seg due" id="seg-due" style="width: {{ $w('due') }}%"></i>
            <i class="seg overdue" id="seg-overdue" style="width: {{ $w('overdue') }}%"></i>
        </div>
        <div class="breakdownayoayos" id="tnLegend">
            <div class="breakdownyow is-on" data-s="all">
                <div class="breakdownbox" style="background-color: #672C2C;"></div>
                <h1 class="allbreakdown" id="lg-all" style="color:#672C2C;">All ({{ $total }})</h1>
            </div>
            <div class="breakdownyow" data-s="paid">
                <div class="breakdownbox"></div>
                <h1 class="allbreakdown" id="lg-paid">Paid ({{ $counts['paid'] }})</h1>
            </div>
            <div class="breakdownyow" data-s="due">
                <div class="breakdownbox" style="background-color: #C98A2C;"></div>
                <h1 class="allbreakdown" id="lg-due" style="color:#C98A2C;">Due Soon ({{ $counts['due'] }})</h1>
            </div>
            <div class="breakdownyow" data-s="overdue">
                <div class="breakdownbox" style="background-color: #AB1210;"></div>
                <h1 class="allbreakdown" id="lg-overdue" style="color:#AB1210;">Overdue ({{ $counts['overdue'] }})</h1>
            </div>
        </div>
    </div>
     <div class="breakdownbababa">

        <div class="samabreakdown">
            <div class="newtenantthismonth">
               <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M19 8v6M16 11h6"/></svg>
            </div>
            <div class="breakdownpababa">
                <h1>{{ $newCount }} {{ \Illuminate\Support\Str::plural('tenant', $newCount) }}</h1>
                <span>New this month</span>
            </div>
        </div>

        <div class="samabreakdown">
            <div class="newtenantthismonth">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2"/><path d="M16 2v4M8 2v4M3 10h18"/><circle cx="12" cy="16" r="3"/><path d="M12 14.6V16l1 .6"/></svg>
            </div>
            <div class="breakdownpababa">
                <h1>{{ $avgStay }} months</h1>
                <span>Avg. length of stay</span>
            </div>
        </div>

        <div class="samabreakdown">
            <div class="newtenantthismonth">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M2 20v-8a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v8"/><path d="M4 10V6a2 2 0 0 1 2-2h12a2 2 0 0 1 2 2v4"/><path d="M12 4v6"/><path d="M2 18h20"/></svg>
            </div>
            <div class="breakdownpababa">
                <h1 id="tnBeds">{{ $total }} / {{ $bedsTotal }}</h1>
                <span id="tnOcc">{{ $occPct }}% occupied</span>
            </div>
        </div>

    </div>
</div>

<div class="breakdownsearch">
    <input type="search" placeholder="Search tenant, room or email..." class="breakdownsearchBOX" id="tnSearch" autocomplete="off">

    <div class="tn-filterwrap">
        <button type="button" class="searchbreakdownbox" id="tnFilterBtn" aria-expanded="false">Filter</button>
        <div class="tn-pop" id="tnPop" hidden>
            <label for="tnFloor">Floor</label>
            <select id="tnFloor"><option value="all">All floors</option></select>
            <label for="tnPay">Payment</label>
            <select id="tnPay">
                <option value="all">All</option>
                <option value="paid">Paid</option>
                <option value="due">Due soon</option>
                <option value="overdue">Overdue</option>
            </select>
            <label for="tnSort">Sort by</label>
            <select id="tnSort">
                <option value="name">Name (A–Z)</option>
                <option value="new">Newest move-in</option>
                <option value="old">Oldest move-in</option>
                <option value="room">Room number</option>
            </select>
            <button type="button" class="tn-reset" id="tnReset">Reset</button>
        </div>
    </div>

    <button type="button" class="listtolistbreakdown" id="tnView" aria-label="Switch view">
        <span class="ltl-half ltl-left" data-v="list">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <line x1="4" y1="7" x2="20" y2="7" />
                <line x1="4" y1="12" x2="20" y2="12" />
                <line x1="4" y1="17" x2="20" y2="17" />
            </svg>
        </span>
        <span class="ltl-half ltl-right" data-v="grid">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <rect x="4" y="4" width="6" height="6" rx="1" />
                <rect x="14" y="4" width="6" height="6" rx="1" />
                <rect x="4" y="14" width="6" height="6" rx="1" />
                <rect x="14" y="14" width="6" height="6" rx="1" />
            </svg>
        </span>
    </button>
</div>

<div class="tn-panel">

    <div class="listnaetonangbreakdown" id="tnList">
        <div class="breakdownpahabarow breakdownhead">
            <input type="checkbox" class="breakdowncheckbox" id="tnAll" aria-label="Select all on this page">
            <h1 class="textbreakdown">TENANTS</h1>
            <h1 class="textbreakdown">ROOM / BED</h1>
            <h1 class="textbreakdown">MOVE-IN</h1>
            <h1 class="textbreakdown">PAYMENT</h1>
            <h1 class="textbreakdown">ACTION</h1>
        </div>
        <div id="tnRows"></div>
    </div>

    <div class="tn-grid" id="tnGrid" hidden></div>

    <div class="parightbreakdown">
        <h1 id="tnInfo"></h1>
        <div class="onetwobreakdown" id="tnPager"></div>
    </div>

</div>

<div class="tn-modal" id="tnModal" hidden>
    <div class="tn-box" role="dialog" aria-modal="true" aria-labelledby="tnModalTitle">
        <h2 id="tnModalTitle">Delete tenant?</h2>
        <p id="tnModalText"></p>
        <div class="tn-btns">
            <button type="button" class="tn-cancel" id="tnCancel">Cancel</button>
            <button type="button" class="tn-del" id="tnConfirm">Yes, delete</button>
        </div>
    </div>
</div>
<div class="ev-toast" id="tnToast"></div>
@endsection

@push('scripts')
<script>
(function () {
    var T = @json($list);
    var BEDS = @json($bedsTotal);
    var DEL_URL = @json(url('/admin/tenants'));
    var CSRF = document.querySelector('meta[name="csrf-token"]').content;
    var PER = 6;
    var LABEL = { paid: 'PAID', due: 'DUE SOON', overdue: 'OVERDUE' };

    var $ = function (id) { return document.getElementById(id); };
    var st = { view: 'list', q: '', floor: 'all', pay: 'all', sort: 'name', page: 1 };
    var sel = {};
    var delTarget = null;

    try { if (localStorage.getItem('tnView') === 'grid') st.view = 'grid'; } catch (e) {}

    function esc(s) {
        return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
            return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
        });
    }

    var tt;
    function toast(msg, bad) {
        var t = $('tnToast');
        t.textContent = msg;
        t.className = 'ev-toast is-show' + (bad ? ' is-bad' : '');
        clearTimeout(tt);
        tt = setTimeout(function () { t.className = 'ev-toast'; }, 2600);
    }

    function api(url, method) {
        return fetch(url, {
            method: method,
            headers: { 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
            credentials: 'same-origin'
        }).then(function (r) {
            return r.json().catch(function () { return {}; }).then(function (d) {
                if (!r.ok) {
                    var msg = d.message || 'Something went wrong.';
                    if (r.status === 419) msg = 'Session expired. Refresh the page.';
                    throw new Error(msg);
                }
                return d;
            });
        });
    }

    var SV = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';
    var EYE   = SV + '><path d="M2 12s3.6-7 10-7 10 7 10 7-3.6 7-10 7S2 12 2 12z"/><circle cx="12" cy="12" r="3"/></svg>';
    var MSG   = SV + '><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>';
    var TRASH = SV + ' style="color:red;"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6"/><path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2"/></svg>';

    function actions(id) {
        return '<div class="actionbreakdown">'
            + '<button type="button" class="actionbreakdown1" data-act="view" data-id="' + id + '" title="View">' + EYE + '</button>'
            + '<button type="button" class="actionbreakdown1" data-act="msg" data-id="' + id + '" title="Message">' + MSG + '</button>'
            + '<button type="button" class="actionbreakdown1" data-act="del" data-id="' + id + '" title="Delete">' + TRASH + '</button>'
            + '</div>';
    }
    function pill(t) {
        return '<div class="colorbreak ' + (t.status === 'paid' ? '' : t.status) + '"><h1 class="textbreakdown56">' + LABEL[t.status] + '</h1></div>';
    }

    function row(t) {
        return '<div class="breakdownpahabarow43" data-id="' + t.id + '">'
            + '<input type="checkbox" class="breakdowncheckbox tn-chk" data-id="' + t.id + '"' + (sel[t.id] ? ' checked' : '') + '>'
            + '<div class="samabreakdown8"><div class="newtenantthismonth8"><h1>' + esc(t.initial) + '</h1></div>'
            + '<div class="breakdownpababa8"><h1>' + esc(t.name) + '</h1><span>' + esc(t.email) + '</span></div></div>'
            + '<div class="breakdownpababa8 breakdowncenter"><h1>Room <strong class="roomno">' + esc(t.room) + '</strong></h1><span>' + esc(t.bed) + '</span></div>'
            + '<h1 class="textbreakdown4">' + esc(t.since) + '</h1>'
            + pill(t) + actions(t.id) + '</div>';
    }
    function card(t) {
        return '<div class="tn-card" data-id="' + t.id + '">'
            + '<div class="tn-av">' + esc(t.initial) + '</div>'
            + '<h3>' + esc(t.name) + '</h3><p class="tn-mail">' + esc(t.email) + '</p>'
            + '<p class="tn-room">Room <b>' + esc(t.room) + '</b> • ' + esc(t.bed) + '</p>'
            + '<p class="tn-since">Since ' + esc(t.since) + '</p>'
            + '<div class="tn-foot">' + pill(t) + actions(t.id) + '</div></div>';
    }

    function filtered() {
        var s = st.q.trim().toLowerCase();
        var out = T.filter(function (t) {
            if (st.pay !== 'all' && t.status !== st.pay) return false;
            if (st.floor !== 'all' && String(t.floor) !== st.floor) return false;
            if (s && (t.name + ' ' + t.email + ' room ' + t.room + ' ' + t.bed + ' ' + LABEL[t.status]).toLowerCase().indexOf(s) === -1) return false;
            return true;
        });
        out.sort(function (a, b) {
            if (st.sort === 'new')  return b.since_ts - a.since_ts;
            if (st.sort === 'old')  return a.since_ts - b.since_ts;
            if (st.sort === 'room') return (parseInt(a.room, 10) || 0) - (parseInt(b.room, 10) || 0) || a.name.localeCompare(b.name);
            return a.name.localeCompare(b.name);
        });
        return out;
    }

    function render() {
        var list = filtered();
        var pages = Math.max(1, Math.ceil(list.length / PER));
        if (st.page > pages) st.page = pages;
        var from = (st.page - 1) * PER;
        var slice = list.slice(from, from + PER);

        var html = slice.length
            ? slice.map(st.view === 'grid' ? card : row).join('')
            : '<div class="tn-empty">No tenants match your search.</div>';

        if (st.view === 'grid') $('tnGrid').innerHTML = html; else $('tnRows').innerHTML = html;
        $('tnList').hidden = st.view === 'grid';
        $('tnGrid').hidden = st.view !== 'grid';
        $('tnView').classList.toggle('is-grid', st.view === 'grid');

        var picked = Object.keys(sel).filter(function (k) { return sel[k]; }).length;
        $('tnInfo').textContent = list.length
            ? 'Showing ' + (from + 1) + '–' + (from + slice.length) + ' of ' + list.length + ' ' + (list.length === 1 ? 'tenant' : 'tenants') + (picked ? ' · ' + picked + ' selected' : '')
            : 'Showing 0 tenants';

        var p = '<button type="button" class="onetwobreakdown1" data-p="prev"' + (st.page <= 1 ? ' disabled' : '') + '>‹</button>';
        for (var i = 1; i <= pages; i++) {
            p += '<button type="button" class="onetwobreakdown1' + (i === st.page ? ' is-on' : '') + '" data-p="' + i + '">' + i + '</button>';
        }
        p += '<button type="button" class="onetwobreakdown1" data-p="next"' + (st.page >= pages ? ' disabled' : '') + '>›</button>';
        $('tnPager').innerHTML = p;

        $('tnAll').checked = slice.length > 0 && slice.every(function (t) { return sel[t.id]; });
        document.querySelectorAll('#tnLegend .breakdownyow').forEach(function (el) {
            el.classList.toggle('is-on', el.dataset.s === st.pay);
        });
    }

    function update() { st.page = 1; render(); }

    function summary() {
        var c = { paid: 0, due: 0, overdue: 0 }, n = T.length;
        T.forEach(function (t) { c[t.status]++; });
        var rooms = {}; T.forEach(function (t) { rooms[t.room] = 1; });
        var nr = Object.keys(rooms).length;

        $('tnTotal').textContent = n;
        $('tnRooms').textContent = 'Across ' + nr + (nr === 1 ? ' room' : ' rooms');
        $('lg-all').textContent = 'All (' + n + ')';
        $('lg-paid').textContent = 'Paid (' + c.paid + ')';
        $('lg-due').textContent = 'Due Soon (' + c.due + ')';
        $('lg-overdue').textContent = 'Overdue (' + c.overdue + ')';
        ['paid', 'due', 'overdue'].forEach(function (k) { $('seg-' + k).style.width = (n ? c[k] / n * 100 : 0) + '%'; });
        $('tnBeds').textContent = n + ' / ' + BEDS;
        $('tnOcc').textContent = (BEDS ? Math.round(n / BEDS * 100) : 0) + '% occupied';
    }

    $('tnSearch').addEventListener('input', function (e) { st.q = e.target.value; update(); });

    var floors = {};
    T.forEach(function (t) { floors[t.floor] = 1; });
    Object.keys(floors).sort(function (a, b) { return a - b; }).forEach(function (f) {
        var o = document.createElement('option');
        o.value = f; o.textContent = 'Floor ' + f;
        $('tnFloor').appendChild(o);
    });

    $('tnFilterBtn').addEventListener('click', function (e) {
        e.stopPropagation();
        var pop = $('tnPop');
        pop.hidden = !pop.hidden;
        this.setAttribute('aria-expanded', String(!pop.hidden));
    });
    document.addEventListener('click', function (e) {
        if (!$('tnPop').hidden && !$('tnPop').contains(e.target)) $('tnPop').hidden = true;
    });
    $('tnFloor').addEventListener('change', function (e) { st.floor = e.target.value; update(); });
    $('tnPay').addEventListener('change', function (e) { st.pay = e.target.value; update(); });
    $('tnSort').addEventListener('change', function (e) { st.sort = e.target.value; update(); });
    $('tnReset').addEventListener('click', function () {
        st.q = ''; st.floor = 'all'; st.pay = 'all'; st.sort = 'name';
        $('tnSearch').value = ''; $('tnFloor').value = 'all'; $('tnPay').value = 'all'; $('tnSort').value = 'name';
        update();
    });

    $('tnLegend').addEventListener('click', function (e) {
        var el = e.target.closest('[data-s]');
        if (!el) return;
        st.pay = el.dataset.s;
        $('tnPay').value = st.pay;
        update();
    });

    $('tnView').addEventListener('click', function (e) {
        var half = e.target.closest('[data-v]');
        st.view = half ? half.dataset.v : (st.view === 'list' ? 'grid' : 'list');
        try { localStorage.setItem('tnView', st.view); } catch (err) {}
        st.page = 1;
        render();
    });

    $('tnPager').addEventListener('click', function (e) {
        var b = e.target.closest('[data-p]');
        if (!b || b.disabled) return;
        var v = b.dataset.p;
        st.page = v === 'prev' ? st.page - 1 : v === 'next' ? st.page + 1 : Number(v);
        render();
    });
    $('tnAll').addEventListener('change', function () {
        var on = this.checked;
        filtered().slice((st.page - 1) * PER, st.page * PER).forEach(function (t) { sel[t.id] = on; });
        render();
    });
    $('tnRows').addEventListener('change', function (e) {
        var c = e.target.closest('.tn-chk');
        if (c) { sel[c.dataset.id] = c.checked; render(); }
    });

    function onAction(e) {
        var b = e.target.closest('[data-act]');
        if (!b) return;
        var t = T.filter(function (x) { return String(x.id) === b.dataset.id; })[0];
        if (!t) return;

                     if (b.dataset.act === 'view') { window.location.href = DEL_URL + '/' + t.id; return; }

        if (b.dataset.act === 'msg') {
            if (!window.DaceChat) { toast('Chat is not ready. Refresh the page.', true); return; }
            window.DaceChat.open({
                kind: 'direct', item_id: t.id, with_id: t.id,
                with_name: t.name, with_room: 'Room ' + t.room,
                item_title: 'Direct message', item_price: 'Room ' + t.room, photo: ''
            });
            return;
        }

        delTarget = t;   
        $('tnModalText').textContent = 'You are about to permanently delete ' + t.name + ' (Room ' + t.room + '). '
            + 'Their account, payments, complaints, messages and marketplace listings will be removed. This cannot be undone.';
        $('tnModal').hidden = false;
        $('tnCancel').focus();
    }
    $('tnRows').addEventListener('click', onAction);
    $('tnGrid').addEventListener('click', onAction);

    function closeModal() { $('tnModal').hidden = true; delTarget = null; }
    $('tnCancel').addEventListener('click', closeModal);
    $('tnModal').addEventListener('click', function (e) { if (e.target === this) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !$('tnModal').hidden) closeModal(); });

    $('tnConfirm').addEventListener('click', function () {
        if (!delTarget) return;
        var btn = this, t = delTarget;
        btn.disabled = true; btn.textContent = 'Deleting...';

        api(DEL_URL + '/' + t.id, 'DELETE')
            .then(function (d) {
                T = T.filter(function (x) { return x.id !== t.id; });
                delete sel[t.id];
                closeModal();
                summary();
                render();
                toast(d.message || 'Tenant deleted.');
            })
            .catch(function (err) { toast(err.message, true); })
            .then(function () { btn.disabled = false; btn.textContent = 'Yes, delete'; });
    });

    summary();
    render();
})();
</script>
@endpush