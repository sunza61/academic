$(document).ready(function() {
    // เปิดใช้งาน Select2
    if ($('.select2-basic').length) {
        $('.select2-basic').select2({ theme: 'bootstrap4' });
    }

    // Submit ฟอร์มหลัก
    $('form').on('submit', function() {
        if (!$(this).hasClass('form-delete')) {
            $(this).find('button[type="submit"]').html('<i class="fas fa-spinner fa-spin"></i> อัปเดต...').prop('disabled', true);
        }
    });

    // ยิง AJAX สร้างหมวดหมู่หลัก (Modal) เหมือนตอน Create
    $('#btnSaveMain').click(function() {
        let name = $('#new_main_name').val().trim();
        if(!name) {
            Swal.fire('แจ้งเตือน', 'กรุณากรอกชื่อหมวดหมู่หลัก', 'warning');
            return;
        }

        let btn = $(this);
        btn.html('<i class="fas fa-spinner fa-spin"></i> กำลังบันทึก...').prop('disabled', true);

        $.ajax({
            // url: "/master-data/budget-incomes/main/store-ajax",
            url: storeMainAjaxUrl,
            type: "POST",
            data: {
                _token: $('input[name="_token"]').val(),
                name_th: name
            },
            success: function(res) {
                if(res.status === 'success') {
                    $('#modalNewMain').modal('hide');
                    $('#new_main_name').val('');
                    
                    // เพิ่ม Option เข้าไปใน Dropdown แล้วเลือกให้เลย
                    let newOption = new Option(res.data.name_th, res.data.id, true, true);
                    $('#main_category_id').append(newOption).trigger('change');

                    Swal.fire({ title: 'สำเร็จ!', text: 'เพิ่มหมวดหมู่หลักเรียบร้อยแล้ว', icon: 'success', timer: 1500, showConfirmButton: false });
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