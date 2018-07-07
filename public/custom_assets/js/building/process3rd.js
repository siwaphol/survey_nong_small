$( document ).ready(function() {


    if ($('#p3').length>0){
        $( "#save" ).on( "click", function() {
            var index = 0;

            $(".building-template").each(function() {
                $(this).find('.text-building_id').attr("name","building["+index+"][building_id]");
                $(this).find('.text-year').attr("name","building["+index+"][year][]");
                $(this).find('.text-month').attr("name","building["+index+"][month][]");
                $(this).find('.text-air_space').attr("name","building["+index+"][air_space][]");
                $(this).find('.text-non_air_space').attr("name","building["+index+"][non_air_space][]");
                $(this).find('.text-sum_space').attr("name","building["+index+"][sum_space][]");
                $(this).find('.text-total_room').attr("name","building["+index+"][total_room][]");
                $(this).find('.text-total_bed').attr("name","building["+index+"][total_bed][]");
                $(this).find('.text-avg_area').attr("name","building["+index+"][avg_area]");
                $(this).find('.text-sum_area').attr("name","building["+index+"][sum_area]");
                $(this).find('.text-avg_room').attr("name","building["+index+"][avg_room]");
                $(this).find('.text-sum_room').attr("name","building["+index+"][sum_room]");
                $(this).find('.text-avg_bed').attr("name","building["+index+"][avg_bed]");
                $(this).find('.text-sum_bed').attr("name","building["+index+"][sum_bed]");

                index+=1;
            });
            $('#p3').submit();
        });
    }else {
        $( "#save" ).on( "click", function() {
            var index = 0;
            $(".building-template").each(function() {
                $(this).find('.text-building_id').attr("name","building["+index+"][building_id]");
                $(this).find('.text-wpm_id').attr("name","building["+index+"][wpm_id][]");
                $(this).find('.text-year').attr("name","building["+index+"][year][]");
                $(this).find('.text-month').attr("name","building["+index+"][month][]");
                $(this).find('.text-air_space').attr("name","building["+index+"][air_space][]");
                $(this).find('.text-non_air_space').attr("name","building["+index+"][non_air_space][]");
                $(this).find('.text-sum_space').attr("name","building["+index+"][sum_space][]");
                $(this).find('.text-total_room').attr("name","building["+index+"][total_room][]");
                $(this).find('.text-total_bed').attr("name","building["+index+"][total_bed][]");
                $(this).find('.text-avg_area').attr("name","building["+index+"][avg_area]");
                $(this).find('.text-sum_area').attr("name","building["+index+"][sum_area]");
                $(this).find('.text-avg_room').attr("name","building["+index+"][avg_room]");
                $(this).find('.text-sum_room').attr("name","building["+index+"][sum_room]");
                $(this).find('.text-avg_bed').attr("name","building["+index+"][avg_bed]");
                $(this).find('.text-sum_bed').attr("name","building["+index+"][sum_bed]");

                index+=1;
            });
            $('#p3_edit').submit();
        });
    }


    $('a.electric.icon.icon-sm.icon-close.icon-fw').click(function () {
        var id = $(this).data('id');
        // console.log(id)
        var buildingID = $(this).data('pid');
        var token = $('input[name="_token"]').val();
        if ($(this).parent().parent().parent().find("tr").length<=1){
            swal("ต้องมีเดือนอย่างน้อย 1 เดือน","","warning");
            return false;
        }
        var res = confirmDelete();

        if (res == 1) {

            $.ajax({
                url: "/deleteMonthB3",
                method: "get",
                dataType: 'json',
                data: {id: id}
            }).done(function (data) {
                // console.log(data);
                // console.log('a');

            }).fail(function (data) {
                // console.log(data);
                // console.log('b');
            });

            $(this).parent().parent().remove();
            refreshSumArea();
            refreshSumAreaAll(buildingID);
        }
        else{

        }


    });
    $(".add-month").click(function () {
        var building_id = $(this).data('pid');

        $('#building_table_'+building_id+' tbody>tr:last').clone(false).insertAfter('#building_table_'+building_id+' tbody>tr:last');
        $('#building_table_'+building_id+' tbody>tr:last .select').val(0);
        // $('#building_table_'+building_id+' tbody>tr:last').find('input').val("");
        $('a.electric.icon.icon-sm.icon-close.icon-fw').unbind();
        $('a.electric.icon.icon-sm.icon-close.icon-fw').click(function () {
            if ($('#building_table_'+building_id+' tbody>tr').length > 1) {
                var res = confirmDelete();
                if (res == 1) {
                    $(this).parent().parent().remove();
                    refreshSumArea();
                    refreshSumAreaAll(building_id);
                }
            }else{
                swal("ต้องมีเดือนอย่างน้อย 1 เดือน","","warning");
            }
        });

        refreshSumArea();
        refreshSumAreaAll(building_id);
        sumArea();
        sumAreaAll();
    });

    refreshSumArea();
    sumArea();
    $('.building-template').each(function(){
        refreshSumAreaAll($(this).find('input[name="building_id[]"]').val())
    })
    sumAreaAll();
});

function attachKeyUpEvents() {
    var $allTR = $('.building-table tbody tr');

    $allTR.each(function () {
        $(this).off('keyup')
        $(this).keyup(function () {
            getSumSpace()
        })
    })
}

