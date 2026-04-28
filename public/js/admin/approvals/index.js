$(document).ready(function() {
    
    // ==========================================================
    // 1. เรียกใช้งาน DataTables
    // ==========================================================
    if ($('#approvalTable').length) {
        $('#approvalTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [], 
            "columnDefs": [
                { "orderable": false, "targets": [0, 6] } 
            ],
            // 🌟 เพิ่มท่อนภาษาไทยตรงนี้เข้าไปครับ
            "language": {
                "emptyTable": "ขณะนี้ไม่มีโครงการที่รอการอนุมัติครับ",
                "zeroRecords": "ไม่พบข้อมูลที่ค้นหา",
                "infoEmpty": "แสดง 0 ถึง 0 จาก 0 รายการ"
            }
        });
    }
    // ==========================================================
    // 2. ดักจับปุ่ม "อนุมัติ" (Approve)
    // ==========================================================
    $(document).on('click', '.btn-approve-project', function(e) {
        e.preventDefault();
        
        let form = $(this).closest('form')[0]; 
        let projectName = $(this).data('name');

        Swal.fire({
            title: 'ยืนยันการอนุมัติ?',
            html: `คุณต้องการอนุมัติโครงการ<br><strong class="text-success">"${projectName}"</strong><br>ใช่หรือไม่?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
            confirmButtonText: '<i class="fas fa-check"></i> ใช่, อนุมัติเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({
                    title: 'กำลังบันทึกข้อมูล...',
                    allowOutsideClick: false,
                    onBeforeOpen: () => { Swal.showLoading(); },
                    didOpen: () => { Swal.showLoading(); }
                });
                form.submit();
            }
        });
    });

    // ==========================================================
    // 3. ดักจับปุ่ม "ตีกลับ" (Reject) เพื่อเปิด Modal
    // ==========================================================
    $(document).on('click', '.btn-reject-project', function() {
        let id = $(this).data('id');
        let name = $(this).data('name');
        
        // เซ็ตชื่อโปรเจกต์ให้เห็นชัดๆ ใน Modal
        $('#rejectProjectName').text(name);
        
        // เคลียร์ข้อความเก่า
        $('#reject_reason').val(''); 
        
        // 🌟 ดึง URL จากตัวแปร Global ที่เราฝังไว้ในหน้า Blade
        let url = window.ROUTES.rejectBaseUrl;
        url = url.replace(':id', id);
        
        // เซ็ต Action URL ของฟอร์มให้วิ่งไปหาโปรเจกต์นี้
        $('#formReject').attr('action', url);
        
        // เปิด Modal
        $('#modalReject').modal('show');
    });

    // ==========================================================
    // 4. ดักตอนกด Submit ใน Modal ตีกลับ
    // ==========================================================
    $('#formReject').on('submit', function() {
        $('#btnConfirmReject').html('<i class="fas fa-spinner fa-spin mr-1"></i> กำลังประมวลผล...').prop('disabled', true);
        Swal.fire({ 
            title: 'กำลังส่งข้อมูล...', 
            allowOutsideClick: false, 
            onBeforeOpen: () => { Swal.showLoading(); },
            didOpen: () => { Swal.showLoading(); } 
        });
    });

    // ==========================================================
    // 5. ดักจับตอน Modal กำลังจะปิด (แก้ Warning: Blocked aria-hidden)
    // ==========================================================
    $('#modalReject').on('hide.bs.modal', function () {
        if (document.activeElement) {
            document.activeElement.blur(); // สั่งให้ปุ่มที่โดนคลิกอยู่ เลิกโฟกัส
        }
    });

});