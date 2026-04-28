// public/js/trainings/projects/index.js

$(document).ready(function () {

    // 🌟 1. ปลุกพลัง DataTables (เอามาไว้บนสุดให้มันโหลดตารางก่อน)
    if ($('#projectTable').length) {
        $('#projectTable').DataTable({
            "paging": true,
            "lengthChange": true,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": true,
            "order": [[1, "desc"]], // ให้เรียงรหัสโครงการล่าสุดขึ้นก่อน
            "columnDefs": [
                { "orderable": false, "targets": [0, 6] } // ปิดการเรียงลำดับคอลัมน์ ลำดับ และ จัดการ
            ]
        });
    }

    // ==========================================================
    // 💬 ส่วนของการอ่านเหตุผลตีกลับ/ยกเลิก (Modal)
    // ==========================================================
    $(document).on('click', '.btn-view-reason', function () {
        // ดึงข้อมูลจาก Data Attributes
        const reason = $(this).data('reason');
        const actionBy = $(this).data('by');
        const actionDate = $(this).data('date');
        const title = $(this).data('title');

        // นำข้อมูลไปยัดใส่ใน Modal
        $('#reasonTitle').text(title);
        $('#reasonText').text(reason);
        $('#reasonBy').text(actionBy);
        $('#reasonDate').text(actionDate);

        // สั่งให้ Modal แสดงผล
        $('#modalViewReason').modal('show');
    });

    // ==========================================================
    // 🚫 ส่วนของการยกเลิกโครงการ (Cancel Project)
    // ==========================================================
    
    // 1. เมื่อกดปุ่มยกเลิกโครงการ (ดึง ID และ ชื่อ มาใส่ Modal)
    // ใช้ $(document).on() เพื่อให้ปุ่มที่อยู่ในหน้า 2-3 ของ DataTables ทำงานได้
    $(document).on('click', '.btn-cancel-project', function () {
        let id = $(this).data('id');
        let name = $(this).data('name');

        $('#cancel_project_id').val(id);
        $('#cancel_project_name').text(name);
        $('#cancel_reason').val(''); // เคลียร์ข้อความเก่าทิ้ง

        $('#modalCancelProject').modal('show');
    });

    // 2. เมื่อกดยืนยันใน Modal
    $('#btn-submit-cancel').click(function () {
        let id = $('#cancel_project_id').val();
        let reason = $('#cancel_reason').val();

        if (reason.trim() === '') {
            Swal.fire({
                icon: 'warning',
                title: 'ข้อมูลไม่ครบ',
                text: 'กรุณาระบุเหตุผลการยกเลิกโครงการด้วยครับ'
            });
            return;
        }

        let btn = $(this);
        let originalText = btn.html();
        btn.html('<i class="fas fa-spinner fa-spin mr-1"></i> กำลังประมวลผล...').prop('disabled', true);

        $.ajax({
            url: window.ROUTES.cancelProjectBaseUrl + "/" + id + "/cancel",
            type: "PUT",
            data: {
                _token: window.ROUTES.csrfToken,
                cancel_reason: reason
            },
            success: function (response) {
                if (response.success) {
                    $('#modalCancelProject').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'ยกเลิกสำเร็จ!',
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false
                    }).then(() => {
                        location.reload(); // รีเฟรชหน้าให้ตารางอัปเดตเป็น Status 900
                    });
                }
            },
            error: function (xhr) {
                btn.html(originalText).prop('disabled', false);
                let errMsg = 'เกิดข้อผิดพลาดในการยกเลิก';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: 'error',
                    title: 'ผิดพลาด',
                    text: errMsg
                });
            }
        });
    });

    // ==========================================================
    // 🗑️ ส่วนของการลบโครงการ (Delete Project)
    // ==========================================================
    
    // ดักจับปุ่มลบ (ใช้ logic ใหม่ล่าสุด ที่ดัก Admin / Status 900 / Status 800)
    $(document).on('click', '.btn-delete-project', function(e) {
        e.preventDefault();

        let form = $(this).closest('form');
        let projectName = $(this).data('name');
        let status = $(this).data('status');
        let isAdmin = $(this).data('is-admin') === true || $(this).data('is-admin') === 'true';

        // 🚩 เงื่อนไขที่ 1.1: ถ้าโครงการถูกยกเลิกไปแล้ว (900)
        if (status == 900) {
            return Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถลบได้!',
                text: 'โครงการนี้อยู่ในสถานะ "ยกเลิก" เรียบร้อยแล้ว ไม่สามารถลบออกจากระบบได้ครับ',
                confirmButtonColor: '#6c757d',
                confirmButtonText: 'รับทราบ'
            });
        }

        // 🚩 เงื่อนไขที่ 1.2: ถ้าโครงการเสร็จสิ้นไปแล้ว (800) - บล็อกทุกสิทธิ์
        if (status == 800) {
            return Swal.fire({
                icon: 'error',
                title: 'ไม่สามารถลบได้!',
                text: 'โครงการนี้รายงานผลและ "เสร็จสิ้นโครงการ" ไปแล้ว ระบบไม่อนุญาตให้ลบทิ้งในทุกกรณีครับ',
                confirmButtonColor: '#6c757d',
                confirmButtonText: 'รับทราบ'
            });
        }

        // 🚩 เงื่อนไขที่ 2: ถ้าโครงการ "ไม่ใช่" ฉบับร่าง (ไม่ใช่ 100)
        if (status != 100) {
            if (isAdmin) {
                // 👑 กรณีเป็น Admin: เตือนแรงๆ แต่เปิดทางให้ลบได้
                return Swal.fire({
                    icon: 'warning',
                    title: 'ยืนยันการลบโครงการ?',
                    html: `เนื่องจากโครงการ <strong>"${projectName}"</strong> มีการดำเนินการไปแล้ว (ไม่ได้อยู่ในสถานะฉบับร่าง)<br><br>
                           หากท่านไม่ต้องการจัดกิจกรรมนี้แล้ว <span class="text-danger font-weight-bold">ควรใช้ปุ่ม "ยกเลิกโครงการ" (ไอคอนสีเทา) แทนครับ</span>`,
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#17a2b8',
                    confirmButtonText: 'ไม่เป็นไร ต้องการลบ',
                    cancelButtonText: 'เข้าใจแล้ว (กลับไปยกเลิก)'
                }).then((result) => {
                    if (result.isConfirmed) {
                        confirmFinalDelete(form); // Admin ยืนยันจะลบจริงๆ
                    }
                });
            } else {
                // 👤 กรณีไม่ใช่ Admin (Staff/Manager/User): บล็อกการลบ 100%
                return Swal.fire({
                    icon: 'warning',
                    title: 'ไม่สามารถลบทิ้งได้!',
                    html: `เนื่องจากโครงการมีการดำเนินการไปแล้ว (ไม่ได้อยู่ในสถานะฉบับร่าง)<br><br>
                           หากท่านไม่ต้องการจัดกิจกรรมนี้แล้ว <span class="text-danger font-weight-bold">ควรใช้ปุ่ม "ยกเลิกโครงการ" (ไอคอนสีเทา) แทนครับ</span>`,
                    confirmButtonColor: '#17a2b8',
                    confirmButtonText: 'เข้าใจแล้ว'
                });
            }
        }

        // 🚩 เงื่อนไขที่ 3: กรณีสถานะเป็นฉบับร่าง (100) -> ลบได้ตามปกติ
        Swal.fire({
            title: 'ยืนยันการลบข้อมูล?',
            html: `คุณต้องการลบโครงการ<br><strong class="text-danger">"${projectName}"</strong><br>ใช่หรือไม่?`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'ใช่, ลบทิ้งเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                confirmFinalDelete(form);
            }
        });
    });

    // ฟังก์ชันย่อยสำหรับโชว์ Loading และกด Submit Form
    function confirmFinalDelete(form) {
        Swal.fire({ 
            title: 'กำลังลบข้อมูล...', 
            allowOutsideClick: false, 
            didOpen: () => { Swal.showLoading(); } 
        });
        form.submit();
    }

});