
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(function () {

    function getTotalMarks() {
        return parseInt($('#daily_test_total').val()) || 0;
    }

    function calcPercentage(obtained) {
        let total = getTotalMarks();

        if (total <= 0)
            return "0%";

        return ((obtained / total) * 100).toFixed(2) + "%";
    }

    function updateRowPercentage(input) {

        $(input)
            .closest('tr')
            .find('.pct-cell')
            .text(calcPercentage(parseFloat($(input).val()) || 0));

    }

    function escapeHtml(text) {
        return $('<div>').text(text || '').html();
    }

    // ===============================
    // CLASS CHANGE
    // ===============================

    $('#class_id').change(function () {

        $('#submit-wrap').addClass('d-none');
        $('#students-card').addClass('d-none');

        let classId = $(this).val();

        $('#section_id').html('<option>Loading...</option>');

        if (!classId) {
            return;
        }

        $.ajax({

            url: "{{ url('ajax/sections') }}/" + classId,
            type: "GET",

            success: function (sections) {

                let html = '<option value="">Select Section From List</option>';

                $.each(sections, function (i, section) {

                    html += `<option value="${section.id}">
                                ${section.section_name}
                             </option>`;

                });

                $('#section_id').html(html);

            }

        });

    });


    // ===============================
    // LOAD STUDENTS
    // ===============================

    function loadStudents() {

        let classId = $('#class_id').val();
        let sectionId = $('#section_id').val();
        let testDate = $('#daily_test_date').val();

        $('#students-tbody').html('');
        $('#students-table').addClass('d-none');
        $('#students-empty').addClass('d-none');
        $('#submit-wrap').addClass('d-none');

        if (!classId || !sectionId || !testDate) {

            $('#students-card').addClass('d-none');
            return;

        }

        $('#students-card').removeClass('d-none');
        $('#students-loading').removeClass('d-none');

        $.ajax({

            url: "{{ url('ajax/students') }}/" + classId + "/" + sectionId + "/" + testDate,
            type: "GET",
            dataType: "json",

            success: function (students) {

                $('#students-loading').addClass('d-none');

                if (students.length == 0) {

                    $('#students-empty').removeClass('d-none');
                    $('#students-count').text('0 Student(s)');
                    return;

                }

                let total = getTotalMarks();

                $.each(students, function (index, student) {

                    $('#students-tbody').append(`

                        <tr>

                            <td>${index + 1}</td>

                            <td class="fw-medium">
                                <a href="{{ url('student/show') }}/${student.id}" target="_blank">
                                    ${escapeHtml(student.student_name)}
                                </a>
                            </td>

                            <td>${escapeHtml(student.student_roll_number ?? '—')}</td>

                            <td class="text-center">

                                <input
                                    type="number"
                                    class="form-control form-control-sm obtained-input text-center mx-auto"
                                    style="max-width:100px"
                                    name="students[${student.id}]"
                                    value="0"
                                    min="0"
                                    max="${total}"
                                    required>

                            </td>

                            <td class="text-center pct-cell">
                                0%
                            </td>

                        </tr>

                    `);

                });

                $('#students-table').removeClass('d-none');
                $('#submit-wrap').removeClass('d-none');
                $('#students-count').text(students.length + ' Student(s)');

            },

            error: function () {

                $('#students-loading').addClass('d-none');

                $('#students-empty')
                    .removeClass('d-none')
                    .text('Unable to load students.');

            }

        });

    }


    // ===============================
    // EVENTS
    // ===============================

    $('#section_id').change(loadStudents);

    $('#daily_test_date').change(loadStudents);

    $(document).on('input', '.obtained-input', function () {

        let total = getTotalMarks();

        $(this).attr('max', total);

        if (parseFloat($(this).val()) > total) {

            $(this).val(total);

        }

        updateRowPercentage(this);

    });

    $('#daily_test_total').on('input', function () {

        let total = getTotalMarks();

        $('.obtained-input').each(function () {

            $(this).attr('max', total);

            if (parseFloat($(this).val()) > total) {

                $(this).val(total);

            }

            updateRowPercentage(this);

        });

    });

});
</script>