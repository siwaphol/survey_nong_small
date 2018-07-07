$(document).ready(function () {

    $('#user-list-table').DataTable({
        "language": {
            "lengthMenu": "Display _MENU_ records per page",
            "zeroRecords": "Nothing found - sorry",
            "info": "Showing page _PAGE_ of _PAGES_",
            "infoEmpty": "No records available",
            "infoFiltered": "(filtered from _MAX_ total records)"
        }
    });
    $("a[act='delete']").click(function () {
        var uid = $(this).attr('uid');
        swal({
                title: "ยืนยันการลบ?",
                text: "ยืนยันการลบผู้ใช้ออกจากระบบหรือไม่? User ID: " + $(this).attr('uid'),
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
                closeOnConfirm: false
            },
            function () {

                $("#form" + uid).submit();
            });
    });


});