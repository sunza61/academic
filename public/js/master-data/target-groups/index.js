// public/js/master-data/target-groups/index.js

$(document).ready(function() {

    // 1. 🌟 ตั้งค่า DataTables
    $('#targetGroupTable').DataTable({
        "paging": true,
        "lengthChange": true,
        "searching": true,
        "ordering": true,        // เปิดการเรียงลำดับ
        "order": [[2, "asc"]],   // สั่งให้ตอนเปิดหน้ามา โฟกัสการเรียง (ก-ฮ) ไปที่คอลัมน์ที่ 2 (Hierarchy Path)
        "columnDefs": [          // กำหนดเงื่อนไขรายคอลัมน์
            { 
                "orderable": false, 
                "targets": [0, 5] // ปิดการกดเรียงลำดับที่คอลัมน์ 0 (ลำดับ #) และคอลัมน์ 5 (ปุ่มจัดการ)
            }
        ],
        "info": true,
        "autoWidth": false,
        "responsive": true,
    });

    // 2. 📌 ดักจับการคลิกปุ่มลบ (ใช้ delegate เผื่อ DataTables เปลี่ยนหน้า)
    $('#targetGroupTable tbody').on('click', '.btn-delete', function(e) {
        e.preventDefault(); // หยุดการทำงานของปุ่มไว้ก่อน
        
        let form = $(this).closest('form');
        let fullPathName = $(this).data('name'); // ดึงเส้นทางเต็มมาจาก data-name

        Swal.fire({
            title: 'ยืนยันการลบข้อมูล?',
            // โชว์ชื่อเส้นทางเต็ม และข้อความแจ้งเตือนแม่-ลูก
            html: `คุณต้องการลบกลุ่มเป้าหมาย<br>
                   <strong class="text-danger">"${fullPathName}"</strong><br>ใช่หรือไม่?<br><br>
                   <div class="alert alert-warning py-2 mt-2" style="font-size: 0.9em; text-align: left;">
                       <i class="fas fa-exclamation-triangle"></i> <strong>หมายเหตุ:</strong> หากลบข้อมูลนี้ ข้อมูลกลุ่มลูกหรือกลุ่มย่อยที่อยู่ภายใต้ทั้งหมด จะถูกลบหายไปด้วย!
                   </div>`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ใช่, ยืนยันการลบ!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                // ถ้ากดยืนยัน ให้โชว์หน้าต่างโหลดดิ้ง
                Swal.fire({ 
                    title: 'กำลังลบข้อมูล...', 
                    allowOutsideClick: false, 
                    didOpen: () => { Swal.showLoading(); } 
                });
                
                // สั่งให้ฟอร์มทำงาน (วิ่งไปที่ Controller)
                form.submit();
            }
        });
    });

});