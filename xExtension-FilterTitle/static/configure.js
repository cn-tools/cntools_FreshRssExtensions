(() => {
    function initRules() {
        const rows = document.getElementById('filter-title-rules');
        if (!rows || rows.dataset.initialised) return;
        rows.dataset.initialised = '1';
        const form = rows.closest('form');
        const template = document.getElementById('filter-title-rule-template');
        const add = document.getElementById('filter-title-add-rule');
        const originalRows = Array.from(rows.children, row => row.cloneNode(true));

        function updateRows() {
            Array.from(rows.children).forEach((row, index) => {
                row.querySelectorAll('[data-rule-control]').forEach(control => {
                    const field = control.dataset.ruleControl;
                    control.name = `rules[${index}][${field}]`;
                    control.id = `rule-${index}-${field}`;
                });
                row.querySelectorAll('[data-rule-label]').forEach(label => {
                    label.htmlFor = `rule-${index}-${label.dataset.ruleLabel}`;
                });
                row.querySelector('[data-remove-rule]').value = `remove_${index}`;
                row.querySelector('[data-rule-control="case_sensitive"]').disabled =
                    row.querySelector('[data-rule-control="operator"]').value === 'regex';
            });
        }

        add.addEventListener('click', event => {
            event.preventDefault();
            const row = template.content.firstElementChild.cloneNode(true);
            rows.append(row);
            updateRows();
            row.querySelector('select').focus();
        });
        rows.addEventListener('click', event => {
            const remove = event.target.closest('[data-remove-rule]');
            if (!remove) return;
            event.preventDefault();
            remove.closest('.filter-title-rule').remove();
            updateRows();
            add.focus();
        });
        rows.addEventListener('change', updateRows);
        form.addEventListener('submit', updateRows);
        form.addEventListener('reset', () => {
            // Run after the browser resets the original controls, then restore removed rows too.
            setTimeout(() => {
                rows.replaceChildren(...originalRows.map(row => row.cloneNode(true)));
                updateRows();
            }, 0);
        });
        updateRows();
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initRules);
    } else {
        initRules();
    }
    document.addEventListener('freshrss:slider-load', initRules, true);
})();
