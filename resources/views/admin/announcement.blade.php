@extends('layouts.app')

@section('title', 'Announcement')
@section('page_title', 'Announcement')
@section('page_subtitle', 'Post, pin and manage notices for your tenants - everyone sees this the moment they log in.')
@section('page_action')
    <button type="button" class="roomlist-btn gawangannbtn" id="annOpenBtn">
        <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.2" stroke-linecap="round"
            stroke-linejoin="round" aria-hidden="true">
            <path d="M12 5v14M5 12h14" />
        </svg>
        Make a Announcement
    </button>
@endsection

@section('content')
    <div class="fournafourbox">
        <div class="fournafourbox1 statcard active-stat" data-stat="all" role="button" tabindex="0"
            title="Show all announcements">
            <div class="fourbox1four">
                <h1 id="stTotal">0</h1>
                <div class="totalposted"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M14 3H7a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h10a2 2 0 0 0 2-2V8z" />
                        <polyline points="14 3 14 8 19 8" />
                        <line x1="9" y1="13" x2="15" y2="13" />
                        <line x1="9" y1="17" x2="13" y2="17" />
                    </svg></div>
            </div>
            <h1>Total Posted</h1>
        </div>
        <div class="fournafourbox1 statcard" data-stat="pinned" role="button" tabindex="0"
            title="Show pinned announcements">
            <div class="fourbox1four">
                <h1 id="stPinned">0</h1>
                <div class="totalposted"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <line x1="12" y1="17" x2="12" y2="22" />
                        <path
                            d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24z" />
                    </svg></div>
            </div>
            <h1>Pinned</h1>
        </div>
        <div class="fournafourbox1 statcard" data-stat="new" role="button" tabindex="0" title="Posted in the last 3 days">
            <div class="fourbox1four">
                <h1 id="stNew">0</h1>
                <div class="totalposted"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <path d="M12 20h9" />
                        <path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" />
                    </svg> </div>
            </div>
            <h1>New Post</h1>
        </div>
        <div class="fournafourbox1 statcard" data-stat="expiring" role="button" tabindex="0" title="Expiring within 3 days">
            <div class="fourbox1four">
                <h1 id="stExpiring">0</h1>
                <div class="totalposted"><svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                        stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"
                        aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <polyline points="12 6 12 12 16 14" />
                    </svg></div>
            </div>
            <h1>Expiring Soon</h1>
        </div>
    </div>

    <div class="sixnabelog">
        <div class="sixnabelog1 active" data-filter="all">
            <h1>All</h1>
        </div>
        <div class="sixnabelog1" data-filter="utility">
            <h1>Utility</h1>
        </div>
        <div class="sixnabelog1" data-filter="safety">
            <h1>Safety</h1>
        </div>
        <div class="sixnabelog1" data-filter="payment">
            <h1>Payment</h1>
        </div>
        <div class="sixnabelog1" data-filter="event">
            <h1>Event</h1>
        </div>
        <div class="sixnabelog1" data-filter="general">
            <h1>General</h1>
        </div>
        <div class="ann-pager" id="annPager"></div>
    </div>

    <div class="boxnabrown" id="annBoard">
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

    <div class="bagongannoverlay" id="annFormModal" hidden>
        <div class="bagongannmodal" role="dialog" aria-modal="true" aria-labelledby="annFormTitle">
            <button type="button" class="sarahanann" id="annFormClose" aria-label="Close">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
                    <path d="M5 5l14 14M19 5L5 19" />
                </svg>
            </button>

            <h2 class="pamagatnamodal" id="annFormTitle">New Announcement</h2>

            <label class="labelnaann" for="fAnnTitle">Title</label>
                    <input type="text" class="kahonnaann" id="fAnnTitle" maxlength="60"
                placeholder="e.g Water Interruption - Aug 28" autocomplete="off">

            <div class="ann-tpl" id="annTemplates">
                <button type="button" class="ann-tpl-chip" data-title="Announcement" data-cat="general">Announcement</button>
                <button type="button" class="ann-tpl-chip" data-title="Water Interruption - " data-cat="utility">Water Interruption</button>
                <button type="button" class="ann-tpl-chip" data-title="Power Interruption - " data-cat="utility">Power Interruption</button>
                <button type="button" class="ann-tpl-chip" data-title="Internet Maintenance - " data-cat="utility">Internet Maintenance</button>
                <button type="button" class="ann-tpl-chip" data-title="Rent Due Reminder" data-cat="payment">Rent Due Reminder</button>
                <button type="button" class="ann-tpl-chip" data-title="Curfew Reminder" data-cat="safety">Curfew Reminder</button>
                <button type="button" class="ann-tpl-chip" data-title="Fire Drill - " data-cat="safety">Fire Drill</button>
                <button type="button" class="ann-tpl-chip" data-title="Dorm Meeting - " data-cat="event">Dorm Meeting</button>
                <button type="button" class="ann-tpl-chip" data-title="Cleaning Schedule" data-cat="general">Cleaning Schedule</button>
            </div>

            <label class="labelnaann" for="fAnnMsg">Message</label>
            <textarea class="kahonnaann kahonnaannmalaki" id="fAnnMsg" maxlength="150"
                placeholder="Give tenants exactly what they need to know..."></textarea>

            <span class="labelnaann">Category</span>
            <div class="pilikategorya" id="fAnnCats">
                <button type="button" class="pilikategorya1" data-cat="utility" style="--c:#1060BC"><i></i>Utility</button>
                <button type="button" class="pilikategorya1" data-cat="safety" style="--c:#672c2c"><i></i>Safety</button>
                <button type="button" class="pilikategorya1" data-cat="payment" style="--c:#C98A2C"><i></i>Payment</button>
                <button type="button" class="pilikategorya1" data-cat="event" style="--c:#2D7248"><i></i>Event</button>
                <button type="button" class="pilikategorya1 is-pili" data-cat="general"
                    style="--c:#1F7A8C"><i></i>General</button>
            </div>

            <div class="dalawahangkahon">
                <div>
                    <label class="labelnaann" for="fAnnAud">Audience</label>
                    <select class="kahonnaann" id="fAnnAud">
                        <option value="All tenants">All Tenants</option>
                        <option value="Unpaid only">Unpaid Only</option>
                    </select>
                </div>
                <div>
                    <label class="labelnaann" for="fAnnExp">Expires</label>
                    <input type="date" class="kahonnaann" id="fAnnExp">
                </div>
            </div>

            <div class="pinnasaitaas">
                <div>
                    <h1>Pin to top</h1>
                    <span>Pinned notices always show first on the board</span>
                </div>
                <label class="switchnapin">
                    <input type="checkbox" id="fAnnPin" aria-label="Pin to top">
                    <span class="switchnapin1"></span>
                </label>
            </div>

            <p class="annmali" id="annFormErr" hidden></p>

            <div class="butonsaibaba">
                <button type="button" class="kanselaann" id="annFormCancel">Cancel</button>
                <button type="button" class="ipostann" id="annFormSave">Post Announcement</button>
            </div>
        </div>
    </div>

    <div class="ev-toast" id="annToast"></div>
