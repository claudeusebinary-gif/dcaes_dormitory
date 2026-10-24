@php
    $user = auth()->user();
   $isAdmin  = $user->isAdmin();
$active   = str_replace(['tenant.', 'admin.'], '', (string) request()->route()?->getName());
$roleLine = $isAdmin ? 'Owner / Admin' : ($user->room_number ? 'Tenant · Room ' . $user->room_number : 'Tenant');

    $icons = [
        'menu' => '<path d="M8 6h13M8 12h13M8 18h13M3 6h.01M3 12h.01M3 18h.01"/>',
        'home' => '<path d="M3 10.5 12 3l9 7.5V20a1 1 0 0 1-1 1h-5v-6H9v6H4a1 1 0 0 1-1-1z"/>',
        'profile' => '<circle cx="12" cy="8" r="4"/><path d="M4 21a8 8 0 0 1 16 0"/>',
        'dorm' => '<path d="M22 12h-6l-2 3h-4l-2-3H2"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/>',
        'rooms' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M9 3v18"/>',
        'payments' => '<rect x="3" y="3" width="18" height="18" rx="2"/><path d="M3 9h18"/><path d="M9 21V9"/>',
        'announcement' => '<path d="M12 3H5a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"/><path d="M18.4 2.6a2.1 2.1 0 0 1 3 3L12 15l-4 1 1-4z"/>',
        'location' => '<path d="M20 10c0 6-8 12-8 12S4 16 4 10a8 8 0 0 1 16 0z"/><circle cx="12" cy="10" r="3"/>',
        'settings' => '<path d="M12.22 2h-.44a2 2 0 0 0-2 2v.18a2 2 0 0 1-1 1.73l-.43.25a2 2 0 0 1-2 0l-.15-.08a2 2 0 0 0-2.73.73l-.22.38a2 2 0 0 0 .73 2.73l.15.1a2 2 0 0 1 1 1.72v.51a2 2 0 0 1-1 1.74l-.15.09a2 2 0 0 0-.73 2.73l.22.38a2 2 0 0 0 2.73.73l.15-.08a2 2 0 0 1 2 0l.43.25a2 2 0 0 1 1 1.73V20a2 2 0 0 0 2 2h.44a2 2 0 0 0 2-2v-.18a2 2 0 0 1 1-1.73l.43-.25a2 2 0 0 1 2 0l.15.08a2 2 0 0 0 2.73-.73l.22-.39a2 2 0 0 0-.73-2.73l-.15-.08a2 2 0 0 1-1-1.74v-.5a2 2 0 0 1 1-1.74l.15-.09a2 2 0 0 0 .73-2.73l-.22-.38a2 2 0 0 0-2.73-.73l-.15.08a2 2 0 0 1-2 0l-.43-.25a2 2 0 0 1-1-1.73V4a2 2 0 0 0-2-2z"/><circle cx="12" cy="12" r="3"/>',
        'logout' => '<path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><path d="m16 17 5-5-5-5"/><path d="M21 12H9"/>',
        'marketplace' => '<path d="m2 7 4.41-4.41A2 2 0 0 1 7.83 2h8.34a2 2 0 0 1 1.42.59L22 7"/><path d="M4 12v8a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2v-8"/><path d="M15 22v-4a2 2 0 0 0-2-2h-2a2 2 0 0 0-2 2v4"/><path d="M2 7h20"/><path d="M22 7v3a2 2 0 0 1-2 2 2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 16 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 12 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 8 12a2.7 2.7 0 0 1-1.59-.63.7.7 0 0 0-.82 0A2.7 2.7 0 0 1 4 12a2 2 0 0 1-2-2V7"/>',
        'calendar' => '<path d="M8 2v4"/><path d="M16 2v4"/><rect width="18" height="18" x="3" y="4" rx="2"/><path d="M3 10h18"/>',
        'chevron' => '<path d="m6 9 6 6 6-6"/>',
        'dashboard'  => '<rect width="7" height="7" x="3" y="3" rx="1"/><rect width="7" height="7" x="14" y="3" rx="1"/><rect width="7" height="7" x="14" y="14" rx="1"/><rect width="7" height="7" x="3" y="14" rx="1"/>',
'tenants'    => '<path d="M16 21v-2a4 4 0 0 0-4-4H6a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M22 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/>',
'complaints' => '<path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3"/><path d="M12 9v4"/><path d="M12 17h.01"/>',
'approvals'  => '<path d="M20 13c0 5-3.5 7.5-7.66 8.95a1 1 0 0 1-.67-.01C7.5 20.5 4 18 4 13V6a1 1 0 0 1 1-1c2 0 4.5-1.2 6.24-2.72a1.17 1.17 0 0 1 1.52 0C14.51 3.81 17 5 19 5a1 1 0 0 1 1 1z"/><path d="m9 12 2 2 4-4"/>',
    ];

    $icon = fn (string $key) => '<svg class="sb-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $icons[$key] . '</svg>';

    $menu = [
        ['key' => 'home',         'label' => 'Home',         'route' => 'tenant.home'],
        ['key' => 'profile',      'label' => 'Profile',      'route' => 'tenant.profile'],
        ['key' => 'dorm',         'label' => 'Dorm',         'route' => 'tenant.dorm'],
        ['key' => 'rooms',        'label' => 'Rooms',        'route' => 'tenant.rooms'],
        ['key' => 'payments',     'label' => 'Payments',     'route' => 'tenant.payments'],
        ['key' => 'announcement', 'label' => 'Announcement', 'route' => 'tenant.announcement'],
        ['key' => 'location',     'label' => 'Location',     'route' => 'tenant.location'],
    ];
    $preferences = [
        ['key' => 'settings', 'label' => 'Settings', 'route' => 'tenant.settings'],
    ];
    $userMenu = [
        ['key' => 'marketplace', 'label' => 'Tenant Marketplace', 'route' => 'tenant.marketplace'],
        ['key' => 'calendar',    'label' => 'Event Calendar',     'route' => 'tenant.calendar'],
    ];
    if ($isAdmin) {
    $menu = [
        ['key' => 'dashboard',    'label' => 'Dashboard',    'route' => 'admin.dashboard'],
        ['key' => 'tenants',      'label' => 'Tenants',      'route' => 'admin.tenants'],
        ['key' => 'announcement', 'label' => 'Announcement', 'route' => 'admin.announcement'],
        ['key' => 'rooms',        'label' => 'Rooms',        'route' => 'admin.rooms'],
        ['key' => 'payments',     'label' => 'Payments',     'route' => 'admin.payments'],
        ['key' => 'complaints',   'label' => 'Complaints',   'route' => 'admin.complaints'],
        ['key' => 'approvals',    'label' => 'Approvals',    'route' => 'admin.approvals'],
    ];
    $preferences = [
        ['key' => 'settings', 'label' => 'Settings', 'route' => 'admin.settings'],
    ];
    $userMenu = [];
}
    $userMenuOpen = in_array($active, array_column($userMenu, 'key'), true);
