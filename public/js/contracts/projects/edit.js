// ============================================================
// 1. GLOBAL / UTILITIES
// ============================================================

let activeCustomerSelectBox = null;
let activeExternalSelectBox = null;

// -------------------------
// Number Format
// -------------------------
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

// -------------------------
// Select2 Customer
// -------------------------
function initSelect2Customer(element) {
    element
        .select2({
            width: "100%",
            placeholder: "-- ค้นหากลุ่มเป้าหมาย --",
            language: {
                noResults: function () {
                    return `
                        <button type="button"
                            class="btn btn-sm btn-primary w-100 mt-1"
                            onmousedown="openTargetGroupModal(event)">
                            <i class="fas fa-plus"></i>
                            เพิ่มกลุ่มเป้าหมายใหม่
                        </button>`;
                },
            },
            escapeMarkup: function (markup) {
                return markup;
            },
        })
        .on("select2:open", function () {
            activeCustomerSelectBox = $(this);
        });
}

// -------------------------
// Select2 External
// -------------------------
function initSelect2External(element) {
    element
        .select2({
            width: "100%",
            placeholder: "-- ค้นหาชื่อบุคคลภายนอก --",
            language: {
                noResults: function () {
                    return `
                        <button type="button"
                            class="btn btn-sm btn-info w-100 mt-1"
                            onmousedown="openExternalModal(event)">
                            <i class="fas fa-plus"></i>
                            เพิ่มบุคคลภายนอกใหม่
                        </button>`;
                },
            },
            escapeMarkup: function (markup) {
                return markup;
            },
        })
        .on("select2:open", function () {
            activeExternalSelectBox = $(this);
        });
}

// -------------------------
// Modal
// -------------------------
window.openTargetGroupModal = function (e) {
    e.preventDefault();

    $(".select2-customer").select2("close");
    $("#formNewTargetGroup")[0].reset();
    $("#modalNewTargetGroup").modal("show");
};

window.openExternalModal = function (e) {
    e.preventDefault();

    $(".select2-external").select2("close");
    $("#formNewExternal")[0].reset();
    $("#modalNewExternal").modal("show");
};

// -------------------------
// Check Unique Position
// -------------------------
window.checkUniquePositions = function () {
    let selectedUniques = [];

    $(".position-select").each(function () {
        let selectedOption = $(this).find("option:selected");

        if (selectedOption.data("unique") == 1 && $(this).val() !== "") {
            selectedUniques.push($(this).val());
        }
    });

    $(".position-select").each(function () {
        let currentVal = $(this).val();

        $(this)
            .find("option")
            .each(function () {
                if ($(this).data("unique") == 1) {
                    let isDuplicate =
                        selectedUniques.includes($(this).val()) &&
                        $(this).val() !== currentVal;

                    $(this).prop("disabled", isDuplicate);
                }
            });
    });
};

// -------------------------
// Number Input Format
// -------------------------
$(document).on(
    "keyup change blur",
    ".format-number-budget, .format-expense, .format-remuneration, .format-summary",
    function () {
        let val = $(this).val().replace(/,/g, "");

        if (val !== "" && !isNaN(val)) {
            $(this).val(window.formatNumber(val));
        }
    }
);

// ============================================================
// 2. GLOBAL INITIALIZATION
// ============================================================

$(document).ready(function () {
    // -------------------------
    // Format Existing Numbers
    // -------------------------
    $(
        ".format-number-budget, .format-expense, .format-remuneration, .format-summary"
    ).each(function () {
        let val = $(this).val().replace(/,/g, "");

        if (val !== "" && !isNaN(val)) {
            $(this).val(window.formatNumber(val));
        }
    });

    // -------------------------
    // Select2
    // -------------------------
    $(".select2-multiple").select2({
        width: "100%",
        placeholder: "คลิกเพื่อเลือกข้อมูล",
    });

    $(".select2-basic").select2({
        width: "100%",
        placeholder: "-- เลือก --",
    });

    $(".select2-staff").select2({
        width: "100%",
        placeholder: "-- ค้นหาชื่อบุคลากรในคณะ --",
    });

    initSelect2Customer($(".select2-customer"));
    initSelect2External($(".select2-external"));

    // -------------------------
    // Flatpickr
    // -------------------------
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        locale: "th",
        allowInput: true,
    });

    // -------------------------
    // Remember Active Tab
    // -------------------------
    $('a[data-toggle="pill"]').on("shown.bs.tab", function (e) {
        let target = $(e.target).attr("href").replace("#", "");
        let url = new URL(window.location.href);

        url.searchParams.set("tab", target);
        window.history.replaceState({}, "", url);
    });
});

// ============================================================
// 3. TAB 1 : BASIC INFORMATION + OBJECTIVES
// ============================================================

