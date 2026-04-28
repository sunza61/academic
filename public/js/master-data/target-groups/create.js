// public/js/master-data/target-groups/create.js

$(document).ready(function() {
    // เช็คก่อนว่ามีคลาสนี้อยู่ในหน้าเว็บไหม ค่อยเรียกใช้ Select2
    if ($('.select2-basic').length) {
        $('.select2-basic').select2({
            placeholder: "-- ค้นหาตำแหน่งที่ต้องการ --",
            allowClear: true
        });
    }
});