$(document).ready(function() {
    // 1. กดปุ่มบันทึกใน Modal
    $('#btnSaveMain').click(function() {
        let name = $('#new_main_name').val().trim();
        if(!name) {
            Swal.fire('แจ้งเตือน', 'กรุณากรอกชื่อหมวดหมู่หลัก', 'warning');
            return;
        }

        let btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...').prop('disabled', true);

        // ยิง AJAX ไปหลังบ้าน
        $.ajax({
            // url: "/master-data/budget-incomes/main/store-ajax", // ตาม Route ที่ตั้งไว้
            url: storeMainAjaxUrl,
            type: "POST",
            data: {
                _token: $('input[name="_token"]').val(), // เอา Token จากฟอร์มหลักมาใช้
                name_th: name
            },
            success: function(res) {
                if(res.status === 'success') {
                    // ปิด Modal และล้างค่า
                    $('#modalNewMain').modal('hide');
                    $('#new_main_name').val('');
                    
                    // 🌟 Magic! เพิ่ม Option เข้าไปใน Dropdown ทันทีและเลือกให้เลย
                    let newOption = new Option(res.data.name_th, res.data.id, true, true);
                    $('#main_category_id').append(newOption).trigger('change');

                    Swal.fire({
                        title: 'สำเร็จ!',
                        text: 'เพิ่มหมวดหมู่หลักเรียบร้อยแล้ว',
                        icon: 'success',
                        timer: 1500,
                        showConfirmButton: false
                    });
                }
            },
            error: function() {
                Swal.fire('ข้อผิดพลาด', 'ไม่สามารถบันทึกข้อมูลได้', 'error');
            },
            complete: function() {
                btn.html('<i class="fas fa-save"></i> บันทึก').prop('disabled', false);
            }
        });
    });
});