$(document).ready(function () {
    // -------------------------
    // TAB 1 Submit
    // -------------------------
    $("#tab1 form").on("submit", function (e) {
        e.preventDefault();

        let startDate = $('input[name="start_date"]').val();
        let endDate = $('input[name="end_date"]').val();

        if (startDate && endDate && new Date(endDate) < new Date(startDate)) {
            Swal.fire({
                icon: "error",
                title: "ข้อผิดพลาด!",
                text: "วันที่สิ้นสุดโครงการต้องไม่น้อยกว่าวันที่เริ่มต้นโครงการ",
                confirmButtonColor: "#dc3545",
            });

            return;
        }

        let form = this;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: "ยืนยันการบันทึก?",
            text: "คุณตรวจสอบข้อมูลพื้นฐานครบถ้วนแล้วใช่หรือไม่?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "บันทึก & ไปต่อ",
            cancelButtonText: "ยกเลิก",
        }).then(function (result) {
            if (result.isConfirmed || result.value) {
                Swal.fire({
                    title: "กำลังบันทึก...",
                    allowOutsideClick: false,
                    didOpen: function () {
                        Swal.showLoading();
                    },
                });

                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });

    // -------------------------
    // Add Objective
    // -------------------------
    let objectiveIndex = window.OBJECTIVE_START_INDEX || 0;

    $("#btn-add-objective").click(function () {
        let customerGroupsHtml = "";

        if (window.CUSTOMER_GROUPS) {
            window.CUSTOMER_GROUPS.forEach(function (group) {
                customerGroupsHtml += `
                    <option value="${group.id}">
                        ${group.name_th}
                    </option>`;
            });
        }

        let newRow = `
            <div class="objective-box p-2 border-bottom animate__animated animate__fadeIn">
                <div class="row align-items-center">

                    <div class="col-md-3">
                        <select
                            class="form-control form-control-sm select2-objective"
                            name="objectives[${objectiveIndex}][group_id]"
                            required>
                            <option value="">-- เลือกกลุ่ม --</option>
                            ${customerGroupsHtml}
                        </select>
                    </div>

                    <div class="col-md-8 col-10">
                        <input
                            type="text"
                            class="form-control form-control-sm w-100"
                            name="objectives[${objectiveIndex}][detail]"
                            placeholder="ระบุรายละเอียดเพิ่มเติม...">
                    </div>

                    <div class="col-md-1 col-2 text-right pl-0">
                        <button
                            type="button"
                            class="btn btn-sm btn-link text-danger btn-remove-objective px-1">
                            <i class="fas fa-trash-alt fa-lg"></i>
                        </button>
                    </div>

                </div>
            </div>`;

        $("#objective-container").append(newRow);

        $(`select[name="objectives[${objectiveIndex}][group_id]"]`).select2({
            width: "100%",
            placeholder: "-- เลือกกลุ่ม --",
        });

        objectiveIndex++;

        $(".btn-remove-objective").show();
        $(".objective-header-row").show();
    });

    // -------------------------
    // Remove Objective
    // -------------------------
    $(document).on("click", ".btn-remove-objective", function () {
        $(this).closest(".objective-box").remove();

        if ($(".objective-box").length === 0) {
            $(".objective-header-row").hide();
        }

        if ($(".objective-box").length === 1) {
            $(".btn-remove-objective").hide();
        }
    });
});

// ============================================================
// 4. TAB 2 : TARGET GROUP + COMMITTEE
// ============================================================

$(document).ready(function () {
    // -------------------------
    // Add Target Group
    // -------------------------
    $("#btn-add-target").click(function () {
        let newRow = $("#table-target-group tbody tr:first").clone();

        newRow.find(".select2-container").remove();

        newRow
            .find("select")
            .removeClass("select2-hidden-accessible")
            .removeAttr("data-select2-id aria-hidden tabindex")
            .val("")
            .show();

        newRow
            .find("option")
            .removeAttr("data-select2-id")
            .prop("selected", false);

        newRow.find("input").val("");
        newRow.find(".btn-remove-row").prop("disabled", false);

        newRow.hide().appendTo("#table-target-group tbody").fadeIn(200);

        initSelect2Customer(newRow.find(".select2-customer"));
    });

    // -------------------------
    // Add Committee
    // -------------------------
    $("#btn-add-committee").click(function () {
        let newRow = $("#table-committee tbody tr:first").clone();

        newRow.find(".select2-container").remove();

        newRow
            .find("select")
            .removeClass("select2-hidden-accessible")
            .removeAttr("data-select2-id aria-hidden tabindex")
            .val("")
            .show();

        newRow
            .find("option")
            .removeAttr("data-select2-id")
            .prop("selected", false);

        newRow.find("input").val("");
        newRow.find(".btn-remove-row").prop("disabled", false);

        newRow.find(".member-type-select").val("1");
        newRow.find(".external-zone").hide();
        newRow.find(".internal-zone").show();

        newRow.hide().appendTo("#table-committee tbody").fadeIn(200);

        newRow.find(".select2-staff").select2({
            width: "100%",
            placeholder: "-- ค้นหาชื่อบุคลากรในคณะ --",
        });

        initSelect2External(newRow.find(".select2-external"));

        checkUniquePositions();
    });

    // -------------------------
    // Member Type
    // -------------------------
    $("#table-committee").on("change", ".member-type-select", function () {
        let tr = $(this).closest("tr");

        if ($(this).val() === "1") {
            tr.find(".external-zone").hide();
            tr.find(".internal-zone").show();

            tr.find(".select2-staff").prop("required", true);

            tr.find(".select2-external")
                .prop("required", false)
                .val("")
                .trigger("change");
        } else {
            tr.find(".internal-zone").hide();
            tr.find(".external-zone").show();

            tr.find(".select2-staff")
                .prop("required", false)
                .val("")
                .trigger("change");

            tr.find(".select2-external").prop("required", true);
        }
    });

    // -------------------------
    // Unique Position
    // -------------------------
    $("#table-committee").on("change", ".position-select", function () {
        checkUniquePositions();
    });

    // -------------------------
    // TAB 2 Submit
    // -------------------------
    $("#tab2 form").on("submit", function (e) {
        e.preventDefault();

        let form = this;

        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        Swal.fire({
            title: "ยืนยันการบันทึก?",
            text: "บันทึกข้อมูลเฉพาะและคณะทำงาน ใช่หรือไม่?",
            icon: "question",
            showCancelButton: true,
            confirmButtonColor: "#007bff",
            cancelButtonColor: "#6c757d",
            confirmButtonText: "บันทึก & ไปต่อ",
            cancelButtonText: "ยกเลิก",
        }).then(function (result) {
            if (result.isConfirmed || result.value) {
                Swal.fire({
                    title: "กำลังบันทึก...",
                    allowOutsideClick: false,
                    didOpen: function () {
                        Swal.showLoading();
                    },
                });

                HTMLFormElement.prototype.submit.call(form);
            }
        });
    });
});

// ============================================================
// 5. TAB 3 : BUDGET
// ============================================================

$(document).ready(function () {
    // ========================================================
    // 5.1 INCOME
    // ========================================================

    window.calculateIncome = function () {
        let grandTotal = 0;

        $("#table-budget-incomes tbody tr:not(.template-row)").each(
            function () {
                let unitCost = window.unformatNumber(
                    $(this).find(".income-unit-cost").val()
                );

                let quantity = window.unformatNumber(
                    $(this).find(".income-quantity").val()
                );

                let total = unitCost * quantity;

                if (total > 0) {
                    $(this)
                        .find(".income-total-amount")
                        .val(window.formatNumber(total.toFixed(2)));

                    grandTotal += total;
                } else {
                    $(this).find(".income-total-amount").val("");
                }
            }
        );

        $("#income-grand-total").val(
            grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : ""
        );

        $('input[name="total_budget_summary"]').val(
            grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00"
        );

        calculateSummary();
    };

    // ========================================================
    // 5.2 EXPENSE
    // ========================================================

    window.calculateExpense = function () {
        let grandTotal = 0;

        $("#table-budget-expenses tbody tr:not(.template-row)").each(
            function () {
                let costPerUnit = window.unformatNumber(
                    $(this).find(".expense-cost").val()
                );

                let factor1 =
                    window.unformatNumber(
                        $(this).find(".expense-factor1").val()
                    ) || 1;

                let factor2 =
                    window.unformatNumber(
                        $(this).find(".expense-factor2").val()
                    ) || 1;

                let total = costPerUnit * factor1 * factor2;

                if (total > 0) {
                    $(this)
                        .find(".expense-total-amount")
                        .val(window.formatNumber(total.toFixed(2)));

                    grandTotal += total;
                } else {
                    $(this).find(".expense-total-amount").val("");
                }
            }
        );

        $("#expense-grand-total").val(
            grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00"
        );

        $('input[name="operation_fee"]').val(
            grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00"
        );

        calculateSummary();
    };

    // ========================================================
    // 5.3 REMUNERATION
    // ========================================================

    window.calculateRemuneration = function () {
        let grandTotal = 0;

        $("#table-budget-remuneration tbody tr:not(.template-row)").each(
            function () {
                let costPerUnit = window.unformatNumber(
                    $(this).find(".remuneration-cost").val()
                );

                let factor1 =
                    window.unformatNumber(
                        $(this).find(".remuneration-factor1").val()
                    ) || 1;

                let factor2 =
                    window.unformatNumber(
                        $(this).find(".remuneration-factor2").val()
                    ) || 1;

                let total = costPerUnit * factor1 * factor2;

                if (total > 0) {
                    $(this)
                        .find(".remuneration-total-amount")
                        .val(window.formatNumber(total.toFixed(2)));

                    grandTotal += total;
                } else {
                    $(this).find(".remuneration-total-amount").val("");
                }
            }
        );

        $("#remuneration-grand-total").val(
            grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00"
        );

        $('input[name="remuneration_fee"]').val(
            grandTotal > 0 ? window.formatNumber(grandTotal.toFixed(2)) : "0.00"
        );

        calculateSummary();
    };

    // ========================================================
    // 5.4 BUDGET SUMMARY
    // ========================================================

    window.calculateSummary = function () {
        let totalProjectBudget = window.unformatNumber(
            $('input[name="total_budget_summary"]').val()
        );

        let totalRemuneration = window.unformatNumber(
            $('input[name="remuneration_fee"]').val()
        );

        let totalOperation = window.unformatNumber(
            $('input[name="operation_fee"]').val()
        );

        let sumExpenses = totalRemuneration + totalOperation;

        let maxExpense = (totalProjectBudget * 100) / 115;

        let serviceFeeAmountLabel = (totalProjectBudget * 15) / 115;

        $("#max_expense_label").text(
            window.formatNumber(maxExpense.toFixed(2)) + " บาท"
        );

        $("#service_fee_label").text(
            window.formatNumber(serviceFeeAmountLabel.toFixed(2)) + " บาท"
        );

        let serviceFeePercent =
            115 - (maxExpense > 0 ? (sumExpenses / maxExpense) * 100 : 0);

        if (serviceFeePercent < 0) {
            serviceFeePercent = 0;
        }

        let allocDeptPercent = serviceFeePercent - 4.0;

        if (allocDeptPercent < 0) {
            allocDeptPercent = 0;
        }

        $('input[name="service_fee_percent"]').val(
            serviceFeePercent.toFixed(2)
        );

        let serviceFeeAmount =
            totalProjectBudget - totalRemuneration - totalOperation;

        if (serviceFeeAmount < 0) {
            serviceFeeAmount = 0;
        }

        $('input[name="service_fee_amount"]').val(
            window.formatNumber(serviceFeeAmount.toFixed(2))
        );

        $('input[name="alloc_dept_percent"]').val(allocDeptPercent.toFixed(2));

        let allocUniAmount = maxExpense * (1.5 / 100);

        let allocCampusAmount = maxExpense * (2.5 / 100);

        let allocDeptAmount = maxExpense * (allocDeptPercent / 100);

        $('input[name="alloc_uni_amount"]').val(
            window.formatNumber(allocUniAmount.toFixed(2))
        );

        $('input[name="alloc_campus_amount"]').val(
            window.formatNumber(allocCampusAmount.toFixed(2))
        );

        $('input[name="alloc_dept_amount"]').val(
            window.formatNumber(allocDeptAmount.toFixed(2))
        );
        window.calculateSubDeptAllocations();
    };

    // ฟังก์ชันคำนวณสัดส่วน กองทุนวิจัย, คณะ, ศูนย์ (อัปเดตสูตรใหม่)
    function calculateSubDeptAllocations() {
        // 1. ดึงค่า "เปอร์เซ็นต์รวม" และ "ยอดเงินรวม" ของคณะ/หน่วยงาน
        let deptPercentStr = $('input[name="alloc_dept_percent"]').val() || "0";
        let deptPercent = parseFloat(deptPercentStr) || 0;

        let deptAmountStr = $('input[name="alloc_dept_amount"]').val() || "0";
        let deptAmount = parseFloat(deptAmountStr.replace(/,/g, "")) || 0;

        // ---------------------------------------------------------
        // 2. คำนวณเปอร์เซ็นต์ (%) ตามสูตรใหม่
        // ---------------------------------------------------------
        // - กองทุนวิจัย = 5% (0.05) ของเปอเซ็นต์คณะ
        let fundResearchPercent = deptPercent * 0.05;

        // - คณะ กับ ศูนย์ = (เปอเซ็นต์คณะ - เปอเซ็นต์กองทุนวิจัย) / 2
        let facultyAndCenterPercent = (deptPercent - fundResearchPercent) / 2;

        // อัปเดตเปอร์เซ็นต์ลงในช่อง Input (ใช้ toFixed(3) เพื่อให้ได้ทศนิยม 3 ตำแหน่ง)
        $('input[name="fund_research_percent"]').val(
            fundResearchPercent.toFixed(3)
        );
        $('input[name="faculty_percent"]').val(
            facultyAndCenterPercent.toFixed(3)
        );
        $('input[name="center_percent"]').val(
            facultyAndCenterPercent.toFixed(3)
        );

        // ---------------------------------------------------------
        // 3. คำนวณยอดเงิน (บาท) ตามสัดส่วนเดียวกัน
        // ---------------------------------------------------------
        // - เงินกองทุนวิจัย = 5% (0.05) ของยอดเงินคณะ
        let fundResearchAmount = deptAmount * 0.05;

        // - เงินคณะ กับ ศูนย์ = (ยอดเงินคณะ - เงินกองทุนวิจัย) / 2
        let facultyAndCenterAmount = (deptAmount - fundResearchAmount) / 2;

        // อัปเดตยอดเงินลงในช่อง Input (ใส่ comma และทศนิยม 2 ตำแหน่ง)
        $('input[name="fund_research_amount"]').val(
            fundResearchAmount.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
        );
        $('input[name="faculty_amount"]').val(
            facultyAndCenterAmount.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
        );
        $('input[name="center_amount"]').val(
            facultyAndCenterAmount.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
        );
    }

    // ผูก Event ให้คำนวณใหม่ทุกครั้งที่มีการพิมพ์แก้ไข "เปอร์เซ็นต์คณะ" หรือ "ยอดเงินคณะ"
    $(document).on(
        "input",
        'input[name="alloc_dept_percent"], input[name="alloc_dept_amount"]',
        function () {
            calculateSubDeptAllocations();
        }
    );

    // ========================================================
    // ฟังก์ชันคำนวณสัดส่วน กองทุนวิจัย, คณะ, ศูนย์ (แบบเรียกใช้ได้ทุกที่)
    // ========================================================
    window.calculateSubDeptAllocations = function () {
        let deptPercentStr = $('input[name="alloc_dept_percent"]').val() || "0";
        let deptPercent = parseFloat(deptPercentStr) || 0;

        let deptAmountStr = $('input[name="alloc_dept_amount"]').val() || "0";
        let deptAmount = parseFloat(deptAmountStr.replace(/,/g, "")) || 0;

        // คำนวณเปอร์เซ็นต์
        let fundResearchPercent = deptPercent * 0.05;
        let facultyAndCenterPercent = (deptPercent - fundResearchPercent) / 2;

        $('input[name="fund_research_percent"]').val(
            fundResearchPercent.toFixed(3)
        );
        $('input[name="faculty_percent"]').val(
            facultyAndCenterPercent.toFixed(3)
        );
        $('input[name="center_percent"]').val(
            facultyAndCenterPercent.toFixed(3)
        );

        // คำนวณยอดเงิน
        let fundResearchAmount = deptAmount * 0.05;
        let facultyAndCenterAmount = (deptAmount - fundResearchAmount) / 2;

        $('input[name="fund_research_amount"]').val(
            fundResearchAmount.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
        );
        $('input[name="faculty_amount"]').val(
            facultyAndCenterAmount.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
        );
        $('input[name="center_amount"]').val(
            facultyAndCenterAmount.toLocaleString("en-US", {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2,
            })
        );
    };

    // ดักจับ Event เผื่อในกรณีที่ User พิมพ์แก้ไขช่อง % คณะด้วยตัวเอง
    $(document).on(
        "keyup change blur",
        'input[name="alloc_dept_percent"]',
        function () {
            window.calculateSubDeptAllocations();
        }
    );

    // ========================================================
    // 5.5 BUDGET EVENTS
    // ========================================================

    $("#table-budget-incomes").on(
        "keyup change input",
        ".income-unit-cost, .income-quantity",
        window.calculateIncome
    );

    $("#table-budget-expenses").on(
        "keyup change input",
        ".expense-cost, .expense-factor1, .expense-factor2",
        window.calculateExpense
    );

    $("#table-budget-remuneration").on(
        "keyup change input",
        ".remuneration-cost, .remuneration-factor1, .remuneration-factor2",
        window.calculateRemuneration
    );

    // ========================================================
    // 5.6 ADD INCOME
    // ========================================================

    $("#btn-add-income").click(function () {
        let template = $("#table-budget-incomes .template-row").clone();

        template.removeClass("template-row d-none");

        template.find('input[type="text"], input[type="number"]').val("");

        template
            .find("select")
            .val("")
            .removeClass("select2-hidden-accessible")
            .removeAttr("data-select2-id")
            .prop("required", true);

        template.find(".select2-container").remove();

        $("#table-budget-incomes tbody").append(template);

        template.find(".select2-basic").select2({
            width: "100%",
            placeholder: "-- เลือก --",
        });

        updateRowIndex("#table-budget-incomes");
    });

    // ========================================================
    // 5.7 ADD EXPENSE
    // ========================================================

    $("#btn-add-expense").click(function () {
        let template = $("#table-budget-expenses .template-row").clone();

        template.removeClass("template-row d-none");

        template.find('input[type="text"], input[type="number"]').val("");

        template
            .find("select")
            .val("")
            .removeClass("select2-hidden-accessible")
            .removeAttr("data-select2-id")
            .prop("required", true);

        template.find(".select2-container").remove();

        let uniqueId = "avg_" + Date.now() + Math.floor(Math.random() * 1000);

        template
            .find(".can-average-switch")
            .attr("id", uniqueId)
            .prop("checked", true);

        template.find("label.custom-control-label").attr("for", uniqueId);

        template.find(".can-average-hidden").val("1");

        $("#table-budget-expenses tbody").append(template);

        template.find(".select2-basic").select2({
            width: "100%",
            placeholder: "-- เลือก --",
        });

        updateRowIndex("#table-budget-expenses");
    });

    // ========================================================
    // 5.8 ADD REMUNERATION
    // ========================================================

    $("#btn-add-remuneration").click(function () {
        let template = $("#table-budget-remuneration .template-row").clone();

        template.removeClass("template-row d-none");

        template.find('input[type="text"], input[type="number"]').val("");

        template
            .find("select")
            .val("")
            .removeClass("select2-hidden-accessible")
            .removeAttr("data-select2-id")
            .prop("required", true);

        template.find(".select2-container").remove();

        let uniqueId =
            "avg_remun_" + Date.now() + Math.floor(Math.random() * 1000);

        template
            .find(".can-average-switch")
            .attr("id", uniqueId)
            .prop("checked", true);

        template.find("label.custom-control-label").attr("for", uniqueId);

        template.find(".can-average-hidden").val("1");

        $("#table-budget-remuneration tbody").append(template);

        template.find(".select2-basic").select2({
            width: "100%",
            placeholder: "-- เลือก --",
        });

        updateRowIndex("#table-budget-remuneration");
    });

    // ========================================================
    // 5.9 AVERAGE CHECKBOX
    // ========================================================

    $(document).on("change", ".can-average-switch", function () {
        $(this)
            .siblings(".can-average-hidden")
            .val(this.checked ? "1" : "0");
    });

    // ========================================================
    // 5.10 INITIAL CALCULATION
    // ========================================================

    window.calculateIncome();
    window.calculateExpense();
    window.calculateRemuneration();

    // ========================================================
    // 5.11 INIT EXISTING INSTALLMENTS
    // ========================================================

    $("#installments-container")
        .find(".installment-block")
        .not(".installment-template")
        .each(function () {
            initInstallmentDatePicker($(this));
        });

    // ---------------------------------------------------------
    // ดักจับตอนกดปุ่มบันทึกฟอร์ม (ป้องกันการเซฟงวดที่กรอกไม่ครบ)
    // ---------------------------------------------------------
    // หมายเหตุ: ถ้าฟอร์มของคุณวัชกรมี ID ให้เปลี่ยน 'form' เป็น '#ไอดีฟอร์ม' เช่น '#budget-form'
    $("form").on("submit", function (e) {
        let isValid = true;
        let errorMessage = "";

        // วนลูปเช็คทุกงวดที่อยู่ใน container (ไม่รวมตัวต้นแบบ template)
        $(
            "#installments-container .installment-block:not(.installment-template)"
        ).each(function (index) {
            // ดึงค่าจากช่องวันที่
            let start = $(this).find(".start-date").val();
            let end = $(this).find(".end-date").val();

            // ดึงค่าจากช่องจำนวนเงิน (ใช้คลาส .install-amount ที่มีอยู่แล้วใน Blade)
            let amountInput = $(this).find(".install-amount").val() || "0";
            let amount = parseFloat(amountInput.replace(/,/g, "")); // ลบลูกน้ำออกก่อนเช็ค

            // เงื่อนไข: ถ้าช่องวันเริ่มว่าง หรือ วันสิ้นสุดว่าง หรือ จำนวนเงินเป็น 0
            if (!start || !end || isNaN(amount) || amount <= 0) {
                isValid = false;
                errorMessage = `กรุณากรอกข้อมูล "งวดที่ ${
                    index + 1
                }" ให้ครบถ้วน (วันที่ และจำนวนเงินต้องมากกว่า 0)`;
                return false; // สั่ง break ลูป each ทันที
            }
        });

        // ถ้าตรวจพบว่ามีงวดที่ข้อมูลไม่ครบ
        if (!isValid) {
            e.preventDefault(); // 🛑 หยุดการส่งฟอร์มทันที! ข้อมูลจะไม่วิ่งไป Backend

            // เด้งแจ้งเตือนแบบหล่อเท่ด้วย SweetAlert2
            Swal.fire({
                icon: "warning",
                title: "ข้อมูลงวดงานไม่สมบูรณ์!",
                text: errorMessage,
                confirmButtonColor: "#3085d6",
                confirmButtonText: "กลับไปแก้ไข",
            });
        }
    });
});

// ============================================================
// 6. INSTALLMENTS
// ============================================================

// -------------------------
// Reindex Installments
// -------------------------
function reindexInstallments() {
    $("#installments-container .installment-block")
        .not(".installment-template")
        .each(function (index) {
            let currentNo = index + 1;

            $(this).find(".installment-number-label").text(currentNo);
            $(this).find(".installment-no-input").val(currentNo);
        });

    // 🌟 อัปเดตกฎปฏิทินทุกครั้งที่มีการลบหรือเพิ่มงวด
    refreshInstallmentDateConstraints();
}

// -------------------------
// Add Installment
// -------------------------
$("#btn-add-installment").click(function (e) {
    e.preventDefault();

    let template = $(".installment-template .installment-block")
        .first()
        .clone();

    template.find(":disabled").prop("disabled", false);

    template.find("input").each(function () {
        if ($(this).attr("type") !== "hidden") {
            $(this).val("");
        }
    });

    // ---------------------------------------------------------
    // 🌟 1. เพิ่มโค้ดส่วนนี้: บังคับเคลียร์ช่องวันที่และจำนวนวันให้เกลี้ยง!
    // ---------------------------------------------------------
    template.find(".start-date").val("").attr("type", "text"); // บังคับเปลี่ยนกลับเป็น text เผื่อโดนซ่อน
    template.find(".end-date").val("").attr("type", "text");
    template.find(".install-duration").val(0);

    // 🌟 2. กำจัดช่องจำลอง (altInput) ที่ Flatpickr อาจจะสร้างค้างไว้ตอน Clone
    template
        .find("input")
        .filter(function () {
            return (
                !$(this).hasClass("start-date") &&
                !$(this).hasClass("end-date") &&
                ($(this).hasClass("flatpickr-input") ||
                    $(this).hasClass("input"))
            );
        })
        .remove();
    // ---------------------------------------------------------

    template.find("select").val("");

    $("#installments-container").append(template);

    reindexInstallments();

    initInstallmentDatePicker(template);
});

// -------------------------
// Init Installment Datepicker
// -------------------------
function initInstallmentDatePicker(block) {
    let startInput = block.find(".start-date");
    let endInput = block.find(".end-date");

    if (startInput[0]?._flatpickr) startInput[0]._flatpickr.destroy();
    if (endInput[0]?._flatpickr) endInput[0]._flatpickr.destroy();

    // 1. สร้างปฏิทินสำหรับ "วันที่เริ่ม"
    let startPicker = startInput.flatpickr({
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        locale: "th",
        allowInput: true,
        onChange: function (selectedDates, dateStr, instance) {
            let currentBlock = $(instance.element).closest(
                ".installment-block"
            );

            // ตรวจสอบความถูกต้องของวันที่
            if (!validateInstallmentDates(currentBlock)) {
                instance.clear(); // ถ้าไม่ผ่านเกณฑ์ ให้ล้างค่าที่เลือกทิ้ง
            }

            calculateDuration(currentBlock);
        },
    });

    // 2. สร้างปฏิทินสำหรับ "วันสิ้นสุด"
    let endPicker = endInput.flatpickr({
        dateFormat: "Y-m-d",
        altInput: true,
        altFormat: "d/m/Y",
        locale: "th",
        allowInput: true,
        onChange: function (selectedDates, dateStr, instance) {
            let currentBlock = $(instance.element).closest(
                ".installment-block"
            );

            // ตรวจสอบความถูกต้องของวันที่
            if (!validateInstallmentDates(currentBlock)) {
                instance.clear(); // ถ้าไม่ผ่านเกณฑ์ ให้ล้างค่าที่เลือกทิ้ง
            }

            calculateDuration(currentBlock);
        },
    });

    calculateDuration(block);
}

// -------------------------
// ฟังก์ชันใหม่: ตรวจสอบวันที่ทับซ้อน (คืนค่า true ถ้าผ่าน, false ถ้าไม่ผ่าน)
// -------------------------
function validateInstallmentDates(currentBlock) {
    let allBlocks = $(
        "#installments-container .installment-block:not(.installment-template)"
    );
    let currentIndex = allBlocks.index(currentBlock);

    let startStr = currentBlock
        .find('input[name="installments[start_date][]"]')
        .val();
    let endStr = currentBlock
        .find('input[name="installments[end_date][]"]')
        .val();

    let currentStart = startStr ? new Date(startStr) : null;
    let currentEnd = endStr ? new Date(endStr) : null;

    // 🔴 กฎข้อ 1: วันสิ้นสุด ต้องไม่ก่อน วันเริ่มต้น
    if (currentStart && currentEnd && currentEnd < currentStart) {
        Swal.fire({
            icon: "warning",
            title: "วันที่ไม่ถูกต้อง!",
            text: "วันสิ้นสุดต้องไม่น้อยกว่าวันที่เริ่มต้นในงวดเดียวกัน กรุณาเลือกใหม่ครับ",
            confirmButtonColor: "#3085d6",
            confirmButtonText: "ตกลง",
        });
        return false;
    }

    // 🔴 กฎข้อ 2: วันเริ่มต้นงวดนี้ ต้องมากกว่า วันสิ้นสุดงวดที่แล้ว
    if (currentIndex > 0 && currentStart) {
        let prevBlock = allBlocks.eq(currentIndex - 1);
        let prevEndStr = prevBlock
            .find('input[name="installments[end_date][]"]')
            .val();
        let prevEnd = prevEndStr ? new Date(prevEndStr) : null;

        if (prevEnd && currentStart <= prevEnd) {
            Swal.fire({
                icon: "warning",
                title: "วันที่ทับซ้อน!",
                text: "ต้องเริ่มหลังจากวันสิ้นสุดของงวดที่แล้ว กรุณาเลือกใหม่ครับ",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "ตกลง",
            });
            return false;
        }
    }

    return true;
}
// -------------------------
// 🌟 ฟังก์ชันใหม่: จัดระเบียบกฎของปฏิทิน (ไม่ให้วันที่ซ้อนทับกัน)
// -------------------------
function refreshInstallmentDateConstraints() {
    let blocks = $(
        "#installments-container .installment-block:not(.installment-template)"
    );
    let previousEndDate = null; // เก็บวันสิ้นสุดของงวดก่อนหน้า

    blocks.each(function () {
        let block = $(this);
        let startPicker = block.find(".start-date")[0]?._flatpickr;
        let endPicker = block.find(".end-date")[0]?._flatpickr;

        // 1. ตั้งค่า วันเริ่มต้น (Start Date) ของงวดนี้ ให้เลือกได้ตั้งแต่วันสิ้นสุดงวดที่แล้ว + 1
        let minStart = null;
        if (previousEndDate) {
            minStart = new Date(previousEndDate);
            minStart.setDate(minStart.getDate() + 1); // บวกไปอีก 1 วัน
        }

        if (startPicker && minStart) {
            startPicker.set("minDate", minStart);
        }

        // 2. ตั้งค่า วันสิ้นสุด (End Date) ของงวดนี้ ต้องไม่น้อยกว่าวันเริ่มต้นของงวดตัวเอง
        let currentStartStr = block
            .find('input[name="installments[start_date][]"]')
            .val();
        let minEnd = currentStartStr ? new Date(currentStartStr) : minStart;

        if (endPicker && minEnd) {
            endPicker.set("minDate", minEnd);
        }

        // 3. อัปเดต previousEndDate เพื่อนำไปใช้กับลูปงวดถัดไป
        let currentEndStr = block
            .find('input[name="installments[end_date][]"]')
            .val();
        if (currentEndStr) {
            previousEndDate = new Date(currentEndStr);
        } else {
            previousEndDate = null; // ถ้างวดยังไม่ระบุวันสิ้นสุด ให้ตัดสายการบังคับไว้ก่อน
        }
    });
}
// -------------------------
// Remove Installment
// -------------------------
$(document).on("click", ".btn-remove-installment", function (e) {
    e.preventDefault();

    let block = $(this).closest(".installment-block");

    Swal.fire({
        title: "ยืนยันการลบงวดงาน?",
        text: "คุณต้องการยกเลิกและลบงวดงานนี้ใช่หรือไม่?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#d33",
        cancelButtonColor: "#6c757d",
        confirmButtonText: "ใช่, ลบทิ้งเลย!",
        cancelButtonText: "ยกเลิก",
    }).then(function (result) {
        if (result.isConfirmed || result.value) {
            block.remove();
            reindexInstallments();
        }
    });
});

// -------------------------
// Calculate Installment
// -------------------------
function calculateInstallment(block) {
    let amount = window.unformatNumber(block.find(".install-amount").val());

    let guarPct = window.unformatNumber(block.find(".install-guar-pct").val());

    let guarAmt = 0;

    if (amount > 0 && guarPct > 0) {
        guarAmt = amount * (guarPct / 100);
    }

    let netAmount = amount - guarAmt;

    block
        .find(".install-guar-amt")
        .val(window.formatNumber(guarAmt.toFixed(2)));

    block
        .find(".install-net-text")
        .text(window.formatNumber(netAmount.toFixed(2)));

    block.find(".install-net-input").val(netAmount.toFixed(2));
}

// -------------------------
// Parse Local Date
// -------------------------
function parseDateLocal(dateStr) {
    if (!dateStr) {
        return null;
    }

    let parts = dateStr.split("-").map(Number);

    if (parts.length !== 3 || parts.some(isNaN)) {
        return null;
    }

    let date = new Date(parts[0], parts[1] - 1, parts[2]);

    if (isNaN(date.getTime())) {
        return null;
    }

    return date;
}

// -------------------------
// Calculate Duration
// -------------------------

function calculateDuration(block) {
    // 🌟 ใช้ name="..." เพื่อล็อคเป้าดึงค่าจากช่องที่ถูกซ่อนไว้ (ที่เป็น Y-m-d) ให้ชัวร์ 100%
    let startDateStr = block
        .find('input[name="installments[start_date][]"]')
        .val();
    let endDateStr = block.find('input[name="installments[end_date][]"]').val();

    if (startDateStr && endDateStr) {
        // JS สามารถจับ string Y-m-d โยนเข้า Date() ได้เลย
        let startDate = new Date(startDateStr);
        let endDate = new Date(endDateStr);

        // เช็คก่อนว่าค่าที่ได้ไม่ Error (ไม่ใช่ Invalid Date)
        if (!isNaN(startDate.getTime()) && !isNaN(endDate.getTime())) {
            let diffTime = endDate - startDate;
            let diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24)) + 1; // รวมวันเริ่มต้น (+1)

            if (diffDays > 0) {
                block.find(".install-duration").val(diffDays);
            } else {
                block.find(".install-duration").val(0);
            }
        }
    }
}

