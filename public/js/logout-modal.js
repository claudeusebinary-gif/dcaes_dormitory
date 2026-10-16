(function () {
    const modal = document.getElementById('logoutModal');
    const link = document.getElementById('logoutLink');
    const form = document.getElementById('logoutForm');
    const confirmBtn = document.getElementById('loConfirm');
    if (!modal || !link || !form) return;

    const cancelBtn = modal.querySelector('.lo-cancel');
    let lastFocus = null;

    function open() {
        lastFocus = document.activeElement;
        modal.hidden = false;
        document.body.classList.add('lo-open');
        requestAnimationFrame(() => modal.classList.add('show'));
        cancelBtn.focus();
    }

    function close() {
        modal.classList.remove('show');
        document.body.classList.remove('lo-open');
        setTimeout(() => { modal.hidden = true; }, 180);
        if (lastFocus) lastFocus.focus();
    }

    link.addEventListener('click', (e) => {
        e.preventDefault();
        open();
    });


    modal.addEventListener('click', (e) => {
        if (e.target === modal || e.target.closest('[data-lo-close]')) close();
    });

    confirmBtn.addEventListener('click', () => {
        confirmBtn.disabled = true;
        form.submit();              
    });

    document.addEventListener('keydown', (e) => {
        if (modal.hidden) return;

        if (e.key === 'Escape') {
            close();
            return;
        }

       
        if (e.key === 'Tab') {
            const items = modal.querySelectorAll('button:not([disabled])');
            const first = items[0];
            const last = items[items.length - 1];
            if (e.shiftKey && document.activeElement === first) {
                e.preventDefault();
                last.focus();
            } else if (!e.shiftKey && document.activeElement === last) {
                e.preventDefault();
                first.focus();
            }
        }
    });
})();
