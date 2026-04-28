$(document).ready(function() {
    if ($('#positionTable').length) {
        $('#positionTable').DataTable({
            "language": { "emptyTable": "📂 ยังไม่มีข้อมูลตำแหน่งในระบบ", "search": "ค้นหา:" },
            "columnDefs": [{ "orderable": false, "targets": [0, 4] }]
        });
    }

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let form = $(this).closest('form')[0]; 
        let name = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการลบข้อมูล?',
            html: `คุณต้องการลบตำแหน่ง<br><strong class="text-danger">"${name}"</strong><br>ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ลบข้อมูล',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            // รองรับ SWL รุ่นเก่า
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