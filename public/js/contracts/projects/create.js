// public/js/contracts/projects/create.js

$(document).ready(function() {
    // 1. ตั้งค่า Select2
    $('.select2-multiple').select2({
        width: '100%',
        placeholder: "คลิกเพื่อเลือกข้อมูล"
    });

    // 2. ตั้งค่า Datepicker
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d", 
        altInput: true,
        altFormat: "d/m/Y",  
        locale: "th",
        allowInput: true
    });

    // 🎯 3. ระบบจัดการวัตถุประสงค์ (เพิ่ม/ลบ)
    let objectiveIndex = 1;
    $('#btn-add-objective').click(function() {
        let customerGroupsHtml = '';
        if (window.CUSTOMER_GROUPS) {
            window.CUSTOMER_GROUPS.forEach(function(group) {
                customerGroupsHtml += `<option value="${group.id}">${group.name_th}</option>`;
            });
        }

        let newRow = `
            <div class="objective-box p-2 border-bottom animate__animated animate__fadeIn">
                <div class="row align-items-center">
                    <div class="col-md-3">
                        <!-- Mobile Label -->
                        <label class="small font-weight-bold text-muted d-md-none mb-1">กลุ่มผู้ว่าจ้าง/แหล่งทุน *</label>
                        <select class="form-control form-control-sm select2-objective" name="objectives[${objectiveIndex}][group_id]" required>
                            <option value="">-- เลือกกลุ่ม --</option>
                            ${customerGroupsHtml}
                        </select>
                    </div>
                    <div class="col-md-8 col-10">
                        <!-- Mobile Label -->
                        <label class="small font-weight-bold text-muted d-md-none mb-1">รายละเอียดวัตถุประสงค์</label>
                        <input type="text" class="form-control form-control-sm w-100" name="objectives[${objectiveIndex}][detail]" placeholder="ระบุรายละเอียดเพิ่มเติม...">
                    </div>
                    <div class="col-md-1 col-2 text-right pl-0">
                        <button type="button" class="btn btn-sm btn-link text-danger btn-remove-objective px-1">
                            <i class="fas fa-trash-alt fa-lg"></i>
                        </button>
                    </div>
                </div>
            </div>
        `;
        $('#objective-container').append(newRow);
        
        // สั่งให้ Select2 ทำงานกับช่องใหม่
        $(`select[name="objectives[${objectiveIndex}][group_id]"]`).select2({ 
            width: '100%', 
            placeholder: "-- เลือกกลุ่ม --" 
        });
        
        objectiveIndex++;
        $('.btn-remove-objective').show();
        $('.objective-header-row').show(); // แสดง Header ถ้ามีข้อมูล
    });

    $(document).on('click', '.btn-remove-objective', function() {
        $(this).closest('.objective-box').remove();
        if($('.objective-box').length === 0) {
            $('.objective-header-row').hide();
        }
        // ถ้าเหลือแถวเดียวในหน้า Create เราอาจจะซ่อนปุ่มลบหรือไม่ก็ได้ (ตามความสะดวก)
        if($('.objective-box').length === 1) {
            $('.btn-remove-objective').hide();
        }
    });
});