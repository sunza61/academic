$(document).ready(function() {
    if ($('#incomeTable').length) {
        $('#incomeTable').DataTable({
            "language": { "emptyTable": "📂 ยังไม่มีข้อมูลหมวดหมู่รายรับในระบบ", "search": "ค้นหา:" },
            "columnDefs": [{ "orderable": false, "targets": [0, 5] }]
        });
    }

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let form = $(this).closest('form')[0]; 
        let name = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการลบข้อมูล?',
            html: `คุณต้องการลบหมวดหมู่ย่อย<br><strong class="text-danger">"${name}"</strong><br>ใช่หรือไม่?`,
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
                    onBeforeOpen: () => { Swal.showLoading(); },
                    didOpen: () => { Swal.showLoading(); } 
                });
                form.submit();
            }
        });
    });
});