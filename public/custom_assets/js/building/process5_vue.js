Vue.component('select2', {
    props: ['options', 'value'],
    template: '#select2-template',
    mounted: function () {
        var vm = this
        $(this.$el)
            .val(this.value)
            // init select2
            .select2({ data: this.options })
            // emit event on change.
            .on('change', function () {
                vm.$emit('input', this.value)
            })
    },
    watch: {
        value: function (value) {
            // update value
            $(this.$el).val(value)
        },
        options: function (options) {
            var vm = this
            // update options
            $(this.$el).select2({ data: options })
            // after options update should select selected option
            setTimeout(function () {
                $(vm.$el).val(vm.value).trigger("change")
            }, 500);
        }
    },
    destroyed: function () {
        $(this.$el).off().select2('destroy')
    }
})

var externalTemplate = Immutable.Map({et_id: 0})
Vue.component('my-card',{
    template: '#my-content',
    props: ['energy_type'],
    data: function() {
        return {
            energy_and_juristic: [],
            month_template: Immutable.Map({
                year: (new Date().getFullYear()+543),
                month: 1,
                unit: null,
                cost_unit: null,
                total_cost: null,
                mj: null
            }),
            energy_and_juristic_template: Immutable.Map({
                et_id: 0,
                energy_used_per_years: []
            }),
            options: [
            ],
            loading: false
        }
    },
    created: function () {
        var vm = this
        vm.loading = true
        // ดึงค่าเดิม
        axios.get(energyAndJuristicUrl+mainId, {params: {energy_type: vm.energy_type}})
            .then(function (response) {
                vm.energy_and_juristic = response.data
                vm.energy_and_juristic = vm.energy_and_juristic.map(function (item) {
                    if (item.energy_used_per_years.length<=0){
                        var newMonth = vm.month_template.toObject()
                        item.energy_used_per_years.push(newMonth)
                    }
                    return item
                })

                vm.loading = false
            })
            .catch(function (error) {
                console.log(error)
                vm.loading = false
            })
        // ดึงค่า select
        axios.get(energyTypeUrl,{params:{type: vm.energy_type}})
            .then(function (response) {
                vm.options = response.data
            })
            .catch(function (error) {
                console.log(error)
            })
    },
    methods: {
        addMonth: function (TI) {
            var vm = this

            vm.energy_and_juristic = vm.energy_and_juristic.map(function (item) {
                if (item === TI){
                    item.energy_used_per_years.push(vm.month_template.toObject())
                }
                return item
            })
        },
        removeMonth: function (TI, month) {
            var vm = this
            var lastMonth = false

            vm.energy_and_juristic.map(function (item) {
                if (item === TI){
                    if (item.energy_used_per_years.length<=1)
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
                vm.energy_and_juristic = vm.energy_and_juristic.map(function (item) {
                    if (item === TI){
                        item.energy_used_per_years.splice(item.energy_used_per_years.indexOf(month),1)
                    }
                    return item
                })
            });

        },
        addCard: function () {
            var vm = this
            var newTI = externalTemplate.toObject()
            // ใส่เพื่อแก้ปัญหา mutable data
            newTI.energy_used_per_years = []
            newTI.energy_used_per_years.push(vm.month_template.toObject())
            vm.energy_and_juristic.push(newTI)
        },
        removeCard: function(TI){
            var vm = this
            if (this.energy_and_juristic.length<=1){
                swal("ต้องมีเชื้อเพลิงอย่างน้อย 1 เชื้อเพลิง","","warning");
                return false
            }

            swal({
                title: "ยืนยันการลบ?",
                text: "ยืนยันการลบเชื้อเพลิงหรือไม่?",
                type: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "ยืนยัน",
                cancelButtonText: "ยกเลิก",
            }).then(function () {
                vm.energy_and_juristic.splice(vm.energy_and_juristic.indexOf(TI),1)
            });
        },
        getEnergyTypeData: function (EAJ, attr) {
            var selected = this.options.filter(function (item) {
                return item.id.toString()===EAJ.et_id.toString()
            })
            if (selected.length>0 && selected[0][attr].trim()){
                if (attr=='heat_rate'){
                    EAJ.energy_used_per_years.map(function (item) {
                        item.mj = item.unit * parseFloat(selected[0][attr].trim())
                        item.total_cost = item.unit * item.cost_unit
                        return item
                    })
                }

                return selected[0][attr]
            }

            if (attr=='heat_rate'){
                EAJ.energy_used_per_years.map(function (item) {
                    item.mj = null
                    item.total_cost = item.unit * item.cost_unit
                    return item
                })
            }

            return 'ไม่มี'
        }
    }
})

var myVue = new Vue({
    el: '#root',
    data: {
        loading: false,
        energy_and_juristic: []
    },

    methods: {
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

            this.energy_and_juristic = this.energy_and_juristic.concat(this.$refs.nonrenewable_energy.$data.energy_and_juristic)
            this.energy_and_juristic = this.energy_and_juristic.concat(this.$refs.renewable_energy.$data.energy_and_juristic)
            vm.loading = true
            this.energy_and_juristic.forEach(function (item) {
                if (parseInt(item.et_id) === 0)
                    allOK = false

                item.energy_used_per_years.forEach(function (sub_item) {
                    if(sub_item.year === null || sub_item.unit===null || sub_item.cost_unit===null)
                        allOK = false
                })
            })

            if(!allOK){
                swal("กรุณากรอกข้อมูลให้ครบก่อนบันทึก","","warning")
                vm.loading = false
                return false;
            }

            axios.post(process5Url+mainId,
                {
                    energy_and_juristic: vm.energy_and_juristic,
                    _token: token
                }
            )
                .then(function (response) {
                    window.location = '/building/process6/'+mainId+'/edit'
                })
                .then(function (error) {
                    console.log(error)
                    vm.loading = false
                })
        }
    }
});