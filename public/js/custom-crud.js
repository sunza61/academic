document.addEventListener("DOMContentLoaded", function () {

    // success alert
    const successMeta = document.querySelector('meta[name="flash-success"]');
    if (successMeta) {
        Swal.fire({
            icon: 'success',
            title: 'สำเร็จ',
            text: successMeta.content,
            timer: 2000,
            showConfirmButton: false
        });
    }

    // error alert
    const errorMeta = document.querySelector('meta[name="flash-error"]');
    if (errorMeta) {
        Swal.fire({
            icon: 'error',
            title: 'ผิดพลาด',
            text: errorMeta.content
        });
    }

    // confirm delete
    document.querySelectorAll(".form-delete").forEach(function (form) {

        form.addEventListener("submit", function (e) {
    
            console.log("submit detected");
    
            e.preventDefault();
    
            Swal.fire({
                title: "ยืนยันการลบ?",
                text: "ข้อมูลจะถูกลบถาวร",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "ลบ",
                cancelButtonText: "ยกเลิก"
            }).then((result) => {
    
                console.log("swal result:", result);
    
                if (result.value) {
    
                    console.log("submit form");
    
                    HTMLFormElement.prototype.submit.call(form);
    
                }
    
            });
    
        });
    
    });

});