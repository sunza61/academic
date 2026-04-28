// public/js/master-data/target-groups/edit.js

$(document).ready(function() {
    // เช็คว่ามีคลาสนี้ในหน้าเว็บไหม ถ้ามีก็ปลุกพลัง Select2 พร้อม Theme Bootstrap4
    if ($('.select2-basic').length) {
        $('.select2-basic').select2({
            theme: 'bootstrap4',
            placeholder: "-- เปลี่ยนตำแหน่งที่ต้องการ (ถ้ามี) --",
            allowClear: true
        });
    }
});