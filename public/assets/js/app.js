/**
 * Survey System — App JS
 * Minimal, progressive enhancement only.
 */

document.addEventListener('DOMContentLoaded', () => {

    // ── Star rating: highlight on hover ──────────────────────
    document.querySelectorAll('.star-rating').forEach(container => {
        const labels = container.querySelectorAll('label');
        labels.forEach(label => {
            label.addEventListener('mouseenter', () => {
                labels.forEach(l => l.style.color = '');
            });
        });
    });

    // ── Auto-dismiss alerts ───────────────────────────────────
    document.querySelectorAll('.alert').forEach(alert => {
        setTimeout(() => {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(() => alert.remove(), 500);
        }, 5000);
    });

    // ── Form submit: disable button to prevent double submit ──
    document.querySelectorAll('.survey-form').forEach(form => {
        form.addEventListener('submit', () => {
            const btn = form.querySelector('button[type="submit"]');
            if (btn) {
                btn.disabled     = true;
                btn.textContent  = 'Đang gửi...';
            }
        });
    });

});
