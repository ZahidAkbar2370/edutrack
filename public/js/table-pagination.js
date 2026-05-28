/**
 * Simple client-side table pagination (no reload).
 * Usage: add `data-js-paginate` on a <table> (or on a wrapper element and point to a table).
 *
 * Optional data attributes:
 * - data-page-sizes="10,20,50,100,all" (default: 10,20,50,100,all)
 * - data-default-size="10" (default: 10)
 */
(function () {
    function el(tag, attrs) {
        var node = document.createElement(tag);
        if (attrs) {
            Object.keys(attrs).forEach(function (k) {
                if (k === 'text') node.textContent = attrs[k];
                else if (k === 'html') node.innerHTML = attrs[k];
                else node.setAttribute(k, attrs[k]);
            });
        }
        return node;
    }

    function parseSizes(str) {
        var raw = (str || '10,20,50,100,all').split(',').map(function (s) { return s.trim(); }).filter(Boolean);
        var out = [];
        raw.forEach(function (v) {
            if (v.toLowerCase() === 'all') out.push('all');
            else {
                var n = parseInt(v, 10);
                if (!isNaN(n) && n > 0) out.push(n);
            }
        });
        if (!out.length) out = [10, 20, 50, 100, 'all'];
        return out;
    }

    function clamp(n, min, max) {
        return Math.max(min, Math.min(max, n));
    }

    function initTable(table) {
        if (!table || table.__jsPaginateInit) return;
        table.__jsPaginateInit = true;

        var tbody = table.tBodies && table.tBodies[0];
        if (!tbody) return;

        var rows = Array.prototype.slice.call(tbody.rows);
        if (rows.length <= 10) return; // nothing to paginate

        var sizes = parseSizes(table.getAttribute('data-page-sizes'));
        var defaultSizeAttr = table.getAttribute('data-default-size');
        var defaultSize = defaultSizeAttr ? (defaultSizeAttr.toLowerCase() === 'all' ? 'all' : parseInt(defaultSizeAttr, 10)) : 10;
        if (defaultSize !== 'all' && (isNaN(defaultSize) || defaultSize <= 0)) defaultSize = 10;

        var state = {
            page: 1,
            perPage: sizes.indexOf(defaultSize) !== -1 ? defaultSize : (sizes[0] || 10)
        };

        var wrap = el('div', { class: 'd-flex flex-wrap justify-content-between align-items-center gap-2 mt-2 js-table-pager' });
        var left = el('div', { class: 'd-flex align-items-center gap-2 flex-wrap' });
        var right = el('div', { class: 'd-flex align-items-center gap-2 flex-wrap' });

        var label = el('span', { class: 'small text-muted', text: 'Rows:' });
        var select = el('select', { class: 'form-select form-select-sm', style: 'width:auto;min-width:5rem;' });
        sizes.forEach(function (s) {
            select.appendChild(el('option', { value: String(s), text: s === 'all' ? 'All' : String(s) }));
        });
        select.value = String(state.perPage);

        var info = el('span', { class: 'small text-muted' });

        var prev = el('button', { type: 'button', class: 'btn btn-outline-secondary btn-sm', text: 'Prev' });
        var next = el('button', { type: 'button', class: 'btn btn-outline-secondary btn-sm', text: 'Next' });
        var pageText = el('span', { class: 'small text-muted', text: '' });

        left.appendChild(label);
        left.appendChild(select);
        left.appendChild(info);
        right.appendChild(prev);
        right.appendChild(pageText);
        right.appendChild(next);
        wrap.appendChild(left);
        wrap.appendChild(right);

        table.parentNode && table.parentNode.appendChild(wrap);

        function render() {
            rows = Array.prototype.slice.call(tbody.rows); // in case rows change
            var total = rows.length;
            var per = state.perPage === 'all' ? total : state.perPage;
            var totalPages = per > 0 ? Math.max(1, Math.ceil(total / per)) : 1;
            state.page = clamp(state.page, 1, totalPages);

            var start = state.perPage === 'all' ? 0 : (state.page - 1) * per;
            var end = state.perPage === 'all' ? total : start + per;

            rows.forEach(function (r, idx) {
                r.style.display = (idx >= start && idx < end) ? '' : 'none';
            });

            var shownFrom = total === 0 ? 0 : (start + 1);
            var shownTo = total === 0 ? 0 : Math.min(end, total);
            info.textContent = total ? (shownFrom + '–' + shownTo + ' of ' + total) : '0';

            pageText.textContent = 'Page ' + state.page + ' / ' + totalPages;
            prev.disabled = state.page <= 1 || state.perPage === 'all';
            next.disabled = state.page >= totalPages || state.perPage === 'all';
        }

        select.addEventListener('change', function () {
            var v = select.value;
            state.perPage = v === 'all' ? 'all' : parseInt(v, 10);
            state.page = 1;
            render();
        });
        prev.addEventListener('click', function () {
            state.page -= 1;
            render();
        });
        next.addEventListener('click', function () {
            state.page += 1;
            render();
        });

        render();
    }

    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('table[data-js-paginate]').forEach(initTable);
    });
})();

