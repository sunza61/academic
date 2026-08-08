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
// 🌟 ส่วนของ AJAX ล้วนๆ แยกออกมาให้เป็นระเบียบ
// =========================================================





$(document).ready(function() {
    
    // 1. เคลียร์ Event เก่าทิ้งก่อนผูกใหม่ ป้องกันการทำงานซ้ำซ้อน
    $('.btn-force-change-status').off('click').on('click', function(e) {
        e.preventDefault();
        
        // เราใช้ $(this) เพียวๆ ไม่ผ่าน document แล้ว จะได้ไม่หลง
        let btn = $(this);
        let form = $('#form-admin-change-status'); // เรียกฟอร์มด้วย ID ชัวร์สุด
        let url = form.attr('action');
        let statusText = $('#admin_new_status option:selected').text(); 

        console.log("📍 [Step 1] URL ที่จะส่ง: ", url);

        Swal.fire({
            title: 'ยืนยันการใช้อำนาจผู้ดูแลระบบ?',
            html: `คุณกำลังจะบังคับเปลี่ยนสถานะเป็น<br><strong class="text-danger">"${statusText}"</strong><br>แน่ใจหรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ยืนยัน, บังคับเปลี่ยนสถานะ!',
            cancelButtonText: 'ยกเลิก',
            // 🔥 ปิดไม่ให้กดยกเลิกตอนโหลด
            allowOutsideClick: false 
        }).then((result) => {
            
            // 🌟 ดักให้ครอบคลุมทุกเวอร์ชันของ SweetAlert
            if (result.isConfirmed || result.value) {
                
                console.log("📍 [Step 2] ยิง AJAX ออกไปแล้ว! 🚀");
                
                Swal.fire({ 
                    title: 'กำลังอัปเดตสถานะ...', 
                    allowOutsideClick: false, 
                    didOpen: () => { Swal.showLoading(); } 
                });
                
                $.ajax({
                    url: url,
                    type: 'POST',
                    data: form.serialize(),
                    success: function(response) {
                        console.log("✅ [Step 3] ตอบกลับสำเร็จ: ", response);
                        if (response.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'สำเร็จ!',
                                text: response.message,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                location.reload(); 
                            });
                        }
                    },
                    error: function(xhr) {
                        console.error("❌ [Step 3] Error หลังบ้าน: ", xhr);
                        let errMsg = 'เกิดข้อผิดพลาดในการเปลี่ยนสถานะ';
                        if (xhr.responseJSON && xhr.responseJSON.message) {
                            errMsg = xhr.responseJSON.message;
                        }
                        Swal.fire('ผิดพลาด', errMsg, 'error');
                    }
                });
            } else {
                console.log("📍 ยกเลิกการเปลี่ยนสถานะ");
            }
        });
    });

});