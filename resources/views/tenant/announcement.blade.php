@extends('layouts.app')

@section('title', 'Announcements')

@section('content')
@php
    $items = [
        ['category' => 'payment', 'title' => 'Rent Due Reminder',
         'body' => 'Friendly reminder that rent is due on the 5th. Message admin if you need an extension.',
         'audience' => 'ALL TENANTS', 'pinned' => true,  'posted_ago' => 7, 'expires_in' => 9],
        ['category' => 'event',   'title' => 'Weekend Building Cleaning',
         'body' => 'General cleaning of hallways and common areas this Saturday, 7–9 AM. Please keep the corridor clear.',
         'audience' => 'UNPAID ONLY', 'pinned' => false, 'posted_ago' => 1, 'expires_in' => 3],
        ['category' => 'utility', 'title' => 'Water Interruption — Nov 3',
         'body' => 'Scheduled water interruption from 8:00 AM to 12:00 PM. Please store water in advance.',
         'audience' => 'ALL TENANTS', 'pinned' => false, 'posted_ago' => 2, 'expires_in' => 9],
        ['category' => 'safety',  'title' => 'New CCTV Cameras Installed',
         'body' => "Additional CCTV coverage added near the garage and laundry area for everyone's safety.",
         'audience' => 'UNPAID ONLY', 'pinned' => false, 'posted_ago' => 5, 'expires_in' => 12],
        ['category' => 'utility', 'title' => 'Aircon Cleaning Schedule',
         'body' => 'Aircon units will be cleaned room by room this week. Please be present or leave your room unlocked.',
         'audience' => 'ALL TENANTS', 'pinned' => false, 'posted_ago' => 3, 'expires_in' => 5],
        ['category' => 'general', 'title' => 'Quiet Hours Reminder',
         'body' => 'Quiet hours are from 10:00 PM to 6:00 AM. Please keep noise down in the rooms and hallways.',
         'audience' => 'ALL TENANTS', 'pinned' => false, 'posted_ago' => 4, 'expires_in' => null],
        ['category' => 'event',   'title' => 'Movie Night in the Lounge',
         'body' => 'Join us this Friday at 7:00 PM in the lounge. Bring your own snacks and keep the volume friendly.',
         'audience' => 'ALL TENANTS', 'pinned' => false, 'posted_ago' => 0, 'expires_in' => 2],
        ['category' => 'safety',  'title' => 'Fire Drill Schedule',
         'body' => 'A fire drill will be held next week. Please learn the nearest exit and gather at the front gate.',
         'audience' => 'ALL TENANTS', 'pinned' => true,  'posted_ago' => 6, 'expires_in' => 20],
        ['category' => 'general', 'title' => 'Laundry Room Schedule',
         'body' => 'To avoid crowding, laundry use is by room number: odd rooms on weekdays, even rooms on weekends.',
         'audience' => 'ALL TENANTS', 'pinned' => false, 'posted_ago' => 10, 'expires_in' => null],
    ];

    $colors = ['payment' => '#C98A2C', 'event' => '#2D7248', 'utility' => '#1060BC', 'safety' => '#672c2c', 'general' => '#1F7A8C'];

    usort($items, fn ($a, $b) => [$b['pinned'], $a['posted_ago']] <=> [$a['pinned'], $b['posted_ago']]);

    $items = array_map(function ($a) use ($colors) {
        $posted  = now()->subDays($a['posted_ago']);
        $expires = $a['expires_in'] === null ? null : now()->addDays($a['expires_in']);

        $a['color']       = $colors[$a['category']];
        $a['posted_text'] = $a['posted_ago'] === 0 ? 'Posted today' : ($a['posted_ago'] === 1 ? 'Posted yesterday' : 'Posted ' . $posted->diffForHumans());
        $a['posted_full'] = $posted->format('F j, Y');
        $a['is_new']      = $a['posted_ago'] <= 3;
        $a['is_expiring'] = $a['expires_in'] !== null && $a['expires_in'] <= 3;
        $a['expire_text'] = $expires === null ? 'No expiry'
            : ($a['expires_in'] === 0 ? 'Expires today' : ($a['is_expiring'] ? 'Expires in ' . $a['expires_in'] . 'd' : 'Until ' . $expires->format('Y-m-d')));
        $a['expire_full'] = $expires === null ? 'No expiry date' : $expires->format('F j, Y');
        return $a;
    }, $items);

    $total    = count($items);
    $pinned   = count(array_filter($items, fn ($a) => $a['pinned']));
    $newCount = count(array_filter($items, fn ($a) => $a['is_new']));
    $expiring = count(array_filter($items, fn ($a) => $a['is_expiring']));
