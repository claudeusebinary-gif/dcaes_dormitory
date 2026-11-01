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

            </div>

            <div class="st-panel" data-panel="room-rent">
                <h2 class="st-title">Room & Rent</h2>
                <h1 class="yoursas">Default rates, deposits, and room rules.</h1>

            </div>

            <div class="st-panel" data-panel="payment-methods">
                <h2 class="st-title">Payment Methods</h2>
                <h1 class="yoursas">Accounts and methods tenants can pay with.</h1>

            </div>

            <div class="st-panel" data-panel="notification">
                <h2 class="st-title">Notification</h2>
                <h1 class="yoursas">Choose which alerts and emails go out.</h1>

            </div>

            <div class="st-panel" data-panel="staff-access">
                <h2 class="st-title">Staff & Access</h2>
                <h1 class="yoursas">Who can sign in to the admin side and what they can do.</h1>

            </div>

            <div class="st-panel" data-panel="security">
                <h2 class="st-title">Security</h2>
                <h1 class="yoursas">Password, sessions, and account protection.</h1>

            </div>

        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
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
        });
    </script>
@endsection