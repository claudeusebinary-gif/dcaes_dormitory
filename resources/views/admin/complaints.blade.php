@extends('layouts.app')

@section('title', 'Complaints')
@section('page_title', 'Complaints')
@section('page_subtitle', 'Every tenant issue on record — from a leaking faucet to a noise dispute — filed, tracked, and closed out in one place.')
@section('page_action')
    <button type="button" class="addroombtn" id="manualBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
            stroke-linejoin="round" aria-hidden="true">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>Manual record complaints
    </button>
@endsection
@section('content')
    <div class="complaintboxesa">
        <div class="complaintboxesa1">
            <div class="complainpangayos">
                <h1>{{ $stats['open'] }}</h1>
                <div class="complaintboxpola"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M22 19a2 2 0 0 1-2 2H4a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h5l2 3h9a2 2 0 0 1 2 2z" />
                        <line x1="12" y1="11" x2="12" y2="17" />
                        <line x1="9" y1="14" x2="15" y2="14" />
                    </svg></div>
            </div>
            <h1 class="OPENCASE">OPEN CASES</h1>
        </div>
        <div class="complaintboxesa1">
            <div class="complainpangayos">
                <h1>{{ $stats['progress'] }}</h1>
                <div class="complaintboxpola"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M21 12a9 9 0 1 1-3-6.7L21 8" />
                        <path d="M21 3v5h-5" />
                        <polyline points="12 7 12 12 15 14" />
                    </svg></div>
            </div>
            <h1 class="OPENCASE">IN PROGRESS</h1>
        </div>
        <div class="complaintboxesa1">
            <div class="complainpangayos">
                <h1>{{ $stats['month'] }}</h1>
                <div class="complaintboxpola"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg></div>
            </div>
            <h1 class="OPENCASE">RESOLVE THIS MONTH</h1>
        </div>
        <div class="complaintboxesa1">
            <div class="complainpangayos">
                <h1>{{ $stats['high'] }}</h1>
                <div class="complaintboxpola"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path
                            d="M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0z" />
                        <line x1="12" y1="9" x2="12" y2="13" />
                        <circle cx="12" cy="17" r="0.5" />
                    </svg></div>
            </div>
            <h1 class="OPENCASE">HIGH PRIORITY</h1>
        </div>
    </div>
    <div class="search">
        <div class="search-box">
            <svg class="search-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7" />
                <path d="m20 20-3.5-3.5" />
            </svg>
            <input type="search" id="paySearch" placeholder="Search complaint..." autocomplete="off" />
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

                <label for="fType">Category</label>
                <select id="fType">
                    <option value="">Any</option>
                    @foreach (\App\Models\Complaint::TYPES as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>

                <button type="button" class="filter-reset" id="fReset">Reset</button>
            </div>
        </div>
    </div>
    <div class="filtering payfilterbar"
        style="width:100%; max-width:none; display:flex; flex-direction:row; align-items:center; gap:10px;">
        <input type="button" class="fiveb active" data-filter="all" value="All cases" style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="open" value="Open" style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="progress" value="In progress" style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="resolved" value="Resolved" style="width:112px; flex:0 0 112px;">
        <div class="ann-pager" id="payPager"
            style="margin:0 0 0 auto; justify-content:flex-end; flex-wrap:nowrap; white-space:nowrap;"></div>
    </div>
    <div class="boxhabacomplaint" id="cList">
        @foreach ($cards as $c)
            <div class="boxhabacomplaint1 st-{{ $c['status'] }}" data-status="{{ $c['status'] }}" data-room="{{ $c['room'] }}"
                data-type="{{ $c['type'] }}" data-json="{{ json_encode($c) }}"
                data-search="{{ strtolower($c['name'] . ' room ' . $c['room'] . ' ' . $c['type_label'] . ' ' . $c['title'] . ' ' . $c['message'] . ' ' . $c['label']) }}">
                <div class="comjuelleplaints">
                    <h1 class="h1com">{{ strtoupper($c['name']) }} · ROOM
                        {{ $c['room'] }}{{ $c['bed'] ? ', BED ' . $c['bed'] : '' }}</h1>
                    <div class="comhi">
                        <h1 class="OPENCOMd">{{ $c['label'] }}</h1>
                    </div>
                </div>
                <h3>{{ $c['title'] }}</h3>
                <h2>{{ $c['message'] }}</h2>
                <div class="notifsacomplaint">
                    <div class="notifsacomplaint1">
                        <h4>{{ $c['type_label'] }}</h4>
                    </div>
                    @if ($c['high'] && $c['status'] !== 'resolved')
                        <div class="notifsacomplaint1 chip-high">
                            <h4>High priority</h4>
                        </div>
                    @endif
                </div>
                <hr class="padiretso231759274" style="margin-top:20px;">
                <div class="parightcomjueplaint">
                    <h5>Filed {{ $c['filed'] }}</h5>
                    <div class="cutoncom">
                        <button type="button" class="cutoncom1" data-view>
                            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round">
                                <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8S1 12 1 12z" />
                                <circle cx="12" cy="12" r="3" />
                            </svg>View details
                        </button>
                        @if ($c['status'] === 'open')
                            <button type="button" class="cutoncom2" data-next="in_progress" data-url="{{ $c['status_url'] }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>Start progress
                            </button>
                        @elseif ($c['status'] === 'progress')
                            <button type="button" class="cutoncom2" data-next="resolved" data-url="{{ $c['status_url'] }}">
                                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round">
                                    <polyline points="20 6 9 17 4 12" />
                                </svg>Mark as resolved
                            </button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if ($cards->isEmpty())
        <p class="no-rooms">No complaints yet.</p>
    @endif
    <p class="no-rooms" id="noPay" hidden>No complaints match your search.</p>
    <div class="bagongannoverlay" id="cViewModal" hidden>
        <div class="bagongannmodal" role="dialog" aria-modal="true">
            <div class="cv-head">
                <div>
                    <p class="cv-who" id="vWho"></p>
                    <h2 class="cv-title" id="vTitle"></h2>
                </div>
                <span class="cv-pill" id="vPill"></span>
                <button type="button" class="cv-x" id="vX" aria-label="Close">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                        <path d="M5 5l14 14M19 5L5 19" />
                    </svg>
                </button>
            </div>
            <div class="cv-body">
                <div class="cv-chips">
                    <span class="cv-chip cv-blue" id="vType"></span>
                    <span class="cv-chip cv-gold" id="vHigh">High priority</span>
                    <span class="cv-chip cv-grey" id="vFiled"></span>
                </div>
                <p class="cv-label">DESCRIPTION</p>
                <div class="cv-box" id="vMsg"></div>
                <div class="cv-box cv-grid">
                    <div>
                        <p class="cv-label">PHONE</p><b id="vPhone"></b>
                    </div>
                    <div>
                        <p class="cv-label">EMAIL</p><b id="vEmail"></b>
                    </div>
                </div>
                <p class="cv-label">CASE TIMELINE</p>
                <ol class="cv-time">
                    <li id="vS1"><i></i><b>Complaint filed</b><small id="vT1"></small></li>
                    <li id="vS2"><i></i><b>Marked in progress</b><small id="vT2"></small></li>
                    <li id="vS3"><i></i><b>Marked resolved</b><small id="vT3"></small></li>
                </ol>
                <div class="cv-foot">
                    <button type="button" class="cutoncom1" id="vClose">Close</button>
                    <button type="button" class="cutoncom2" id="vAction">
                        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round">
                            <polyline points="20 6 9 17 4 12" />
                        </svg><span id="vActText"></span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="bagongannoverlay" id="cNewModal" hidden data-url="{{ route('admin.complaints.store') }}">
        <div class="bagongannmodal" role="dialog" aria-modal="true">
            <button type="button" class="sarahanann" id="nClose" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M5 5l14 14M19 5L5 19" />
                </svg>
            </button>
            <h2 class="pamagatnamodal">File a complaint</h2>
            <p class="modalsub">Log a new issue reported by a tenant.</p>

            <label class="labelnaann" for="nTenant">Tenant / Room</label>
            <select class="kahonnaann" id="nTenant">
                <option value="">Select a tenant</option>
                @foreach ($tenants as $t)
                    <option value="{{ $t->id }}">{{ $t->display_name }} - Room
                        {{ $t->room_number }}{{ $t->bed_number ? ', Bed ' . $t->bed_number : '' }}</option>
                @endforeach
            </select>

            <label class="labelnaann" for="nType">Category</label>
            <select class="kahonnaann" id="nType">
                @foreach (\App\Models\Complaint::TYPES as $k => $v)
                    <option value="{{ $k }}">{{ strtoupper($v) }}</option>
                @endforeach
            </select>

            <label class="labelnaann" for="nMsg">Description</label>
            <textarea class="kahonnaann kahonnaannmalaki" id="nMsg" maxlength="1000"
                placeholder="What happened?"></textarea>

            <p class="annmali" id="nErr" hidden></p>
            <div class="butonsaibaba" style="margin-top:26px">
                <button type="button" class="kanselaann paybtnhalf" id="nCancel">Cancel</button>
                <button type="button" class="ipostann paybtnhalf" id="nSave">File complaint</button>
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

            const cards = Array.from(document.querySelectorAll('#cList .boxhabacomplaint1'));
            const search = $('paySearch'), tabs = document.querySelectorAll('.fiveb');
            const fBtn = $('filterBtn'), fPop = $('filterPop'), fRoom = $('fRoom'), fType = $('fType');
            const none = $('noPay'), pager = $('payPager');
            const PER_PAGE = 2;
            let status = 'all', page = 1;

            let tt;
            function toast(msg, bad) {
                const t = $('payToast');
                t.textContent = msg;
                t.className = 'ev-toast is-show' + (bad ? ' is-bad' : '');
                clearTimeout(tt);
                tt = setTimeout(() => { t.className = 'ev-toast'; }, 2600);
            }
            async function post(url, body) {
                const res = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf },
                    body: JSON.stringify(body || {})
                });
                const data = await res.json().catch(() => ({}));
                if (res.status === 419) throw new Error('Your session expired. Refresh the page and try again.');
                if (res.status === 422 && data.errors) throw new Error(Object.values(data.errors)[0][0]);
                if (!res.ok) throw new Error(data.message || 'Something went wrong. Please try again.');
                return data;
            }
            const netMsg = e => e instanceof TypeError ? 'No connection. Check your internet and try again.' : e.message;

            function matches(c) {
                if (status !== 'all' && c.dataset.status !== status) return false;
                const words = search.value.toLowerCase().trim().split(/\s+/).filter(Boolean);
                if (!words.every(w => c.dataset.search.includes(w))) return false;
                if (fRoom.value && c.dataset.room !== fRoom.value) return false;
                if (fType.value && c.dataset.type !== fType.value) return false;
                return true;
            }
            function renderPager(pages, total, from, shown) {
                pager.innerHTML = '';
                if (pages <= 1) return;
                function btn(label, target, disabled, current) {
                    const b = document.createElement('button');
                    b.type = 'button'; b.textContent = label;
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
                const active = (fRoom.value ? 1 : 0) + (fType.value ? 1 : 0);
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
            [fRoom, fType].forEach(el => el.addEventListener('input', () => apply()));
            $('fReset').addEventListener('click', () => { fRoom.value = ''; fType.value = ''; apply(); });
            apply();

            async function setStatus(btn) {
                btn.disabled = true;
                try {
                    const d = await post(btn.dataset.url, { status: btn.dataset.next });
                    toast(d.message);
                    setTimeout(() => location.reload(), 800);
                } catch (e) { btn.disabled = false; toast(netMsg(e), true); }
            }
            document.querySelectorAll('[data-next]').forEach(b => b.addEventListener('click', () => setStatus(b)));

            const vm = $('cViewModal'), act = $('vAction');
            function openView(c) {
                $('vWho').textContent = (c.name + ' · Room ' + c.room + (c.bed ? ', Bed ' + c.bed : '')).toUpperCase();
                $('vTitle').textContent = c.title;
                const pill = $('vPill'); pill.textContent = c.label; pill.className = 'cv-pill st-' + c.status;
                $('vType').textContent = c.type_label;
                $('vHigh').hidden = !(c.high && c.status !== 'resolved');
                $('vFiled').textContent = 'Filed ' + c.filed;
                $('vMsg').textContent = c.message;
                $('vPhone').textContent = c.phone || '—';
                $('vEmail').textContent = c.email || '—';
                $('vT1').textContent = c.filed_full;
                $('vT2').textContent = c.progress_at || 'Not yet reached';
                $('vT3').textContent = c.resolved_at || 'Not yet reached';
                $('vS1').classList.add('done');
                $('vS2').classList.toggle('done', !!c.progress_at);
                $('vS3').classList.toggle('done', !!c.resolved_at);

                act.hidden = c.status === 'resolved';
                act.disabled = false;
                act.dataset.url = c.status_url;
                act.dataset.next = c.status === 'open' ? 'in_progress' : 'resolved';
                $('vActText').textContent = c.status === 'open' ? 'Start progress' : 'Mark as resolved';
                vm.hidden = false;
            }
            function closeView() { vm.hidden = true; }
            document.querySelectorAll('[data-view]').forEach(b => b.addEventListener('click', () => {
                openView(JSON.parse(b.closest('.boxhabacomplaint1').dataset.json));
            }));
            act.addEventListener('click', () => setStatus(act));
            $('vX').addEventListener('click', closeView);
            $('vClose').addEventListener('click', closeView);
            vm.addEventListener('click', e => { if (e.target === vm) closeView(); });

            const nm = $('cNewModal'), nTenant = $('nTenant'), nType = $('nType'), nMsg = $('nMsg'), nErr = $('nErr'), nSave = $('nSave');
            function nShow(m) { nErr.hidden = !m; nErr.textContent = m || ''; }
            function openNew() { nShow(''); nTenant.value = ''; nType.selectedIndex = 0; nMsg.value = ''; nm.hidden = false; nTenant.focus(); }
            function closeNew() { nm.hidden = true; }
            $('manualBtn').addEventListener('click', openNew);
            $('nClose').addEventListener('click', closeNew);
            $('nCancel').addEventListener('click', closeNew);
            nm.addEventListener('click', e => { if (e.target === nm) closeNew(); });
            document.addEventListener('keydown', e => {
                if (e.key !== 'Escape') return;
                if (!nm.hidden) closeNew();
                if (!vm.hidden) closeView();
            });
            nSave.addEventListener('click', async () => {
                if (!nTenant.value) return nShow('Choose a tenant.');
                if (!nMsg.value.trim()) return nShow('Describe what happened.');
                const label = nSave.textContent;
                nSave.disabled = true; nSave.textContent = 'Saving...'; nShow('');
                try {
                    const d = await post(nm.dataset.url, { tenant_id: +nTenant.value, type: nType.value, message: nMsg.value.trim() });
                    closeNew(); toast(d.message);
                    setTimeout(() => location.reload(), 800);
                } catch (e) {
                    nShow(netMsg(e)); nSave.disabled = false; nSave.textContent = label;
                }
            });
        })();
    </script>
@endpush