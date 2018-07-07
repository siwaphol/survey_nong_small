$( document ).ready(function() {

    /*------------------------------------------------------------------------------------------*/

    var energyVM = new Vue({
        el: '#example',
        template: '#insert-template',
        data: function () {
            return {
                energys: [
                    {id:0,months:[],monthId:1}
                ],
                data: 1
            }
        },
        methods:{
            addEnergy: function () {
                var temp = {id: this.data, months:[],monthId:1};
                this.energys.push(temp);

                this.data+=1;
            },
            addMonth: function (energyId) {
                var temp = this.energys.filter(function(x){
                    return parseInt(x.id)==parseInt(energyId)
                });

                temp[0].months.push(temp[0].monthId);
                temp[0].monthId +=1;
            },
            deleteMonth: function (energyId, monthId) {
                console.log('deleteMonth ',energyId,monthId);
                var temp = this.energys.filter(function(x){
                    return parseInt(x.id)==parseInt(energyId)
                });
                console.log(this);
                //fruits.splice(2, 1);
                temp[0].months = temp[0].months.filter(function (item) {
                    return parseInt(item)!=parseInt(monthId)
                });
                //temp[0].monthId +=1;
            }
        }
    });

    /*------------------------------------------------------------------------------------------*/

    var recycleVM = new Vue({
        el: '#example2',
        template: '#insert-template2',
        data: function () {
            return {
                recycles: [
                    {id:0,months:[],monthId:1}
                ],
                data: 1
            }
        },
        methods:{
            addRecycle: function () {
                var temp = {id: this.data, months:[],monthId:1};
                this.recycles.push(temp);

                this.data+=1;
            },
            addMonth: function (recycleId) {
                var temp = this.recycles.filter(function(x){
                    return parseInt(x.id)==parseInt(recycleId)
                });

                temp[0].months.push(temp[0].monthId);
                temp[0].monthId +=1;
            },
            deleteMonth: function (recycleId, monthId) {
                console.log('deleteMonth ',recycleId,monthId);
                var temp = this.recycles.filter(function(x){
                    return parseInt(x.id)==parseInt(recycleId)
                });
                console.log(this);
                //fruits.splice(2, 1);
                temp[0].months = temp[0].months.filter(function (item) {
                    return parseInt(item)!=parseInt(monthId)
                });
                //temp[0].monthId +=1;
            }
        }
    });

    /*------------------------------------------------------------------------------------------*/

    $(".add-month").click(function () {
        var $row = $(this).parent().parent().parent();
        var $table = $row.children().find('table');
        var $parentTR = $table.children().find('.clone:last');
        var $newTR = $parentTR.clone(false).insertAfter($parentTR);
        var $btndelete = $table.children().find('.demo-icons .icon-close');

        $btndelete.click(function () {
            $(this).parent().parent().remove();
        });

    });

    $(".add-energy").click(function () {
        $('[id^="energy_name_"]').each(function() {
            $(this).selectpicker();
        });
    });

    $('.demo-icons .icon-close').click(function () {
        var id = $(this).data('id');

        var res = confirmDelete();

        if (res == 1) {

            $.ajax({
                url: "/deleteMonth4",
                method: "get",
                dataType: 'json',
                data: {id: id}
            }).done(function (data) {
                console.log(data);

            }).fail(function (data) {
                console.log(data);
            });

            $(this).parent().parent().remove();
        }
        else{

        }
    });

    /*------------------------------------------------------------------------------------------*/

    $( "#save" ).on( "click", function() {
        var index_e = 0;
        var index_r = 0;

        $(".energy-template").each(function() {
            $(this).find('.energy-name').attr("name","energy["+index_e+"][id]");
            $(this).find('.energy-heat').attr("name","energy["+index_e+"][heat]");
            $(this).find('.er-id').attr("name","energy["+index_e+"][er_id]");
            $(this).find('.e-id').attr("name","energy["+index_e+"][m_id][]");
            $(this).find('.year').attr("name","energy["+index_e+"][year][]");
            $(this).find('.month').attr("name","energy["+index_e+"][month][]");
            $(this).find('.unit').attr("name","energy["+index_e+"][unit][]");
            $(this).find('.cost-unit').attr("name","energy["+index_e+"][cost_unit][]");
            $(this).find('.total-cost').attr("name","energy["+index_e+"][total_cost][]");
            $(this).find('.mj').attr("name","energy["+index_e+"][mj][]");

            index_e+=1;
        });

        $(".recycle-template").each(function() {
            $(this).find('.recycle-name').attr("name","recycle["+index_r+"][id]");
            $(this).find('.recycle-heat').attr("name","recycle["+index_r+"][heat]");
            $(this).find('.er-id').attr("name","recycle["+index_r+"][er_id]");
            $(this).find('.r-id').attr("name","recycle["+index_r+"][m_id][]");
            $(this).find('.year').attr("name","recycle["+index_r+"][year][]");
            $(this).find('.month').attr("name","recycle["+index_r+"][month][]");
            $(this).find('.unit').attr("name","recycle["+index_r+"][unit][]");
            $(this).find('.cost-unit').attr("name","recycle["+index_r+"][cost_unit][]");
            $(this).find('.total-cost').attr("name","recycle["+index_r+"][total_cost][]");
            $(this).find('.mj').attr("name","recycle["+index_r+"][mj][]");

            index_r+=1;
        });

        $('#p4').submit();
    });

    /*------------------------------------------------------------------------------------------*/

});

