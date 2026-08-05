// public/js/contracts/projects/index.js

$(document).ready(function () {
    // 🌟 1. ปลุกพลัง DataTables
    if ($("#projectTable").length) {
        $("#projectTable").DataTable({
            paging: true,
            lengthChange: true,
            searching: true,
            ordering: true,
            info: true,
            autoWidth: false,
            responsive: true,
            order: [[1, "desc"]], // ให้เรียงรหัสโครงการล่าสุดขึ้นก่อน
            columnDefs: [
                { orderable: false, targets: [0, 6] }, // ปิดการเรียงลำดับคอลัมน์ ลำดับ และ จัดการ
            ],
            language: {
                search: "ค้นหา:",
                lengthMenu: "แสดง _MENU_ รายการ",
                info: "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                paginate: {
                    first: "หน้าแรก",
                    last: "หน้าสุดท้าย",
                    next: "ถัดไป",
                    previous: "ก่อนหน้า",
                },
                emptyTable: "ไม่พบข้อมูลโครงการ",
                zeroRecords: "ไม่พบข้อมูลที่ค้นหา",
            },
        });
    }

    // ==========================================================
    // 💬 ส่วนของการอ่านเหตุผลตีกลับ/ยกเลิก (Modal)
    // ==========================================================
    $(document).on("click", ".btn-view-reason", function () {
        const reason = $(this).data("reason");
        const actionBy = $(this).data("by");
        const actionDate = $(this).data("date");
        const title = $(this).data("title");

        $("#reasonTitle").text(title);
        $("#reasonText").text(reason);
        $("#reasonBy").text(actionBy);
        $("#reasonDate").text(actionDate);

        $("#modalViewReason").modal("show");
    });

    // ==========================================================
    // 🚫 ส่วนของการยกเลิกโครงการ (Cancel Project)
    // ==========================================================
    $(document).on("click", ".btn-cancel-project", function () {
        let id = $(this).data("id");
        let name = $(this).data("name");

        $("#cancel_project_id").val(id);
        $("#cancel_project_name").text(name);
        $("#cancel_reason").val("");

        $("#modalCancelProject").modal("show");
    });

    $("#btn-submit-cancel").click(function () {
        let id = $("#cancel_project_id").val();
        let reason = $("#cancel_reason").val();

        if (reason.trim() === "") {
            Swal.fire({
                icon: "warning",
                title: "ข้อมูลไม่ครบ",
                text: "กรุณาระบุเหตุผลการยกเลิกโครงการด้วยครับ",
            });
            return;
        }

        let btn = $(this);
        let originalText = btn.html();
        btn.html(
            '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังประมวลผล...'
        ).prop("disabled", true);

        $.ajax({
            url: window.ROUTES.cancelProjectBaseUrl + "/" + id + "/cancel",
            type: "PUT",
            data: {
                _token: window.ROUTES.csrfToken,
                cancel_reason: reason,
            },
            success: function (response) {
                if (response.success) {
                    $("#modalCancelProject").modal("hide");
                    Swal.fire({
                        icon: "success",
                        title: "ยกเลิกสำเร็จ!",
                        text: response.message,
                        timer: 2000,
                        showConfirmButton: false,
                    }).then(() => {
                        location.reload();
                    });
                }
            },
            error: function (xhr) {
                btn.html(originalText).prop("disabled", false);
                let errMsg = "เกิดข้อผิดพลาดในการยกเลิก";
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errMsg = xhr.responseJSON.message;
                }
                Swal.fire({
                    icon: "error",
                    title: "ผิดพลาด",
                    text: errMsg,
                });
            },
        });
    });

    // ==========================================================
    // 🗑️ ส่วนของการลบโครงการ (Delete Project) พร้อมระบบเช็คเงื่อนไข
    // ==========================================================
    $(document).on("click", ".btn-delete-project", function (e) {
        e.preventDefault();

        let button = $(this);
        let form = button.closest("form");
        let projectName = button.data("name");
        let status = button.data("status");
        let isAdmin =
            button.data("is-admin") === true ||
            button.data("is-admin") === "true";

        // ดึง URL และข้อมูล Form มาเก็บไว้ในตัวแปรก่อน
        let actionUrl = form.attr("action");
        let formData = form.serialize();

        // 🚀 สร้างฟังก์ชันยิง AJAX เก็บไว้ใช้ซ้ำ จะได้ไม่ต้องเขียนโค้ดเดิม 2 รอบ
        const executeDelete = () => {
            Swal.fire({
                title: "กำลังลบข้อมูล...",
                text: "กรุณารอสักครู่",
                allowOutsideClick: false,
                showConfirmButton: false,
                didOpen: () => {
                    Swal.showLoading();
                },
            });

            $.ajax({
                url: actionUrl,
                type: "POST",
                data: formData,
                dataType: "json",
                headers: { "X-Requested-With": "XMLHttpRequest" },
                success: function (response) {
                    if (response.success) {
                        Swal.fire({
                            icon: "success",
                            title: "สำเร็จ!",
                            text: response.message || "ลบข้อมูลเรียบร้อยแล้ว",
                            confirmButtonText: "ตกลง",
                        }).then(() => {
                            window.location.reload();
                        });
                    } else {
                        Swal.fire(
                            "ผิดพลาด",
                            response.message || "ไม่สามารถลบข้อมูลได้",
                            "error"
                        );
                    }
                },
                error: function (xhr) {
                    let errorMsg = "เกิดข้อผิดพลาดในการลบข้อมูล";
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        errorMsg = xhr.responseJSON.message;
                    } else if (xhr.status === 403) {
                        errorMsg =
                            "คุณไม่มีสิทธิ์ในการลบ หรือโครงการไม่ได้อยู่ในสถานะร่าง";
                    } else if (xhr.status === 419) {
                        errorMsg =
                            "เซสชันหมดอายุ (CSRF Token Expired) กรุณารีเฟรชหน้าจอแล้วลองใหม่";
                    } else if (xhr.status === 500) {
                        errorMsg =
                            "เกิดข้อผิดพลาดรุนแรงที่ฝั่งเซิร์ฟเวอร์ (500 Internal Server Error)";
                    }
                    Swal.fire({
                        icon: "error",
                        title: "ไม่สามารถลบได้!",
                        text: errorMsg,
                        confirmButtonText: "เข้าใจแล้ว",
                    });
                },
            });
        };

        // 🚩 เงื่อนไขที่ 1.1: ถ้าโครงการถูกยกเลิกไปแล้ว (900)
        if (status == 900 && !isAdmin) {
            return Swal.fire({
                icon: "error",
                title: "ไม่สามารถลบได้!",
                text: 'โครงการนี้อยู่ในสถานะ "ยกเลิก" เรียบร้อยแล้ว ไม่สามารถลบออกจากระบบได้ครับ',
                confirmButtonColor: "#6c757d",
                confirmButtonText: "รับทราบ",
            });
        }

        // 🚩 เงื่อนไขที่ 1.2: ถ้าโครงการเสร็จสิ้นไปแล้ว (800) - บล็อกทุกสิทธิ์
        if (status == 800 && !isAdmin) {
            return Swal.fire({
                icon: "error",
                title: "ไม่สามารถลบได้!",
                text: 'โครงการนี้รายงานผลและ "เสร็จสิ้นโครงการ" ไปแล้ว ระบบไม่อนุญาตให้ลบทิ้งในทุกกรณีครับ',
                confirmButtonColor: "#6c757d",
                confirmButtonText: "รับทราบ",
            });
        }

        if (status == 200 && !isAdmin) {
            return Swal.fire({
                icon: "error",
                title: "ไม่สามารถลบได้!",
                text: 'เนื่องจากโครงการอยู่ในสถานะ "เสนอขออนุมัติ" ระบบไม่อนุญาตให้ลบทิ้งในทุกกรณีครับ',
                confirmButtonColor: "#6c757d",
                confirmButtonText: "รับทราบ",
            });
        }

        // 🚩 เงื่อนไขที่ 2: ถ้าโครงการ "ไม่ใช่" ฉบับร่าง (ไม่ใช่ 100)
        if (status != 100) {
            if (isAdmin) {
                // 👑 กรณีเป็น Admin: เตือนแรงๆ แต่เปิดทางให้ลบได้
                return Swal.fire({
                    icon: "warning",
                    title: "ยืนยันการลบโครงการ?",
                    html: `เนื่องจากโครงการ <strong>"${projectName}"</strong> มีการดำเนินการไปแล้ว (ไม่ได้อยู่ในสถานะฉบับร่าง)<br><br>
                       หากท่านไม่ต้องการจัดกิจกรรมนี้แล้ว <span class="text-danger font-weight-bold">ควรใช้ปุ่ม "ยกเลิกโครงการ" (ไอคอนสีเทา) แทนครับ</span>`,
                    showCancelButton: true,
                    confirmButtonColor: "#d33",
                    cancelButtonColor: "#17a2b8",
                    confirmButtonText: "ไม่เป็นไร ต้องการลบ",
                    cancelButtonText: "เข้าใจแล้ว (กลับไปยกเลิก)",
                }).then((result) => {
                    // Admin ยืนยันจะลบจริงๆ
                    if (result.isConfirmed || result.value) {
                        executeDelete();
                    }
                });
            } else {
                // 👤 กรณีไม่ใช่ Admin (Staff/Manager/User): บล็อกการลบ 100%
                return Swal.fire({
                    icon: "warning",
                    title: "ไม่สามารถลบทิ้งได้!",
                    html: `เนื่องจากโครงการมีการดำเนินการไปแล้ว (ไม่ได้อยู่ในสถานะฉบับร่าง)<br><br>
                       หากท่านไม่ต้องการจัดกิจกรรมนี้แล้ว <span class="text-danger font-weight-bold">ควรใช้ปุ่ม "ยกเลิกโครงการ" (ไอคอนสีเทา) แทนครับ</span>`,
                    confirmButtonColor: "#17a2b8",
                    confirmButtonText: "เข้าใจแล้ว",
                });
            }
        }

        // 🚩 เงื่อนไขที่ 3: กรณีสถานะเป็นฉบับร่าง (100) -> ลบได้ตามปกติ
        Swal.fire({
            title: "ยืนยันการลบข้อมูล?",
            html: `คุณต้องการลบโครงการ<br><strong class="text-danger">"${projectName}"</strong><br>ใช่หรือไม่?`,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#d33",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "ใช่, ลบทิ้งเลย!",
            cancelButtonText: "ยกเลิก",
        }).then((result) => {
            if (result.isConfirmed || result.value) {
                executeDelete();
            } else if (
                result.isDismissed ||
                result.dismiss === Swal.DismissReason.cancel
            ) {
               console.log ("❌ User กดยกเลิกการลบ (ฉบับร่าง)");
            }
        });
    });
});
