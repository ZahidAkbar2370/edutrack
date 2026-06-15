<script>
    let selectedClassId = null;
    let selectedStatus = null;
    let selectedCheckbox = null;

$(document).on('change', '#publication-status-toggle', function (e) {

    selectedCheckbox = $(this);

    // Prevent immediate visual change
    selectedCheckbox.prop('checked', !selectedCheckbox.prop('checked'));

    selectedClassId = selectedCheckbox.data('class-id');
    selectedStatus = selectedCheckbox.data('publication-status');

    $('#saveChangesConfirmModal').modal('show');
});

$('#saveChangesConfirmYesBtn').on('click', function () {

    $.ajax({
        url: "{{ url('class/update-publication-status') }}",
        type: "POST",
        data: {
            _token: "{{ csrf_token() }}",
            class_id: selectedClassId,
            publication_status: selectedStatus
        },
        success: function (response) {

            // Update checkbox state
            selectedCheckbox.prop(
                'checked',
                selectedStatus === 'active'
            );

            // Update next status for future toggle
            selectedCheckbox.data(
                'status',
                selectedStatus === 'active' ? 'inactive' : 'active'
            );

            $('#saveChangesConfirmModal').modal('hide');

            // Optional toast
            toastr.success('Publication status updated successfully');
        },
        error: function () {
            toastr.error('Something went wrong');
        }
    });
});

$('#saveChangesConfirmCancelBtn, #saveChangesConfirmCloseBtn').on('click', function () {
    selectedClassId = null;
    selectedStatus = null;
    selectedCheckbox = null;
});
</script>