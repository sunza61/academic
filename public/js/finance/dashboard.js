$(document).ready(function() {
    if ($('#financeTable').length) {
        $('#financeTable').DataTable({
            "language": {
                "emptyTable": "📂 ไม่พบโครงการที่ต้องตรวจสอบงบประมาณ",
                "search": "ค้นหา:",
                "zeroRecords": "ไม่พบข้อมูลที่ค้นหา",
                "info": "แสดง _START_ ถึง _END_ จากทั้งหมด _TOTAL_ รายการ",
                "infoEmpty": "แสดง 0 ถึง 0 จากทั้งหมด 0 รายการ",
                "infoFiltered": "(กรองจากทั้งหมด _MAX_ รายการ)",
                "paginate": {
                    "first": "หน้าแรก",
                    "last": "หน้าสุดท้าย",
                    "next": "ถัดไป",
                    "previous": "ก่อนหน้า"
                }
            },
            "columnDefs": [{ "orderable": false, "targets": [0, 5] }]
        });
    }
});
