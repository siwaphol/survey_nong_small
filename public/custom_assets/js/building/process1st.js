$( document ).ready(function() {

/*
    $('#hotel').change(function(){
        console.log('inside');
        if($(this).prop("checked")) {
            console.log('checked');
            $('#total_room').show();
        } else {
            console.log('unchecked');
            $('#total_room').hide();
        }
    });
*/
    $('.select').select2();

    $('input[type=radio][name=building_type_select]').change(function() {
        $('#total_room').removeClass('hidden');
        $('#total_room').hide();
        $('#total_room').prop( "disabled", true );
        $('#total_room_text').val('');


        if (this.value == '3') {

            $('#total_room').show();
            $('#total_room').prop( "disabled", false );
       }
    });

    $('input[type=radio][name=building_type_select]').change(function() {
        $('#total_bed').removeClass('hidden');
        $('#total_bed').hide();
        $('#total_bed').prop( "disabled", true );
        $('#total_bed_text').val('');

        if (this.value == '4') {
            $('#total_bed').show();
            $('#total_bed').prop( "disabled", false );
        }
    });

    $('#save').click(function(){
        if ($('#sub-district-select').val() != 0) {
            $("#p1").submit();
        }
        else {
            swal('กรุณาเลือกจังหวัด ,อำเภอ/เขต หรือตำบล/แขวง ให้ครบถ้วน');
        }
    });

    $('#save_edit').click(function(){
        if ($('#sub-district-select').val() != 0) {
            $("#p1_edit").submit();
        }
        else {
            swal('กรุณาเลือกจังหวัด ,อำเภอ/เขต หรือตำบล/แขวง ให้ครบถ้วน');
        }
    });

    $("select[name='building_province']").change(function () {
        var provinceID = $("#province-select").val()
        var districtID = $("#district-select").val()
        $.ajax({
            url: "/api/v1/district",
            global: false,
            type: "GET",
            data: {DISTRICT_ID: districtID},
            dataType: "JSON",
            async: false,
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            success: function (data) {
                var opt = "<option value=\"0\" selected=\"selected\">เลือกอำเภอ/เขต</option>";
                var opt_s = "<option value=\"0\" selected=\"selected\">ตำบล/แขวง</option>";

                for (i = 0; i < data.length; i++) {
                    if (data[i]["PROVINCE_ID"] == provinceID)
                        opt += "<option value='" + data[i]["DISTRICT_ID"] + "'>" + data[i]["DISTRICT_NAME"] + "</option>"
                }
                $("#district-select").html(opt);
                $("#sub-district-select").html(opt_s);
            }
        });

    });

    $("select[name='building_district']").change(function () {
        var districtID = $("#district-select").val()
        var subdistrictID = $("#sub-district-select").val()
        $.ajax({
            url: "/api/v1/subdistrict",
            global: false,
            type: "GET",
            data: {SUB_DISTRICT_ID: subdistrictID},
            dataType: "JSON",
            async: false,
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            success: function (data) {
                var opt = "<option value=\"0\" selected=\"selected\">เลือกตำบล/แขวง</option>";

                for (i = 0; i < data.length; i++) {
                    if (data[i]["DISTRICT_ID"] == districtID)
                        opt += "<option value='" + data[i]["SUB_DISTRICT_ID"] + "'>" + data[i]["SUB_DISTRICT_NAME"] + "</option>"
                }
                $("#sub-district-select").html(opt);
            }
        });

    });

    $('#save').on("click", function (e) {
        $p = 0;
        for ($i = 0; $i < $('#tech select.technology-type').length; $i++) {
            if (($($('#tech select.technology-type')[$i]).val() == 0 )
                && ($($('div[name="tech-type-hidden"]')[$i]).hasClass("hidden")) == false)
                $p = 1;
        }
        if ($p == 0) {
            $('#tech').submit();
        }
        else {
            swal('กรุณาเลือกประเภทของพลังงาน ,เทคโนโลยี หรือประเภท/แบบ ให้ครบถ้วน');
        }
    });

});
