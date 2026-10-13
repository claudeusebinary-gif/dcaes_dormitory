<div class="notif" id="notif"
    data-list-url="{{ route('tenant.notifications.index') }}"
    data-read-all-url="{{ route('tenant.notifications.read-all') }}"
    data-read-url="{{ route('tenant.notifications.read', ['id' => '__ID__']) }}">
    <button class="notif-btn" id="notifBtn" aria-label="Notifications" aria-expanded="false">
        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="#672C2C" stroke-width="2"
            stroke-linecap="round" stroke-linejoin="round">
            <path d="M18 8a6 6 0 0 0-12 0c0 7-3 9-3 9h18s-3-2-3-9" />
            <path d="M13.7 21a2 2 0 0 1-3.4 0" />
        </svg>
        <span class="notif-dot" id="notifDot" hidden></span>
    </button>
    <div class="notif-panel" id="notifPanel" hidden>
        <div class="notif-head">
            <span>Notifications</span>
            <button type="button" id="notifReadAll">Mark all as read</button>
        </div>
        <div class="notif-list" id="notifList">
            <p class="notif-empty">No notifications yet.</p>
        </div>
    </div>
</div>
