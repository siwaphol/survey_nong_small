$( document ).ready(function() {
    // sumArea();
    //
    // var building_BUI = new Vue({
    //     el: '#example',
    //     template: '#insert-template',
    //     data: function () {
    //         return {
    //             buildings: [
    //                 {id:0}
    //             ],data: 1
    //         }
    //     },
    //     methods:{
    //         addbuilding: function () {
    //             sumArea();
    //
    //             console.log("เพิ่ม")
    //             // sumArea();
    //             // console.log(this.data)
    //             var temp = {id: this.data};
    //             this.buildings.push(temp);
    //             // sumArea();
    //             this.data+=1;
    //             sumArea();
    //             // console.log(this.data)
    //
    //         },
    //         deletebuilding: function (buildingID) {
    //
    //             var vm = this;
    //             if (vm.buildings.length<=1){
    //                 swal("ต้องมีอาคารอย่างน้อย 1 อาคาร","","warning")
    //                 return false
    //             }
    //             swal({
    //                 title: "ยืนยันการลบอาคารที่เลือก?",
    //                 text: "",
    //                 type: "warning",
    //                 showCancelButton: true,
    //                 showLoaderOnConfirm: true,
    //                 confirmButtonColor: "#DD6B55",
    //                 confirmButtonText: "ยืนยัน",
    //                 cancelButtonText: "ยกเลิก",
    //                 allowOutsideClick:false
    //             }).then(function () {
    //                 vm.buildings = vm.buildings.filter(function (item) {
    //                     return item.id.toString() != buildingID.toString()
    //                 })
    //             });
    //             sumArea();
    //         }
    //     }
    // });
    $('#add-building-btn').on('click', function () {
        $('#building_clone').clone().attr('id', null).insertAfter('.building-template:last').addClass('building-template');
        sumArea();
    });

    sumArea();
});