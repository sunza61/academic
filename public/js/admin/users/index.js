$(document).ready(function() {
    if ($('#userTable').length) {
        $('#userTable').DataTable({
            "language": { 
                "emptyTable": "📂 ยังไม่มีข้อมูลผู้ใช้งานในระบบ", 
                "search": "ค้นหา:",
                "lengthMenu": "แสดง _MENU_ รายการ",
                "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                "infoEmpty": "แสดง 0 ถึง 0 จากทั้งหมด 0 รายการ",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            },
            "columnDefs": [{ "orderable": false, "targets": [0, 6] }]
        });
    }

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let form = $(this).closest('form')[0]; 
        let name = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการลบผู้ใช้งาน?',
            html: `คุณต้องการลบข้อมูลของ<br><strong class="text-danger">"${name}"</strong><br>ใช่หรือไม่?`,
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
