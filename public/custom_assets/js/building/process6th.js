$(function () {

	bindSelectBox()

    $('a.electric.icon.icon-close').click(function () {
        if ($('#e_machine_table tbody>tr').length > 1) {
            $(this).parent().parent().remove();
        }else{
            swal("ต้องมีเหลืออย่างน้อย 1 แถว","","warning");
        }
    });
    $('a.fire.icon.icon-close').click(function () {
        if ($('#f_machine_table tbody>tr').length > 1) {
            $(this).parent().parent().remove();
        }else{
            swal("ต้องมีเหลืออย่างน้อย 1 แถว","","warning");
        }
    });
    $("#add_machine_e").click(function () {
    	var $lastItem = $('#e_machine_table tbody>tr:last')
        $lastItem.find('.select').select2('destroy')

        $('#e_machine_table tbody>tr:last').clone(true).insertAfter('#e_machine_table tbody>tr:last');

        $('#e_machine_table tbody>tr:last .select').val(0);

        $('#e_machine_table tbody>tr:last input[name="e_machine_size[]"]').val("");
        $('#e_machine_table tbody>tr:last input[name="e_machine_size_unit[]"]').val("");
        $('#e_machine_table tbody>tr:last input[name="e_machine_amount[]"]').val("");
        $('#e_machine_table tbody>tr:last input[name="e_machine_life_time[]"]').val("");
        $('#e_machine_table tbody>tr:last input[name="e_hour_per_year[]"]').val("");
        $('#e_machine_table tbody>tr:last input[name="e_electric_using[]"]').val("");
        $('#e_machine_table tbody>tr:last input[name="e_system_energy[]"]').val("");
        $('#e_machine_table tbody>tr:last input[name="e_ps[]"]').val("");
        bindSelectBox()

        $('a.electric.icon.icon-close').unbind();
        $('a.electric.icon.icon-close').click(function () {
            if ($('#e_machine_table tbody>tr').length > 1) {
                $(this).parent().parent().remove();
            }else{
                swal("ต้องมีเหลืออย่างน้อย 1 แถว","","warning");
            }
        });
    });

    $("#add_machine_f").click(function () {
        var $lastItem = $('#f_machine_table tbody>tr:last')
        $lastItem.find('.select').select2('destroy')

        $('#f_machine_table tbody>tr:last').clone(true).insertAfter('#f_machine_table tbody>tr:last');
        // $('#f_machine_table tbody>tr:last input[name="f_machine_name[]"]').val("");
        $('#f_machine_table tbody>tr:last .select').val(0);
        // $('#f_machine_table tbody>tr:last .select').select2('destroy');
        $('#f_machine_table tbody>tr:last input[name="f_machine_size[]"]').val("");
        $('#f_machine_table tbody>tr:last input[name="f_machine_size_unit[]"]').val("");
        $('#f_machine_table tbody>tr:last input[name="f_machine_amount[]"]').val("");
        $('#f_machine_table tbody>tr:last input[name="f_machine_life_time[]"]').val("");
        $('#f_machine_table tbody>tr:last input[name="f_hour_per_year[]"]').val("");
        $('#f_machine_table tbody>tr:last input[name="energy_type[]"]').val("");
        $('#f_machine_table tbody>tr:last input[name="unit_en[]"]').val("");
        $('#f_machine_table tbody>tr:last input[name="f_electric_using[]"]').val("");
        $('#f_machine_table tbody>tr:last input[name="f_system_energy[]"]').val("");
        $('#f_machine_table tbody>tr:last input[name="f_ps[]"]').val("");
        bindSelectBox()

        $('a.fire.icon.icon-close').unbind();
        $('a.fire.icon.icon-close').click(function () {
            if ($('#f_machine_table tbody>tr').length > 1) {
                $(this).parent().parent().remove();
            }else{
                swal("ต้องมีเหลืออย่างน้อย 1 แถว","","warning");
			}
        });
    });
	/*$("#save").click(function () {
	 $("#p6").submit();
	 });*/

})

function bindSelectBox() {

    $('.select').select2();

    $("select[name='e_machine_id[]']").change(function(){
        var row = $(this).parent().parent();
        $.ajax({
            url: "/machineUnit",
            method: "GET",
            dataType: 'json',
            data: {id: this.value}
        }).done(function (data) {
            row.children().find('input[name="e_machine_size_unit[]"]').val(data);
        }).fail(function (data) {
            console.log(data);
        });
    });
    $("select[name='f_machine_id[]']").change(function(){
        var row = $(this).parent().parent();

        $.ajax({
            url: "/machineUnit",
            method: "GET",
            dataType: 'json',
            data: {id: this.value}
        }).done(function (data) {
            row.children().find('input[name="f_machine_size_unit[]"]').val(data);
        }).fail(function (data) {
            console.log(data);
        });
    });

    $("select[name='energy_type[]']").change(function(){
        var row = $(this).parent().parent();

        $.ajax({
            url: "/energyUnit",
            method: "GET",
            dataType: 'json',
            data: {id: this.value}
        }).done(function (data) {
            row.children().find('input[name="unit_en[]"]').val(data);
        }).fail(function (data) {
            console.log(data);
        });

    });
}