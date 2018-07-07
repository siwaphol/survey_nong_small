var testVue2 = new Vue({
    el: '#root',
    data: {
        tranformer_info: [],
        month_template: Immutable.Map({
            year: (new Date().getFullYear()+543),
            month: 1,
            on_peak: 0,
            off_peak: 0,
            holiday: 0,
            cost_need: 0,
            power_used: 0,
            cost_true: 0
        }),
        tranformer_info_template: Immutable.Map({
            electric_user_no: null,
            elec_meter_no: null,
            elec_use_type: 1,
            electric_ratio: 1,
            tranformer_power: null,
            amount: null,
            electric_used_per_years: []
        }),
        loading:false
    },
    created: function () {
        var vm = this
        vm.loading = true
        axios.get(getBuildingAjaxUrl+mainId)
            .then(function (response) {
                vm.tranformer_info = response.data
                vm.tranformer_info = vm.tranformer_info.map(function (item) {
                    if (item.electric_used_per_years.length<=0){
                        var newMonth = vm.month_template.toObject()
                        item.electric_used_per_years.push(newMonth)
                    }
                    return item
                })

                vm.loading = false
            })
            .catch(function (error) {
                console.log(error)
                vm.loading = false
            })
    },
    methods: {
        addMonth: function (TI) {
            var vm = this

            vm.tranformer_info = vm.tranformer_info.map(function (item) {
                if (item === TI){
                    item.electric_used_per_years.push(vm.month_template.toObject())
                }
                return item
            })
        },
        removeMonth: function (TI, month) {
            var vm = this
            var lastMonth = false

            vm.tranformer_info.map(function (item) {
                if (item === TI){
                    if (item.electric_used_per_years.length<=1)
                        lastMonth = true
                }
            })

            if (lastMonth){
                swal("ต้องมีเดือนอย่างน้อย 1 เดือน","","warning");
                return false
            }

            swal({
                title: "ยืนยันการลบ?",
                text: "ยืนยันการลบเดือนหรือไม่?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
            }).then(function () {
                vm.tranformer_info = vm.tranformer_info.map(function (item) {
                    if (item === TI){
                        item.electric_used_per_years.splice(item.electric_used_per_years.indexOf(month),1)
                    }
                    return item
                })
            });

        },
        addTI: function () {
            var vm = this
            var newTI = vm.tranformer_info_template.toObject()
            newTI.electric_used_per_years = []
            newTI.electric_used_per_years.push(vm.month_template.toObject())
            vm.tranformer_info.push(newTI)
        },
        removeTI: function(TI){
            var vm = this
            if (this.tranformer_info.length<=1){
                swal("ต้องมีหม้อแปลงอย่างน้อย 1 หม้อแปลง","","warning");
                return false
            }

            swal({
                title: "ยืนยันการลบ?",
                text: "ยืนยันการลบหม้อแปลงหรือไม่?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
            }).then(function () {
                vm.tranformer_info.splice(vm.tranformer_info.indexOf(TI),1)
            });
        },
        hasNull: function (target) {
            for (var member in target) {
                if (target[member] === null || target[member]==="")
                    return true;
            }
            return false;
        },
        saveData: function () {
            var allOK = true
            var vm = this
            var token = $('input[name="_token"]').val();

            vm.loading = true
            this.tranformer_info.forEach(function (item) {
                if (vm.hasNull(item))
                    allOK = false
            })

            if(!allOK){
                swal("กรุณากรอกข้อมูลให้ครบก่อนบันทึก","","warning")
                vm.loading = false
                return false;
            }

            axios.post(
                getBuildingAjaxUrl+mainId,
                {
                    tranformer_info: vm.tranformer_info,
                    _token: token
                }
            )
                .then(function (response) {
                    window.location = '/building/process5/'+mainId+'/edit'
                })
                .then(function (error) {
                    console.log(error)
                    vm.loading = false
                })
        }
    }
});