@endphp

   @section('page_title', 'Announcements')
@section('page_subtitle', 'View all announcement here.')

     <div class="fournafourbox">
        <div class="fournafourbox1 statcard active-stat" data-stat="all" role="button" tabindex="0" title="Show all announcements">
         <div class="fourbox1four">
            <h1>{{ $total }}</h1>
            <div class="totalposted"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
    <polyline points="14 3 14 8 19 8" />
    <line x1="9" y1="13" x2="15" y2="13" />
    <line x1="9" y1="17" x2="13" y2="17" />
</svg></div>
         </div>
         <h1>Total Posted</h1>
        </div>
        <div class="fournafourbox1 statcard" data-stat="pinned" role="button" tabindex="0" title="Show pinned announcements"><div class="fourbox1four">
            <h1>{{ $pinned }}</h1>
            <div class="totalposted"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <line x1="12" y1="17" x2="12" y2="22" />
    <path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24z" />
</svg></div>
         </div>
         <h1>Pinned</h1></div>
        <div class="fournafourbox1 statcard" data-stat="new" role="button" tabindex="0" title="Posted in the last 3 days"><div class="fourbox1four">
            <h1>{{ $newCount }}</h1>
            <div class="totalposted"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <path d="M12 20h9" />
    <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
</svg>   </div>
         </div>
         <h1>New Post</h1></div>
        <div class="fournafourbox1 statcard" data-stat="expiring" role="button" tabindex="0" title="Expiring within 3 days"><div class="fourbox1four">
            <h1>{{ $expiring }}</h1>
            <div class="totalposted"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
    <circle cx="12" cy="12" r="10" />
    <polyline points="12 6 12 12 16 14" />
