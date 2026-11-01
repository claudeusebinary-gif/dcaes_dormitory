@extends('layouts.app')

@section('title', 'Calendar')

@section('page_title', 'Calendar')
@section('page_subtitle', 'Dorm activities, floor meetings, and maintenance schedule.')
@section('page_action')
   <button id="addEventBtn" class="roomlist-btn" style="border: 1px solid #672C2C; cursor: pointer;">Add Event</button>
@endsection

@section('content')
   <div class="kabuuannanglahtlaat">
      <div class="calendar">
         <div class="switch21">
            <button type="button" class="kaliwanaswitch" id="calPrev" aria-label="Previous month">
               <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#672C2C" stroke-width="2.4"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M15 6l-6 6 6 6" />
               </svg>
            </button>
            <h1 id="calTitle" title="Go to today"></h1>
            <button type="button" class="kananswitch" id="calNext" aria-label="Next month">
               <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="#672C2C" stroke-width="2.4"
                  stroke-linecap="round" stroke-linejoin="round">
                  <path d="M9 6l6 6-6 6" />
               </svg>
            </button>
         </div>
         <div class="hileranadate">
            <h1 class="mgadatengayon">SUN</h1>
            <h1 class="mgadatengayon">MON</h1>
            <h1 class="mgadatengayon">TUE</h1>
            <h1 class="mgadatengayon">WED</h1>
            <h1 class="mgadatengayon">THU</h1>
            <h1 class="mgadatengayon">FRI</h1>
            <h1 class="mgadatengayon">SAT</h1>
         </div>
         <div class="calgrid" id="calGrid"></div>
      </div>
      <div class="searchboxlaglagayan">
         <input type="search" class="heightsearch" id="evSearch" placeholder="Search by event...">

         <div class="fileters" id="evFilters">
            <button type="button" class="fileters1 is-active" data-f="All">All</button>
            <button type="button" class="fileters1" data-f="Social">Social</button>
            <button type="button" class="fileters1" data-f="Meeting">Meeting</button>
            <button type="button" class="fileters1" data-f="Maintenance">Maintenance</button>
            <button type="button" class="fileters1" data-f="My Event">My Event</button>
         </div>

         <div class="eventsummarie">
            <div class="eventsummarie1">
               <h1 id="stTotal">0</h1><span>Total Events</span>
            </div>
            <div class="eventsummarie1">
               <h1 id="stGoing">0</h1><span id="stGoingLbl">Joining</span>
            </div>
            <div class="eventsummarie1">
               <h1 id="stWeek">0</h1><span>This Week</span>
            </div>
         </div>

         <div class="upcoming-row">
            <h1 class="upcoming" id="evHeading">UPCOMING EVENTS</h1>
            <button type="button" class="ev-clear" id="evClear" hidden>Show upcoming</button>
         </div>

         <div class="mgaeventslahat" id="evList"></div>
      </div>

   </div>
   <div class="evm" id="eventModal" hidden>
      <div class="evm-box" role="dialog" aria-modal="true" aria-label="Add Event">
         <button type="button" class="evm-close" id="eventModalClose" aria-label="Close">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round">
               <path d="M5 5l14 14M19 5L5 19" />
            </svg>
         </button>

         <div class="evm-body">
            <h1 class="postmoitem">Add an event</h1>
            <span class="listfellow">Plan something for the dorm. Everyone can see it and join.</span>

            <div class="pirsname54">
               <h1>Event title</h1>
               <input type="text" id="evTitle" class="laglaggayanfd" maxlength="80" placeholder="e.g Movie Night">
            </div>

            <div class="pirsname295">
               <div class="pirsname54">
                  <h1>Type</h1>
                  <select id="evType" class="laglaggayan2">
                     <option value="Social">Social</option>
                     <option value="Meeting">Meeting</option>
                  </select>
               </div>
               <div class="pirsname54">
                  <h1>Location</h1>
                  <input type="text" id="evLocation" class="laglaggayanfd" maxlength="80" placeholder="Whole Dormitory">
               </div>
            </div>

            <div class="pirsname295">
               <div class="pirsname54">
                  <h1>Date</h1>
                  <input type="date" id="evDate" class="laglaggayanfd">
               </div>
               <div class="pirsname54">
                  <h1>Time</h1>
                  <input type="time" id="evTime" class="laglaggayanfd">
               </div>
            </div>

            <div class="pirsname54">
               <h1>Description (optional)</h1>
               <textarea id="evDesc" class="laglaggayan22" maxlength="200"
                  placeholder="What should people know?"></textarea>
            </div>

            <p class="mk-status" id="evStatus" hidden></p>
            <button type="button" class="messagemosiseller" id="evSubmit">Add Event</button>
         </div>
      </div>
   </div>
   <div class="ev-toast" id="evToast"></div>
