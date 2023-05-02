// Styles
import './styles/app.scss';

// Filters
document.addEventListener('DOMContentLoaded', () => {
    const filters = document.querySelectorAll('[data-filter]');

    filters.forEach(filter => {
        filter.addEventListener('focusout', function (event) {
            if (filter.contains(event.relatedTarget) || !document.hasFocus()) {
                return;
            }

            const toggle = filter.querySelector('[data-toggle]');

            toggle.hidden = true;
        });
    });

    filters.forEach(filter => {
        const toggler = filter.querySelector('[data-toggler]');
        const toggle = filter.querySelector('[data-toggle]');

        toggler.addEventListener('click', () => {
            toggle.hidden = !toggle.hidden;
        });
    });
});
