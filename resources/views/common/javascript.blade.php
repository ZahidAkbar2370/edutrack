<!-- Save Changes Confirm Modal -->
<script>

document.addEventListener('DOMContentLoaded', function () {
    var modalEl = document.getElementById('saveChangesConfirmModal');
    if (!modalEl) return;

    var modal = new bootstrap.Modal(modalEl);
    var yesBtn = document.getElementById('saveChangesConfirmYesBtn');
    var cancelBtn = document.getElementById('saveChangesConfirmCancelBtn');
    var pendingForm = null;

    document.querySelectorAll('form.needs-validation').forEach(function (form) {
        form.addEventListener('submit', function (e) {

            if (form.dataset.bcpConfirmed === '1') {
                delete form.dataset.bcpConfirmed;
                return;
            }

            e.preventDefault();

            if (!form.checkValidity()) {
                form.classList.add('was-validated');
                return;
            }

            pendingForm = form;
            modal.show();
        });
    });

    yesBtn.addEventListener('click', function () {
        if (!pendingForm) return;
        modal.hide();
        pendingForm.dataset.bcpConfirmed = '1';
        pendingForm.submit();
        pendingForm = null;
    });
});

</script>