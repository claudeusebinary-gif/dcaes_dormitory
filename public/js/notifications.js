(function () {
    const root = document.getElementById('notif');
    if (!root) return;

    const LIST_URL = root.dataset.listUrl;
    const READ_ALL_URL = root.dataset.readAllUrl;
    const READ_URL = root.dataset.readUrl; 
    const csrf = document.querySelector('meta[name="csrf-token"]').content;

    const btn = document.getElementById('notifBtn');
    const panel = document.getElementById('notifPanel');
    const dot = document.getElementById('notifDot');
    const list = document.getElementById('notifList');

    const esc = s => String(s).replace(/[&<>"']/g, c =>
        ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]));

    function ago(dateStr) {
        const s = Math.floor((Date.now() - new Date(dateStr)) / 1000);
        if (s < 60) return 'just now';
        if (s < 3600) return Math.floor(s / 60) + ' mins ago';
        if (s < 86400) return Math.floor(s / 3600) + ' hrs ago';
        return Math.floor(s / 86400) + ' days ago';
    }

    function setUnread(n) {
        dot.hidden = n < 1;
        btn.classList.toggle('has-unread', n > 0);
        dot.textContent = n > 9 ? '9+' : n;
    }

    async function load() {
        try {
            const res = await fetch(LIST_URL, {
                cache: 'no-store',
                headers: { 'Accept': 'application/json' },
                credentials: 'same-origin'
            });
            const data = await res.json();
            setUnread(data.unread);
            list.innerHTML = data.items.length
                ? data.items.map(n => `
                    <div class="notif-item ${!n.is_read ? 'unread' : ''}" data-id="${n.id}" data-key="${esc(n.key || '')}">
                        <b>${esc(n.title)}</b>
                        <p>${esc(n.message)}</p>
                        <small>${ago(n.created_at)}</small>
                    </div>`).join('')
                : '<p class="notif-empty">No notifications yet.</p>';
        } catch (e) { console.error('Notifications failed', e); }
    }

    function post(url, data) {
        return fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            credentials: 'same-origin',
            body: data ? new URLSearchParams(data) : undefined
        });
    }

    btn.addEventListener('click', e => {
        e.stopPropagation();
        panel.hidden = !panel.hidden;
        btn.setAttribute('aria-expanded', !panel.hidden);
        if (!panel.hidden) load();
    });

    list.addEventListener('click', async e => {
        const item = e.target.closest('.notif-item');
        if (!item || !item.classList.contains('unread')) return;
        await post(READ_URL.replace('__ID__', item.dataset.id), { key: item.dataset.key });
        load();
    });

    document.getElementById('notifReadAll').addEventListener('click', async () => {
        await post(READ_ALL_URL);
        load();
    });

    document.addEventListener('click', e => {
        if (!root.contains(e.target)) panel.hidden = true;
    });

    load();
      setInterval(function () { if (!document.hidden) load(); }, 30000);
})();
/* ===== Fade-up stagger on load ===== */
(function () {
    if (window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    var SEL = [
        '.bxsa', '.boxes > div', '.sixbs', '.lagayan',   // tenant home
        '.upatnaapatbox1',                               // tenant payments
        '.fournafourbox1', '.boxnabrown1',               // announcements
        '.roomstart',                                    // rooms list
        '.upataapatfourboxw1',                           // admin dashboard
        '.siven', '.siven4', '.upatbx'                   // dorm map
    ].join(',');

    function run() {
        document.querySelectorAll(SEL).forEach(function (el, i) {
            el.style.setProperty('--i', Math.min(i, 12));   // cap so late cards don't wait long
            el.classList.add('rise');
        });
    }

    var tries = 0;
    function go() {
        var c = document.getElementById('pageCurtain');
        if (c && c.classList.contains('covered') && tries++ < 40) { setTimeout(go, 50); return; }
        run();
    }

    if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', go);
    else go();
})();
/* ===== Rotating featured rooms (every 2 seconds) ===== */
(function () {
    var INTERVAL = 2000; // change this to speed up or slow down (milliseconds)
    var items = window.FEATURED_ROOMS || [];
    var slots = [].slice.call(document.querySelectorAll('#featuredBoxes .fr-card'));
    var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    if (!slots.length || items.length <= slots.length || reduce) return;

    var page = 0, paused = false;
    slots.forEach(function (s) {
        s.addEventListener('mouseenter', function () { paused = true; });
        s.addEventListener('mouseleave', function () { paused = false; });
    });

    function fill(card, r) {
        card.querySelector('.roomp').textContent = 'Room ' + r.number;
        card.querySelector('.roomp1').textContent = '— Good For ' + r.beds;
        card.querySelector('.roomp2, .roomp22').textContent = 'Double Bed For ' + r.decks;
        card.querySelector('.roomp3').textContent =
            '₱' + Math.round(r.price).toLocaleString('en-US') + ' / month';
    }

    function tick() {
        if (paused || document.hidden || !slots[0].offsetParent) return;
        page++;
        slots.forEach(function (slot, i) {
            setTimeout(function () {
                slot.classList.add('fr-out');
                setTimeout(function () {
                    fill(slot, items[(page * slots.length + i) % items.length]);
                    slot.style.transition = 'none';
                    slot.classList.remove('fr-out');
                    slot.classList.add('fr-pre');
                    void slot.offsetWidth;
                    slot.style.transition = '';
                    slot.classList.remove('fr-pre');
                }, 350);
            }, i * 120);
        });
    }

    setInterval(tick, INTERVAL);
})();