// ดักจับเมื่อมีการเปลี่ยนวันที่เริ่ม หรือ วันที่สิ้นสุด
$(document).on("change changeDate blur", ".start-date, .end-date", function () {
    let block = $(this).closest(".installment-block");
    calculateDuration(block);
});

// ============================================================
// 7. INSTALLMENT AMOUNT VALIDATION
// ============================================================

$(document).on("input", ".install-amount", function () {
    let currentInput = $(this);

    let block = currentInput.closest(".installment-block");

    // -------------------------
    // Project Budget
    // -------------------------
    let totalProjectBudget = window.unformatNumber(
        $('input[name="total_budget_summary"]').val()
    );

    // -------------------------
    // No Budget
    // -------------------------
    if (totalProjectBudget <= 0) {
        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: "warning",
                title: "ยังไม่ได้ระบุงบประมาณ!",
                text: "กรุณากรอกแผนรายรับ (ส่วนที่ 3.1) เพื่อกำหนดงบประมาณโครงการก่อนกรอกข้อมูลงวดงานครับ",
            });
        } else {
            alert(
                "กรุณากรอกแผนรายรับ (ส่วนที่ 3.1) เพื่อกำหนดงบประมาณโครงการก่อนครับ"
            );
        }

        currentInput.val("");

        calculateInstallment(block);

        return;
    }

    // -------------------------
    // Sum All Installments
    // -------------------------
    let sumInstallments = 0;

    $(".install-amount:not(:disabled)").each(function () {
        sumInstallments += window.unformatNumber($(this).val());
    });

    // -------------------------
    // Exceed Budget
    // -------------------------
    if (sumInstallments > totalProjectBudget) {
        let excessAmount = sumInstallments - totalProjectBudget;

        let currentValue = window.unformatNumber(currentInput.val());

        let maxAllowed = currentValue - excessAmount;

        if (maxAllowed < 0) {
            maxAllowed = 0;
        }

        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: "warning",
                title: "ยอดเงินเกินงบประมาณ!",
                html:
                    "คุณระบุเงินงวดงานเกินงบประมาณโครงการ<br>" +
                    "ระบบได้ปรับเป็นยอดสูงสุดที่ระบุได้คือ " +
                    "<b>" +
                    window.formatNumber(maxAllowed.toFixed(2)) +
                    "</b> บาท",
            });
        } else {
            alert("ยอดเงินรวมทุกงวดเกินงบประมาณโครงการ!");
        }

        currentInput.val(window.formatNumber(maxAllowed.toFixed(2)));
    }

    calculateInstallment(block);
});