function e_total(data){
    var div = $(data).parent().parent().parent().parent().parent().parent();
    var row = $(data).parent().parent();
    var unit = row.children().find('input[name="e_unit[]"]').val();
    var cost = row.children().find('input[name="e_cost_unit[]"]').val();
    var mj = div.children().find('input[name="e_heat[]"]').val();

    //console.log(div);

    row.children().find('input[name="e_total_cost[]"]').val(unit*cost);
    row.children().find('input[name="e_mj[]"]').val(unit*mj);
}

function r_total(data){
    var div = $(data).parent().parent().parent().parent().parent().parent();
    var row = $(data).parent().parent();
    var unit = row.children().find('input[name="r_unit[]"]').val();
    var cost = row.children().find('input[name="r_cost_unit[]"]').val();
    var mj = div.children().find('input[name="r_heat[]"]').val();

    //console.log(div);

    row.children().find('input[name="r_total_cost[]"]').val(unit*cost);
    row.children().find('input[name="r_mj[]"]').val(unit*mj);
}

function energy_change(id)
{
    $.ajax({
        url: "/energyDetail",
        method: "GET",
        dataType: 'json',
        data: {id: $("#energy_name_"+id).val()}
    }).done(function (data) {
        //console.log(data);

        //alert(data.unit);
        if(data.heat_rate == ''){
            $("#energy_heat_"+id).val("ไม่มี");
        }
        else{
            $("#energy_heat_"+id).val(data.heat_rate);
        }

        $("#energy_unit_"+id).html(data.unit);

        var row = $("#energy_name_"+id).parent().parent().parent();

        var unit = row.children().find('input[name="e_unit[]"]').val();
        var mj = row.children().find('input[name="e_heat[]"]').val();

        if(mj == "ไม่มี"){
            row.children().find('input[name="e_mj[]"]').val(0);
        }
        else{
            row.children().find('input[name="e_mj[]"]').val(unit*mj);
        }

    }).fail(function (data) {
        //console.log(data);
    });
}

function recycle_change(id)
{
    $.ajax({
        url: "/recycleDetail",
        method: "GET",
        dataType: 'json',
        data: {id: $("#recycle_name_"+id).val()}
    }).done(function (data) {
        //console.log(data);

        //alert(data.unit);
        if(data.heat_rate == ''){
            $("#recycle_heat_"+id).val("ไม่มี");
        }
        else{
            $("#recycle_heat_"+id).val(data.heat_rate);
        }

        $("#recycle_unit_"+id).html(data.unit);

        var row = $("#recycle_name_"+id).parent().parent().parent();

        var unit = row.children().find('input[name="r_unit[]"]').val();
        var mj = row.children().find('input[name="r_heat[]"]').val();

        if(mj == "ไม่มี"){
            row.children().find('input[name="r_mj[]"]').val(0);
        }
        else{
            row.children().find('input[name="r_mj[]"]').val(unit*mj);
        }

    }).fail(function (data) {
        //console.log(data);
    });
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