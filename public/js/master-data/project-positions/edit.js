$(document).ready(function() {
    // ดักตอนกด Submit ให้ปุ่มขึ้น Loading ป้องกันการกดเบิ้ล
    $('form').on('submit', function() {
        let btn = $(this).find('button[type="submit"]');
        btn.html('<i class="fas fa-spinner fa-spin"></i> อัปเดต...').prop('disabled', true);
    });
});