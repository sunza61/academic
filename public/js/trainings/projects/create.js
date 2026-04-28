// public/js/master-data/trainings/projects/create.js

$(document).ready(function() {
    // 1. ตั้งค่า Select2
    $('.select2-multiple').select2({
        width: '100%',
        placeholder: "คลิกเพื่อเลือกข้อมูล (เลือกได้มากกว่า 1)"
    });

    // 2. ตั้งค่า Datepicker
    flatpickr(".datepicker", {
        dateFormat: "Y-m-d", 
        altInput: true,
        altFormat: "d/m/Y",  
        locale: "th",
        allowInput: true
    });
});