/**
 * Script to dynamically populate college and major dropdowns
 * based on selected university and college using jQuery and AJAX.
 *
 * - When the user selects a university, fetch the related colleges via AJAX
 * - When the user selects a college, fetch the related majors via AJAX
 * - This creates a smooth, dependent dropdown experience for registration forms
 *
 * APIs:
 * - GET /api/universities/{id}/colleges → returns colleges for a university
 * - GET /api/colleges/{id}/majors → returns majors for a college
 */

$(document).ready(function () {
    $('#university_id').on('change', function () {
        let universityId = $(this).val();
        $('#college_id').empty().append('<option value="">اختر الكلية</option>');
        $('#major_id').empty().append('<option value="">اختر التخصص</option>');

        if (universityId) {
            $.get(`/api/universities/${universityId}/colleges`, function (colleges) {
                $.each(colleges, function (index, college) {
                    $('#college_id').append(`<option value="${college.id}">${college.name_ar}</option>`);
                });
            });
        }
    });

    $('#college_id').on('change', function () {
        let collegeId = $(this).val();
        $('#major_id').empty().append('<option value="">اختر التخصص</option>');

        if (collegeId) {
            $.get(`/api/colleges/${collegeId}/majors`, function (majors) {
                $.each(majors, function (index, major) {
                    $('#major_id').append(`<option value="${major.id}">${major.name_ar}</option>`);
                });
            });
        }
    });
});
