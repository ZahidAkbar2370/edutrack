                                                        <!-- Create Student Form JavaScript -->


<!-- When Select Class, Filter Sections -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var classSelect = document.getElementById('class_id');
        var sectionSelect = document.getElementById('section_id');
        if (!classSelect || !sectionSelect) return;

        var allOptions = Array.from(sectionSelect.querySelectorAll('option[data-class-id]'));

        function filterSections() {
            var classId = classSelect.value;
            var currentValue = sectionSelect.value;

            sectionSelect.innerHTML = '<option value="">Select Section</option>';

            if (!classId) return;

            allOptions.forEach(function (opt) {
                if (opt.getAttribute('data-class-id') === classId) {
                    sectionSelect.appendChild(opt.cloneNode(true));
                }
            });

            if (currentValue && sectionSelect.querySelector('option[value="' + currentValue + '"]')) {
                sectionSelect.value = currentValue;
            }
        }

        classSelect.addEventListener('change', filterSections);
        filterSections();

        var photoInput = document.getElementById('student_photo');
        var photoPreview = document.getElementById('student_photo_preview');
        var defaultPhoto = '{{ asset(\App\Models\Student::DEFAULT_PHOTO) }}';

        if (photoInput && photoPreview) {
            photoInput.addEventListener('change', function () {
                var file = photoInput.files[0];
                if (!file) {
                    photoPreview.src = defaultPhoto;
                    return;
                }
                photoPreview.src = URL.createObjectURL(file);
            });
        }
    });
</script>