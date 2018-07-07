var testVue2 = new Vue({
    el: '#test-vue2',
    data: {
        building_information: [],
        month_template: Immutable.Map({
            year: null,
            month: 1,
            air_conditioned: null,
            non_air_conditioned: null,
            sumspace: null,
            hotel: null,
            hospital: null
        }),
        loading:false
    },
    created: function () {
        var vm = this
        vm.loading = true
        axios.get(getBuildingAjaxUrl+mainId)
            .then(function (response) {
                vm.building_information = response.data

                vm.building_information = vm.building_information.map(function (item) {
                    if (item.working_per_months.length<=0){
                        var newMonth = vm.month_template.toObject()
                        newMonth.air_conditioned = item.air_conditioned
                        newMonth.non_air_conditioned = item.non_air_conditioned
                        item.working_per_months.push(newMonth)
                    }
                    return item
                })

                vm.loading = false
            })
    },
    methods: {
        addMonth: function (b_id) {
            var vm = this

            vm.building_information = vm.building_information.map(function (item) {
                if (item.id.toString() === b_id.toString()){
                    var lastMonth = item.working_per_months[item.working_per_months.length-1]
                    var newMonth = vm.month_template.toObject()
                    for (var member in newMonth){
                        newMonth[member] = lastMonth[member]
                    }
                    item.working_per_months.push(newMonth)
                }
                return item
            })
        },
        removeMonth: function (b_id, month) {
            var vm = this
            var lastMonth = false

            vm.building_information.map(function (item) {
                if (item.id.toString() === b_id.toString()){
                    if (item.working_per_months.length<=1)
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
                vm.building_information = vm.building_information.map(function (item) {
                    if (item.id.toString() === b_id.toString()){
                        item.working_per_months.splice(item.working_per_months.indexOf(month),1)
                    }
                    return item
                })
            });

        },
        getSum: function (b_id, sumProp) {
            var sum = 0
            var vm = this
            var selectedBI =  vm.building_information.filter(function(item){
                return item.id.toString()===b_id.toString()
            })

            if (selectedBI.length > 0)
                sum = selectedBI[0].working_per_months.reduce(function(a,b){
                    var l_sum = 0
                    sumProp.forEach(function (prop) {
                        l_sum += (isNaN(b[prop])?0:parseFloat(b[prop]))
                    })
                    return a + l_sum
                }, 0)

            return sum
        },
        getAverage: function (b_id, averageProp) {
            var vm = this
            var sum = this.getSum(b_id, averageProp)

            var selectedBI =  vm.building_information.filter(function(item){
                return item.id.toString()===b_id.toString()
            })

            if (!selectedBI || selectedBI[0].working_per_months.length<=0)
                return null

            var average = (sum/selectedBI[0].working_per_months.length)
            return Number.isInteger(average)?average:average.toFixed(2)
        },
        saveData: function () {
            var allOK = true
            var vm = this
            var token = $('input[name="_token"]').val();

            vm.loading = true
            this.building_information.forEach(function (item) {
                if (isNaN(vm.getSum(item.id, ['air_conditioned','non_air_conditioned','hotel','hospital'])))
                    allOK = false

                item.working_per_months.forEach(function (wpm) {
                    if(!wpm.year)
                        allOK = false
                })
            })

            if(!allOK){
                swal("กรุณากรอกข้อมูลให้ครบก่อนบันทึก","","warning")
                vm.loading = false
                return false;
            }

            axios.post(
                buildingProcess3Url+mainId,
                {
                    building_information: vm.building_information,
                    _token: token
                }
            )
                .then(function (response) {
                    window.location = '/building/process4/'+mainId+'/edit'
                })
                .then(function (error) {
                    console.log(error)
                    vm.loading = false
                })
        }
    }
});