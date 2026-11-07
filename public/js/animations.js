(function () {
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;


    var SEL = 'button, a[href], [role="button"], [role="tab"], ' +
        'input[type="button"], input[type="submit"], input[type="checkbox"], ' +
        'input[type="radio"], label, summary, select, .clickcard, [data-filter]';

    function findClickable(el) {
        var hit = el.closest(SEL);
        if (hit) return hit;


        var n = el;
        for (var i = 0; n && n !== document.body && i < 6; i++, n = n.parentElement) {
            if (getComputedStyle(n).cursor === 'pointer') return n;
        }
        return null;
    }

    document.addEventListener('pointerdown', function (e) {
        if (e.button !== 0) return;

        var t = findClickable(e.target);
        if (!t || t.disabled || t.getAttribute('aria-disabled') === 'true') return;


        var r = document.createElement('span');
        r.className = 'click-ripple';
        r.style.left = e.clientX + 'px';
        r.style.top = e.clientY + 'px';
        document.body.appendChild(r);
        r.addEventListener('animationend', function () { r.remove(); });


        var rect = t.getBoundingClientRect();
        if (rect.height > 450 || rect.width > 900) return;

        t.classList.remove('click-anim');
        void t.offsetWidth;
        t.classList.add('click-anim');
        t.addEventListener('animationend', function done(ev) {
            if (ev.target !== t) return;
            t.classList.remove('click-anim');
            t.removeEventListener('animationend', done);
        });
    }, true);
})();

/* ===== Count-up numbers on the admin stat cards ===== */
(function () {
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var DURATION = 1400;   // how long each number counts (milliseconds)
    var STAGGER  = 70;     // small delay between one number and the next

    var SELECTORS = [
        '.upataapatfourboxw1 h2', '.upataapatfourboxw1 span',                          // Dashboard
        '#tnTotal', '#tnRooms', '#lg-all', '#lg-paid', '#lg-due', '#lg-overdue',       // Tenants
        '.breakdownbababa .breakdownpababa h1', '.breakdownbababa .breakdownpababa span',
        '#stTotal', '#stPinned', '#stNew', '#stExpiring',                              // Announcement
        '.namber9',                                                                    // Rooms
        '.collectpay',                                                                 // Payments
        '.complainpangayos h1'                                                         // Complaints + Approvals
    ].join(',');

    var NUM = /\d[\d,]*(?:\.\d+)?/g;

    function render(original, p) {
        return original.replace(NUM, function (m) {
            var dec   = (m.split('.')[1] || '').length;
            var comma = m.indexOf(',') > -1;
            var s = (parseFloat(m.replace(/,/g, '')) * p).toFixed(dec);
            if (comma) {
                var parts = s.split('.');
                parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                s = parts.join('.');
            }
            return s;
        });
    }

    function start() {
        var items = [].slice.call(document.querySelectorAll(SELECTORS));
        items.forEach(function (el, i) {
            var original = el.textContent.trim();
            if (!/\d/.test(original)) return;

            el.classList.add('count-up');
            el.textContent = render(original, 0);

            setTimeout(function () {
                var t0 = null;
                (function frame(now) {
                    if (t0 === null) t0 = now;
                    var t = Math.min((now - t0) / DURATION, 1);
                    var eased = 1 - Math.pow(1 - t, 3);
                    el.textContent = t < 1 ? render(original, eased) : original;
                    if (t < 1) requestAnimationFrame(frame);
                })(performance.now());
            }, Math.min(i, 6) * STAGGER);
        });
    }

    // If the page-change curtain is still covering the screen, wait until it lifts
    function whenReady(fn) {
        var c = document.getElementById('pageCurtain');
        if (!c || !c.classList.contains('covered')) { fn(); return; }
        var done = false, mo;
        function go() { if (done) return; done = true; if (mo) mo.disconnect(); fn(); }
        mo = new MutationObserver(function () { if (!c.classList.contains('covered')) go(); });
        mo.observe(c, { attributes: true, attributeFilter: ['class'] });
        setTimeout(go, 7000);
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function () { whenReady(start); });
    } else {
        whenReady(start);
    }
})();
/* ===== Rise-in animation for boxes / cards on page load (all pages) ===== */
(function () {
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var BOXES = [
        '.pangsell1',            // Marketplace item cards
        '.mgaeventslahat1',      // Events list cards
        '.eventsummarie1',       // Events summary boxes
        '.calendar',             // Events calendar
        '.bxsa', '.sixbs', '.lagayan',      // Home
        '.fournafourbox1', '.boxnabrown1',  // Announcements
        '.upatnaapatbox1',                  // Payments (tenant)
        '.upataapatfourboxw1',              // Admin dashboard
        '.collectedthismonth1', '.tickercollected1',   // Admin payments
        '.complaintboxesa1', '.boxhabacomplaint1',     // Admin complaints
        '.approvalsboxsix1'                            // Admin approvals (stat boxes reuse .complaintboxesa1)
    ].join(',');

    function run() {
        var n = 0;
        [].slice.call(document.querySelectorAll('.main-content :is(' + BOXES + ')')).forEach(function (el) {
            if (!el.getClientRects().length) return;                          // hidden (other page / closed modal)
            if (el.parentElement && el.parentElement.closest(BOXES)) return;  // nested inside another box
            el.style.setProperty('--i', Math.min(n++, 10));
            el.classList.add('rise');
            el.addEventListener('animationend', function done(ev) {
                if (ev.target !== el || ev.animationName !== 'riseIn') return;
                el.classList.remove('rise');
                el.style.removeProperty('--i');
                el.removeEventListener('animationend', done);
            });
        });
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', run);
    else run();
})();