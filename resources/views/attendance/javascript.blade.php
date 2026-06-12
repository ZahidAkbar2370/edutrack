
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function () {

    // Class Change
    $('#class_id').on('change', function () {

        $('#attendance-submit-wrap').addClass('d-none');

        let classId = $(this).val();

        $('#section_id').html(
            '<option value="">Loading Sections...</option>'
        );

        // Hide Students
        $('#students-card').addClass('d-none');

        if (!classId) {
            return;
        }

        $.ajax({
            url: '/ajax/sections/' + classId,
            type: 'GET',
            success: function (response) {

                let options =
                    '<option value="">Select Section From List</option>';

                $.each(response, function (index, section) {
                    options +=
                        `<option value="${section.id}">
                            ${section.section_name}
                        </option>`;
                });

                $('#section_id').html(options);
            }
        });
    });

    // Section Change
    $('#section_id').on('change', function () {

        $('#attendance-submit-wrap').addClass('d-none');

        loadStudents();
    });

    function loadStudents()
    {
        let classId = $('#class_id').val();
        let sectionId = $('#section_id').val();

        if (!classId || !sectionId) {
            return;
        }

        $('#students-card').removeClass('d-none');
        $('#students-loading').removeClass('d-none');
        $('#students-empty').addClass('d-none');
        $('#students-table').addClass('d-none');

        $.ajax({
            url: '/ajax/students/' + classId + '/' + sectionId,
            type: 'GET',
                success: function (response) {

                $('#students-loading').addClass('d-none');

                if (response.length === 0) {

                    $('#students-empty').removeClass('d-none');
                    $('#students-count').text('0 Students');

                    return;
                }

                let html = '';

                $.each(response, function(index, student){

                    html += `
                        <tr>
                            <td>${index + 1}</td>
                            <td>${student.student_name}</td>
                            <td>${student.student_roll_number ?? ''}</td>
                            <td class="text-center">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="students[${student.id}]"
                                           value="present"
                                           checked>
                                    <label class="form-check-label">
                                        Present
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="students[${student.id}]"
                                           value="absent">
                                    <label class="form-check-label">
                                        Absent
                                    </label>
                                </div>

                                <div class="form-check form-check-inline">
                                    <input class="form-check-input"
                                           type="radio"
                                           name="students[${student.id}]"
                                           value="leave">
                                    <label class="form-check-label">
                                        Leave
                                    </label>
                                </div>
                            </td>
                        </tr>
                    `;
                });

                $('#students-tbody').html(html);

                $('#students-table').removeClass('d-none');

                $('#attendance-submit-wrap').removeClass('d-none');

                $('#students-count').text(
                    students.length + ' Students'
                );
            }
        });
    }
});
</script>