import './bootstrap';
import Alpine from 'alpinejs';
import mask from '@alpinejs/mask';

Alpine.plugin(mask);
window.Alpine = Alpine;
Alpine.start();

// Auto-slide patrocinadores: um card por vez (desktop e mobile)
const setupPatrocinadoresCarousel = (containerId, trackId, intervalMs = 2800) => {
    const carousel = document.getElementById(containerId);
    const track = document.getElementById(trackId);
    if (!carousel || !track) return;

    const items = Array.from(track.children);
    if (items.length === 0) return;

    let current = 0;

    const setWidths = () => {
        const width = carousel.clientWidth;
        items.forEach((item) => {
            item.style.width = `${width}px`;
        });
        track.style.transform = `translateX(-${current * width}px)`;
    };

    const goToNext = () => {
        current = (current + 1) % items.length;
        const width = carousel.clientWidth;
        track.style.transform = `translateX(-${current * width}px)`;
    };

    setWidths();
    window.addEventListener('resize', setWidths);
    setInterval(goToNext, intervalMs);
};

const setupScrollReveal = () => {
    const revealItems = Array.from(document.querySelectorAll('[data-reveal]'));
    if (!revealItems.length) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const revealElement = (element) => {
        const animation = element.dataset.reveal || 'fadeInUp';
        const delay = parseInt(element.dataset.revealDelay || '0', 10);

        element.style.setProperty('--animate-delay', `${delay}ms`);
        element.classList.add('animate__animated', `animate__${animation}`, 'is-visible');
    };

    if (prefersReducedMotion || !('IntersectionObserver' in window)) {
        revealItems.forEach(revealElement);
        return;
    }

    const observer = new IntersectionObserver((entries) => {
        entries.forEach((entry) => {
            if (!entry.isIntersecting) return;

            revealElement(entry.target);
            observer.unobserve(entry.target);
        });
    }, {
        threshold: 0.18,
        rootMargin: '0px 0px -10% 0px',
    });

    revealItems.forEach((item) => observer.observe(item));
};

document.addEventListener('DOMContentLoaded', () => {
    setupPatrocinadoresCarousel('patrocinadores-carousel', 'patrocinadores-track', 2800);
    setupPatrocinadoresCarousel('patrocinadores-carousel-mobile', 'patrocinadores-track-mobile', 2600);
    setupScrollReveal();

    const pixModal = document.getElementById('pix-modal');
    const closePixBtn = document.getElementById('close-pix');
    const pixTriggers = document.querySelectorAll('[data-pix-trigger]');

    const hidePixModal = () => pixModal?.classList.add('hidden');

    if (pixModal && pixTriggers.length) {
        pixTriggers.forEach((btn) => {
            btn.addEventListener('click', () => {
                pixModal.classList.remove('hidden');
            });
        });
    }

    if (closePixBtn && pixModal) {
        closePixBtn.addEventListener('click', hidePixModal);
    }

    if (pixModal) {
        pixModal.addEventListener('click', (e) => {
            if (e.target === pixModal) hidePixModal();
        });
    }

    const inscricaoAlert = document.getElementById('inscricao-alert');
    let inscricaoAlertTimeout;
    window.addEventListener('inscricao-alert', (event) => {
        if (!inscricaoAlert) return;
        const message = event.detail?.message || '';
        inscricaoAlert.textContent = message;
        inscricaoAlert.classList.remove('hidden');
        clearTimeout(inscricaoAlertTimeout);
        inscricaoAlertTimeout = setTimeout(() => {
            inscricaoAlert.classList.add('hidden');
        }, 7000);
    });
});
