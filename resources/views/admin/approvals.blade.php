@extends('layouts.app')

@section('title', 'Approvals')
@section('page_title', 'Approvals')
@section('page_subtitle', 'Move-ins, room transfers, move-outs, and guest requests waiting on your sign-off — review the checklist, then approve or reject.')
@section('page_action')
    <button type="button" class="addroombtn" id="manualBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"
            stroke-linejoin="round" aria-hidden="true">
            <line x1="12" y1="5" x2="12" y2="19" />
            <line x1="5" y1="12" x2="19" y2="12" />
        </svg>Manual record request
    </button>
@endsection
@section('content')
    <div class="complaintboxesa">
        <div class="complaintboxesa1">
            <div class="complainpangayos">
                <h1>{{ $stats['pending'] }}</h1>
                <div class="complaintboxpola"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg></div>
            </div>
            <h1 class="OPENCASE">AWAITING REVIEW</h1>
        </div>
        <div class="complaintboxesa1">
            <div class="complainpangayos">
                <h1>{{ $stats['approved'] }}</h1>
                <div class="complaintboxpola"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14" />
                        <polyline points="22 4 12 14.01 9 11.01" />
                    </svg></div>
            </div>
            <h1 class="OPENCASE">APPROVED THIS MONTH</h1>
        </div>
        <div class="complaintboxesa1">
            <div class="complainpangayos">
                <h1>{{ $stats['rejected'] }}</h1>
                <div class="complaintboxpola"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <line x1="15" y1="9" x2="9" y2="15" />
                        <line x1="9" y1="9" x2="15" y2="15" />
                    </svg></div>
            </div>
            <h1 class="OPENCASE">REJECTED THIS MONTH</h1>
        </div>
        <div class="complaintboxesa1">
            <div class="complainpangayos">
                <h1>{{ $stats['movein'] }}</h1>
                <div class="complaintboxpola"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M15 3h4a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2h-4" />
                        <polyline points="10 17 15 12 10 7" />
                        <line x1="15" y1="12" x2="3" y2="12" />
                    </svg></div>
            </div>
            <h1 class="OPENCASE">MOVE-INS PENDING</h1>
        </div>
    </div>

    <div class="search">
        <div class="search-box">
            <svg class="search-ico" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"
                stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                <circle cx="11" cy="11" r="7" />
                <path d="m20 20-3.5-3.5" />
            </svg>
            <input type="search" id="paySearch" placeholder="Search by name, room number or request type..."
                autocomplete="off" />
            <span class="search-caret" aria-hidden="true"></span>
        </div>
        <div class="filter-wrap">
            <input type="button" class="filter" id="filterBtn" value="Filter" aria-expanded="false"
                aria-controls="filterPop">

            <div class="filter-pop" id="filterPop" hidden>
                <label for="fRoom">Room</label>
                <select id="fRoom">
                    <option value="">Any</option>
                    @foreach ($cards->pluck('room')->filter()->unique()->sort()->values() as $r)
                        <option value="{{ $r }}">Room {{ $r }}</option>
                    @endforeach
                </select>

                <label for="fType">Category</label>
                <select id="fType">
                    <option value="">Any</option>
                    @foreach (\App\Models\Approval::TYPES as $k => $v)
                        <option value="{{ $k }}">{{ $v }}</option>
                    @endforeach
                </select>

                <button type="button" class="filter-reset" id="fReset">Reset</button>
            </div>
        </div>
    </div>

    <div class="filtering payfilterbar"
        style="width:100%; max-width:none; display:flex; flex-direction:row; align-items:center; gap:10px;">
        <input type="button" class="fiveb active" data-filter="all" value="All request"
            style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="pending" value="Pending" style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="approved" value="Approved" style="width:112px; flex:0 0 112px;">
        <input type="button" class="fiveb" data-filter="rejected" value="Rejected" style="width:112px; flex:0 0 112px;">
        <div class="ann-pager" id="payPager"
            style="margin:0 0 0 auto; justify-content:flex-end; flex-wrap:nowrap; white-space:nowrap;"></div>
    </div>

    <div class="approvalsboxsix" id="aList">
        @foreach ($cards as $c)
            <div class="approvalsboxsix1" data-status="{{ $c['status'] }}" data-room="{{ $c['room'] }}"
                data-type="{{ $c['type'] }}" data-json="{{ json_encode($c) }}"
                data-search="{{ strtolower($c['name'] . ' room ' . $c['room'] . ' ' . $c['room_text'] . ' ' . $c['type_label'] . ' ' . $c['status']) }}">
                <div class="jvlogjueaprove">
                    <div class="jvlogjueaprove1">
                        <div class="jvlog">
                            <div class="jvlog1">
                                <h1>{{ $c['initials'] }}</h1>
                            </div>
                        </div>
                        <div class="juelogapprove">
                            <h1>{{ $c['name'] }}</h1>
                            <span>{{ $c['room_text'] }}</span>
                        </div>
                    </div>
                    <div class="pendingjueapproved st-{{ $c['status'] }}">
                        <h1>{{ $c['label'] }}</h1>
                    </div>
                </div>

                <h1 class="viewapprovejue" data-view><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z" />
                        <circle cx="12" cy="12" r="3" />
                    </svg>View application</h1>

                <div class="boxessaapproved">
                    <div class="boxessaapproved1">
                        <h1>{{ $c['type_label'] }}</h1>
                    </div>
                    <div class="boxessaapproved1" style="background-color:#FAF8F4;">
                        <h1 data-met style="color:{{ $c['met'] === $c['total'] ? '#2D7248' : '#8f6e2d' }};">
                            {{ $c['met'] }}/{{ $c['total'] }} requirements met</h1>
                    </div>
                </div>

                <div class="mgarequirmentsbox">
                    @foreach ($c['reqs'] as $r)
                        <label class="requireapprove1">
                            <input type="checkbox" class="requireapprove" data-key="{{ $r['key'] }}"
                                @checked($r['met']) @disabled($r['auto'] || $c['status'] !== 'pending')>
                            <h1>{{ $r['label'] }}</h1>
                        </label>
                    @endforeach
                </div>
                <hr class="hrge">
                <div class="rejectsabaysubmmit">
                    <h1 class="submmietedsa">Submitted {{ $c['submitted'] }}</h1>
                    <div class="rejectapprovedapproval">
                        @if ($c['status'] === 'pending')
                            <button type="button" class="rejectapprovedapproval1" data-decide="reject">Reject</button>
                            <button type="button" class="rejectapprovedapproval2" data-decide="approve">Approve</button>
                        @else
                            <button type="button" class="rejectapprovedapproval2" data-view
                                style="background-color:#FAF6F4; color:#672c2c; width:150px;">View details</button>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    </div>
    @if ($cards->isEmpty())
        <p class="no-rooms">No requests yet.</p>
    @endif
    <p class="no-rooms" id="noPay" hidden>No requests match your search.</p>

    <div class="bagongannoverlay" id="aViewModal" hidden>
        <div class="bagongannmodal" role="dialog" aria-modal="true">
            <div class="cv-head">
                <div>
                    <h2 class="cv-title" id="avName"></h2>
                    <p class="cv-who" id="avSub"></p>
                </div>
                <span class="cv-pill" id="avPill"></span>
                <button type="button" class="cv-x" id="avX" aria-label="Close">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                        <path d="M5 5l14 14M19 5L5 19" />
                    </svg>
                </button>
            </div>

            <div class="av-body">
                <div id="avDetails">
                    <div class="av-card">
                        <div class="av-card-head">
                            <div class="lagayanicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2" />
                                    <circle cx="12" cy="7" r="4" />
                                </svg></div>
                            <h3>Personal Information<span>Legal name and basic details</span></h3>
                        </div>
                        <div class="av-fields">
                            <div class="av-field"><small>First name</small><b id="avFirst"></b></div>
                            <div class="av-field"><small>Last name</small><b id="avLast"></b></div>
                            <div class="av-field"><small>Occupation</small><b id="avOcc"></b></div>
                            <div class="av-field"><small>Civil status</small><b id="avCivil"></b></div>
                            <div class="av-field"><small>Birthdate</small><b id="avBirth"></b></div>
                            <div class="av-field"><small>Gender</small><b id="avGender"></b></div>
                            <div class="av-field"><small>School</small><b id="avSchool"></b></div>
                        </div>
                    </div>

                    <div class="av-two">
                        <div class="av-card">
                            <div class="av-card-head">
                                <div class="lagayanicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path
                                            d="M22 16.92v3a2 2 0 0 1-2.18 2 19.79 19.79 0 0 1-8.63-3.07 19.5 19.5 0 0 1-6-6 19.79 19.79 0 0 1-3.07-8.67A2 2 0 0 1 4.11 2h3a2 2 0 0 1 2 1.72 12.84 12.84 0 0 0 .7 2.81 2 2 0 0 1-.45 2.11L8.09 9.91a16 16 0 0 0 6 6l1.27-1.27a2 2 0 0 1 2.11-.45 12.84 12.84 0 0 0 2.81.7A2 2 0 0 1 22 16.92z" />
                                    </svg></div>
                                <h3>Contact details<span>How we'll reach them</span></h3>
                            </div>
                            <div class="av-fields one">
                                <div class="av-field"><small>Mobile number</small><b id="avPhone"></b></div>
                                <div class="av-field"><small>Email address</small><b id="avEmail"></b></div>
                                <div class="av-field"><small>Home address</small><b id="avAddress"></b></div>
                            </div>
                        </div>
                        <div class="av-card">
                            <div class="av-card-head">
                                <div class="lagayanicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2" />
                                        <circle cx="9" cy="7" r="4" />
                                        <path d="M23 21v-2a4 4 0 0 0-3-3.87" />
                                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                                    </svg></div>
                                <h3>Emergency contact<span>In case we need to reach someone</span></h3>
                            </div>
                            <div class="av-fields one">
                                <div class="av-field"><small>Full name</small><b id="avEcName"></b></div>
                                <div class="av-field"><small>Relationship</small><b id="avEcRel"></b></div>
                                <div class="av-field"><small>Contact</small><b id="avEcPhone"></b></div>
                            </div>
                        </div>
                    </div>

                    <div class="av-two">
                        <div class="av-card">
                            <div class="av-card-head">
                                <div class="lagayanicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="2" y="4" width="20" height="16" rx="2" />
                                        <circle cx="9" cy="11" r="2.5" />
                                        <path d="M5.5 17a3.5 3.5 0 0 1 7 0" />
                                        <path d="M15 9.5h4" />
                                        <path d="M15 13.5h4" />
                                    </svg></div>
                                <h3>Valid ID<span>Government-issued ID on file</span></h3>
                            </div>
                            <div class="av-fields one">
                                <div class="av-field"><small>ID type</small><b id="avIdType"></b></div>
                                <div class="av-field"><small>ID number</small><b id="avIdNo"></b></div>
                            </div>
                        </div>
                        <div class="av-card">
                            <div class="av-card-head">
                                <div class="lagayanicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                        stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <rect x="3" y="4" width="18" height="18" rx="2" ry="2" />
                                        <path d="M16 2v4" />
                                        <path d="M8 2v4" />
                                        <path d="M3 10h18" />
                                    </svg></div>
                                <h3>Move-in details<span>When they're moving in</span></h3>
                            </div>
                            <div class="av-fields one">
                                <div class="av-field"><small>Preferred move-in date</small><b id="avDate"></b></div>
                                <div class="av-field"><small>Expected length of stay</small><b id="avStay"></b></div>
                                <div class="av-field"><small>Certification</small><b id="avCert"></b></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="avManual" hidden>
                    <div class="av-card">
                        <div class="av-card-head">
                            <div class="lagayanicon"><svg viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M12 20h9" />
                                    <path d="M16.5 3.5a2.1 2.1 0 0 1 3 3L7 19l-4 1 1-4z" />
                                </svg></div>
                            <h3>Manual record<span>Logged by the admin</span></h3>
                        </div>
                        <div class="av-fields">
                            <div class="av-field"><small>Applicant</small><b id="avmName"></b></div>
                            <div class="av-field"><small>Request type</small><b id="avmType"></b></div>
                            <div class="av-field"><small>Room / bed</small><b id="avmRoom"></b></div>
                            <div class="av-field"><small>Logged on</small><b id="avmLogged"></b></div>
                        </div>
                    </div>
                </div>
            </div>

            <p class="av-decided" id="avDecided" hidden></p>
            <div class="av-foot" id="avFoot">
                <button type="button" class="rejectapprovedapproval1" id="avReject">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <path d="M18 6 6 18M6 6l12 12" />
                    </svg>Reject
                </button>
                <button type="button" class="rejectapprovedapproval2" id="avApprove">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round">
                        <polyline points="20 6 9 17 4 12" />
                    </svg>Approve
                </button>
            </div>
        </div>
    </div>

    <div class="bagongannoverlay" id="aNewModal" hidden data-url="{{ route('admin.approvals.store') }}">
        <div class="bagongannmodal" role="dialog" aria-modal="true">
            <button type="button" class="sarahanann" id="nClose" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M5 5l14 14M19 5L5 19" />
                </svg>
            </button>
            <h2 class="pamagatnamodal">Log a request</h2>
            <p class="modalsub">Record a new approval request from a tenant or applicant.</p>

            <label class="labelnaann" for="nName">Applicant name</label>
            <input type="text" class="kahonnaann" id="nName" maxlength="100" placeholder="e.g. Juelle Villarosa"
                autocomplete="off">

            <label class="labelnaann" for="nType">Request type</label>
            <select class="kahonnaann" id="nType">
                @foreach (\App\Models\Approval::TYPES as $k => $v)
                    <option value="{{ $k }}">{{ strtoupper($v) }}</option>
                @endforeach
            </select>

            <label class="labelnaann" for="nRoom">Room / Bed</label>
            <input type="text" class="kahonnaann" id="nRoom" maxlength="60" placeholder="e.g. Room 100, Bed 2"
                autocomplete="off">

            <p class="annmali" id="nErr" hidden></p>
            <div class="butonsaibaba" style="margin-top:26px">
                <button type="button" class="kanselaann paybtnhalf" id="nCancel">Cancel</button>
                <button type="button" class="ipostann paybtnhalf" id="nSave">Save request</button>
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

            const cards = Array.from(document.querySelectorAll('#aList .approvalsboxsix1'));
            const search = $('paySearch'), tabs = document.querySelectorAll('.fiveb');
            const fBtn = $('filterBtn'), fPop = $('filterPop'), fRoom = $('fRoom'), fType = $('fType');
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

            function paintReqs(card, d) {
                const c = JSON.parse(card.dataset.json);
                c.reqs = d.reqs; c.met = d.met;
                card.dataset.json = JSON.stringify(c);
                d.reqs.forEach(r => {
                    const box = card.querySelector('.requireapprove[data-key="' + r.key + '"]');
                    if (box) { box.checked = r.met; box.disabled = r.auto; }
                });
                const m = card.querySelector('[data-met]');
                m.textContent = d.met + '/' + d.total + ' requirements met';
                m.style.color = d.met === d.total ? '#2D7248' : '#8f6e2d';
            }
            cards.forEach(card => {
                card.querySelectorAll('.requireapprove').forEach(box => {
                    box.addEventListener('change', async () => {
                        const c = JSON.parse(card.dataset.json);
                        box.disabled = true;
                        try {
                            paintReqs(card, await post(c.check_url, { key: box.dataset.key, value: box.checked }));
                        } catch (e) {
                            box.checked = !box.checked;
                            box.disabled = false;
                            toast(netMsg(e), true);
                        }
                    });
                });
            });

            async function decide(c, kind, btn) {
                if (kind === 'approve' && c.met < c.total && !confirm('Not every requirement is met yet. Approve anyway?')) return;
                if (kind === 'reject' && !confirm('Reject this request? The applicant will be notified.')) return;
                if (btn) btn.disabled = true;
                try {
                    await post(kind === 'approve' ? c.approve_url : c.reject_url);
                    toast(kind === 'approve' ? 'Request approved.' : 'Request rejected.');
                    setTimeout(() => location.reload(), 800);
                } catch (e) {
                    if (btn) btn.disabled = false;
                    toast(netMsg(e), true);
                }
            }
            document.querySelectorAll('[data-decide]').forEach(b => b.addEventListener('click', () => {
                decide(JSON.parse(b.closest('.approvalsboxsix1').dataset.json), b.dataset.decide, b);
            }));

            const vm = $('aViewModal');
            let open = null;
            const put = (id, v) => { $(id).textContent = v || '—'; };
            function openView(card) {
                const c = JSON.parse(card.dataset.json);
                open = c;
                $('avName').textContent = c.name;
                $('avSub').textContent = c.type_label + ' request · ' + c.room_text;
                const pill = $('avPill'); pill.textContent = c.label; pill.className = 'cv-pill st-' + c.status;

                $('avDetails').hidden = c.manual;
                $('avManual').hidden = !c.manual;
                if (c.manual) {
                    put('avmName', c.name); put('avmType', c.type_label);
                    put('avmRoom', c.room_text); put('avmLogged', c.submitted_full);
                } else {
                    put('avFirst', c.person.first); put('avLast', c.person.last);
                    put('avOcc', c.person.occupation); put('avCivil', c.person.civil);
                    put('avBirth', c.person.birthdate); put('avGender', c.person.gender);
                    put('avSchool', c.person.school);
                    put('avPhone', c.contact.phone); put('avEmail', c.contact.email); put('avAddress', c.contact.address);
                    put('avEcName', c.emergency.name); put('avEcRel', c.emergency.relationship); put('avEcPhone', c.emergency.phone);
                    put('avIdType', c.idcard.type); put('avIdNo', c.idcard.number);
                    put('avDate', c.movein.date); put('avStay', c.movein.stay);
                    const cert = $('avCert');
                    cert.textContent = c.movein.agreed ? '✓ Agreed to house rules & terms of stay' : 'Not confirmed';
                    cert.className = c.movein.agreed ? 'av-ok' : 'av-no';
                }

                const pending = c.status === 'pending';
                $('avFoot').hidden = !pending;
                $('avDecided').hidden = pending;
                $('avDecided').textContent = (c.status === 'approved' ? 'Approved on ' : 'Rejected on ') + c.decided;
                vm.querySelector('.bagongannmodal').scrollTop = 0;
                vm.hidden = false;
            }
            function closeView() { vm.hidden = true; open = null; }
            document.querySelectorAll('[data-view]').forEach(b => b.addEventListener('click', () => {
                openView(b.closest('.approvalsboxsix1'));
            }));
            $('avApprove').addEventListener('click', () => {
                const c = open;
                if (!c) return;
                // keep the checklist numbers fresh from the card the modal came from
                const card = cards.find(x => JSON.parse(x.dataset.json).id === c.id);
                decide(card ? JSON.parse(card.dataset.json) : c, 'approve', $('avApprove'));
            });
            $('avReject').addEventListener('click', () => { if (open) decide(open, 'reject', $('avReject')); });
            $('avX').addEventListener('click', closeView);
            vm.addEventListener('click', e => { if (e.target === vm) closeView(); });

            const nm = $('aNewModal'), nName = $('nName'), nType = $('nType'), nRoom = $('nRoom'), nErr = $('nErr'), nSave = $('nSave');
            function nShow(m) { nErr.hidden = !m; nErr.textContent = m || ''; }
            function openNew() { nShow(''); nName.value = ''; nType.selectedIndex = 0; nRoom.value = ''; nm.hidden = false; nName.focus(); }
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
                if (!nName.value.trim()) return nShow('Enter the applicant name.');
                if (!nRoom.value.trim()) return nShow('Enter the room / bed.');
                const label = nSave.textContent;
                nSave.disabled = true; nSave.textContent = 'Saving...'; nShow('');
                try {
                    const d = await post(nm.dataset.url, { name: nName.value.trim(), type: nType.value, room: nRoom.value.trim() });
                    closeNew(); toast(d.message);
                    setTimeout(() => location.reload(), 800);
                } catch (e) {
                    nShow(netMsg(e)); nSave.disabled = false; nSave.textContent = label;
                }
            });
        })();
    </script>
@endpush