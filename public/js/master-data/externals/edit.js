$(document).ready(function() {
    // 1. เปิดใช้งาน Select2 สำหรับ Dropdown คำนำหน้า
    if ($('.select2-basic').length) {
        $('.select2-basic').select2({
            theme: 'bootstrap4',
            placeholder: '-- เลือก --',
            allowClear: false // บังคับให้ต้องเลือก เพราะเป็น field required
        });
    }

    // 2. ดักตอนกด Submit ให้ปุ่มขึ้น Loading ป้องกันการกดเบิ้ล
    $('form').on('submit', function() {
        let btn = $(this).find('button[type="submit"]');
        btn.html('<i class="fas fa-spinner fa-spin"></i> กำลังอัปเดต...').prop('disabled', true);
    });
});