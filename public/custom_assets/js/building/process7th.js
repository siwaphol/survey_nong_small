$(function () {

	$('a.future.icon.icon-close').click(function () {
		//alert("asd");
		if ($('#future_conserve_table tbody>tr').length > 1) {
			$(this).parent().parent().remove();
		} else {
			swal("ต้องมีมาตราการอย่างน้อย 1 มาตราการ", "", "warning")
		}
	});

	$('a.past.icon.icon-close').click(function () {
		//alert("asd");
		if ($('#past_conserve_table tbody>tr').length > 1) {
			$(this).parent().parent().remove();
		} else {
			swal("ต้องมีมาตราการอย่างน้อย 1 มาตราการ", "", "warning")
		}
	});


	$("#add_past_conserve").click(function () {
        var $lastItem = $('#past_conserve_table tbody>tr:last')
        $lastItem.find('.select').select2('destroy')

		$('#past_conserve_table tbody>tr:last').clone(false).insertAfter('#past_conserve_table tbody>tr:last');
		// $('#past_conserve_table tbody>tr:last input[name="past_conserve[measure][]"]').val("");
		$('#past_conserve_table tbody>tr:last .select').val(0);
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[before_elec_kw][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[before_elec_kwh_per_year][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[before_elec_cost_per_year][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[before_fuel_kg_per_year][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[before_fuel_cost_per_year][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[after_elec_kw][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[after_elec_kwh_per_year][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[after_elec_cost_per_year][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[after_fuel_kg_per_year][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[after_fuel_cost_per_year][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[investment][]"]').val("");
		$('#past_conserve_table tbody>tr:last input[name="past_conserve[payback][]"]').val("");

		$('a.past.icon.icon-close').unbind();
		$('a.past.icon.icon-close').click(function () {
			if ($('#past_conserve_table tbody>tr').length > 1) {
				$(this).parent().parent().remove();
			} else {
				swal("ต้องมีมาตราการอย่างน้อย 1 มาตราการ", "", "warning")
			}
		});

		$('.select').select2();
	});

	$("#add_future_conserve").click(function () {
        var $lastItem = $('#future_conserve_table tbody>tr:last')
        $lastItem.find('.select').select2('destroy')
		$('#future_conserve_table tbody>tr:last').clone(false).insertAfter('#future_conserve_table tbody>tr:last');
		// $('#future_conserve_table tbody>tr:last input[name="future_conserve[measure][]"]').val("");
		$('#future_conserve_table tbody>tr:last .select').val(0);
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[before_elec_kw][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[before_elec_kwh_per_year][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[before_elec_cost_per_year][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[before_fuel_kg_per_year][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[before_fuel_cost_per_year][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[after_elec_kw][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[after_elec_kwh_per_year][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[after_elec_cost_per_year][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[after_fuel_kg_per_year][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[after_fuel_cost_per_year][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[investment][]"]').val("");
		$('#future_conserve_table tbody>tr:last input[name="future_conserve[payback][]"]').val("");

		$('a.future.icon.icon-close').unbind();
		$('a.future.icon.icon-close').click(function () {
			if ($('#future_conserve_table tbody>tr').length > 1) {
				$(this).parent().parent().remove();
			} else {
				swal("ต้องมีมาตราการอย่างน้อย 1 มาตราการ", "", "warning")
			}
		});
		$('.select').select2();
	});

    $('.select').select2();
})