$(document).ready(function() {
    if ($('#externalTable').length) {
        $('#externalTable').DataTable({
            "language": { "emptyTable": "📂 ยังไม่มีข้อมูลบุคคลภายนอกในระบบ", "search": "ค้นหา:" },
            "columnDefs": [{ "orderable": false, "targets": [0, 5] }]
        });
    }

    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        let form = $(this).closest('form')[0]; 
        let name = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการลบข้อมูล?',
            html: `คุณต้องการลบข้อมูลของ<br><strong class="text-danger">"${name}"</strong><br>ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ลบข้อมูล',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            
            // 🌟 1. แก้ตรงนี้: เพิ่ม result.value เพื่อให้รองรับรุ่นเก่า
            if (result.isConfirmed || result.value) { 
                
                // 🌟 2. แก้ตรงนี้: ใส่ onBeforeOpen เข้าไปคู่กับ didOpen 
                Swal.fire({ 
                    title: 'กำลังลบ...', 
                    allowOutsideClick: false, 
                    onBeforeOpen: () => { Swal.showLoading(); }, // ของรุ่นเก่า
                    didOpen: () => { Swal.showLoading(); }       // ของรุ่นใหม่
                });
                
                form.submit();
            }
        });
    });
});