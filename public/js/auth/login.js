$(document).ready(function() {
    // ดึงค่าแจ้งเตือนจาก Hidden Input
    var flashWarning = $('#flash-warning').val();
    var flashRights = $('#flash-rights').val();

    // แสดงผล SweetAlert ถ้ามีข้อความแจ้งเตือน
    if (flashWarning) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: flashWarning,
        });
    } else if (flashRights) {
        Swal.fire({
            icon: 'error',
            title: 'Oops...',
            text: flashRights,
        });
    }
});
