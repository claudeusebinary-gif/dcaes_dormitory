<div class="cb" id="chatbox" hidden>
    <div class="cb-head" id="cbHead">
        <div class="cb-avatar" id="cbAvatar">?</div>
        <div class="cb-who">
            <div class="cb-name" id="cbName"></div>
            <div class="cb-room" id="cbRoom"></div>
        </div>
        <button type="button" class="cb-btn" id="cbMin" aria-label="Minimize">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M5 12h14"/></svg>
        </button>
        <button type="button" class="cb-btn" id="cbClose" aria-label="Close chat">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round"><path d="M6 6l12 12M18 6L6 18"/></svg>
        </button>
    </div>

    <div class="cb-body">
        <div class="cb-item">
            <div class="cb-thumb" id="cbThumb">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"><path d="M21 19V5a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2zM8.5 13.5l2.5 3.01L14.5 12l4.5 6H5l3.5-4.5z"/></svg>
            </div>
            <div>
                <div class="cb-item-title" id="cbItemTitle"></div>
                <div class="cb-item-price" id="cbItemPrice"></div>
            </div>
        </div>

        <div class="cb-msgs" id="cbMsgs"></div>
        <p class="cb-err" id="cbErr" hidden></p>

        <div class="cb-form">
            <input type="text" class="cb-input" id="cbInput" maxlength="500" placeholder="Write a message..." autocomplete="off">
            <button type="button" class="cb-send" id="cbSend" aria-label="Send" disabled>
                <svg viewBox="0 0 24 24" fill="currentColor"><path d="M3.4 20.4 21 12 3.4 3.6 3.4 10l12 2-12 2z"/></svg>
            </button>
        </div>
    </div>
</div>