// -------------------------
// Guarantee Percentage
// -------------------------
$(document).on("input change", ".install-guar-pct", function () {
    let block = $(this).closest(".installment-block");

    calculateInstallment(block);
});

// ============================================================
// 8. INSTALLMENT SUBMIT VALIDATION
// ============================================================

$("#form-tab3-budget").on("submit", function (e) {
    let totalProjectBudget = window.unformatNumber(
        $('input[name="total_budget_summary"]').val()
    );

    let sumInstallments = 0;

    $(".install-amount:not(:disabled)").each(function () {
        sumInstallments += window.unformatNumber($(this).val());
    });

    // ต้องการแค่ "ไม่เกินงบ"
    if (sumInstallments > totalProjectBudget) {
        e.preventDefault();

        let excessAmount = sumInstallments - totalProjectBudget;

        if (typeof Swal !== "undefined") {
            Swal.fire({
                icon: "warning",
                title: "ยอดเงินงวดงานเกินงบประมาณ!",
                html:
                    "ยอดรวมเงินทุกงวด <b>(" +
                    window.formatNumber(sumInstallments.toFixed(2)) +
                    " บาท)</b><br>" +
                    "งบประมาณโครงการ <b>(" +
                    window.formatNumber(totalProjectBudget.toFixed(2)) +
                    " บาท)</b><br><br>" +
                    "ยอดที่เกิน <b>" +
                    window.formatNumber(excessAmount.toFixed(2)) +
                    " บาท</b><br><br>" +
                    "กรุณาปรับลดจำนวนเงินงวดงาน",
            });
        } else {
            alert("ยอดรวมเงินทุกงวดเกินงบประมาณโครงการ!");
        }

        return false;
    }

    return true;
});