function getSumSpace() {
    var $allTR = $('.building-table tbody tr');
    var allSumSpace = 0;
    var allSumRoom =0;
    var allSumBed=0;

    $allTR.each(function () {
        var sumSpace = 0;
        var sumRoom =0;
        var sumBed =0;
        //รวม space
        $(this).find('.text-air_space,.text-non_air_space').each(function () {
            if(!isNaN($(this).val())){
                sumSpace+=parseFloat($(this).val())
            }
        })

        $(this).find('.text-sum_space').first().val(sumSpace.toFixed(2))
        allSumSpace+=sumSpace;

        //รวมห้องพัก
        $(this).find('.text-total_room').each(function () {
            if(!isNaN($(this).val())){
                sumRoom+=parseFloat($(this).val())
            }
        })
        allSumRoom+=sumRoom;

        //รวมคนไข้ใน
        $(this).find('.text-total_bed').each(function () {
            if(!isNaN($(this).val())){
                sumBed+=parseFloat($(this).val())
            }
        })
        allSumBed+=sumBed;
    });

    $('input[name="avg_space[]"]').first().val((allSumSpace/$allTR.length).toFixed(2));
    $('input[name="sum_space_all[]"]').first().val(allSumSpace);
    $('input[name="avg_total_room[]"]').first().val((allSumRoom/$allTR.length).toFixed(2));
    $('input[name="sum_total_room[]"]').first().val(allSumRoom);
    $('input[name="avg_total_bed[]"]').first().val((allSumBed/$allTR.length).toFixed(2));
    $('input[name="sum_total_bed[]"]').first().val(allSumBed);
}


function confirmDelete() {
    var x;
    if (confirm("ยืนยันการลบข้อมูลเดิมใช่หรือไม่") == true) {
        x = 1;
    } else {
        x = 0;
    }

    return x;
}

function refreshSumArea() {
    var space, nonSpace, sumA, sumB,sum;

    $.each($( 'input[name="sum_space[]"]' ), function (i, total) {
        space = $('input[name="air_space[]')[i];
        nonSpace = $('input[name="non_air_space[]')[i];
        sumA = $('input[name="sum_space[]"]')[i];

        if (!isNaN(parseFloat($(space).val())) && !isNaN(parseFloat($(nonSpace).val()))) {
            $(total).val((parseFloat($(space).val())) + (parseFloat($(nonSpace).val())));
        }
    });
}

function refreshSumAreaAll(building_id) {
    var sumA,avgR,sumR,avgB,sumB;
    var Area,room,bed;

    var count = 0;
    $('.building-template').each(function () {
        if($(this).find('input[id="avg_space_'+building_id+'_[]"]').length>0)
            return false;
        count++;
    });

    $.each($( 'input[id="avg_space_'+building_id+'_[]"]' ), function (i, total) {
        var $buildingTemplate = $(this).parents('.building-template');
        sumA = $buildingTemplate.find('input[id="sum_space_all_'+building_id+'_[]"]');
        avgR = $buildingTemplate.find('input[id="avg_total_room_'+building_id+'_[]"]');
        sumR = $buildingTemplate.find('input[id="sum_total_room_'+building_id+'_[]"]');
        avgB = $buildingTemplate.find('input[id="avg_total_bed_'+building_id+'_[]"]');
        sumB = $buildingTemplate.find('input[id="sum_total_bed_'+building_id+'_[]"]');
        Area = 0;
        room = 0;
        bed = 0;

        var monthCount = $buildingTemplate.find('.working-per-months>tr').length;
        $buildingTemplate.find('.working-per-months>tr').each(function () {
            var $buildSum = $(this).find('input[id="build_sum_'+building_id+'_[' + count + ']"]');
            Area += isNaN($buildSum.val())?0:parseFloat($buildSum.val());
            var $buildRoom = $(this).find('input[id="build_room_'+building_id+'_[' + count + ']"]');
            room += isNaN($buildRoom.val())?0:parseFloat($buildRoom.val());
            var $bed = $(this).find('input[id="build_bed_'+building_id+'_[' + count + ']"]');
            bed += isNaN($bed.val())?0:parseFloat($bed.val());
        })
        // for (var j = 0; j < $('input[id="build_sum_'+building_id+'_[' + i + ']"]').length; j++) {
        //     if((parseFloat($($('input[id="build_sum_'+building_id+'_[' + i + ']"]')[j]).val()))) {
        //         Area += (parseFloat($($('input[id="build_sum_'+building_id+'_[' + i + ']"]')[j]).val()));
        //         room += (parseFloat($($('input[id="build_room_'+building_id+'_[' + i + ']"]')[j]).val()));
        //         bed += (parseFloat($($('input[id="build_bed_'+building_id+'_[' + i + ']"]')[j]).val()));
        //     }
        // }

        $(sumA).val(Area);
        $(sumR).val(room);
        $(sumB).val(bed);
        $(avgR).val(parseFloat($(sumR).val())/monthCount);
        $(avgB).val(parseFloat($(sumB).val())/monthCount);
        $(total).val(parseFloat($(sumA).val())/monthCount);
    })
}

function sumArea() {
	$( 'input[name="air_space[]"],[name="non_air_space[]"]').focusout(function () {
        refreshSumArea();
	});
}

function sumAreaAll() {
	$( 'input[name="air_space[]"],[name="non_air_space[]"],[name="total_room[]"],[name="total_bed[]"').focusout(function () {
		var building_id = $(this).data('pid');
        refreshSumAreaAll(building_id);
	});
}