import './bootstrap';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();

window.addEventListener('load', () => {
    document.querySelectorAll('[data-page-skeleton]').forEach((skeleton) => {
        window.setTimeout(() => skeleton.classList.add('page-skeleton-hidden'), 300);
    });
}, { once: true });
