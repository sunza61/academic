// public/js/contracts/projects/show.js

// =========================================================
// 🖨️ ส่วนจัดการการพิมพ์ (Print)
// =========================================================

// ฟังก์ชันพิมพ์แบบปกติ (เซ็นสด)
window.printNormal = function () {
    document.body.classList.remove('print-digital');
    setTimeout(function () {
        window.print();
    }, 100);
};

// ฟังก์ชันพิมพ์แบบมีลายเซ็นดิจิทัล (#sg..)
window.printDigital = function () {
    document.body.classList.add('print-digital');
    setTimeout(function () {
        window.print();
    }, 100);
};

// 🛑 ดักจับการกด Ctrl+P หรือ Cmd+P บนคีย์บอร์ด
document.addEventListener('keydown', function (event) {
    if ((event.ctrlKey || event.metaKey) && (event.key === 'p' || event.key === 'P')) {
        event.preventDefault();
        Swal.fire({
            icon: 'warning',
            title: 'ช้าก่อน!',
            text: 'กรุณาใช้ปุ่ม "พิมพ์เพื่อเซ็นสด" หรือ "พิมพ์แบบ Digital" ที่มุมขวาบนของหน้าจอ เพื่อให้รูปแบบเอกสารออกมาถูกต้องสมบูรณ์ครับ',
            confirmButtonColor: '#17a2b8',
            confirmButtonText: 'เข้าใจแล้ว'
        });
    }
});

// =========================================================
// 🌟 ส่วนของ AJAX: จัดการสถานะ (Admin Override)
// =========================================================

$(document).ready(function() {
    
    // เคลียร์ Event เก่าทิ้งก่อนผูกใหม่ ป้องกันการทำงานซ้ำซ้อน
    $('.btn-force-change-status').off('click').on('click', function(e) {
        e.preventDefault(); // ป้องกันฟอร์มเด้งเปลี่ยนหน้าแบบปกติ
        
        let btn = $(this);
        let form = $('#form-admin-change-status'); 
        let url = form.attr('action');
        let statusText = $('#admin_new_status option:selected').text(); 

        console.log("📍 [Admin] ยิงไปที่ URL: ", url);

        Swal.fire({
            title: 'ยืนยันการใช้อำนาจผู้ดูแลระบบ?',
            html: `คุณกำลังจะบังคับเปลี่ยนสถานะโครงการบริการวิชาการเป็น<br><strong class="text-danger">"${statusText}"</strong><br>แน่ใจหรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ยืนยัน, บังคับเปลี่ยนสถานะ!',
            cancelButtonText: 'ยกเลิก',
            allowOutsideClick: false 
        }).then((result) => {
            
            if (result.isConfirmed || result.value) {
                
                // โชว์ Loading สวยๆ ระหว่างรอเซิร์ฟเวอร์
                Swal.fire({ 
                    title: 'กำลังอัปเดตสถานะ...', 
                    allowOutsideClick: false, 
                    didOpen: () => { Swal.showLoading(); } 
                });
                
                $.ajax({
                    url: url,
                    type: 'POST', // ส่งเป็น POST (ในฟอร์มมี @method('PATCH') อยู่แล้ว Laravel จะจัดการต่อเอง)
                    data: form.serialize(),
                    success: function(response) {
                        console.log("✅ อัปเดตสำเร็จ: ", response);
                        
                        Swal.fire({
                            icon: 'success',
                            title: 'สำเร็จ!',
                            text: response.message || 'เปลี่ยนสถานะโครงการเรียบร้อยแล้ว',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            location.reload(); // รีเฟรชหน้าเว็บเพื่อโชว์สถานะใหม่
                        });
                    },
                    error: function(xhr) {
                        console.error("❌ Error หลังบ้าน: ", xhr);
                        let errMsg = 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ';
                        
                        // ดึงข้อความ Error จาก Controller (ถ้ามีส่งมา)
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        
                        Swal.fire('ผิดพลาด', errMsg, 'error');
                    }
                });
            } else {
                console.log("📍 ยกเลิกการทำรายการ");
            }
        });
    });

});