@endsection

@push('scripts')
   <script>
      (function () {
         var EVENTS = @json($events);
         var STORE_URL = @json(route('tenant.calendar.store'));
         var CSRF = document.querySelector('meta[name="csrf-token"]').content;

         var COLORS = { Social: '#CA8D31', Meeting: '#672C2C', Maintenance: '#2B6C45' };
         var MONTHS = ['JAN', 'FEB', 'MAR', 'APR', 'MAY', 'JUN', 'JUL', 'AUG', 'SEP', 'OCT', 'NOV', 'DEC'];
         var CLOCK = '<svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>';

         var $ = function (id) { return document.getElementById(id); };
         var grid = $('calGrid'), title = $('calTitle'), list = $('evList');

         var today = new Date(); today.setHours(0, 0, 0, 0);
         var view = new Date(today.getFullYear(), today.getMonth(), 1);
         var selected = null;
         var filter = 'All', query = '';

         function pad(n) { return n < 10 ? '0' + n : '' + n; }
         function iso(d) { return d.getFullYear() + '-' + pad(d.getMonth() + 1) + '-' + pad(d.getDate()); }
         function same(a, b) { return !!a && !!b && iso(a) === iso(b); }
         function parseISO(s) { var p = s.split('-'); return new Date(+p[0], +p[1] - 1, +p[2]); }
         function esc(s) {
            return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
               return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c];
            });
         }
         function sortEvents() {
            EVENTS.sort(function (a, b) { return (a.date + a.time).localeCompare(b.date + b.time); });
         }

         var toastTimer;
         function toast(msg, bad) {
            var t = $('evToast');
            t.textContent = msg;
            t.className = 'ev-toast is-show' + (bad ? ' is-bad' : '');
            clearTimeout(toastTimer);
            toastTimer = setTimeout(function () { t.className = 'ev-toast'; }, 2600);
         }

         function api(url, method, body) {
            return fetch(url, {
               method: method,
               headers: { 'Accept': 'application/json', 'Content-Type': 'application/json', 'X-CSRF-TOKEN': CSRF },
               credentials: 'same-origin',
               body: body ? JSON.stringify(body) : undefined
            }).then(function (r) {
               return r.json().catch(function () { return {}; }).then(function (data) {
                  if (!r.ok) {
                     var msg = data.errors ? Object.values(data.errors)[0][0] : (data.message || 'Something went wrong.');
                     if (r.status === 419) msg = 'Your session expired. Refresh the page and try again.';
                     if (r.status === 429) msg = 'Too many requests. Please wait a moment.';
                     throw new Error(msg);
                  }
                  return data;
               });
            });
         }

         function visible() {
            var q = query.trim().toLowerCase();
            return EVENTS.filter(function (e) {
               if (filter === 'My Event' && !e.mine) return false;
               if (filter !== 'All' && filter !== 'My Event' && e.type !== filter) return false;
               if (q && (e.title + ' ' + e.type + ' ' + e.location + ' ' + (e.description || '')).toLowerCase().indexOf(q) === -1) return false;
               return true;
            });
         }

         function renderCalendar() {
            title.textContent = view.toLocaleDateString('en-US', { month: 'long', year: 'numeric' });

            var byDate = {};
            visible().forEach(function (e) { (byDate[e.date] = byDate[e.date] || []).push(e); });

            var first = new Date(view.getFullYear(), view.getMonth(), 1);
            var start = new Date(first);
            start.setDate(1 - first.getDay());

            var html = '';
            for (var i = 0; i < 42; i++) {
               var d = new Date(start);
               d.setDate(start.getDate() + i);

               var evs = byDate[iso(d)] || [];
               var cls = 'calday';
               if (d.getMonth() !== view.getMonth()) cls += ' is-out';
               if (same(d, today)) cls += ' is-today';
               if (same(d, selected)) cls += ' is-selected';
               if (evs.length) cls += ' has-events';

               var dots = '';
               if (evs.length) {
                  var seen = {}, types = [];
                  evs.forEach(function (e) { if (!seen[e.type]) { seen[e.type] = 1; types.push(e.type); } });
                  dots = '<i class="caldots">' + types.map(function (t) {
                     return '<b style="background:' + COLORS[t] + '"></b>';
                  }).join('') + '</i>';
               }

               html += '<button type="button" class="' + cls + '" data-date="' + iso(d) + '"'
                  + ' aria-label="' + d.toDateString() + (evs.length ? ', ' + evs.length + ' event(s)' : '') + '"'
                  + (same(d, selected) ? ' aria-pressed="true"' : '') + '>'
                  + '<span>' + d.getDate() + '</span>' + dots + '</button>';
            }
            grid.innerHTML = html;
         }

         function card(e) {
            var d = parseISO(e.date), past = d < today, notice = e.type === 'Maintenance', on = e.going;
            var label = notice ? (on ? 'Noted' : 'Mark noted') : (on ? 'Going' : "I'll Go");

            var people;
            if (notice) {
               people = '<span class="evt-more">Notice only</span>';
            } else {
               var av = e.people.map(function (i) { return '<span class="evt-avatar">' + esc(i) + '</span>'; }).join('');
               var more = e.count === 0 ? 'Be the first to join'
                  : (e.count > e.people.length ? '+' + (e.count - e.people.length) + ' more' : e.count + ' going');
               people = (av ? '<div class="evt-avatars">' + av + '</div>' : '') + '<span class="evt-more">' + more + '</span>';
            }

            var del = e.mine ? '<button type="button" class="evt-del" data-del="' + e.id + '">Delete</button>' : '';

            return '<div class="mgaeventslahat1' + (past ? ' is-past' : '') + '" data-date="' + e.date + '"'
               + ' style="border-left:6px solid ' + COLORS[e.type] + ';" title="' + esc(e.description || '') + '">'
               + '<div class="invoss96j">'
               + '<div class="invoss196j"><h1>' + d.getDate() + '</h1><span>' + MONTHS[d.getMonth()] + '</span></div>'
               + '<div class="ibosses96j"><h1>' + esc(e.title) + '</h1><span>' + CLOCK + esc(e.time_label) + ' • ' + esc(e.location) + '</span></div>'
               + '</div>'
               + '<div class="typenangmeetingss"><h1>' + esc(e.type) + '</h1></div>'
               + '<div class="evt-foot">'
               + '<div class="evt-people">' + people + '</div>'
               + '<div class="evt-actions">' + del
               + '<button type="button" class="evt-going' + (on ? '' : ' is-off') + '" data-rsvp="' + e.id + '"' + (past ? ' disabled' : '') + '>' + label + '</button>'
               + '</div>'
               + '</div>'
               + '</div>';
         }

         function renderList() {
            var all = visible(), shown, empty;

            if (selected) {
               var key = iso(selected);
               shown = all.filter(function (e) { return e.date === key; });
               $('evHeading').textContent = 'EVENTS ON ' + selected.toLocaleDateString('en-US', { month: 'short', day: 'numeric', year: 'numeric' }).toUpperCase();
               empty = 'No events on this date.';
            } else {
               shown = all.filter(function (e) { return parseISO(e.date) >= today; });
               $('evHeading').textContent = 'UPCOMING EVENTS';
               empty = (filter !== 'All' || query.trim()) ? 'No events match your search.' : 'No upcoming events.';
            }

            $('evClear').hidden = !selected;
            list.innerHTML = shown.length ? shown.map(card).join('') : '<div class="ev-empty">' + empty + '</div>';
         }

         function renderStats() {
            var all = visible();
            var weekStart = new Date(today); weekStart.setDate(today.getDate() - today.getDay());
            var weekEnd = new Date(weekStart); weekEnd.setDate(weekStart.getDate() + 7);

            $('stTotal').textContent = all.length;
            var scope = selected
               ? all.filter(function (e) { return e.date === iso(selected); })
               : all.filter(function (e) { return parseISO(e.date) >= today; });

                        var joining = scope
               .filter(function (e) { return e.type !== 'Maintenance'; })
               .reduce(function (n, e) { return n + e.count; }, 0);

            $('stGoing').textContent = joining;
            $('stGoingLbl').textContent = selected
               ? 'Joining · ' + selected.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
               : 'Joining (Upcoming)';
            $('stWeek').textContent = all.filter(function (e) { var d = parseISO(e.date); return d >= weekStart && d < weekEnd; }).length;
         }

         function refresh() { renderCalendar(); renderList(); renderStats(); }

         function select(d, force, focus) {
            d = new Date(d.getFullYear(), d.getMonth(), d.getDate());
            if (!force && same(selected, d)) {
               selected = null;
            } else {
               selected = d;
               view = new Date(d.getFullYear(), d.getMonth(), 1);
            }
            refresh();
            if (focus) { var b = grid.querySelector('.is-selected'); if (b) b.focus(); }
         }

         $('calPrev').addEventListener('click', function () { view = new Date(view.getFullYear(), view.getMonth() - 1, 1); refresh(); });
         $('calNext').addEventListener('click', function () { view = new Date(view.getFullYear(), view.getMonth() + 1, 1); refresh(); });
         title.addEventListener('click', function () { view = new Date(today.getFullYear(), today.getMonth(), 1); selected = null; refresh(); });
         $('evClear').addEventListener('click', function () { selected = null; refresh(); });

         grid.addEventListener('click', function (e) {
            var b = e.target.closest('.calday');
            if (b) select(parseISO(b.dataset.date), false, true);
         });
         grid.addEventListener('keydown', function (e) {
            var step = { ArrowLeft: -1, ArrowRight: 1, ArrowUp: -7, ArrowDown: 7 }[e.key];
            if (!step) return;
            e.preventDefault();
            var f = document.activeElement && document.activeElement.dataset ? document.activeElement.dataset.date : null;
            var d = f ? parseISO(f) : new Date(selected || today);
            d.setDate(d.getDate() + step);
            select(d, true, true);
         });

         function setChips() {
            document.querySelectorAll('#evFilters [data-f]').forEach(function (x) {
               x.classList.toggle('is-active', x.dataset.f === filter);
            });
         }
         $('evFilters').addEventListener('click', function (e) {
            var b = e.target.closest('[data-f]');
            if (!b) return;
            filter = b.dataset.f;
            setChips();
            refresh();
         });
         $('evSearch').addEventListener('input', function (e) { query = e.target.value; refresh(); });

         function replaceEvent(ev) {
            var i = EVENTS.findIndex(function (x) { return x.id === ev.id; });
            if (i > -1) EVENTS[i] = ev;
            refresh();
         }

         list.addEventListener('click', function (e) {
            var rs = e.target.closest('[data-rsvp]');
            var dl = e.target.closest('[data-del]');

            if (rs) {
               rs.disabled = true;
               api(STORE_URL + '/' + rs.dataset.rsvp + '/rsvp', 'POST')
                  .then(replaceEvent)
                  .catch(function (err) { toast(err.message, true); refresh(); });
               return;
            }
            if (dl) {
               if (!confirm('Delete this event?')) return;
               var id = +dl.dataset.del;
               api(STORE_URL + '/' + id, 'DELETE')
                  .then(function () { EVENTS = EVENTS.filter(function (x) { return x.id !== id; }); refresh(); toast('Event deleted.'); })
                  .catch(function (err) { toast(err.message, true); });
               return;
            }
            var c = e.target.closest('.mgaeventslahat1');
            if (c) select(parseISO(c.dataset.date), true);
         });

         var modal = $('eventModal'), openBtn = $('addEventBtn'), closeBtn = $('eventModalClose');

         function say(msg, good) {
            var s = $('evStatus');
            s.textContent = msg || '';
            s.hidden = !msg;
            s.className = 'mk-status' + (msg ? (good ? ' is-good' : ' is-bad') : '');
         }
         function openModal() {
            $('evTitle').value = '';
            $('evType').value = 'Social';
            $('evLocation').value = '';
            $('evDesc').value = '';
            $('evDate').min = iso(today);
            $('evDate').value = iso(selected && selected >= today ? selected : today);
            $('evTime').value = '18:00';
            say('');
            modal.hidden = false;
            document.body.style.overflow = 'hidden';
            $('evTitle').focus();
         }
         function closeModal() {
            modal.hidden = true;
            document.body.style.overflow = '';
            openBtn.focus();
         }

         openBtn.addEventListener('click', openModal);
         closeBtn.addEventListener('click', closeModal);
         modal.addEventListener('click', function (e) { if (e.target === modal) closeModal(); });
         document.addEventListener('keydown', function (e) { if (e.key === 'Escape' && !modal.hidden) closeModal(); });

         $('evSubmit').addEventListener('click', function () {
            var btn = this;
            var titleVal = $('evTitle').value.trim(), date = $('evDate').value, time = $('evTime').value;

            if (titleVal.length < 2) return say('Please enter an event title.');
            if (!date) return say('Please pick a date.');
            if (date < iso(today)) return say('The date cannot be in the past.');
            if (!time) return say('Please pick a time.');

            var label = btn.textContent;
            btn.disabled = true; btn.textContent = 'Adding...'; say('');

            api(STORE_URL, 'POST', {
               title: titleVal,
               type: $('evType').value,
               event_date: date,
               event_time: time,
               location: $('evLocation').value.trim(),
               description: $('evDesc').value.trim()
            })
               .then(function (ev) {
                  EVENTS.push(ev); sortEvents();
                  filter = 'All'; query = ''; $('evSearch').value = ''; setChips();
                  selected = parseISO(ev.date);
                  view = new Date(selected.getFullYear(), selected.getMonth(), 1);
                  closeModal();
                  refresh();
                  toast('Event added.');
               })
               .catch(function (err) { say(err.message); })
               .then(function () { btn.disabled = false; btn.textContent = label; });
         });

         sortEvents();
         refresh();
      })();
   </script>
@endpush