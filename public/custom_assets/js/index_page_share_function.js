function deleteMain(uid, pid) {
    swal({
            title: "ยืนยันการลบ?",
            text: "ยืนยันการลบแบบสอบถามนี้หรือไม่? ID: " + uid,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "ยืนยัน",
            cancelButtonText: "ยกเลิก",
            closeOnConfirm: false
        },
        function () {
            $("#form" + pid).submit();
        });
}