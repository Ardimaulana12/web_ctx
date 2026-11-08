import './bootstrap';
const toggle = document.getElementById('menu-toggle');
const navigation = document.getElementById('navigation');
function setMenu(open) {
    toggle.setAttribute('aria-expanded', String(open));
    toggle.setAttribute('aria-label', open ? toggle.dataset.closeLabel : toggle.dataset.openLabel);
    navigation.classList.toggle('is-open', open);
}
toggle?.addEventListener('click', () => setMenu(toggle.getAttribute('aria-expanded') !== 'true'));
navigation?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setMenu(false)));
document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && toggle?.getAttribute('aria-expanded') === 'true') {
        setMenu(false);
        toggle.focus();
    }
});
document.addEventListener('click', event => {
    if (!event.target.closest('.site-header')) setMenu(false);
});
window.matchMedia('(min-width: 1280px)').addEventListener('change', () => setMenu(false));

const languageDropdown = document.querySelector('.language-dropdown');
document.addEventListener('click', event => {
    if (!event.target.closest('.language-dropdown')) languageDropdown?.removeAttribute('open');
});
document.addEventListener('keydown', event => {
    if (event.key === 'Escape' && languageDropdown?.open) {
        languageDropdown.open = false;
        languageDropdown.querySelector('summary').focus();
    }
});

// Animate the first view without a blocking loader; content remains visible without JS.
const motionPreference = window.matchMedia('(prefers-reduced-motion: reduce)');
let observer;
const revealElements = [...document.querySelectorAll('main > section:not(.hero):not(.page-heading), main > .quote-band')].flatMap(section => {
    if (section.matches('.services-section, .process-section, .leadership')) {
        const cards = [...section.querySelectorAll('article')];
        cards.forEach((card, index) => card.style.setProperty('--reveal-delay', `${(index % 3) * 80}ms`));
        return [...section.querySelectorAll('.section-heading'), ...cards];
    }
    return [section];
});
if (!motionPreference.matches) {
    document.body.classList.add('motion-ready');
    observer = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('is-visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.05 });
    revealElements.forEach(element => {
        element.classList.add('reveal');
        observer.observe(element);
    });
}
motionPreference.addEventListener('change', event => {
    if (event.matches) {
        document.body.classList.remove('motion-ready');
        observer?.disconnect();
        revealElements.forEach(element => element.classList.add('is-visible'));
    }
});

// Count once when the figures first enter the viewport, so the animation is visible.
const counters = [...document.querySelectorAll('[data-count]')];
const runningCounts = new Map();
let counterObserver;
function finishCounts() {
    counterObserver?.disconnect();
    runningCounts.forEach(id => cancelAnimationFrame(id));
    runningCounts.clear();
    counters.forEach(counter => { counter.textContent = counter.dataset.count; });
}
if (!motionPreference.matches && counters.length) {
    counterObserver = new IntersectionObserver(entries => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;
            const counter = entry.target;
            const target = Number(counter.dataset.count);
            const start = performance.now();
            const tick = now => {
                const progress = Math.min((now - start) / 1800, 1);
                counter.textContent = Math.round(target * (1 - (1 - progress) ** 3));
                if (progress < 1) runningCounts.set(counter, requestAnimationFrame(tick));
                else runningCounts.delete(counter);
            };
            runningCounts.set(counter, requestAnimationFrame(tick));
            counterObserver.unobserve(counter);
        });
    }, { threshold: 0.6 });
    counters.forEach(counter => { counter.textContent = '0'; counterObserver.observe(counter); });
}
motionPreference.addEventListener('change', event => { if (event.matches) finishCounts(); });

// Keep native validation without JavaScript; enhance it with translated inline alerts.
const contactForm = document.querySelector('[data-contact-form]');
if (contactForm) {
    contactForm.noValidate = true;
    const fields = [...contactForm.querySelectorAll('input:not([type="hidden"]), textarea')];
    const summary = contactForm.querySelector('[data-error-summary]');
    let submitted = false;
    function validateField(field) {
        const value = field.value.trim();
        let error = '';
        if (field.required && !value) error = field.dataset.required;
        else if (value && field.type === 'email' && field.validity.typeMismatch) error = field.dataset.format;
        else if (value && field.minLength > 0 && value.length < field.minLength) error = field.dataset.min;
        else if (value.length > field.maxLength) error = field.dataset.max;
        field.setAttribute('aria-invalid', String(Boolean(error)));
        const output = document.getElementById(`${field.id}-error`);
        output.textContent = error;
        output.hidden = !error;
        return error;
    }
    function refreshSummary() {
        const errors = fields.map(validateField).filter(Boolean);
        summary.querySelector('ul').replaceChildren(...errors.map(message => {
            const item = document.createElement('li');
            item.textContent = message;
            return item;
        }));
        summary.hidden = errors.length === 0;
        return errors;
    }
    fields.forEach(field => {
        field.addEventListener('blur', () => validateField(field));
        field.addEventListener('input', () => {
            if (submitted) refreshSummary();
            else if (field.getAttribute('aria-invalid') === 'true') validateField(field);
        });
    });
    contactForm.addEventListener('submit', event => {
        submitted = true;
        if (refreshSummary().length) {
            event.preventDefault();
            fields.find(field => field.getAttribute('aria-invalid') === 'true')?.focus();
            return;
        }
        const button = contactForm.querySelector('[type="submit"]');
        button.disabled = true;
        button.textContent = button.dataset.sending;
    });
    if (fields.some(field => field.getAttribute('aria-invalid') === 'true')) summary.focus();
}
