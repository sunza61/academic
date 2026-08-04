$(document).ready(function() {
    // ลบ Select2 ออกไปเลย ปล่อยให้ระบบหลักจัดการเอง
    
    $('form').on('submit', function() {
        if (!$(this).hasClass('form-delete')) {
            $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> กำลังประมวลผล...').prop('disabled', true);
        }
    });

    $('#btnSaveMain').click(function() {
        let name = $('#new_main_name').val().trim();
        if(!name) { Swal.fire('แจ้งเตือน', 'กรุณากรอกชื่อหมวดหมู่หลัก', 'warning'); return; }

        let btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin"></i> บันทึก...').prop('disabled', true);

        $.ajax({
            url: storeMainAjaxUrl, 
            type: "POST",
            data: { _token: $('input[name="_token"]').val(), name_th: name },
            success: function(res) {
                if(res.status === 'success') {
                    // 1. สั่งปิด Modal
                    $('#modalNewMain').modal('hide');
                    
                    // 🌟 2. เพิ่ม 2 บรรทัดนี้เพื่อบังคับเคลียร์ Backdrop จอดำๆ ทิ้งไปเลย!
                    $('body').removeClass('modal-open');
                    $('.modal-backdrop').remove();
                    
                    // 3. คืนค่าและเพิ่ม Option ให้ Dropdown
                    $('#new_main_name').val('');
                    let newOption = new Option(res.data.name_th, res.data.id, true, true);
                    $('#main_category_id').append(newOption).trigger('change');
                    
                    // 4. แสดงแจ้งเตือนสำเร็จ
                    Swal.fire({ 
                        title: 'สำเร็จ!', 
                        text: 'เพิ่มหมวดหมู่หลักเรียบร้อย', 
                        icon: 'success', 
                        timer: 1500, 
                        showConfirmButton: false 
                    });
                }
            },
            error: function() { Swal.fire('ผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้', 'error'); },
            complete: function() { btn.html('<i class="fas fa-save"></i> บันทึก').prop('disabled', false); }
        });
    });
});