// ============================================================
// 9. GLOBAL TABLE ROW MANAGEMENT
// ============================================================

$(document).ready(function () {
    // -------------------------
    // Update Row Index
    // -------------------------
    window.updateRowIndex = function (tableId) {
        $(tableId + " tbody tr:not(.template-row)").each(function (index) {
            $(this)
                .find(".row-index")
                .text(index + 1);
        });
    };

    // -------------------------
    // Remove Table Row
    // -------------------------
    $(document).on("click", ".btn-remove-row", function () {
        let tbody = $(this).closest("tbody");
        let tr = $(this).closest("tr");

        if (tr.hasClass("template-row")) {
            return;
        }

        let tableId = "#" + tr.closest("table").attr("id");

        let rowCount = tbody.find("tr:not(.template-row)").length;

        if (rowCount > 1) {
            tr.fadeOut(200, function () {
                $(this).remove();

                if (tableId === "#table-committee") {
                    window.checkUniquePositions();
                }

                if (tableId === "#table-budget-incomes") {
                    window.calculateIncome();
                }

                if (tableId === "#table-budget-expenses") {
                    window.calculateExpense();
                }

                if (tableId === "#table-budget-remuneration") {
                    window.calculateRemuneration();
                }

                window.updateRowIndex(tableId);
            });
        } else {
            Swal.fire({
                icon: "warning",
                title: "ลบไม่ได้ครับ!",
                text: "ต้องมีข้อมูลอย่างน้อย 1 แถวเสมอ",
            });
        }
    });

    // ============================================================
    // 10. SIGNATURES (ผู้ลงนาม)
    // ============================================================

    $(document).ready(function () {
        // -------------------------
        // ฟังก์ชันจัดเรียง Index และอัปเดต UI (Reindex)
        // -------------------------
        function reindexSignatures() {
            $("#signature-container .signature-row").each(function (index) {
                // 1. อัปเดต Label ลำดับที่
                let labelText = $(this).find(
                    'label:contains("ชื่อ-นามสกุลผู้ลงนาม")'
                );
                if (labelText.length > 0) {
                    labelText.html(
                        `${
                            index + 1
                        }. ชื่อ-นามสกุลผู้ลงนาม <span class="text-danger">*</span>`
                    );
                }

                // 2. อัปเดต name ของ input/select ให้ตรงกับลำดับ Array
                $(this)
                    .find(".select2-staff")
                    .attr("name", `signatures[${index}][staff_id]`);
                $(this)
                    .find(".executive-position")
                    .attr("name", `signatures[${index}][executive_position]`);
                $(this)
                    .find(".select2-role")
                    .attr("name", `signatures[${index}][signature_role_id]`);

                // 3. จัดการปุ่มลบ (แถวแรกต้องห้ามลบ)
                let btnRemove = $(this).find(".btn-remove-signature");
                if (index === 0) {
                    btnRemove
                        .removeClass("btn-danger")
                        .addClass("btn-secondary")
                        .prop("disabled", true)
                        .attr("title", "ต้องมีอย่างน้อย 1 คน");
                } else {
                    btnRemove
                        .removeClass("btn-secondary")
                        .addClass("btn-danger")
                        .prop("disabled", false)
                        .removeAttr("title");
                }
            });
        }

        // -------------------------
        // เพิ่มผู้ลงนาม (Add Signature)
        // -------------------------
        $("#btn-add-signature").click(function () {
            let rowCount = $("#signature-container .signature-row").length;

            // เช็คเงื่อนไขห้ามเกิน 10 คน
            if (rowCount >= 10) {
                if (typeof Swal !== "undefined") {
                    Swal.fire({
                        icon: "warning",
                        title: "เต็มจำนวนแล้ว!",
                        text: "สามารถเพิ่มผู้ลงนามได้สูงสุด 10 คนเท่านั้นครับ",
                        confirmButtonColor: "#3085d6",
                    });
                } else {
                    alert("สามารถเพิ่มผู้ลงนามได้สูงสุด 10 คนเท่านั้นครับ");
                }
                return;
            }

            // คัดลอกแถวแรกสุดมาเป็นต้นแบบ
            let template = $(
                "#signature-container .signature-row:first"
            ).clone();

            // **สำคัญมาก**: ลบ Select2 ของเดิมทิ้งก่อน เพื่อให้ทำงานได้ตอน Clone
            template.find(".select2-container").remove();
            template
                .find("select")
                .removeClass("select2-hidden-accessible")
                .removeAttr("data-select2-id aria-hidden tabindex")
                .val("")
                .show();
            template
                .find("option")
                .removeAttr("data-select2-id")
                .prop("selected", false);

            // เคลียร์ค่า input แบบ text
            template.find('input[type="text"]').val("");

            // ซ่อนก่อนเอาไปต่อท้าย แล้วค่อย fadeIn ให้ดูสมูท
            template.hide().appendTo("#signature-container").fadeIn(200);

            // จัดเรียงลำดับ Index ใหม่
            reindexSignatures();

            // เรียกใช้ Select2 ใหม่สำหรับแถวล่าสุดที่เพิ่งเพิ่มเข้าไป
            let newRow = $("#signature-container .signature-row:last");

            newRow.find(".select2-staff").select2({
                width: "100%",
                placeholder: "-- ค้นหาชื่อบุคลากร --",
            });

            newRow.find(".select2-role").select2({
                width: "100%",
                placeholder: "-- เลือกบทบาท --",
            });
        });

        // -------------------------
        // ลบผู้ลงนาม (Remove Signature)
        // -------------------------
        $(document).on("click", ".btn-remove-signature", function () {
            // ถ้าปุ่มโดนล็อคไว้ (disabled) ไม่ต้องทำอะไร
            if ($(this).prop("disabled")) return;

            let row = $(this).closest(".signature-row");
            let rowCount = $("#signature-container .signature-row").length;

            // เช็คว่าต้องเหลืออย่างน้อย 1 แถว
            if (rowCount > 1) {
                row.fadeOut(200, function () {
                    $(this).remove();
                    reindexSignatures();
                });
            }
        });
    });
    // ============================================================
    // 11. AJAX MODAL: บันทึกกลุ่มเป้าหมายและบุคคลภายนอก (Contracts)
    // ============================================================

    $(document).ready(function () {
        // ----------------------------------------------------
        // บันทึกกลุ่มเป้าหมายใหม่ (Target Group / แหล่งทุน)
        // ----------------------------------------------------
        $("#btn-save-new-target-group").click(function () {
            let name_th = $("#new_target_group_name_th").val();
            let name_en = $("#new_target_group_name_en").val();

            if (name_th.trim() === "" || name_en.trim() === "") {
                Swal.fire({
                    icon: "warning",
                    title: "ข้อมูลไม่ครบถ้วน",
                    text: "กรุณากรอกชื่อกลุ่มเป้าหมายให้ครบถ้วน",
                });
                return;
            }

            let btn = $(this);
            let originalText = btn.html();
            btn.html(
                '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...'
            ).prop("disabled", true);

            $.ajax({
                url: window.ROUTES.storeTargetGroup,
                type: "POST",
                data: {
                    _token: window.ROUTES.csrfToken,
                    parent_id: $("#new_target_group_parent_id").val(),
                    name_th: name_th,
                    name_en: name_en,
                },
                success: function (response) {
                    if (response.success) {
                        // อัปเดต Select2 ทั้งตัวเลือกกลุ่มเป้าหมายทั่วไป และ แหล่งทุนในวัตถุประสงค์
                        $(".select2-customer, .select2-objective").each(
                            function () {
                                if (
                                    $(this).find(
                                        "option[value='" + response.id + "']"
                                    ).length === 0
                                ) {
                                    let displayName = response.full_path
                                        ? response.full_path
                                        : response.name_th;
                                    $(this).append(
                                        new Option(
                                            displayName,
                                            response.id,
                                            false,
                                            false
                                        )
                                    );
                                }
                            }
                        );

                        // เลือกค่าให้ช่องที่กำลังถูกเปิดใช้งานอยู่โดยอัตโนมัติ
                        if (activeCustomerSelectBox) {
                            activeCustomerSelectBox
                                .val(response.id)
                                .trigger("change");
                        }

                        $("#modalNewTargetGroup").modal("hide");
                        btn.html(originalText).prop("disabled", false);
                        Swal.fire({
                            icon: "success",
                            title: "สำเร็จ!",
                            text: "เพิ่มกลุ่มเป้าหมาย/แหล่งทุนเรียบร้อยแล้ว",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    }
                },
                error: function (xhr) {
                    btn.html(originalText).prop("disabled", false);
                    Swal.fire({
                        icon: "error",
                        title: "ระบบผิดพลาด!",
                        text: "ไม่สามารถเชื่อมต่อหรือบันทึกฐานข้อมูลได้",
                    });
                },
            });
        });

        // ----------------------------------------------------
        // บันทึกบุคคลภายนอกใหม่ (External) สำหรับคณะทำงาน
        // ----------------------------------------------------
        $("#btn-save-new-external").click(function () {
            let prefix_id = $("#new_ext_prefix_id").val();
            let firstname = $("#new_ext_firstname").val();
            let lastname = $("#new_ext_lastname").val();
            let department = $("#new_ext_department").val();

            if (!prefix_id || !firstname || !lastname || !department) {
                Swal.fire({
                    icon: "warning",
                    title: "แจ้งเตือน",
                    text: "กรุณากรอกข้อมูลที่มีเครื่องหมายดอกจัน (*) ให้ครบถ้วน",
                });
                return;
            }

            let btn = $(this);
            let originalText = btn.html();
            btn.html(
                '<i class="fas fa-spinner fa-spin mr-1"></i> กำลังบันทึก...'
            ).prop("disabled", true);

            $.ajax({
                url: window.ROUTES.storeExternal,
                type: "POST",
                data: {
                    _token: window.ROUTES.csrfToken,
                    prefix_id: prefix_id,
                    firstname: firstname,
                    lastname: lastname,
                    department: department,
                },
                success: function (response) {
                    if (response.success) {
                        // แทรกตัวเลือกใหม่เข้าไปในทุกช่องค้นหาบุคคลภายนอก
                        $(".select2-external").each(function () {
                            if (
                                $(this).find(
                                    "option[value='" + response.id + "']"
                                ).length === 0
                            ) {
                                $(this).append(
                                    new Option(
                                        response.fullname,
                                        response.id,
                                        false,
                                        false
                                    )
                                );
                            }
                        });

                        // เลือกค่าทันที
                        if (activeExternalSelectBox) {
                            activeExternalSelectBox
                                .val(response.id)
                                .trigger("change");
                        }

                        $("#modalNewExternal").modal("hide");
                        btn.html(originalText).prop("disabled", false);
                        Swal.fire({
                            icon: "success",
                            title: "สำเร็จ!",
                            text: "เพิ่มบุคคลภายนอกเรียบร้อยแล้ว",
                            showConfirmButton: false,
                            timer: 1500,
                        });
                    }
                },
                error: function () {
                    btn.html(originalText).prop("disabled", false);
                    Swal.fire({
                        icon: "error",
                        title: "ผิดพลาด!",
                        text: "ไม่สามารถบันทึกข้อมูลบุคคลภายนอกได้",
                    });
                },
            });
        });
    });
});
