$(document).ready(function() {
    if ($('#mainExpenseTable').length) {
        $('#mainExpenseTable').DataTable({
            "language": { "emptyTable": "📂 ยังไม่มีข้อมูลหมวดหมู่หลักในระบบ", "search": "ค้นหา:" },
            "columnDefs": [{ "orderable": false, "targets": [0, 3] }]
        });
    }

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let form = $(this).closest('form')[0]; 
        let name = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการลบข้อมูล?',
            html: `คุณต้องการลบหมวดหมู่หลัก<br><strong class="text-danger">"${name}"</strong><br>ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ลบข้อมูล',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({ 
                    title: 'กำลังลบ...', 
                    allowOutsideClick: false, 
                    didOpen: () => { Swal.showLoading(); } 
                });
                form.submit();
            }
        });
    });
});