</svg></div>
         </div>
         <h1>Expiring Soon</h1>   </div>
     </div>

     <div class="sixnabelog">
      <div class="sixnabelog1 active" data-filter="all"><h1>All</h1></div>
      <div class="sixnabelog1" data-filter="utility"><h1>Utility</h1></div>
      <div class="sixnabelog1" data-filter="safety"><h1>Safety</h1></div>
      <div class="sixnabelog1" data-filter="payment"><h1>Payment</h1></div>
      <div class="sixnabelog1" data-filter="event"><h1>Event</h1></div>
      <div class="sixnabelog1" data-filter="general"><h1>General</h1></div>
       <div class="ann-pager" id="annPager"></div>
     </div>

     <div class="boxnabrown" id="annBoard">
        @foreach ($items as $a)
        <div class="boxnabrown1" style="border-top: 8px solid {{ $a['color'] }}"
             role="button" tabindex="0"
             data-category="{{ $a['category'] }}"
             data-pinned="{{ $a['pinned'] ? 1 : 0 }}"
             data-new="{{ $a['is_new'] ? 1 : 0 }}"
             data-expiring="{{ $a['is_expiring'] ? 1 : 0 }}"
             data-color="{{ $a['color'] }}"
             data-title="{{ $a['title'] }}"
             data-body="{{ $a['body'] }}"
             data-audience="{{ $a['audience'] }}"
             data-posted="{{ $a['posted_full'] }}"
             data-expires="{{ $a['expire_full'] }}">
            @if ($a['pinned'])
            <span class="pinned-mark" title="Pinned"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="17" x2="12" y2="22" /><path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24z" /></svg></span>
            @endif
            <h1 class="toppay" style="color: {{ $a['color'] }}">{{ strtoupper($a['category']) }}</h1>
            <h1 class="rentdue">{{ $a['title'] }}</h1>
            <span class="friendlyreminder">{{ $a['body'] }}</span>
            <div class="unpdaid"><span>{{ $a['audience'] }}</span> </div>
            <div class="unpdaid2main">
                <div class="unpdaid2"><span>{{ $a['expire_text'] }}</span> </div>
                <h1>{{ $a['posted_text'] }}</h1>
            </div>
        </div>
        @endforeach

        <div class="ann-empty" id="annEmpty">No announcements here yet.</div>
     </div>

     <div class="ann-overlay" id="annModal">
        <div class="ann-modal" role="dialog" aria-modal="true" aria-labelledby="annTitle">
            <div class="ann-modal-bar" id="annBar"></div>
            <button type="button" class="ann-close" id="annClose" aria-label="Close">&times;</button>
            <div class="ann-modal-body">
                <p class="ann-cat" id="annCat"></p>
                <h2 id="annTitle"></h2>
                <p class="ann-text" id="annText"></p>
                <div class="ann-meta">
                    <div><span>For</span><strong id="annFor"></strong></div>
                    <div><span>Posted</span><strong id="annPosted"></strong></div>
                    <div><span>Expires</span><strong id="annExpires"></strong></div>
                </div>
            </div>
        </div>
     </div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var PER_PAGE = 6;
    var ROTATIONS = [-1.5, 1.2, -0.8, 1.8, -1.2, 0.9];    

    var board  = document.getElementById('annBoard');
    var cards  = Array.prototype.slice.call(board.querySelectorAll('.boxnabrown1'));
    var empty  = document.getElementById('annEmpty');
    var pager  = document.getElementById('annPager');
    var tabs   = Array.prototype.slice.call(document.querySelectorAll('.sixnabelog1'));
    var stats  = Array.prototype.slice.call(document.querySelectorAll('.statcard'));
    var state  = { category: 'all', stat: 'all', page: 1 };

    function activate(el, fn) {
        el.addEventListener('click', fn);
        el.addEventListener('keydown', function (e) {
            if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fn(); }
        });
    }

    function matches(card) {
        var okCategory = state.category === 'all' || card.dataset.category === state.category;
        var okStat = state.stat === 'all'
            || (state.stat === 'pinned'   && card.dataset.pinned   === '1')
            || (state.stat === 'new'      && card.dataset.new      === '1')
            || (state.stat === 'expiring' && card.dataset.expiring === '1');
        return okCategory && okStat;
    }

    function render() {
        var list  = cards.filter(matches);
        var pages = Math.max(1, Math.ceil(list.length / PER_PAGE));
        if (state.page > pages) state.page = pages;

        var from = (state.page - 1) * PER_PAGE;
        var show = list.slice(from, from + PER_PAGE);

        cards.forEach(function (c) { c.classList.add('is-hidden'); });
        show.forEach(function (c, i) {
            c.classList.remove('is-hidden');
            c.style.setProperty('--rot', ROTATIONS[i % ROTATIONS.length] + 'deg');
        });
        empty.style.display = list.length ? 'none' : 'block';

        tabs.forEach(function (t) { t.classList.toggle('active', t.dataset.filter === state.category); });
        stats.forEach(function (s) { s.classList.toggle('active-stat', s.dataset.stat === state.stat); });

        renderPager(pages, list.length, from, show.length);
    }

    function renderPager(pages, count, from, shown) {
        pager.innerHTML = '';
        if (pages <= 1) return;

        function btn(label, page, disabled, current) {
            var b = document.createElement('button');
            b.type = 'button';
            b.textContent = label;
            if (current) b.className = 'current';
            b.disabled = !!disabled;
            b.addEventListener('click', function () { goTo(page); });
            pager.appendChild(b);
        }

        btn('‹ Prev', state.page - 1, state.page === 1, false);
        for (var p = 1; p <= pages; p++) btn(String(p), p, false, p === state.page);
        btn('Next ›', state.page + 1, state.page === pages, false);

        var info = document.createElement('span');
        info.textContent = 'Showing ' + (from + 1) + '–' + (from + shown) + ' of ' + count;
        pager.appendChild(info);
    }

    function goTo(page) {
        state.page = page;
        render();
        board.scrollIntoView({ behavior: 'smooth', block: 'start' });
    }

   
    tabs.forEach(function (t) {
        activate(t, function () { state.category = t.dataset.filter; state.stat = 'all'; state.page = 1; render(); });
    });

  
    stats.forEach(function (s) {
        activate(s, function () { state.stat = s.dataset.stat; state.category = 'all'; state.page = 1; render(); });
    });

    
    var modal = document.getElementById('annModal');
    var lastFocus = null;

    function openModal(card) {
        lastFocus = card;
        document.getElementById('annBar').style.backgroundColor = card.dataset.color;
        var cat = document.getElementById('annCat');
        cat.textContent = card.dataset.category.toUpperCase();
        cat.style.color = card.dataset.color;
        document.getElementById('annTitle').textContent   = card.dataset.title;
        document.getElementById('annText').textContent    = card.dataset.body;
        document.getElementById('annFor').textContent     = card.dataset.audience;
        document.getElementById('annPosted').textContent  = card.dataset.posted;
        document.getElementById('annExpires').textContent = card.dataset.expires;
        modal.classList.add('open');
        document.getElementById('annClose').focus();
    }
    function closeModal() {
        modal.classList.remove('open');
        if (lastFocus) lastFocus.focus();
    }

    cards.forEach(function (c) { activate(c, function () { openModal(c); }); });
    document.getElementById('annClose').addEventListener('click', closeModal);
    modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
    document.addEventListener('keydown', function (e) { if (e.key === 'Escape') closeModal(); });

    render();
});
</script>
@endsection