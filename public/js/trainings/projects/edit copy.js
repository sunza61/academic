// =========================================
// 🌐 ส่วนกลาง: ตัวแปร Global
// =========================================
let activeCustomerSelectBox = null;
let activeExternalSelectBox = null;
let formHasChanged = false;

// ฟังก์ชัน Initialize Select2
function initSelect2Customer(element) {
    element.select2({
        width: '100%',
        placeholder: "-- ค้นหากลุ่มเป้าหมาย --",
        language: {
            noResults: function() {
                return `<button type="button" class="btn btn-sm btn-primary w-100 mt-1" onmousedown="openTargetGroupModal(event)"><i class="fas fa-plus"></i> เพิ่มกลุ่มเป้าหมายใหม่</button>`;
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    }).on('select2:open', function() {
        activeCustomerSelectBox = $(this);
    });
}

function initSelect2External(element) {
    element.select2({
        width: '100%',
        placeholder: "-- ค้นหาชื่อบุคคลภายนอก --",
        language: {
            noResults: function() {
                return `<button type="button" class="btn btn-sm btn-info w-100 mt-1" onmousedown="openExternalModal(event)"><i class="fas fa-plus"></i> เพิ่มบุคคลภายนอกใหม่</button>`;
            }
        },
        escapeMarkup: function(markup) {
            return markup;
        }
    }).on('select2:open', function() {
        activeExternalSelectBox = $(this);
    });
}

window.openTargetGroupModal = function(e) {
    e.preventDefault();
    $('.select2-customer').select2('close');
    $('#formNewTargetGroup')[0].reset();
    $('#new_target_group_parent_id').select2({
        dropdownParent: $('#modalNewTargetGroup'),
        width: '100%',
        placeholder: "-- สร้างเป็นกลุ่มหลัก (Level 1) --",
        allowClear: true
    }).val('').trigger('change');
    $('#modalNewTargetGroup').modal('show');
};

window.openExternalModal = function(e) {
    e.preventDefault();
    $('.select2-external').select2('close');
    $('#formNewExternal')[0].reset();
    $('#modalNewExternal').modal('show');
};

window.checkUniquePositions = function() {
    let selectedUniques = [];
    $('.position-select').each(function() {
        let selectedOption = $(this).find('option:selected');
        if (selectedOption.data('unique') == 1 && $(this).val() !== "") {
            selectedUniques.push($(this).val());
        }
    });
    $('.position-select').each(function() {
        let currentVal = $(this).val();
        $(this).find('option').each(function() {
            if ($(this).data('unique') == 1) {
                if (selectedUniques.includes($(this).val()) && $(this).val() !== currentVal) {
                    $(this).prop('disabled', true);
                } else {
                    $(this).prop('disabled', false);
                }
            }
        });
    });
};

window.removeApprovalFile = function() {
    Swal.fire({
        title: 'ยืนยันการลบไฟล์?',
        text: "คุณต้องการนำไฟล์เอกสารอนุมัติเดิมออกใช่หรือไม่?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash"></i> ใช่, ลบทิ้งเลย!',
        cancelButtonText: 'ยกเลิก'
    }).then(function(result) {
        if (result.isConfirmed || result.value) {
            $('#file-link-zone').fadeOut(300);
            $('#remove_approval_file').val('1');
            Swal.fire({
                icon: 'success',
                title: 'นำไฟล์ออกแล้ว!',
                text: 'กรุณากดปุ่ม "บันทึก & ถัดไป" ด้านล่างเพื่อยืนยันการลบไฟล์ออกจากระบบ',
                confirmButtonColor: '#28a745',
                confirmButtonText: 'เข้าใจแล้ว'
            });
        }
    });
};

// =========================================
// เมื่อเอกสารพร้อม (Document Ready) โหลดทั้งหมด
// =========================================
$(document).ready(function() {
    // โหลดพื้นฐาน
    $('.select2-multiple').select2({ width: '100%', placeholder: "คลิกเพื่อเลือกข้อมูล" });
    flatpickr(".datepicker", { dateFormat: "Y-m-d", altInput: true, altFormat: "d/m/Y", locale: "th" });

    $('a[data-toggle="pill"], .wizard-nav .nav-link').on('shown.bs.tab click', function(e) {
        let targetTab = $(this).attr("href").replace('#', '');
        let currentUrl = new URL(window.location.href);
        currentUrl.searchParams.set('tab', targetTab);
        window.history.replaceState({}, '', currentUrl);
    });

    // 🌟 ดักจับ Tab 1
    $('#btn-submit-tab1').click(function(e) {
        e.preventDefault();
        let form = $(this).closest('form')[0];
        if (!form.checkValidity()) { form.reportValidity(); return; }

        Swal.fire({
            title: 'ยืนยันการบันทึก?',
            text: "คุณตรวจสอบข้อมูลพื้นฐานครบถ้วนแล้วใช่หรือไม่?",
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#007bff',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'บันทึก & ไปต่อ',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });

    // 🌟 ดักจับ Tab 2
    initSelect2Customer($('.select2-customer'));
    initSelect2External($('.select2-external'));
    $('.select2-staff').select2({ width: '100%', placeholder: "-- ค้นหาชื่อบุคลากรในคณะ --" });

    // AJAX Tab 2 - Target Group
    $('#btn-save-new-target-group').click(function() {
        let name_th = $('#new_target_group_name_th').val();
        let name_en = $('#new_target_group_name_en').val();
        if (name_th.trim() === '' || name_en.trim() === '') {
            Swal.fire({ icon: 'warning', title: 'ข้อมูลไม่ครบถ้วน', text: 'กรุณากรอก "ชื่อกลุ่มเป้าหมาย" ให้ครบ' }); return;
        }

        let btn = $(this); let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...').prop('disabled', true);

        $.ajax({
            url: window.ROUTES.storeTargetGroup,
            type: "POST",
            data: {
                _token: window.ROUTES.csrfToken,
                parent_id: $('#new_target_group_parent_id').val(),
                name_th: name_th,
                name_en: name_en,
                group_type: $('#new_target_group_group_type').val(),
                description: $('#new_target_group_description').val(),
                is_active: $('#new_target_group_is_active').is(':checked') ? 1 : 0
            },
            success: function(response) {
                if (response.success) {
                    $('.select2-customer').each(function() {
                        if ($(this).find("option[value='" + response.id + "']").length === 0) {
                            let displayName = response.full_path ? response.full_path : response.name_th;
                            $(this).append(new Option(displayName, response.id, false, false));
                        }
                    });
                    if (activeCustomerSelectBox) activeCustomerSelectBox.val(response.id).trigger('change');
                    $('#modalNewTargetGroup').modal('hide');
                    btn.html(originalText).prop('disabled', false);
                    Swal.fire({ icon: 'success', title: 'สำเร็จ!', text: 'เพิ่มกลุ่มเป้าหมายเรียบร้อยแล้ว', showConfirmButton: false, timer: 1500 });
                } else {
                    btn.html(originalText).prop('disabled', false);
                    Swal.fire({ icon: 'error', title: 'ไม่สามารถบันทึกได้!', text: response.message });
                }
            },
            error: function(xhr) {
                btn.html(originalText).prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'ระบบผิดพลาด!', text: 'ไม่สามารถเชื่อมต่อฐานข้อมูลได้' });
            }
        });
    });

    // AJAX Tab 2 - External
    $('#btn-save-new-external').click(function() {
        let prefix_id = $('#new_ext_prefix_id').val();
        let firstname = $('#new_ext_firstname').val();
        let lastname = $('#new_ext_lastname').val();
        let department = $('#new_ext_department').val();

        if (!prefix_id || !firstname || !lastname || !department) {
            Swal.fire({ icon: 'warning', title: 'แจ้งเตือน', text: 'กรุณากรอกข้อมูลที่มี * ให้ครบถ้วน' }); return;
        }

        let btn = $(this); let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...').prop('disabled', true);

        $.ajax({
            url: window.ROUTES.storeExternal,
            type: "POST",
            data: {
                _token: window.ROUTES.csrfToken,
                prefix_id: prefix_id, firstname: firstname, lastname: lastname, department: department,
                phone: $('#new_ext_phone').val(), email: $('#new_ext_email').val(), description: $('#new_ext_description').val()
            },
            success: function(response) {
                if (response.success) {
                    $('.select2-external').each(function() {
                        if ($(this).find("option[value='" + response.id + "']").length === 0) {
                            $(this).append(new Option(response.fullname, response.id, false, false));
                        }
                    });
                    if (activeExternalSelectBox) activeExternalSelectBox.val(response.id).trigger('change');
                    $('#modalNewExternal').modal('hide');
                    btn.html(originalText).prop('disabled', false);
                    Swal.fire({ icon: 'success', title: 'สำเร็จ!', showConfirmButton: false, timer: 1500 });
                }
            },
            error: function() {
                btn.html(originalText).prop('disabled', false);
                Swal.fire({ icon: 'error', title: 'ผิดพลาด!', text: 'ไม่สามารถบันทึกได้' });
            }
        });
    });

    // ปุ่ม เพิ่ม/ลบ แถว Tab 2
    $('#btn-add-target').click(function() {
        let newRow = $('#table-target-group tbody tr:first').clone();
        newRow.find('.select2-container').remove();
        newRow.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id aria-hidden tabindex').show();
        newRow.find('option').removeAttr('data-select2-id');
        newRow.find('input, select').val('');
        newRow.find('.btn-remove-row').prop('disabled', false).attr('title', 'ลบข้อมูล');
        newRow.hide().appendTo('#table-target-group tbody').fadeIn(200);
        initSelect2Customer(newRow.find('.select2-customer'));
    });

    $('#btn-add-committee').click(function() {
        let newRow = $('#table-committee tbody tr:first').clone();
        newRow.find('.btn-remove-row').prop('disabled', false);
        newRow.find('.select2-container').remove();
        newRow.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id aria-hidden tabindex').show();
        newRow.find('option').removeAttr('data-select2-id');
        newRow.find('input, select').val('');
        newRow.find('.member-type-select').val('1');
        newRow.find('.external-zone').hide();
        newRow.find('.internal-zone').show();
        newRow.find('.select2-staff').prop('required', true);
        newRow.find('.select2-external').prop('required', false);
        newRow.hide().appendTo('#table-committee tbody').fadeIn(200);
        newRow.find('.select2-staff').select2({ width: '100%', placeholder: "-- ค้นหาชื่อบุคลากรในคณะ --" });
        initSelect2External(newRow.find('.select2-external'));
        checkUniquePositions();
    });

    $('#btn-add-course').click(function() {
        let newCourseRow = `<div class="input-group mb-2 course-row" style="display:none;"><input type="text" name="course_names[]" class="form-control" required placeholder="ระบุชื่อหลักสูตร..."><div class="input-group-append"><button class="btn btn-outline-danger btn-remove-course" type="button"><i class="fas fa-trash"></i></button></div></div>`;
        $('#course-names-container').append(newCourseRow);
        $('#course-names-container .course-row:last').fadeIn(200);
    });

    $('#course-names-container').on('click', '.btn-remove-course', function() {
        if ($('.course-row').length > 1) {
            $(this).closest('.course-row').fadeOut(200, function() { $(this).remove(); });
        }
    });

    $('#table-committee').on('change', '.member-type-select', function() {
        let tr = $(this).closest('tr');
        if ($(this).val() === '1') {
            tr.find('.external-zone').hide(); tr.find('.internal-zone').show();
            tr.find('.select2-staff').prop('required', true); tr.find('.select2-external').prop('required', false).val('').trigger('change');
        } else {
            tr.find('.internal-zone').hide(); tr.find('.external-zone').show();
            tr.find('.select2-staff').prop('required', false).val('').trigger('change'); tr.find('.select2-external').prop('required', true);
        }
    });

    $('#table-committee').on('change', '.position-select', function() { checkUniquePositions(); });

    $('#table-target-group, #table-committee').on('click', '.btn-remove-row', function() {
        let tbody = $(this).closest('tbody'); let tr = $(this).closest('tr');
        if (tbody.find('tr').length > 1) {
            tr.fadeOut(200, function() { $(this).remove(); checkUniquePositions(); });
        } else {
            Swal.fire({ icon: 'warning', title: 'ลบไม่ได้ครับ!', text: 'ต้องมีข้อมูลอย่างน้อย 1 แถวเสมอ' });
        }
    });

    $('#btn-submit-tab2').click(function(e) {
        e.preventDefault(); let form = $(this).closest('form')[0];
        if (!form.checkValidity()) { form.reportValidity(); return; }
        Swal.fire({
            title: 'ยืนยันการบันทึก?', text: "บันทึกข้อมูลการจัดกิจกรรมและคณะทำงาน ใช่หรือไม่?", icon: 'question',
            showCancelButton: true, confirmButtonColor: '#007bff', cancelButtonColor: '#6c757d', confirmButtonText: 'บันทึก & ไปต่อ', cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });

    // 🌟 ดักจับ Tab 3 (Schedules)
    $('#btn-create-schedule').click(function() {
        $('#current_schedule_id').val(''); let newId = Date.now();
        let templateHtml = $('#template-schedule-block').html().replace(/{ID}/g, newId);
        $('#editor-form-container').html(templateHtml); $('#editor-title').text('เพิ่มกิจกรรมใหม่');
        $('#editor-form-container .date-input').flatpickr({ dateFormat: "Y-m-d", altInput: true, altFormat: "d/m/Y", locale: "th" });
        $('#editor-form-container .time-start, #editor-form-container .time-end').flatpickr({ enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true });
        $('#editor-form-container .select2-staff-temp').removeClass('select2-staff-temp').addClass('select2-staff').select2({ width: '100%', placeholder: "-- ค้นหา --" });
        initSelect2External($('#editor-form-container .select2-external-temp').removeClass('select2-external-temp').addClass('select2-external'));
        $('#editor-form-container .select2-province-temp').removeClass('select2-province-temp').addClass('select2-province').select2({ width: '100%' });
        $('#schedule-summary-zone').hide(); $('#form-schedule').fadeIn(300);
    });

    $('#btn-cancel-editor').click(function() {
        $('#editor-form-container').html(''); $('#form-schedule').hide(); $('#schedule-summary-zone').fadeIn(300);
    });

    function reindexScheduleTable() {
        $('#table-schedule-summary tbody tr.sum-row').each(function(index) { $(this).find('.row-index').text(index + 1); });
    }

    function sortScheduleTable() {
        let tbody = $('#table-schedule-summary tbody'); let rows = tbody.find('tr.sum-row').get();
        rows.sort(function(a, b) {
            let dateA = $(a).attr('data-date') || ''; let timeA = $(a).attr('data-time') || '';
            let dateB = $(b).attr('data-date') || ''; let timeB = $(b).attr('data-time') || '';
            return (dateA + ' ' + timeA).localeCompare(dateB + ' ' + timeB);
        });
        $.each(rows, function(index, row) { tbody.append(row); });
        reindexScheduleTable();
    }

    $('#form-schedule').on('submit', function(e) {
        e.preventDefault();
        let formElement = this; let block = $('#editor-form-container .schedule-block');
        let id = block.data('id'); let date = block.find('.date-input').val(); let topic = block.find('.topic-input').val();
        let start = block.find('.time-start').val(); let end = block.find('.time-end').val();

        if (!date || !topic || !start || !end) {
            Swal.fire({ icon: 'warning', title: 'ข้อมูลไม่ครบ', text: 'กรุณาระบุ วันที่, เวลาเริ่ม, เวลาสิ้นสุด และ หัวข้อ ให้ครบถ้วน' }); return;
        }
        if (end <= start) { Swal.fire({ icon: 'error', title: 'เวลาไม่ถูกต้อง!', text: 'เวลาสิ้นสุดกิจกรรม จะต้องมากกว่าเวลาเริ่มต้นครับ' }); return; }

        let isEditing = $(`#sum-tr-${id}`).length > 0; let isOverlap = false; let overlapTopic = '';
        let lastDate = null; let lastDisplayDate = null;

        $('#schedule-vault-zone .schedule-block').each(function() {
            let vaultId = $(this).data('id'); let vDate = $(this).find('.date-input').val();
            let vStart = $(this).find('.time-start').val(); let vEnd = $(this).find('.time-end').val(); let vTopic = $(this).find('.topic-input').val();
            lastDate = vDate; lastDisplayDate = $(this).find('.date-input').next('.form-control').val() || vDate;

            if (vaultId != id && vDate === date) {
                if (start < vEnd && end > vStart) { isOverlap = true; overlapTopic = vTopic; }
            }
        });

        if (!isEditing && lastDate && date < lastDate) {
            Swal.fire({ icon: 'error', title: 'วันที่ลำดับผิดพลาด!', text: `วันที่กิจกรรม จะต้องไม่ย้อนหลัง (ต้องเป็นวันที่ ${lastDisplayDate} หรือหลังจากนั้น)` }); return;
        }

        if (isOverlap) {
            Swal.fire({
                title: 'เวลาจัดกิจกรรมทับซ้อน!', html: `ช่วงเวลานี้มีกิจกรรม <b>"${overlapTopic}"</b> อยู่แล้ว<br>คุณต้องการบันทึกเป็น <b>กิจกรรมคู่ขนาน (ห้องย่อย)</b> ใช่หรือไม่?`,
                icon: 'warning', showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: '<i class="fas fa-save"></i> ใช่, บันทึกเลย!', cancelButtonText: 'ยกเลิก'
            }).then((result) => { if (result.isConfirmed || result.value) executeScheduleAjax(formElement, block, id, date, topic, start, end, isEditing); });
        } else {
            executeScheduleAjax(formElement, block, id, date, topic, start, end, isEditing);
        }
    });

    function executeScheduleAjax(formElement, block, id, date, topic, start, end, isEditing) {
        let formData = new FormData(formElement); formData.append('_token', window.ROUTES.csrfToken);
        Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        $.ajax({
            url: window.ROUTES.storeSchedule, type: "POST", data: formData, contentType: false, processData: false,
            success: function(response) {
                if (response.success) {
                    let docsHtml = '';
                    if (response.docs && response.docs.length > 0) {
                        response.docs.forEach(function(d) { docsHtml += `<div class="mt-1"><small class="text-primary"><i class="fas fa-paperclip"></i> <a href="${window.ROUTES.storageUrl}/${d.file_path}" target="_blank"><u>${d.document_name}</u></a></small></div>`; });
                    }

                    let membersHtml = '';
                    block.find('.member-row').each(function() {
                        let type = $(this).find('.schedule-member-type').val();
                        let name = type === '1' ? $(this).find('.select2-staff option:selected').text() : $(this).find('.select2-external option:selected').text();
                        name = $.trim(name.replace('-- ค้นหา --', ''));
                        let position = $(this).find('select[name="members[training_position_id][]"] option:selected').text();
                        position = $.trim(position.replace('-- เลือก --', ''));
                        let posText = position ? `<b>${position}</b>: ` : '';
                        if (name) membersHtml += `<div class="mb-1"><i class="fas fa-user-tie text-secondary mr-1"></i> ${posText}${name}</div>`;
                    });
                    if (!membersHtml) membersHtml = '<span class="text-muted">-</span>';

                    let locationsHtml = '';
                    block.find('.location-row').each(function() {
                        let locName = $(this).find('.location-name-input').val();
                        let province = $(this).find('.select2-province option:selected').text();
                        province = $.trim(province.replace('-- เลือก --', ''));
                        let provText = province ? ` (${province})` : '';
                        if (locName) locationsHtml += `<div class="mb-1"><i class="fas fa-map-marker-alt text-danger mr-1"></i> ${locName}${provText}</div>`;
                    });
                    if (!locationsHtml) locationsHtml = '<span class="text-muted">-</span>';

                    let realDbId = response.schedule_id ? response.schedule_id : id;
                    let trHtml = `
                        <tr id="sum-tr-${realDbId}" class="sum-row" data-date="${date}" data-time="${start}">
                            <td class="text-center align-middle font-weight-bold row-index"></td>
                            <td class="text-center align-middle">${block.find('.date-input').next('.form-control').val() || date}<br><small class="text-muted">${start} - ${end} น.</small></td>
                            <td class="align-middle">${topic.replace(/\n/g, '<br>')}${docsHtml}</td>
                            <td class="align-middle">${membersHtml}</td>
                            <td class="align-middle">${locationsHtml}</td>
                            <td class="text-center align-middle">
                                <button type="button" class="btn btn-sm btn-outline-info btn-edit-schedule mb-1" data-id="${realDbId}" title="แก้ไขกิจกรรม"><i class="fas fa-edit"></i></button>
                                <button type="button" class="btn btn-sm btn-outline-danger btn-delete-schedule mb-1" data-id="${realDbId}" title="ลบกิจกรรม"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>`;

                    $('#empty-schedule-row').remove();
                    if (isEditing) { $(`#sum-tr-${id}`).replaceWith(trHtml); } else { $('#table-schedule-summary tbody').append(trHtml); }

                    sortScheduleTable(); $('#schedule-vault-zone').append(block); $('#form-schedule').hide(); $('#schedule-summary-zone').fadeIn(300);
                    Swal.fire({ icon: 'success', title: 'บันทึกสำเร็จ!', text: 'ข้อมูลกิจกรรมถูกบันทึกแล้ว', timer: 1500, showConfirmButton: false });
                } else {
                    Swal.fire({ icon: 'error', title: 'ผิดพลาด!', text: response.message });
                }
            },
            error: function() { Swal.fire({ icon: 'error', title: 'พัง!', text: 'ไม่สามารถบันทึกข้อมูลได้' }); }
        });
    }

    $(document).on('click', '.btn-edit-schedule', function() {
        let id = $(this).data('id');
        Swal.fire({ title: 'กำลังดึงข้อมูล...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });

        $.ajax({
            url: window.ROUTES.editSchedule + "/" + id + "/edit-ajax", type: 'GET',
            success: function(res) {
                if (res.success) {
                    Swal.close();
                    let templateHtml = $('#template-schedule-block').html().replace(/{ID}/g, id);
                    let container = $('#editor-form-container'); container.html(templateHtml);
                    $('#current_schedule_id').val(id); let sch = res.schedule;
                    container.find('.topic-input').val(sch.topic);
                    container.find('.date-input').flatpickr({ dateFormat: "Y-m-d", altInput: true, altFormat: "d/m/Y", locale: "th", defaultDate: sch.schedule_date });

                    let startTime = (sch.start_time || '').match(/\d{2}:\d{2}/) ? (sch.start_time).match(/\d{2}:\d{2}/)[0] : '';
                    let endTime = (sch.end_time || '').match(/\d{2}:\d{2}/) ? (sch.end_time).match(/\d{2}:\d{2}/)[0] : '';
                    let dateOnly = sch.schedule_date.split(' ')[0]; let dParts = dateOnly.split('-');
                    let niceDate = dParts.length === 3 ? `${dParts[2]}/${dParts[1]}/${dParts[0]}` : sch.schedule_date;

                    $('#editor-title').text(`แก้ไขกิจกรรมวันที่ ${niceDate} เวลา ${startTime} - ${endTime} น.`);

                    container.find('.time-start').flatpickr({ enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, defaultDate: startTime });
                    container.find('.time-end').flatpickr({ enableTime: true, noCalendar: true, dateFormat: "H:i", time_24hr: true, defaultDate: endTime });

                    let memberTbody = container.find('.member-table tbody'); let memberRowTemp = memberTbody.find('tr:first').clone(); memberTbody.empty();
                    if (res.members && res.members.length > 0) {
                        res.members.forEach(mem => {
                            let row = memberRowTemp.clone();
                            row.find('.select2-container').remove(); row.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id aria-hidden tabindex').show(); row.find('option').removeAttr('data-select2-id');
                            row.find('.schedule-member-type').val(mem.member_type); row.find('select[name="members[training_position_id][]"]').val(mem.training_position_id);
                            memberTbody.append(row);

                            let staffSelect = row.find('.select2-staff-temp').removeClass('select2-staff-temp').addClass('select2-staff').select2({ width: '100%', placeholder: "-- ค้นหา --" });
                            let extSelect = row.find('.select2-external-temp').removeClass('select2-external-temp').addClass('select2-external'); initSelect2External(extSelect);

                            if (mem.member_type == '1') {
                                row.find('.internal-zone').show(); row.find('.external-zone').hide();
                                let dbPersonnelId = parseInt(mem.personnel_id, 10); let optionToSelect = '';
                                staffSelect.find('option').each(function() {
                                    if ($(this).val() && parseInt($(this).val(), 10) === dbPersonnelId) { optionToSelect = $(this).val(); return false; }
                                });
                                staffSelect.val(optionToSelect).trigger('change');
                            } else {
                                row.find('.internal-zone').hide(); row.find('.external-zone').show(); extSelect.val(mem.external_id).trigger('change');
                            }
                        });
                    } else {
                        memberTbody.append(memberRowTemp);
                        memberTbody.find('.select2-staff-temp').removeClass('select2-staff-temp').addClass('select2-staff').select2({ width: '100%', placeholder: "-- ค้นหา --" });
                        initSelect2External(memberTbody.find('.select2-external-temp').removeClass('select2-external-temp').addClass('select2-external'));
                    }

                    let locTbody = container.find('.location-table tbody'); let locRowTemp = locTbody.find('tr:first').clone(); locTbody.empty();
                    if (res.locations && res.locations.length > 0) {
                        res.locations.forEach(loc => {
                            let row = locRowTemp.clone();
                            row.find('.select2-container').remove(); row.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id aria-hidden tabindex').show(); row.find('option').removeAttr('data-select2-id');
                            row.find('.location-name-input').val(loc.location_name); row.find('input[name="locations[latitude][]"]').val(loc.latitude); row.find('input[name="locations[longitude][]"]').val(loc.longitude); row.find('.select2-province-temp').val(loc.province_id);
                            locTbody.append(row);
                            row.find('.select2-province-temp').removeClass('select2-province-temp').addClass('select2-province').select2({ width: '100%' });
                        });
                    } else {
                        locTbody.append(locRowTemp); locTbody.find('.select2-province-temp').removeClass('select2-province-temp').addClass('select2-province').select2({ width: '100%' });
                    }

                    let docTbody = container.find('.document-table tbody'); let docRowTemp = docTbody.find('tr:first').clone(); docTbody.empty();
                    if (res.docs && res.docs.length > 0) {
                        res.docs.forEach(doc => {
                            let row = docRowTemp.clone();
                            row.find('.doc-old-id').val(doc.id); row.find('input[type="text"]').val(doc.document_name);
                            let fileUrl = window.ROUTES.storageUrl + "/" + doc.file_path;
                            row.find('td:eq(1)').append(`<div class="text-success mt-1 small existing-file-notice"><i class="fas fa-check-circle"></i> มีไฟล์เดิม: <a href="${fileUrl}" target="_blank" class="text-primary font-weight-bold"><u>คลิกเพื่อดูไฟล์</u></a></div>`);
                            docTbody.append(row);
                        });
                    } else {
                        docTbody.append(docRowTemp);
                    }

                    $('#schedule-summary-zone').hide(); $('#form-schedule').fadeIn(300);
                } else { Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: res.message }); }
            },
            error: function() { Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: 'ไม่สามารถดึงข้อมูลได้ (เช็ค F12)' }); }
        });
    });

    $(document).on('click', '.btn-delete-schedule', function() {
        let id = $(this).data('id');
        Swal.fire({
            title: 'ลบกิจกรรมนี้?', text: 'ข้อมูลและไฟล์แนบทั้งหมดของกิจกรรมนี้ จะถูกลบอย่างถาวร!', icon: 'warning',
            showCancelButton: true, confirmButtonColor: '#dc3545', cancelButtonColor: '#6c757d', confirmButtonText: '<i class="fas fa-trash"></i> ใช่, ลบทิ้งเลย!', cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({ title: 'กำลังลบ...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                $.ajax({
                    url: window.ROUTES.deleteSchedule + "/" + id + "/delete-ajax", type: 'DELETE', data: { _token: window.ROUTES.csrfToken },
                    success: function(res) {
                        if (res.success) {
                            Swal.fire({ icon: 'success', title: 'ลบสำเร็จ!', text: res.message, timer: 1500, showConfirmButton: false });
                            $(`#sum-tr-${id}`).fadeOut(300, function() {
                                $(this).remove(); reindexScheduleTable();
                                if ($('#table-schedule-summary tbody tr.sum-row').length === 0) { $('#table-schedule-summary tbody').html(`<tr id="empty-schedule-row"><td colspan="6" class="text-center text-muted py-4"><i class="fas fa-calendar-times fa-2x mb-2 text-light"></i><br>ยังไม่มีข้อมูลกิจกรรม กรุณากดปุ่ม <b>"เพิ่มกิจกรรมใหม่"</b></td></tr>`); }
                            });
                            $(`#schedule-vault-zone .schedule-block[data-id="${id}"]`).remove();
                        } else { Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: res.message }); }
                    },
                    error: function() { Swal.fire({ icon: 'error', title: 'ผิดพลาด', text: 'ลบไม่ได้' }); }
                });
            }
        });
    });

    $(document).on('click', '.btn-add-member', function() {
        let tbody = $(this).closest('.col-md-6').find('.member-table tbody'); let newRow = tbody.find('tr:first').clone();
        newRow.find('.select2-container').remove(); newRow.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id aria-hidden tabindex').show(); newRow.find('option').removeAttr('data-select2-id');
        newRow.find('select').val('').trigger('change'); newRow.find('.schedule-member-type').val('1');
        newRow.find('.internal-zone').show(); newRow.find('.external-zone').hide();
        newRow.hide().appendTo(tbody).fadeIn(200);
        newRow.find('.select2-staff').select2({ width: '100%', placeholder: "-- ค้นหา --" }); initSelect2External(newRow.find('.select2-external'));
    });

    $(document).on('change', '.schedule-member-type', function() {
        let tr = $(this).closest('tr');
        if ($(this).val() === '1') {
            tr.find('.external-zone').hide(); tr.find('.internal-zone').show(); tr.find('.select2-external').val('').trigger('change');
        } else {
            tr.find('.internal-zone').hide(); tr.find('.external-zone').show(); tr.find('.select2-staff').val('').trigger('change');
        }
    });

    $(document).on('click', '.btn-add-location', function() {
        let tbody = $(this).closest('.col-md-6').find('.location-table tbody'); let newRow = tbody.find('tr:first').clone();
        newRow.find('.select2-container').remove(); newRow.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id aria-hidden tabindex').show(); newRow.find('option').removeAttr('data-select2-id');
        newRow.find('input').val(''); newRow.find('select').val('').trigger('change');
        newRow.hide().appendTo(tbody).fadeIn(200); newRow.find('.select2-province').select2({ width: '100%' });
    });

    $(document).on('click', '.btn-add-document', function() {
        let tbody = $(this).closest('.col-md-12').find('.document-table tbody'); let newRow = tbody.find('tr:first').clone();
        newRow.find('.doc-old-id').val(''); newRow.find('input[type="text"]').val(''); newRow.find('input[type="file"]').val(''); newRow.find('.existing-file-notice').remove();
        newRow.hide().appendTo(tbody).fadeIn(200);
    });

    $(document).on('click', '.btn-remove-subrow', function() {
        let tbody = $(this).closest('tbody');
        if (tbody.find('tr').length > 1) { $(this).closest('tr').remove(); }
        else { $(this).closest('tr').find('input, select').val(''); $(this).closest('tr').find('.existing-file-notice').remove(); }
    });

    $('#btn-submit-tab3').click(function(e) {
        e.preventDefault();
        Swal.fire({
            title: 'ยืนยันการดำเนินการ?', text: "คุณจัดการกำหนดการจัดกิจกรรมเรียบร้อยแล้วใช่หรือไม่?", icon: 'info',
            showCancelButton: true, confirmButtonColor: '#007bff', cancelButtonColor: '#6c757d', confirmButtonText: 'ไปหน้าถัดไป', cancelButtonText: 'ยังไม่เสร็จ'
        }).then((result) => { if (result.isConfirmed || result.value) { $('.wizard-nav a[href="#tab4"]').tab('show'); } });
    });

   // 🌟 ดักจับ Tab 4 (งบประมาณ - แบบใหม่เหมือน Contracts)
    // ----------------------------------------------------
    // Number Format Helpers
    // ----------------------------------------------------
    window.formatNumber = function (num) {
        if (!num) return "";
        let parts = num.toString().split(".");
        parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ",");
        return parts.join(".");
    };

    window.unformatNumber = function (num) {
        if (!num) return 0;
        return parseFloat(num.toString().replace(/,/g, "")) || 0;
    };

    $(document).on("keyup change blur", ".format-number-budget, .format-expense, .format-remuneration, .format-summary", function () {
        let val = $(this).val().replace(/,/g, "");
        if (val !== "" && !isNaN(val)) {
            $(this).val(window.formatNumber(val));
        }
    });

    // ----------------------------------------------------
    // คำนวณรายรับ (Income)
    // ----------------------------------------------------
    window.calculateIncome = function () {
        let grandTotal = 0;
        $("#table-budget-incomes tbody tr:not(.template-row)").each(function () {
            let unitCost = window.unformatNumber($(this).find(".income-unit-cost").val());
            let quantity = window.unformatNumber($(this).find(".income-quantity").val());
            let total = unitCost * quantity;

            if (total > 0) {
                $(this).find(".income-total-amount").val(window.formatNumber(total.toFixed(2)));
                grandTotal += total;
            } else {
                $(this).find(".income-total-amount").val("");
            }
        });
        $("#income-grand-total").val(grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "");
        $('input[name="total_budget_summary"]').val(grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00");
        window.calculateSummary();
    };

    // ----------------------------------------------------
    // คำนวณค่าดำเนินการ (Expense)
    // ----------------------------------------------------
    window.calculateExpense = function () {
        let grandTotal = 0;
        $("#table-budget-expenses tbody tr:not(.template-row)").each(function () {
            let costPerUnit = window.unformatNumber($(this).find(".expense-cost").val());
            let factor1 = window.unformatNumber($(this).find(".expense-factor1").val()) || 1;
            let factor2 = window.unformatNumber($(this).find(".expense-factor2").val()) || 1;
            let total = costPerUnit * factor1 * factor2;

            if (total > 0) {
                $(this).find(".expense-total-amount").val(window.formatNumber(total.toFixed(2)));
                grandTotal += total;
            } else {
                $(this).find(".expense-total-amount").val("");
            }
        });
        $("#expense-grand-total").val(grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00");
        $('input[name="operation_fee"]').val(grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00");
        window.calculateSummary();
    };

    // ----------------------------------------------------
    // คำนวณค่าตอบแทน (Remuneration)
    // ----------------------------------------------------
    window.calculateRemuneration = function () {
        let grandTotal = 0;
        $("#table-budget-remuneration tbody tr:not(.template-row)").each(function () {
            let costPerUnit = window.unformatNumber($(this).find(".remuneration-cost").val());
            let factor1 = window.unformatNumber($(this).find(".remuneration-factor1").val()) || 1;
            let factor2 = window.unformatNumber($(this).find(".remuneration-factor2").val()) || 1;
            let total = costPerUnit * factor1 * factor2;

            if (total > 0) {
                $(this).find(".remuneration-total-amount").val(window.formatNumber(total.toFixed(2)));
                grandTotal += total;
            } else {
                $(this).find(".remuneration-total-amount").val("");
            }
        });
        $("#remuneration-grand-total").val(grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00");
        $('input[name="remuneration_fee"]').val(grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00");
        window.calculateSummary();
    };

    // ----------------------------------------------------
    // คำนวณค่าธรรมเนียมโครงการ 15% (Summary)
    // ----------------------------------------------------
    window.calculateSummary = function () {
        let totalProjectBudget = window.unformatNumber($('input[name="total_budget_summary"]').val());
        let totalRemuneration = window.unformatNumber($('input[name="remuneration_fee"]').val());
        let totalOperation = window.unformatNumber($('input[name="operation_fee"]').val());

        let sumExpenses = totalRemuneration + totalOperation;
        let maxExpense = (totalProjectBudget * 100) / 115;
        let serviceFeeAmountLabel = (totalProjectBudget * 15) / 115;

        $("#max_expense_label").text(window.formatNumber(maxExpense.toFixed(2)) + " บาท");
        $("#service_fee_label").text(window.formatNumber(serviceFeeAmountLabel.toFixed(2)) + " บาท");

        let serviceFeePercent = 115 - (maxExpense > 0 ? (sumExpenses / maxExpense) * 100 : 0);
        if (serviceFeePercent < 0) serviceFeePercent = 0;

        let allocDeptPercent = serviceFeePercent - 4.0;
        if (allocDeptPercent < 0) allocDeptPercent = 0;

        $('input[name="service_fee_percent"]').val(serviceFeePercent.toFixed(2));
        
        let serviceFeeAmount = totalProjectBudget - totalRemuneration - totalOperation;
        if (serviceFeeAmount < 0) serviceFeeAmount = 0;

        $('input[name="service_fee_amount"]').val(window.formatNumber(serviceFeeAmount.toFixed(2)));
        $('input[name="alloc_dept_percent"]').val(allocDeptPercent.toFixed(2));

        let allocUniAmount = maxExpense * (1.5 / 100);
        let allocCampusAmount = maxExpense * (2.5 / 100);
        let allocDeptAmount = maxExpense * (allocDeptPercent / 100);

        $('input[name="alloc_uni_amount"]').val(window.formatNumber(allocUniAmount.toFixed(2)));
        $('input[name="alloc_campus_amount"]').val(window.formatNumber(allocCampusAmount.toFixed(2)));
        $('input[name="alloc_dept_amount"]').val(window.formatNumber(allocDeptAmount.toFixed(2)));

        window.calculateSubDeptAllocations();
    };

    window.calculateSubDeptAllocations = function () {
        let deptPercent = parseFloat($('input[name="alloc_dept_percent"]').val()) || 0;
        let deptAmount = window.unformatNumber($('input[name="alloc_dept_amount"]').val());

        let fundResearchPercent = deptPercent * 0.05;
        let facultyAndCenterPercent = (deptPercent - fundResearchPercent) / 2;

        $('input[name="fund_research_percent"]').val(fundResearchPercent.toFixed(3));
        $('input[name="faculty_percent"]').val(facultyAndCenterPercent.toFixed(3));
        $('input[name="center_percent"]').val(facultyAndCenterPercent.toFixed(3));

        let fundResearchAmount = deptAmount * 0.05;
        let facultyAndCenterAmount = (deptAmount - fundResearchAmount) / 2;

        $('input[name="fund_research_amount"]').val(window.formatNumber(fundResearchAmount.toFixed(2)));
        $('input[name="faculty_amount"]').val(window.formatNumber(facultyAndCenterAmount.toFixed(2)));
        $('input[name="center_amount"]').val(window.formatNumber(facultyAndCenterAmount.toFixed(2)));
    };

    $(document).on("input", 'input[name="alloc_dept_percent"], input[name="alloc_dept_amount"]', function () {
        window.calculateSubDeptAllocations();
    });

    // ----------------------------------------------------
    // จัดการการพิมพ์ในตาราง
    // ----------------------------------------------------
    $("#table-budget-incomes").on("keyup change input", ".income-unit-cost, .income-quantity", window.calculateIncome);
    $("#table-budget-expenses").on("keyup change input", ".expense-cost, .expense-factor1, .expense-factor2", window.calculateExpense);
    $("#table-budget-remuneration").on("keyup change input", ".remuneration-cost, .remuneration-factor1, .remuneration-factor2", window.calculateRemuneration);

    // ----------------------------------------------------
    // ปุ่มกดเพิ่มแถว
    // ----------------------------------------------------
    function updateRowIndex(tableId) {
        $(tableId + ' tbody tr:not(.template-row)').each(function(index) { $(this).find('.row-index').text(index + 1); });
    }

    $('#btn-add-income').click(function() {
        let template = $('#table-budget-incomes .template-row').clone(); template.removeClass('template-row d-none');
        template.find('input[type="text"], input[type="number"]').val(''); template.find('select').val('').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').prop("required", true); template.find('.select2-container').remove();
        $('#table-budget-incomes tbody').append(template); template.find('.select2-basic').select2({ width: '100%', placeholder: "-- เลือก --" }); updateRowIndex('#table-budget-incomes');
    });

    $('#btn-add-expense').click(function() {
        let template = $('#table-budget-expenses .template-row').clone(); template.removeClass('template-row d-none');
        template.find('input[type="text"], input[type="number"]').val(''); template.find('select').val('').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').prop("required", true); template.find('.select2-container').remove();
        let uniqueId = 'avg_exp_' + Date.now() + Math.floor(Math.random() * 1000);
        template.find('.can-average-switch').attr('id', uniqueId).prop('checked', true); template.find('label.custom-control-label').attr('for', uniqueId); template.find('.can-average-hidden').val('1');
        $('#table-budget-expenses tbody').append(template); template.find('.select2-basic').select2({ width: '100%', placeholder: "-- เลือก --" }); updateRowIndex('#table-budget-expenses');
    });

    $('#btn-add-remuneration').click(function() {
        let template = $('#table-budget-remuneration .template-row').clone(); template.removeClass('template-row d-none');
        template.find('input[type="text"], input[type="number"]').val(''); template.find('select').val('').removeClass('select2-hidden-accessible').removeAttr('data-select2-id').prop("required", true); template.find('.select2-container').remove();
        let uniqueId = 'avg_remun_' + Date.now() + Math.floor(Math.random() * 1000);
        template.find('.can-average-switch').attr('id', uniqueId).prop('checked', true); template.find('label.custom-control-label').attr('for', uniqueId); template.find('.can-average-hidden').val('1');
        $('#table-budget-remuneration tbody').append(template); template.find('.select2-basic').select2({ width: '100%', placeholder: "-- เลือก --" }); updateRowIndex('#table-budget-remuneration');
    });

    $('#table-budget-incomes, #table-budget-expenses, #table-budget-remuneration').on('click', '.btn-remove-row', function() {
        let tr = $(this).closest('tr'); if (tr.hasClass('template-row')) return;
        let tableId = '#' + tr.closest('table').attr('id');
        tr.fadeOut(200, function() { 
            $(this).remove(); 
            updateRowIndex(tableId); 
            if (tableId === '#table-budget-incomes') window.calculateIncome(); 
            if (tableId === '#table-budget-expenses') window.calculateExpense(); 
            if (tableId === '#table-budget-remuneration') window.calculateRemuneration(); 
        });
    });

    $(document).on('change', '.can-average-switch', function() { $(this).siblings('.can-average-hidden').val(this.checked ? '1' : '0'); });
    
    // ตั้งค่าเริ่มต้นตอนเปิดหน้า
    $('.select2-basic').select2({ width: '100%', placeholder: "-- เลือก --" });
    window.calculateIncome();
    window.calculateExpense();
    window.calculateRemuneration();

    // ----------------------------------------------------
    // ปุ่มยืนยันบันทึกข้อมูลงบประมาณ
    // ----------------------------------------------------
    $('#btn-submit-tab4').click(function(e) {
        e.preventDefault(); 
        let isValid = true; let firstInvalidElement = null;

        $('#table-budget-incomes tbody tr:not(.template-row), #table-budget-expenses tbody tr:not(.template-row), #table-budget-remuneration tbody tr:not(.template-row)').each(function() {
            $(this).find('input[required], select[required]').each(function() {
                if ($(this).val() === '' || $(this).val() === null) {
                    isValid = false; $(this).addClass('is-invalid');
                    if ($(this).hasClass('select2-hidden-accessible')) { $(this).next('.select2-container').find('.select2-selection').css('border', '1px solid #dc3545'); }
                    if (!firstInvalidElement) firstInvalidElement = $(this);
                } else {
                    $(this).removeClass('is-invalid');
                    if ($(this).hasClass('select2-hidden-accessible')) { $(this).next('.select2-container').find('.select2-selection').css('border', ''); }
                }
            });
        });

        if (!isValid) {
            Swal.fire({ icon: 'warning', title: 'ข้อมูลไม่ครบถ้วน!', text: 'กรุณาตรวจสอบและระบุข้อมูลให้ครบถ้วนครับ' });
            $('html, body').animate({ scrollTop: firstInvalidElement.offset().top - 150 }, 500); 
            return;
        }

        Swal.fire({
            title: 'ยืนยันการบันทึก?', text: "คุณตรวจสอบและต้องการบันทึกแผนงบประมาณใช่หรือไม่?", icon: 'question',
            showCancelButton: true, confirmButtonColor: '#007bff', cancelButtonColor: '#6c757d', confirmButtonText: '<i class="fas fa-save"></i> บันทึกข้อมูล', cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                $('.template-row').find('input, select').prop('disabled', true); // ปิดไม่ให้ template ถูกส่งไป
                
                // ถอดลูกน้ำออกจาก Input ก่อน Submit
                $('.format-number-budget, .format-expense, .format-remuneration, .format-summary').each(function() {
                    $(this).val(window.unformatNumber($(this).val()));
                });
                
                HTMLFormElement.prototype.submit.call(document.getElementById('form-tab4-budget'));
            }
        });
    });

    function calculateExpense() {
        let grandTotal = 0;
        $('#table-budget-expenses tbody tr:not(.template-row)').each(function() {
            let costPerUnit = parseFloat($(this).find('.expense-cost').val()) || 0;
            let f1_val = $(this).find('.expense-factor1').val(); let factor1 = (f1_val === '' || isNaN(f1_val)) ? 1 : parseFloat(f1_val);
            let f2_val = $(this).find('.expense-factor2').val(); let factor2 = (f2_val === '' || isNaN(f2_val)) ? 1 : parseFloat(f2_val);
            let total = costPerUnit * factor1 * factor2;
            if (total > 0) { $(this).find('.expense-total-amount').val(total.toFixed(2)); grandTotal += total; } else { $(this).find('.expense-total-amount').val(''); }
        });
        $('#expense-grand-total').val(grandTotal > 0 ? grandTotal.toFixed(2) : '');
    }

    function updateRowIndex(tableId) {
        $(tableId + ' tbody tr:not(.template-row)').each(function(index) { $(this).find('.row-index').text(index + 1); });
    }

    $('#table-budget-incomes').on('keyup change input', '.income-unit-cost, .income-quantity', calculateIncome);
    $('#table-budget-expenses').on('keyup change input', '.expense-cost, .expense-factor1, .expense-factor2', calculateExpense);

    $('#btn-add-income').click(function() {
        let template = $('#table-budget-incomes .template-row').clone(); template.removeClass('template-row d-none');
        template.find('input[type="text"], input[type="number"]').val(''); template.find('select').val('').removeClass('select2-hidden-accessible').removeAttr('data-select2-id'); template.find('.select2-container').remove();
        $('#table-budget-incomes tbody').append(template); template.find('.select2-basic').select2({ width: '100%', placeholder: "-- เลือก --" }); updateRowIndex('#table-budget-incomes');
    });

    $('#btn-add-expense').click(function() {
        let template = $('#table-budget-expenses .template-row').clone(); template.removeClass('template-row d-none');
        template.find('input[type="text"], input[type="number"]').val(''); template.find('select').val('').removeClass('select2-hidden-accessible').removeAttr('data-select2-id'); template.find('.select2-container').remove();
        let uniqueId = 'avg_' + Date.now() + Math.floor(Math.random() * 1000);
        template.find('.can-average-switch').attr('id', uniqueId).prop('checked', true); template.find('label.custom-control-label').attr('for', uniqueId); template.find('.can-average-hidden').val('1');
        $('#table-budget-expenses tbody').append(template); template.find('.select2-basic').select2({ width: '100%', placeholder: "-- เลือก --" }); updateRowIndex('#table-budget-expenses');
    });

    $('#table-budget-incomes, #table-budget-expenses').on('click', '.btn-remove-row', function() {
        let tr = $(this).closest('tr'); if (tr.hasClass('template-row')) return;
        let tableId = '#' + tr.closest('table').attr('id');
        tr.fadeOut(200, function() { $(this).remove(); updateRowIndex(tableId); if (tableId === '#table-budget-incomes') calculateIncome(); if (tableId === '#table-budget-expenses') calculateExpense(); });
    });

    $(document).on('change', '.can-average-switch', function() { $(this).siblings('.can-average-hidden').val(this.checked ? '1' : '0'); });
    $('.select2-basic').select2({ width: '100%', placeholder: "-- เลือก --" });
    calculateIncome(); calculateExpense();

    $('#btn-submit-tab4').click(function(e) {
        e.preventDefault(); let isValid = true; let firstInvalidElement = null;

        $('#table-budget-incomes tbody tr:not(.template-row), #table-budget-expenses tbody tr:not(.template-row)').each(function() {
            $(this).find('input[required], select[required]').each(function() {
                if ($(this).val() === '' || $(this).val() === null) {
                    isValid = false; $(this).addClass('is-invalid');
                    if ($(this).hasClass('select2-hidden-accessible')) { $(this).next('.select2-container').find('.select2-selection').css('border', '1px solid #dc3545'); }
                    if (!firstInvalidElement) firstInvalidElement = $(this);
                } else {
                    $(this).removeClass('is-invalid');
                    if ($(this).hasClass('select2-hidden-accessible')) { $(this).next('.select2-container').find('.select2-selection').css('border', ''); }
                }
            });
        });

        if (!isValid) {
            Swal.fire({ icon: 'warning', title: 'ข้อมูลไม่ครบถ้วน!', text: 'กรุณาตรวจสอบและระบุข้อมูลในช่องที่มีกรอบสีแดงให้ครบถ้วนครับ' });
            $('html, body').animate({ scrollTop: firstInvalidElement.offset().top - 150 }, 500); return;
        }

        Swal.fire({
            title: 'ยืนยันการบันทึก?', text: "คุณตรวจสอบและต้องการบันทึกแผนงบประมาณใช่หรือไม่?", icon: 'question',
            showCancelButton: true, confirmButtonColor: '#007bff', cancelButtonColor: '#6c757d', confirmButtonText: '<i class="fas fa-save"></i> บันทึกข้อมูล', cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({ title: 'กำลังบันทึก...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                $('.template-row').find('input, select').prop('disabled', true);
                HTMLFormElement.prototype.submit.call(document.getElementById('form-tab4-budget'));
            }
        });
    });

    // 🌟 ดักจับ Tab 5
    function calculatePercent(scoreInputId, percentInputId) {
        let score = parseFloat($('#' + scoreInputId).val()) || 0;
        if (score > 5) { score = 5; $('#' + scoreInputId).val(5); }
        let percent = score * 20; $('#' + percentInputId).val(percent.toFixed(2));
    }
    $('#satisfaction_score').on('input change', function() { calculatePercent('satisfaction_score', 'satisfaction_percent'); });
    $('#dissatisfaction_score').on('input change', function() { calculatePercent('dissatisfaction_score', 'dissatisfaction_percent'); });

    $('#btn-submit-tab5').click(function(e) {
        e.preventDefault(); let form = document.getElementById('form-tab5-evaluation');
        if (!form.checkValidity()) { form.reportValidity(); return; }

        Swal.fire({
            title: 'ยืนยันการบันทึก?', text: "คุณต้องการบันทึกข้อมูลผลลัพธ์และการประเมินใช่หรือไม่?", icon: 'question',
            showCancelButton: true, confirmButtonColor: '#007bff', cancelButtonColor: '#6c757d', confirmButtonText: '<i class="fas fa-save"></i> บันทึกข้อมูล', cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({ title: 'กำลังบันทึกข้อมูล...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });

    // 🌟 ดักจับ Tab 6 (ภาพรวม & รายชื่อผู้ลงนาม)
    let sigIndex = window.START_SIG_INDEX;
    const maxSignatures = 10;

    $('#signature-container .select2-staff').select2({ width: '100%', placeholder: '-- ค้นหาชื่อบุคลากร --' });
    $('#signature-container .select2-role').select2({ width: '100%', placeholder: '-- เลือกบทบาท --' });

    $('#btn-add-signature').click(function() {
        let currentRowCount = $('.signature-row').length;
        if (currentRowCount >= maxSignatures) { Swal.fire('ข้อควรระวัง', 'สามารถเพิ่มผู้ลงนามได้สูงสุด 10 คนเท่านั้นครับ', 'warning'); return; }

        let newRow = $('.signature-row:first').clone();
        newRow.find('.select2-container').remove(); newRow.find('select').removeClass('select2-hidden-accessible').removeAttr('data-select2-id aria-hidden tabindex').show(); newRow.find('option').removeAttr('data-select2-id');
        newRow.find('input').val(''); newRow.find('select').val('').trigger('change');

        newRow.attr('data-index', sigIndex);
        newRow.find('.select2-staff').attr('name', `signatures[${sigIndex}][staff_id]`);
        newRow.find('.select2-role').attr('name', `signatures[${sigIndex}][signature_role_id]`);
        newRow.find('.executive-position').attr('name', `signatures[${sigIndex}][executive_position]`);

        newRow.find('label:first').html(`${currentRowCount + 1}. ชื่อ-สกุล (บุคลากร) <span class="text-danger">*</span>`);

        let deleteBtn = newRow.find('button');
        deleteBtn.removeClass('btn-secondary shadow-sm').addClass('btn-danger btn-remove-signature').prop('disabled', false).attr('title', 'ลบข้อมูล');

        newRow.hide().appendTo('#signature-container').fadeIn(200);
        newRow.find('.select2-staff').select2({ width: '100%', placeholder: '-- ค้นหาชื่อบุคลากร --' });
        newRow.find('.select2-role').select2({ width: '100%', placeholder: '-- เลือกบทบาท --' });

        sigIndex++;
    });

    $('#signature-container').on('click', '.btn-remove-signature', function() {
        $(this).closest('.signature-row').slideUp(200, function() {
            $(this).remove();
            $('.signature-row').each(function(index) { $(this).find('label:first').html(`${index + 1}. ชื่อ-สกุล (บุคลากร) <span class="text-danger">*</span>`); });
        });
    });

    $('#signature-container').on('change', '.select2-staff', function() {
        let selectedOption = $(this).find(':selected');
        let newPosition = selectedOption.data('position');
        let positionInput = $(this).closest('.signature-row').find('.executive-position');
        if ($(this).val() && newPosition !== undefined && newPosition !== '') { positionInput.val(newPosition); } else if (!$(this).val()) { positionInput.val(''); }
    });

    $('#btn-submit-tab6').click(function(e) {
        e.preventDefault(); let form = $(this).closest('form')[0];
        if (form && !form.checkValidity()) { form.reportValidity(); return; }

        let buttonText = $(this).text().trim();
        let alertTitle = buttonText.includes('ยื่นขออนุมัติ') ? 'ยืนยันการยื่นขออนุมัติ?' : 'ยืนยันการบันทึก?';
        let alertConfirmBtn = buttonText.includes('ยื่นขออนุมัติ') ? '<i class="fas fa-paper-plane"></i> ยื่นขออนุมัติเลย' : '<i class="fas fa-save"></i> บันทึกข้อมูล';

        Swal.fire({
            title: alertTitle, html: "คุณได้ตรวจสอบภาพรวมและ <b>รายชื่อผู้ลงนามโครงการ</b> ครบถ้วนแล้วใช่หรือไม่?", icon: 'question',
            showCancelButton: true, confirmButtonColor: '#28a745', cancelButtonColor: '#6c757d', confirmButtonText: alertConfirmBtn, cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                Swal.fire({ title: 'กำลังดำเนินการ...', allowOutsideClick: false, didOpen: () => { Swal.showLoading(); } });
                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });

});