<script>
(function () {
    var URLS = {
        inbox:  @json(route('tenant.marketplace.inbox')),
        thread: @json(route('tenant.marketplace.chat')),
        send:   @json(route('tenant.marketplace.chat.send'))
    };
    var CSRF = document.querySelector('meta[name="csrf-token"]').content;
    var $ = function (id) { return document.getElementById(id); };

    var box = $('chatbox'), msgs = $('cbMsgs'), input = $('cbInput'), sendBtn = $('cbSend'), errEl = $('cbErr');
    var mRoot = $('msgRoot'), mBtn = $('msgBtn'), mDot = $('msgDot'), mPanel = $('msgPanel'), mList = $('msgList');

    var cur = null, lastId = 0, count = 0, sending = false, threads = [];

    function esc(s) { return String(s == null ? '' : s).replace(/[&<>"']/g, function (c) {
        return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#39;' }[c]; }); }

    function ago(iso) {
        var s = Math.floor((Date.now() - new Date(iso)) / 1000);
        if (s < 60) return 'just now';
        if (s < 3600) return Math.floor(s / 60) + ' mins ago';
        if (s < 86400) return Math.floor(s / 3600) + ' hrs ago';
        return Math.floor(s / 86400) + ' days ago';
    }

    function dayLabel(iso) {
        var d = new Date(iso), t = new Date(), y = new Date();
        y.setDate(t.getDate() - 1);
        if (d.toDateString() === t.toDateString()) return 'Today';
        if (d.toDateString() === y.toDateString()) return 'Yesterday';
        return d.toLocaleDateString([], { month: 'short', day: 'numeric', year: 'numeric' });
    }

    function say(t) { errEl.hidden = !t; errEl.textContent = t || ''; }

    function remember() {
        try {
            if (cur) sessionStorage.setItem('daceChat', JSON.stringify({
                item_id: cur.item_id, with_id: cur.with_id, min: box.classList.contains('is-min')
            }));
            else sessionStorage.removeItem('daceChat');
        } catch (e) {}
    }

   
    function setThumb(url) {
        var t = $('cbThumb');
        t.style.backgroundImage = url ? 'url("' + url + '")' : '';
        t.classList.toggle('has-photo', !!url);
    }
    function fill(d) {
        if (d.with_name != null)  { $('cbName').textContent = d.with_name; $('cbAvatar').textContent = (d.with_name || '').charAt(0).toUpperCase(); }
        if (d.with_room != null)  $('cbRoom').textContent = d.with_room;
        if (d.item_title != null) $('cbItemTitle').textContent = d.item_title;
        if (d.item_price != null) $('cbItemPrice').textContent = d.item_price;
        if (d.photo !== undefined) setThumb(d.photo);
    }

  
    function render(list, force) {
        var near = msgs.scrollHeight - msgs.scrollTop - msgs.clientHeight < 60;
        msgs.innerHTML = '';
        if (!list.length) { msgs.innerHTML = '<p class="cb-empty">No messages yet. Say hi!</p>'; return; }

        var day = '';
        list.forEach(function (m) {
            var label = dayLabel(m.at);
            if (label !== day) {
                day = label;
                var t = document.createElement('div');
                t.className = 'cb-time';
                t.textContent = label;
                msgs.appendChild(t);
            }
            var d = document.createElement('div');
            d.className = 'msg ' + (m.mine ? 'me' : 'them');
            d.textContent = m.body;
            d.title = new Date(m.at).toLocaleString();
            msgs.appendChild(d);
        });
        if (force || near) msgs.scrollTop = msgs.scrollHeight;
    }

    async function loadThread(force) {
        if (!cur) return;
        var mine = cur;
        var peek = box.classList.contains('is-min') ? '&peek=1' : '';
        try {
            var res = await fetch(URLS.thread + '?item_id=' + mine.item_id + '&with=' + mine.with_id + peek, {
                headers: { 'Accept': 'application/json' }, credentials: 'same-origin', cache: 'no-store'
            });
            if (res.status === 403 || res.status === 404 || res.status === 422) { if (cur === mine) close(); return; }
            if (!res.ok) return;
            var data = await res.json();
            if (cur !== mine) return;

            fill({ with_name: data.other.name, with_room: data.other.room,
                   item_title: data.item.title, item_price: data.item.price, photo: data.item.photo || '' });

            var list = data.messages, last = list.length ? list[list.length - 1].id : 0;
            if (force || last !== lastId || list.length !== count) {
                render(list, force);
                refreshInbox();
            }
            lastId = last; count = list.length;
        } catch (e) {}
    }

    function open(d) {
        cur = { item_id: Number(d.item_id), with_id: Number(d.with_id) };
        lastId = 0; count = 0;
        fill({ with_name: '', with_room: '', item_title: '', item_price: '', photo: '' });
        fill(d);
        say('');
        input.value = ''; sendBtn.disabled = true;
        msgs.innerHTML = '<p class="cb-empty">Loading...</p>';
        box.hidden = false;
        box.classList.toggle('is-min', !!d.min);
        remember();
        loadThread(true);
        if (!d.quiet && !d.min) input.focus();
    }

    function close() {
        box.hidden = true;
        cur = null;
        msgs.innerHTML = '';
        remember();
        refreshInbox();
    }

    async function send() {
        var text = input.value.trim();
        if (!text || !cur || sending) return;
        sending = true; sendBtn.disabled = true; say('');
        try {
            var res = await fetch(URLS.send, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': CSRF },
                credentials: 'same-origin',
                body: JSON.stringify({ item_id: cur.item_id, recipient_id: cur.with_id, body: text })
            });
            var data = await res.json().catch(function () { return {}; });
            if (res.status === 419) throw new Error('Session expired. Refresh the page.');
            if (res.status === 429) throw new Error('You are sending too fast. Please wait a moment.');
            if (res.status === 422 && data.errors) throw new Error(Object.values(data.errors)[0][0]);
            if (!res.ok) throw new Error(data.message || 'Could not send your message.');
            input.value = '';
            await loadThread(true);
        } catch (e) {
            say(e instanceof TypeError ? 'No connection. Check your internet.' : e.message);
        } finally {
            sending = false;
            sendBtn.disabled = !input.value.trim();
        }
    }

    input.addEventListener('input', function () { sendBtn.disabled = !input.value.trim(); });
    input.addEventListener('keydown', function (e) { if (e.key === 'Enter') send(); });
    sendBtn.addEventListener('click', send);

    $('cbHead').addEventListener('click', function (e) {
        if (e.target.closest('#cbClose')) return;
        box.classList.toggle('is-min');
        remember();
        if (!box.classList.contains('is-min')) { loadThread(true); input.focus(); }
    });
    $('cbClose').addEventListener('click', close);

   
    function setBadge(n) {
        mDot.hidden = n < 1;
        mDot.textContent = n > 9 ? '9+' : n;
        mBtn.classList.toggle('has-unread', n > 0);
    }

    function renderInbox() {
        if (!threads.length) {
            mList.innerHTML = '<p class="notif-empty">No messages yet. Message a seller from the Marketplace.</p>';
            return;
        }
        mList.innerHTML = threads.map(function (t, i) {
            return '<div class="notif-item' + (t.unread ? ' unread' : '') + '" data-i="' + i + '">'
                + '<b>' + esc(t.with_name) + ' · ' + esc(t.item_title) + '</b>'
                + '<p>' + (t.last_mine ? 'You: ' : '') + esc(t.last) + '</p>'
                + '<small>' + ago(t.at) + (t.unread ? ' · ' + t.unread + ' new' : '') + '</small></div>';
        }).join('');
    }

    async function refreshInbox() {
        try {
            var res = await fetch(URLS.inbox, { headers: { 'Accept': 'application/json' }, credentials: 'same-origin', cache: 'no-store' });
            if (!res.ok) return;
            var data = await res.json();
            threads = data.threads;
            setBadge(data.unread);
            if (!mPanel.hidden) renderInbox();
        } catch (e) {}
    }

    mBtn.addEventListener('click', function () {
        mPanel.hidden = !mPanel.hidden;
        mBtn.setAttribute('aria-expanded', String(!mPanel.hidden));
        if (!mPanel.hidden) { renderInbox(); refreshInbox(); }
    });
    mList.addEventListener('click', function (e) {
        var row = e.target.closest('.notif-item');
        if (!row) return;
        var t = threads[Number(row.dataset.i)];
        if (!t) return;
        mPanel.hidden = true;
        open({ item_id: t.item_id, with_id: t.with_id, with_name: t.with_name, with_room: t.with_room,
               item_title: t.item_title, item_price: t.item_price, photo: t.photo || '' });
    });
  
    document.addEventListener('click', function (e) { if (!mRoot.contains(e.target)) mPanel.hidden = true; }, true);

   
    setInterval(function () {
        if (cur && !box.hidden && !box.classList.contains('is-min') && !document.hidden) loadThread(false);
    }, 4000);
    setInterval(refreshInbox, 15000);

    window.DaceChat = { open: open, refresh: refreshInbox };

  
    try {
        var s = JSON.parse(sessionStorage.getItem('daceChat') || 'null');
        if (s && s.item_id && s.with_id) open({ item_id: s.item_id, with_id: s.with_id, min: s.min, quiet: true });
    } catch (e) {}

    refreshInbox();
})();
</script>