
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {

    $('.pay-fee-button').on('click', function () {
        let studentId = $(this).data('student-id');
        let paymentMonth = $(this).data('payment-month');

        if(!studentId || !paymentMonth) {
            return;
        }
        $('#student-fee-payment-records').html('<div class="text-center text-muted py-4">Loading...</div>');

        $.ajax({
            url: "{{ url('ajax/getStudentFeePaymentRecords/') }}" + '/' + studentId + '/' + paymentMonth,
            type: 'GET',
            success: function (response) { 
                $('#student-fee-payment-records').html(response);
            }
        });
    });


    $(document).on('click', '#feemanagement-save-record-button', function () {

let studentId = $(this).data('student-id');
let paymentMonth = $(this).data('payment-month');

let totalAmount = parseFloat($('#feemanagement-total-amount').val()) || 0;
let fineAmount = parseFloat($('#feemanagement-fine-amount').val()) || 0;
let discountAmount = parseFloat($('#feemanagement-discount-amount').val()) || 0;
let paidAmount = parseFloat($('#feemanagement-paid-amount').val()) || 0;
let note = $('#feemanagement-note').val();



$.ajax({
    url: "{{ url('ajax/storePayFee') }}",
    type: "POST",
    data: {
        _token: "{{ csrf_token() }}",
        student_id: studentId,
        payment_month: paymentMonth,
        total_amount: totalAmount.toFixed(2),
        any_fine_amount: fineAmount.toFixed(2),
        any_discount_amount: discountAmount.toFixed(2),
        paid_amount: paidAmount.toFixed(2),
        note: note
    },
    success: function(response){

        let calculatedRemainingAmount = parseFloat(totalAmount + fineAmount - discountAmount) - paidAmount;

        if(calculatedRemainingAmount <= 0) {
            $('.monthly-display-remaining-amount-'+studentId).text('0');

            $('.monthly-fee-status-container-'+studentId).html('<span class="badge bg-success monthly-fee-paid-status-'+studentId+'">Paid</span>');
        } else {
            $('.monthly-fee-status-container-'+studentId).html('<span class="badge bg-warning monthly-fee-remaining-status-'+studentId+'">Remaining</span>');
        }

        $('.monthly-display-remaining-amount-'+studentId).text(
            calculatedRemainingAmount.toFixed(2)
        );

        $('#student-fee-payment-records').html('<div class="text-center text-muted py-4">Loading...</div>');

        $.ajax({
            url: "{{ url('ajax/getStudentFeePaymentRecords/') }}" + '/' + studentId + '/' + paymentMonth,
            type: 'GET',
            success: function (response) { 
                $('#student-fee-payment-records').html(response);
            }
        });

    },
    error: function(xhr){
        toastr.error(xhr.responseJSON.message);
    }
});

});

    // $('#feemanagement-fine-amount').on('input', function () {
    //     console.log('fine amount input');
    //     let totalAmount = parseFloat($('#feemanagement-total-amount').val());

    //     let fineAmount = parseFloat($('#feemanagement-fine-amount').val());
    //     if(isNaN(fineAmount)) {
    //         fineAmount = 0;
    //     }

    //     let discountAmount = parseFloat($('#feemanagement-discount-amount').val());
    //     if(isNaN(discountAmount)) {
    //         discountAmount = 0;
    //     }

    //     let newTotalAmount = (totalAmount + fineAmount) - discountAmount;
    //     $('#feemanagement-total-amount-display').text(newTotalAmount);
    // });

    $(document).on('input', '#feemanagement-fine-amount, #feemanagement-discount-amount', function () {

        let totalAmount = parseFloat($('#feemanagement-total-amount').val()) || 0;
        let fineAmount = parseFloat($('#feemanagement-fine-amount').val()) || 0;
        let discountAmount = parseFloat($('#feemanagement-discount-amount').val()) || 0;

        let newTotalAmount = (totalAmount + fineAmount) - discountAmount;

        $('#feemanagement-total-amount-display').text(newTotalAmount.toFixed(2));

    });



});
</script>