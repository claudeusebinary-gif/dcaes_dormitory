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