$(document).ready(function () {

    $('#add-building-btn').on('click', function () {
        $('#building_clone').clone().attr('id', null).insertAfter('.building-template:last').addClass('building-template');
        sumArea();
    });

    sumArea();
});