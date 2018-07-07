$(function () {
    $("#save").on("click", function (e) {
        e.preventDefault();
        var index = 0;
        var allNotEmpty = true;
        var $buildingTemplate = $(".building-template");

        $buildingTemplate.each(function () {
            $(this).find("input").each(function () {
                if (!$(this).valid())
                    allNotEmpty = false;
            })
        });

        if (allNotEmpty){
            $buildingTemplate.each(function () {
                $(this).find('.text-b_id').attr('name', 'building[' + index + '][b_id]');
                $(this).find('.text-building_name').attr('name', 'building[' + index + '][building_name]');
                $(this).find('.text-openy').attr('name', 'building[' + index + '][openy]')
                $(this).find('.text-work_hour').attr('name', 'building[' + index + '][work_hour]')
                $(this).find('.text-work_day').attr('name', 'building[' + index + '][work_day]')
                $(this).find('.text-airspace').attr('name', 'building[' + index + '][airspace]')
                $(this).find('.text-nonairspace').attr('name', 'building[' + index + '][nonairspace]')
                $(this).find('.text-a_na').attr('name', 'building[' + index + '][a_na]')
                $(this).find('.text-carspace').attr('name', 'building[' + index + '][carspace]')
                $(this).find('.text-all').attr('name', 'building[' + index + '][all]')

                index += 1;
            });
            $('#p2').submit();
        }
    });


})

function getInputValue(value) {
    console.log(value)
    if (isNaN(value))
        return 0;
    else
        return parseFloat(value);
}

function sumArea() {
    $('.building-template .card-body').each(function () {
        var $all = $(this).find('input[name="all[]"]');
        var $aNA = $(this).find('input[name="a_na[]"]');

        var airSpace = $(this).find('input[name="airspace[]"]');
        var nonAirSpace = $(this).find('input[name="nonairspace[]"]');
        var carSpace = $(this).find('input[name="carspace[]"]');

        $(this).find('input[name="airspace[]"],input[name="nonairspace[]"],input[name="carspace[]"]').each(function () {
            $(this).off("keyup")
            $(this).on("keyup", function () {
                $aNA.val(getInputValue(airSpace.val()) + getInputValue(nonAirSpace.val()));
                $all.val(getInputValue($aNA.val()) + getInputValue(carSpace.val()));
            })
        })
    })
}

function removeHtml(item) {
    if ($('.building-template').length<=1){
        swal("ต้องมีอาคารอย่างน้อย 1 อาคาร","","warning")
        return false;
    }
    swal({
        title: "ยืนยันการลบอาคารที่เลือก?",
        text: "",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "ยืนยัน",
        cancelButtonText: "ยกเลิก"
    }).then(
    function () {
        item.parent().parent().parent().parent().remove();
    });
}

function removeHtmlWithAjax(item,doc_number,building_id) {
    if ($('.building-template').length<=1){
        swal("ต้องมีอาคารอย่างน้อย 1 อาคาร","","warning")
        return false;
    }
    swal({
        title: "ยืนยันการลบอาคารที่เลือก?",
        text: "",
        type: "warning",
        showCancelButton: true,
        showLoaderOnConfirm: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "ยืนยัน",
        cancelButtonText: "ยกเลิก",
        allowOutsideClick:false
    }).then(function () {
        $.ajax({
            method: "GET",
            url: "/building/process2/"+doc_number+"/delete/"+building_id
        }).done(function () {
            item.parent().parent().parent().parent().remove();
            swal.close();
        }).fail(function (e) {
            console.log(e)
        })
    });
}