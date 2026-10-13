(function () {
    const body = document.body;
    const isMobile = () => window.matchMedia('(max-width: 768px)').matches;

    try {
        if (!isMobile() && localStorage.getItem('sbCollapsed') === '1') body.classList.add('sb-collapsed');
    } catch (e) { }

    document.getElementById('sbToggle').addEventListener('click', () => {
        if (isMobile()) {
            body.classList.toggle('sb-open');
        } else {
            const collapsed = body.classList.toggle('sb-collapsed');
            try { localStorage.setItem('sbCollapsed', collapsed ? '1' : '0'); } catch (e) { }
        }
    });

    document.getElementById('sbBackdrop').addEventListener('click', () => body.classList.remove('sb-open'));

    const userBtn = document.getElementById('sbUser');
    const userMenu = document.getElementById('sbUserMenu');
    function setUserMenu(open) {
        userBtn.setAttribute('aria-expanded', String(open));
        userMenu.hidden = !open;
        userBtn.classList.toggle('open', open);
    }

    try {
        if (localStorage.getItem('sbUserOpen') === '1') setUserMenu(true);
    } catch (e) { }

    userBtn.addEventListener('click', () => {
        const open = userBtn.getAttribute('aria-expanded') !== 'true';
        setUserMenu(open);
        try { localStorage.setItem('sbUserOpen', open ? '1' : '0'); } catch (e) { }
    });
})();
