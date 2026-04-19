/**
 * Admin Main Script
 * Global initializations for Premium SaaS theme
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize Choices.js
    if (typeof Choices !== 'undefined') {
        window.initChoices();
    } else {
        console.warn('Choices.js library not found. Falling back to default selects.');
    }
});

/**
 * Helper to initialize or re-initialize Choices on any container
 * @param {HTMLElement} container 
 */
window.initChoices = function(container = document) {
    if (typeof Choices === 'undefined') return;

    const selects = container.querySelectorAll('.form-select:not(.no-choices)');
    selects.forEach(select => {
        // Only init if not already a Choices instance AND visible or part of a template that will be used
        if (!select.classList.contains('choices__input')) {
             const isSmall = select.options.length <= 6;
             try {
                new Choices(select, {
                    searchEnabled: !isSmall,
                    itemSelectText: '',
                    shouldSort: false,
                    allowHTML: true,
                    placeholder: true,
                    placeholderValue: select.querySelector('option[value=""]') ? select.querySelector('option[value=""]').textContent : 'Chọn...',
                    position: 'bottom', // Always open downwards to avoid clipping above the card
                });
             } catch (e) {
                console.error('Failed to init Choices for:', select, e);
             }
        }
    });
};