@endphp

<button type="button" class="sb-toggle" id="sbToggle" aria-label="Toggle sidebar">
    {!! $icon('menu') !!}
</button>

<aside class="sidebar" id="sidebar" aria-label="Sidebar">

    <div class="sb-brand">
        <span class="sb-title">D'aces Dormitory</span>
        <svg class="sb-logo" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true">
            <path d="M12 2.5 3 10v11h6.5v-6h5v6H21V10z" />
        </svg>
    </div>

    <nav class="sb-nav">
        <p class="sb-section">Menu</p>
        <ul>
            @foreach ($menu as $it)
                <li>
                    <a href="{{ Route::has($it['route']) ? route($it['route']) : '#' }}" class="sb-link{{ $it['key'] === $active ? ' active' : '' }}"
                        @if ($it['key'] === $active) aria-current="page" @endif title="{{ $it['label'] }}">
                        {!! $icon($it['key']) !!}
                        <span class="sb-label">{{ $it['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>

        <p class="sb-section">Preferences</p>
        <ul>
            @foreach ($preferences as $it)
                <li>
                    <a href="{{ Route::has($it['route']) ? route($it['route']) : '#' }}" class="sb-link{{ $it['key'] === $active ? ' active' : '' }}"
                        @if ($it['key'] === $active) aria-current="page" @endif title="{{ $it['label'] }}">
                        {!! $icon($it['key']) !!}
                        <span class="sb-label">{{ $it['label'] }}</span>
                    </a>
                </li>
            @endforeach
        </ul>
    </nav>

    <div class="sb-footer">
    <button type="button" class="sb-user{{ $userMenuOpen ? ' open' : '' }}" id="sbUser"
    aria-expanded="{{ $userMenuOpen ? 'true' : 'false' }}" {{ $isAdmin ? 'disabled' : '' }}>
            <span class="sb-avatar">{{ $user->initial }}</span>
            <span class="sb-userinfo">
                <span class="sb-name">{{ $user->display_name }}</span>
                <span class="sb-role">{{ $roleLine }}</span>
            </span>
            @unless($isAdmin)
    <span class="sb-chevron">{!! $icon('chevron') !!}</span>
@endunless
        </button>
        <div class="sb-usermenu" id="sbUserMenu" {{ $userMenuOpen ? '' : 'hidden' }}>
            @foreach ($userMenu as $it)
                <a href="{{ Route::has($it['route']) ? route($it['route']) : '#' }}" class="sb-subitem{{ $it['key'] === $active ? ' active' : '' }}">
                    {!! $icon($it['key']) !!}
                    <span>{{ $it['label'] }}</span>
                </a>
            @endforeach
        </div>

        {{-- Logout must be a POST in Laravel; the link submits the hidden form. --}}
        <a href="#" id="logoutLink" class="sb-link sb-logout" title="Logout">
            {!! $icon('logout') !!}
            <span class="sb-label">Logout</span>
        </a>
        <form id="logoutForm" action="{{ route('logout') }}" method="POST" hidden>@csrf</form>
    </div>
</aside>
<div class="sb-backdrop" id="sbBackdrop"></div>
