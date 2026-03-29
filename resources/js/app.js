import './bootstrap';
import mask from '@alpinejs/mask';

document.addEventListener('alpine:init', () => {
    window.Alpine.plugin(mask);
});

// Auto-slide patrocinadores using DaisyUI carousel markup.
const setupPatrocinadoresCarousel = (containerId, intervalMs = 2800) => {
    const carousel = document.getElementById(containerId);
    if (!carousel) return;

    const items = Array.from(carousel.querySelectorAll('.carousel-item'));
    if (items.length === 0) return;

    let current = 0;

    const goToNext = () => {
        current = (current + 1) % items.length;
        carousel.scrollTo({
            left: items[current].offsetLeft,
            behavior: 'smooth',
        });
    };

    setInterval(goToNext, intervalMs);
};

const setupScrollReveal = () => {
    const revealItems = Array.from(document.querySelectorAll('[data-reveal]'));
    if (!revealItems.length) return;

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const revealElement = (element) => {
        const animation = element.dataset.reveal || 'fadeInUp';
        const delay = parseInt(element.dataset.revealDelay || '0', 10);

        element.style.willChange = 'opacity, transform';
        element.style.setProperty('--animate-delay', `${delay}ms`);
        element.classList.add('animate__animated', `animate__${animation}`, 'is-visible');

        const clearWillChange = () => {
            element.style.willChange = 'auto';
            element.removeEventListener('animationend', clearWillChange);
        };

        element.addEventListener('animationend', clearWillChange, { once: true });
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

const setupLoteCountdown = () => {
    const countdowns = Array.from(document.querySelectorAll('[data-lote-countdown]'));
    if (!countdowns.length) return;

    const renderCountdown = (container) => {
        const expiresAt = container.dataset.countdownExpiresAt;
        const valueNode = container.querySelector('[data-countdown-value]');
        const unitNode = container.querySelector('[data-countdown-unit]');

        if (!expiresAt || !valueNode || !unitNode) return;

        const expiresAtMs = new Date(expiresAt).getTime();

        if (Number.isNaN(expiresAtMs)) return;

        const remainingSeconds = Math.max(0, Math.floor((expiresAtMs - Date.now()) / 1000));

        let value;
        let unit;

        if (remainingSeconds >= 86400) {
            value = Math.max(1, Math.floor(remainingSeconds / 86400));
            unit = value === 1 ? 'dia' : 'dias';
        } else if (remainingSeconds >= 3600) {
            value = Math.max(1, Math.floor(remainingSeconds / 3600));
            unit = value === 1 ? 'hora' : 'horas';
        } else {
            value = Math.max(1, Math.ceil(remainingSeconds / 60));
            unit = value === 1 ? 'minuto' : 'minutos';
        }

        valueNode.style.setProperty('--value', String(value));
        valueNode.style.setProperty('--digits', String(Math.max(String(value).length, 2)));
        valueNode.setAttribute('aria-label', String(value));
        unitNode.textContent = unit;
    };

    countdowns.forEach(renderCountdown);
    window.setInterval(() => countdowns.forEach(renderCountdown), 1000);
};

document.addEventListener('DOMContentLoaded', () => {
    setupPatrocinadoresCarousel('patrocinadores-carousel', 2800);
    setupPatrocinadoresCarousel('patrocinadores-carousel-mobile', 2600);
    setupScrollReveal();
    setupLoteCountdown();

    const pixModal = document.getElementById('pix-modal');
    const closePixBtn = document.getElementById('close-pix');
    const pixTriggers = document.querySelectorAll('[data-pix-trigger]');
    const copyButtons = document.querySelectorAll('[data-copy-button]');

    const hidePixModal = () => pixModal?.classList.add('hidden');

    const copyText = async (text) => {
        if (navigator.clipboard?.writeText) {
            await navigator.clipboard.writeText(text);
            return true;
        }

        const tempInput = document.createElement('input');
        tempInput.value = text;
        tempInput.setAttribute('readonly', '');
        tempInput.style.position = 'absolute';
        tempInput.style.left = '-9999px';
        document.body.appendChild(tempInput);
        tempInput.select();
        const success = document.execCommand('copy');
        document.body.removeChild(tempInput);
        return success;
    };

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

    copyButtons.forEach((button) => {
        const defaultButtonHtml = button.innerHTML;

        button.addEventListener('click', async () => {
            const text = button.dataset.copyText || '';
            if (!text) return;

            try {
                const copied = await copyText(text);
                button.textContent = copied ? 'Copiado!' : 'Falhou';
            } catch {
                button.textContent = 'Falhou';
            }

            window.setTimeout(() => {
                button.innerHTML = defaultButtonHtml;
            }, 1800);
        });
    });

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
