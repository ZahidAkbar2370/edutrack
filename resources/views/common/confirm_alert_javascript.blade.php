<script>
    document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('confirmActionModal');
    if (!modalEl || typeof bootstrap === 'undefined') return;

    var modal = new bootstrap.Modal(modalEl);
    var titleEl = document.getElementById('confirmActionModalLabel');
    var messageEl = document.getElementById('confirmActionModalMessage');
    var yesBtn = document.getElementById('confirmActionModalYes');
    var pendingAction = null;

    function resetYesButton() {
        yesBtn.className = 'btn btn-primary';
        yesBtn.textContent = 'Yes';
    }

    function openConfirm(options) {
        resetYesButton();

        titleEl.textContent = options.title || 'Confirm Action';
        messageEl.textContent = options.message || 'Are you sure you want to continue?';
        yesBtn.textContent = options.yesText || 'Yes';

        if (options.yesClass) {
            yesBtn.className = 'btn ' + options.yesClass;
        }

        pendingAction = options.onConfirm || null;
        modal.show();
    }

    yesBtn.addEventListener('click', function () {
        if (typeof pendingAction === 'function') {
            pendingAction();
        }
        pendingAction = null;
        modal.hide();
    });

    modalEl.addEventListener('hidden.bs.modal', function () {
        pendingAction = null;
        resetYesButton();
    });

    document.addEventListener('click', function (event) {
        var trigger = event.target.closest('[data-confirm-action]');
        if (!trigger) return;

        event.preventDefault();
        event.stopPropagation();

        var message = trigger.getAttribute('data-confirm-message') || 'Are you sure?';
        var title = trigger.getAttribute('data-confirm-title') || 'Confirm Action';
        var yesText = trigger.getAttribute('data-confirm-yes') || 'Yes';
        var yesClass = trigger.getAttribute('data-confirm-yes-class') || 'btn-primary';

        openConfirm({
            title: title,
            message: message,
            yesText: yesText,
            yesClass: yesClass,
            onConfirm: function () {
                // Form submit button
                var form = trigger.closest('form');
                if (form && (trigger.type === 'submit' || trigger.getAttribute('type') === 'submit')) {
                    form.submit();
                    return;
                }

                // Link / anchor
                if (trigger.tagName === 'A' && trigger.href) {
                    window.location.href = trigger.href;
                    return;
                }

                // Button inside form (non-submit fallback)
                if (form) {
                    form.submit();
                }
            }
        });

        return false;
    });
});
</script>