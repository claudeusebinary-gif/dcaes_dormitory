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
        if (!isMobile() && body.classList.contains('sb-collapsed')) {
            body.classList.remove('sb-collapsed');
            try { localStorage.setItem('sbCollapsed', '0'); } catch (e) { }
            setUserMenu(true);
            try { localStorage.setItem('sbUserOpen', '1'); } catch (e) { }
            return;
        }
        const open = userBtn.getAttribute('aria-expanded') !== 'true';
        setUserMenu(open);
        try { localStorage.setItem('sbUserOpen', open ? '1' : '0'); } catch (e) { }
    });
})();

(function () {
    var nav = document.querySelector('.sb-nav');
    if (!nav) return;

    var KEY = 'sbIndicator';
    var reduce = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    function relTop(el) {
        return el.getBoundingClientRect().top - nav.getBoundingClientRect().top;
    }

   
    window.addEventListener('pagehide', function () {
        var a = nav.querySelector('.sb-link.active');
        if (!a) return;
        try {
            sessionStorage.setItem(KEY, JSON.stringify({
                top: relTop(a), h: a.offsetHeight, t: Date.now()
            }));
        } catch (e) { }
    });

    var active = nav.querySelector('.sb-link.active');
    if (!active) return; 

    // measure BEFORE the highlight exists
    var to = { top: relTop(active), h: active.offsetHeight };

    var from = null;
    try {
        var s = JSON.parse(sessionStorage.getItem(KEY) || 'null');
        sessionStorage.removeItem(KEY);
        if (s && Date.now() - s.t < 8000) from = s;
    } catch (e) { }
    var animate = !!from && !reduce && Math.abs(from.top - to.top) > 1;

    var ind = document.createElement('span');
    ind.className = 'sb-indicator';
    function place(p) {
        ind.style.transform = 'translateY(' + p.top + 'px)';
        ind.style.height = p.h + 'px';
    }

    place(animate ? from : to);              
    nav.insertBefore(ind, nav.firstChild);
    document.body.classList.add('sb-has-ind');
    void ind.offsetWidth;                  
    if (animate) place(to);                  
    var last = to;
    function snap() {
        var a = nav.querySelector('.sb-link.active');
        if (!a) return;
        var p = { top: relTop(a), h: a.offsetHeight };
        if (Math.abs(p.top - last.top) < 1 && Math.abs(p.h - last.h) < 1) return;
        last = p;
        ind.classList.add('snapping');
        place(p);
        void ind.offsetWidth;
        ind.classList.remove('snapping');
    }
    window.addEventListener('load', snap);
    window.addEventListener('resize', snap);
    var tg = document.getElementById('sbToggle');
    if (tg) tg.addEventListener('click', snap);
})();