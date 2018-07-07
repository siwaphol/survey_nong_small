$( document ).ready(function() {

    var productVM = new Vue({
        el: '#example',
        template: '#insert-template',
        data: function () {
            return {
                meters: [
                    {id:0,months:[],monthId:1}
                ],
                data: 1
            }
        },
        methods:{
            addMeter: function () {
                var temp = {id: this.data, months:[],monthId:1};
                this.meters.push(temp);
                //console.log("#product_slt_"+temp.id);
                //$("#product_slt_"+temp.id).select2();
                /*$('#product_slt_'+temp.id).change(function() {
                 console.log("asd"); // or $(this).val()
                 });*/

                this.data+=1;
            },
            addMonth: function (meterId) {
                //console.log('addMonth ',meterId);
                var temp = this.meters.filter(function(x){
                    return parseInt(x.id)==parseInt(meterId)
                });

                temp[0].months.push(temp[0].monthId);
                temp[0].monthId +=1;
            },
            deleteMonth: function (meterId, monthId) {
                console.log('deleteMonth ',meterId,monthId);
                var temp = this.meters.filter(function(x){
                    return parseInt(x.id)==parseInt(meterId)
                });
                console.log(this);
                //fruits.splice(2, 1);
                temp[0].months = temp[0].months.filter(function (item) {
                    return parseInt(item)!=parseInt(monthId)
                });
                //temp[0].monthId +=1;
            }
        }
    })

    $(".add-month").click(function () {
        var $row = $(this).parent().parent().parent();
        var $table = $row.children().find('table');
        var $parentTR = $table.children().find('.clone:last');
        $parentTR.clone(false).find('input[name!="year"]').map(function(item){
            return item.val = null
        })
            .insertAfter($parentTR);
        var $btndelete = $table.children().find('.demo-icons .icon-close');

        $btndelete.click(function () {
            $(this).parent().parent().remove();
        });

    });

    $(".add-meter-edit").click(function () {
        var clone = $('#meter-clone-edit').clone().insertAfter('#meter-clone-area');
        clone.removeClass('hidden');
        $('.remove-meter').each(function() {
            $(this).click(function () {
                $(this).parent().parent().parent().parent().remove();
            });
        });

        $(".add-month").click(function () {
            var $row = $(this).parent().parent().parent();
            var $table = $row.children().find('table');
            var $parentTR = $table.children().find('.clone:last');
            $parentTR.clone(false).insertAfter($parentTR);

            var $btndelete = $table.children().find('.demo-icons .icon-close');
            $btndelete.click(function () {
                if ($(this).parents('tbody').find('tr').length<=1){
                    swal("ต้องมีเดือนอย่างน้อย 1 เดือน","","warning");
                    return false
                }

                var res = confirmDelete();
                if (res == 1) {
                    $(this).parent().parent().remove();
                }
            });

        });

    });

    $('.demo-icons .icon-close').click(function () {
        var id = $(this).data('id');

        if ($(this).parents('tbody').find('tr').length<=1){
            swal("ต้องมีเดือนอย่างน้อย 1 เดือน","","warning");
            return false
        }

        var res = confirmDelete();
        if (res == 1) {
            $(this).parent().parent().remove();
        }
    });

    $('.add-meter').click(function () {
        $('.remove-meter').each(function() {
            $(this).click(function () {
                $(this).parent().parent().parent().parent().remove();
            });
        });
    });

    $('.remove-meter').click(function () {
        $(this).parent().parent().parent().parent().remove();
    });

    $('.remove-meter-edit').click(function () {
        var id =  $(this).data('id');

        var res = confirmDelete();

        if (res == 1) {

            $.ajax({
                url: "/deleteMeter",
                method: "get",
                dataType: 'json',
                data: {id: id}
            }).done(function (data) {
                console.log(data);

            }).fail(function (data) {
                console.log(data);
            });

            $(this).parent().parent().parent().parent().remove();

        }
        else{

        }

    });


    $( "#save" ).on( "click", function() {
        var index = 0;

        $(".meter-template").each(function() {
            $(this).find('.tfm-id').attr("name","meter["+index+"][tfm_id]");
            $(this).find('.meter-user-id').attr("name","meter["+index+"][meter_user_id]");
            $(this).find('.meter-id').attr("name","meter["+index+"][meter_id]");
            $(this).find('.meter-type').attr("name","meter["+index+"][meter_type]");
            $(this).find('.meter-ability').attr("name","meter["+index+"][meter_ability]");
            $(this).find('.meter-volume').attr("name","meter["+index+"][meter_volume]");
            $(this).find('.meter-numb').attr("name","meter["+index+"][meter_numb]");
            $(this).find('.euy-id').attr("name","meter["+index+"][euy_id][]");
            $(this).find('.year').attr("name","meter["+index+"][year][]");
            $(this).find('.month-cost').attr("name","meter["+index+"][month_cost][]");
            $(this).find('.on-peak').attr("name","meter["+index+"][on_peak][]");
            $(this).find('.off-peak').attr("name","meter["+index+"][off_peak][]");
            $(this).find('.holiday').attr("name","meter["+index+"][holiday][]");
            $(this).find('.cost-tou').attr("name","meter["+index+"][cost_tou][]");
            $(this).find('.vol-kwh').attr("name","meter["+index+"][vol_kwh][]");
            $(this).find('.cost-used').attr("name","meter["+index+"][cost_used][]");
            index+=1;
        });

        $('#p3').submit();
    });
});

function confirmDelete() {
    var x;
    if (confirm("ยืนยันการลบข้อมูลเดิมใช่หรือไม่") == true) {
        x = 1;
    } else {
        x = 0;
    }

    return x;
}