@endsection

@push('scripts')
    <script>
        (function () {
            var PER_PAGE = 6;
            var ROTATIONS = [-1.5, 1.2, -0.8, 1.8, -1.2, 0.9];
            var COLORS = { payment: '#C98A2C', event: '#2D7248', utility: '#1060BC', safety: '#672c2c', general: '#1F7A8C' };
            var ANN_URL = @json(route('admin.announcements.store'));
            var CSRF = document.querySelector('meta[name="csrf-token"]').content;

            var items = @json($annItems);
            var state = { category: 'all', stat: 'all', page: 1 };
            var editId = null;
            var pickedCat = 'general';

            var $ = function (id) { return document.getElementById(id); };
            var board = $('annBoard'), empty = $('annEmpty'), pager = $('annPager');
            var tabs = Array.prototype.slice.call(document.querySelectorAll('.sixnabelog1'));
            var stats = Array.prototype.slice.call(document.querySelectorAll('.statcard'));

            var PIN_SVG = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="12" y1="17" x2="12" y2="22" /><path d="M5 17h14v-1.76a2 2 0 0 0-1.11-1.79l-1.78-.9A2 2 0 0 1 15 10.76V6h1a2 2 0 0 0 0-4H8a2 2 0 0 0 0 4h1v4.76a2 2 0 0 1-1.11 1.79l-1.78.9A2 2 0 0 0 5 15.24z" /></svg>';
            var EDIT_SVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M12 20h9" /><path d="M16.5 3.5a2.12 2.12 0 0 1 3 3L7 19l-4 1 1-4z" /></svg>';
            var DEL_SVG = '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="3 6 5 6 21 6" /><path d="M19 6l-1 14a2 2 0 0 1-2 2H8a2 2 0 0 1-2-2L5 6" /><path d="M10 11v6M14 11v6" /><path d="M9 6V4a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2" /></svg>';

            function esc(s) { var d = document.createElement('div'); d.textContent = s == null ? '' : s; return d.innerHTML; }
            function pad(n) { return n < 10 ? '0' + n : '' + n; }
            function todayISO() { var d = new Date(); return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }

            // days from today to a Y-m-d date (negative = in the past)
            function dayDiff(iso) {
                var p = iso.split('-'), d = new Date(+p[0], +p[1] - 1, +p[2]), t = new Date();
                t.setHours(0, 0, 0, 0);
                return Math.round((d - t) / 86400000);
            }
            function nice(iso) {
                var p = iso.split('-');
                return new Date(+p[0], +p[1] - 1, +p[2]).toLocaleDateString('en-US', { month: 'long', day: 'numeric', year: 'numeric' });
            }

            var tt;
            function toast(msg, bad) {
                var t = $('annToast');
                t.textContent = msg;
                t.className = 'ev-toast is-show' + (bad ? ' is-bad' : '');
                clearTimeout(tt);
                tt = setTimeout(function () { t.className = 'ev-toast'; }, 2400);
            }

            function api(url, method, body) {
                return fetch(url, {
                    method: method,
                    headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
                    credentials: 'same-origin',
                    body: body ? JSON.stringify(body) : undefined
                }).then(function (r) {
                    return r.json().catch(function () { return {}; }).then(function (d) {
                        if (!r.ok) {
                            var msg = d.errors ? Object.values(d.errors)[0][0] : (d.message || 'Something went wrong.');
                            if (r.status === 419) msg = 'Session expired. Refresh the page.';
                            throw new Error(msg);
                        }
                        return d;
                    });
                });
            }

            function info(a) {
                var ago = Math.max(0, -dayDiff(a.posted));
                var left = a.expires ? dayDiff(a.expires) : null;
                var postedText = ago === 0 ? 'Posted today' : (ago === 1 ? 'Posted yesterday'
                    : 'Posted ' + (ago < 7 ? ago + ' days ago' : (ago < 30 ? Math.floor(ago / 7) + (Math.floor(ago / 7) === 1 ? ' week ago' : ' weeks ago')
                        : Math.floor(ago / 30) + (Math.floor(ago / 30) === 1 ? ' month ago' : ' months ago'))));
                var expireText = left === null ? 'No expiry'
                    : (left < 0 ? 'Expired' : (left === 0 ? 'Expires today' : (left <= 3 ? 'Expires in ' + left + 'd' : 'Until ' + a.expires)));

                return {
                    color: COLORS[a.category] || COLORS.general,
                    isNew: ago <= 3,
                    isExpiring: left !== null && left >= 0 && left <= 3,
                    isExpired: left !== null && left < 0,
                    postedText: postedText,
                    postedFull: nice(a.posted),
                    expireText: expireText,
                    expireFull: a.expires ? nice(a.expires) : 'No expiry date'
                };
            }

            function sorted() {
                return items.slice().sort(function (a, b) {
                    return (b.pinned ? 1 : 0) - (a.pinned ? 1 : 0) || b.id - a.id;
                });
            }

            function matches(a) {
                var i = info(a);
                var okCat = state.category === 'all' || a.category === state.category;
                var okStat = state.stat === 'all'
                    || (state.stat === 'pinned' && a.pinned)
                    || (state.stat === 'new' && i.isNew)
                    || (state.stat === 'expiring' && i.isExpiring);
                return okCat && okStat;
            }

            function cardHtml(a, n) {
                var i = info(a);
                return '<div class="boxnabrown1 pangadminkard' + (i.isExpired ? ' lipasnaann' : '') + '"'
                    + ' style="border-top: 8px solid ' + i.color + '; --rot:' + ROTATIONS[n % ROTATIONS.length] + 'deg"'
                    + ' role="button" tabindex="0" data-id="' + a.id + '">'
                    + (a.pinned ? '<span class="pinned-mark" title="Pinned">' + PIN_SVG + '</span>' : '')
                    + '<div class="editdeletesaann">'
                    + '<button type="button" class="pangeditann" data-act="edit" aria-label="Edit announcement" title="Edit">' + EDIT_SVG + '</button>'
                    + '<button type="button" class="pangburatann" data-act="delete" aria-label="Delete announcement" title="Delete">' + DEL_SVG + '</button>'
                    + '</div>'
                    + '<h1 class="toppay" style="color: ' + i.color + '">' + esc(a.category.toUpperCase()) + '</h1>'
                    + '<h1 class="rentdue">' + esc(a.title) + '</h1>'
                    + '<span class="friendlyreminder">' + esc(a.message) + '</span>'
                    + '<div class="unpdaid"><span>' + esc(a.audience.toUpperCase()) + '</span></div>'
                    + '<div class="unpdaid2main"><div class="unpdaid2"><span>' + esc(i.expireText) + '</span></div><h1>' + esc(i.postedText) + '</h1></div>'
                    + '</div>';
            }

            function render() {
                var all = sorted();
                var list = all.filter(matches);
                var pages = Math.max(1, Math.ceil(list.length / PER_PAGE));
                if (state.page > pages) state.page = pages;
                var from = (state.page - 1) * PER_PAGE;
                var show = list.slice(from, from + PER_PAGE);

                Array.prototype.slice.call(board.querySelectorAll('.boxnabrown1')).forEach(function (c) { c.remove(); });
                board.insertAdjacentHTML('afterbegin', show.map(cardHtml).join(''));
                empty.style.display = list.length ? 'none' : 'block';

                var infos = all.map(info);
                $('stTotal').textContent = all.length;
                $('stPinned').textContent = all.filter(function (a) { return a.pinned; }).length;
                $('stNew').textContent = infos.filter(function (i) { return i.isNew; }).length;
                $('stExpiring').textContent = infos.filter(function (i) { return i.isExpiring; }).length;

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
                    b.addEventListener('click', function () { state.page = page; render(); });
                    pager.appendChild(b);
                }
                btn('‹ Prev', state.page - 1, state.page === 1, false);
                for (var p = 1; p <= pages; p++) btn(String(p), p, false, p === state.page);
                btn('Next ›', state.page + 1, state.page === pages, false);

                var s = document.createElement('span');
                s.textContent = 'Showing ' + (from + 1) + '–' + (from + shown) + ' of ' + count;
                pager.appendChild(s);
            }

            function activate(el, fn) {
                el.addEventListener('click', fn);
                el.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); fn(); }
                });
            }
            tabs.forEach(function (t) {
                activate(t, function () { state.category = t.dataset.filter; state.stat = 'all'; state.page = 1; render(); });
            });
            stats.forEach(function (s) {
                activate(s, function () { state.stat = s.dataset.stat; state.category = 'all'; state.page = 1; render(); });
            });

            var view = $('annModal');
            function openView(a) {
                var i = info(a);
                $('annBar').style.backgroundColor = i.color;
                var cat = $('annCat');
                cat.textContent = a.category.toUpperCase();
                cat.style.color = i.color;
                $('annTitle').textContent = a.title;
                $('annText').textContent = a.message;
                $('annFor').textContent = a.audience.toUpperCase();
                $('annPosted').textContent = i.postedFull;
                $('annExpires').textContent = i.expireFull;
                view.classList.add('open');
            }
            function closeView() { view.classList.remove('open'); }
            $('annClose').addEventListener('click', closeView);
            view.addEventListener('click', function (e) { if (e.target === view) closeView(); });

            var form = $('annFormModal');
            var fTitle = $('fAnnTitle'), fMsg = $('fAnnMsg'), fAud = $('fAnnAud'), fExp = $('fAnnExp'), fPin = $('fAnnPin');
            var saveBtn = $('annFormSave'), errEl = $('annFormErr');

            function say(t) { errEl.hidden = !t; errEl.textContent = t || ''; }

            function pickCat(c) {
                pickedCat = c;
                Array.prototype.forEach.call($('fAnnCats').children, function (b) {
                    b.classList.toggle('is-pili', b.dataset.cat === c);
                });
            }

            function openForm(a) {
                editId = a ? a.id : null;
                $('annFormTitle').textContent = a ? 'Edit Announcement' : 'New Announcement';
                saveBtn.textContent = a ? 'Save Changes' : 'Post Announcement';
                fTitle.value = a ? a.title : '';
                fMsg.value = a ? a.message : '';
                fAud.value = a ? a.audience : 'All tenants';
                fExp.value = a && a.expires ? a.expires : '';
                fExp.min = a ? '' : todayISO();
                fPin.checked = a ? !!a.pinned : false;
                pickCat(a ? a.category : 'general');
                say('');
                form.hidden = false;
                document.body.style.overflow = 'hidden';
                fTitle.focus();
            }
            function closeForm() {
                form.hidden = true;
                document.body.style.overflow = '';
            }

            $('annOpenBtn').addEventListener('click', function () { openForm(null); });
            $('annFormClose').addEventListener('click', closeForm);
            $('annFormCancel').addEventListener('click', closeForm);
            form.addEventListener('click', function (e) { if (e.target === form) closeForm(); });
            $('fAnnCats').addEventListener('click', function (e) {
                var b = e.target.closest('[data-cat]');
                if (b) pickCat(b.dataset.cat);
            });

                    $('annTemplates').addEventListener('click', function (e) {
                var b = e.target.closest('[data-title]');
                if (!b) return;
                fTitle.value = b.dataset.title;
                if (b.dataset.cat) pickCat(b.dataset.cat);
                fTitle.focus();
                fTitle.setSelectionRange(fTitle.value.length, fTitle.value.length);
            });

            saveBtn.addEventListener('click', function () {
                var t = fTitle.value.trim(), m = fMsg.value.trim();
                if (!t) { say('Please enter a title.'); fTitle.focus(); return; }
                if (!m) { say('Please write the message.'); fMsg.focus(); return; }

                var payload = {
                    title: t, message: m, category: pickedCat, audience: fAud.value,
                    ends_at: fExp.value || null, pinned: fPin.checked
                };
                var wasEdit = editId;
                saveBtn.disabled = true; say('');

                var req = wasEdit ? api(ANN_URL + '/' + wasEdit, 'PUT', payload) : api(ANN_URL, 'POST', payload);
                req.then(function (a) {
                    if (wasEdit) {
                        items = items.map(function (x) { return x.id === a.id ? a : x; });
                    } else {
                        items.unshift(a);
                    }
                    state.category = 'all'; state.stat = 'all'; state.page = 1;
                    closeForm();
                    render();
                    toast(wasEdit ? 'Announcement updated.' : 'Announcement posted. Tenants were notified.');
                })
                    .catch(function (err) { say(err.message); })
                    .then(function () { saveBtn.disabled = false; });
            });

            function findItem(card) {
                return items.filter(function (x) { return x.id === Number(card.dataset.id); })[0];
            }

            board.addEventListener('click', function (e) {
                var card = e.target.closest('.boxnabrown1');
                if (!card) return;
                var a = findItem(card);
                if (!a) return;

                var act = e.target.closest('[data-act]');
                if (act && act.dataset.act === 'edit') { openForm(a); return; }
                if (act && act.dataset.act === 'delete') {
                    if (!confirm('Delete "' + a.title + '"? Tenants will no longer see it.')) return;
                    api(ANN_URL + '/' + a.id, 'DELETE')
                        .then(function () {
                            items = items.filter(function (x) { return x.id !== a.id; });
                            render();
                            toast('Announcement deleted.');
                        })
                        .catch(function (err) { toast(err.message, true); });
                    return;
                }
                openView(a);
            });
            board.addEventListener('keydown', function (e) {
                if ((e.key === 'Enter' || e.key === ' ') && e.target.classList.contains('boxnabrown1')) {
                    e.preventDefault();
                    var a = findItem(e.target);
                    if (a) openView(a);
                }
            });

            document.addEventListener('keydown', function (e) {
                if (e.key !== 'Escape') return;
                if (!form.hidden) closeForm(); else closeView();
            });

            render();
        })();
    </script>
@endpush