(function () {
    const root = document.getElementById('notif');
    if (!root) return;

    const LIST_URL = root.dataset.listUrl;
    const READ_ALL_URL = root.dataset.readAllUrl;
    const READ_URL = root.dataset.readUrl; // contains __ID__ placeholder
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
                    <div class="notif-item ${!n.is_read ? 'unread' : ''}" data-id="${n.id}">
                        <b>${esc(n.title)}</b>
                        <p>${esc(n.message)}</p>
                        <small>${ago(n.created_at)}</small>
                    </div>`).join('')
                : '<p class="notif-empty">No notifications yet.</p>';
        } catch (e) { console.error('Notifications failed', e); }
    }

    function post(url) {
        return fetch(url, {
            method: 'POST',
            headers: { 'X-CSRF-TOKEN': csrf, 'Accept': 'application/json' },
            credentials: 'same-origin'
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
        await post(READ_URL.replace('__ID__', item.dataset.id));
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
    setInterval(load, 30000);
})();
