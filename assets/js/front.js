'use strict';

// Make the whole switcher work without double toggling
document.addEventListener('DOMContentLoaded', () => {
    const wrapper  = document.querySelector('.plans-wrapper');
    const toggle   = document.getElementById('plan-toggle');
    const switcher = document.querySelector('.plans-switcher');
    if (!wrapper || !toggle || !switcher) return;

    wrapper.classList.toggle('has-annual', toggle.checked);

    toggle.addEventListener('change', () => {
        wrapper.classList.toggle('has-annual', toggle.checked);
    });

    switcher.addEventListener('click', (e) => {
        if (e.target.closest('label.switch')) return;
        toggle.click();
    });
});