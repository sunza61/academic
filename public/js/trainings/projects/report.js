$(document).ready(function() {
    // 🌟 ดักจับการคำนวณเปอร์เซ็นต์ (คะแนนเต็ม 5 -> 100%)
    function calculatePercent(scoreInputId, percentInputId) {
        let score = parseFloat($('#' + scoreInputId).val()) || 0;
        
        // กันคนกรอกเกิน 5
        if (score > 5) { 
            score = 5; 
            $('#' + scoreInputId).val(5); 
        }
        
        let percent = score * 20; 
        $('#' + percentInputId).val(percent.toFixed(2));
    }

    // ทำงานเมื่อมีการพิมพ์หรือเปลี่ยนค่าในช่องคะแนน
    $('#satisfaction_score').on('input change', function() { 
        calculatePercent('satisfaction_score', 'satisfaction_percent'); 
    });
    
    $('#dissatisfaction_score').on('input change', function() { 
        calculatePercent('dissatisfaction_score', 'dissatisfaction_percent'); 
    });

    // 🌟 ดักจับปุ่มกดยืนยันบันทึกรายงานและปิดโครงการ
    $('#btn-submit-report').click(function(e) {
        e.preventDefault(); 
        
        // อ้างอิง ID ของ Form ให้ตรงกับหน้า Blade ใหม่
        let form = document.getElementById('form-report'); 
        
        // เช็ค Validation ของฟอร์มพื้นฐาน (HTML5)
        if (!form.checkValidity()) { 
            form.reportValidity(); 
            return; 
        }

        // แจ้งเตือนยืนยันก่อนบันทึกปิดโครงการ
        Swal.fire({
            title: 'ยืนยันการปิดโครงการ?', 
            text: "คุณต้องการบันทึกรายงานผลและเปลี่ยนสถานะเป็น 'เสร็จสิ้นโครงการ' ใช่หรือไม่? (กรุณาตรวจสอบข้อมูลให้ครบถ้วน)", 
            icon: 'warning', // ใช้ไอคอน warning ให้ดูจริงจังขึ้นเพราะเป็นการปิดโครงการ
            showCancelButton: true, 
            confirmButtonColor: '#28a745', // สีเขียวให้ตรงกับปุ่มในหน้า UI
            cancelButtonColor: '#6c757d', 
            confirmButtonText: '<i class="fas fa-check-circle"></i> ยืนยันและปิดโครงการ', 
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                // แสดง Loading ระหว่างรอ Submit
                Swal.fire({ 
                    title: 'กำลังบันทึกข้อมูลและปิดโครงการ...', 
                    allowOutsideClick: false, 
                    didOpen: () => { 
                        Swal.showLoading(); 
                    } 
                });
                // ส่งฟอร์มจริงๆ
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });
});