@extends('LayOut')

@section('content')
    <div class="panel panel-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">อาคาร</a></li>
            <li class="breadcrumb-item"><a href="#">{{$data->code}}</a></li>
            <li class="breadcrumb-item active"><a href="#">การใช้งานอาคารในแต่ละเดือน</a></li>
        </ol>
    </div>

    @include('partials._breadcrumb_steps',['progress_type'=> \App\Main::PROGRESS_BUILDING,'progressNo'=>4])

    <div id="root">
        <div class="panel panel-body">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-xs-6">
                    <h4><span class="bg-danger rounded sq-28"><span class=" icon icon-flash"></span></span> ข้อมูลหม้อแปลงไฟฟ้า</h4>
                    <h5> ประเภทของอัตราการใช้ไฟฟ้า ถ้าเป็นระบบปกติ จะไม่มีค่า ความต้องการไฟฟ้า (Onpeak Offpeak Holiday และค่าใช้จ่ายในส่วนความต้องการพลังงานไฟฟ้า ให้ใส่ค่า 0)</h5>
                    <h5> ประเภทของผู้ใช้ไฟฟ้า ให้เลือกตามเลขหลักที่หนึ่ง เช่น 3125 ให้เลือกประเภท 3xxx </h5>
                </div>
                <div class="col-xs-6">
                    <h4><button class="btn btn-sm btn-info pull-right " type="button" v-on:click="addTI()"><span class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มหม้อแปลง</button></h4>
                </div>
            </div>
            <div v-for="TI in tranformer_info">
                <div class="card meter-template">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-sm btn-warning " type="button" v-on:click="removeTI(TI)"><span class="icon icon-minus-square-o icon-lg icon-fw"></span> ลบหม้อแปลง</button>
                            </div>
                            <div class="col-xs-2">
                                <div class="md-form-group md-label-floating">
                                    <input type="text" class="md-form-control" required aria-required="true" v-model="TI.electric_user_no">
                                    <small class="md-help-block">หมายเลขผู้ใช้ไฟฟ้า</small>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="md-form-group md-label-floating">
                                    <input type="text" class="md-form-control" required aria-required="true" v-model="TI.elec_meter_no">
                                    <small class="md-help-block">หมายเลขเครื่องวัดไฟฟ้า</small>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="md-form-group md-label-floating">
                                    {!! Form::select('meter_type',$EUT ,null, ['class' => 'md-form-control','required' => '', 'aria-required' => 'true',"v-model"=>"TI.elec_use_type"]) !!}
                                    <small class="md-help-block">ประเภทผู้ใช้ไฟฟ้า</small>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="md-form-group md-label-floating">
                                    {!! Form::select('meter_ability',array('1' => 'ปกติ', '2' => 'TOU'),null, ['class' => 'md-form-control','required' => '', 'aria-required' => 'true', "v-model"=>"TI.electric_ratio"]) !!}
                                    <small class="md-help-block">อัตราการใช้ไฟฟ้า</small>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="md-form-group md-label-floating">
                                    <input type="number" class="md-form-control" required aria-required="true" v-model="TI.tranformer_power">
                                    <small class="md-help-block">ขนาดหม้อแปลง (kVA)</small>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="md-form-group md-label-floating">
                                    <input type="number" class="md-form-control" required aria-required="true" v-model="TI.amount">
                                    <small class="md-help-block">จำนวน (ตัว)</small>
                                </div>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-xs-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th class="text-center col-md-1" rowspan="2">ปี</th>
                                        <th class="text-center col-md-2" rowspan="2">เดือน</th>
                                        <th class="text-center col-md-4" colspan="3">ความต้องการพลังไฟฟ้า (kW)</th>
                                        <th class="text-center col-md-2" rowspan="2">ค่าใช้จ่าย (บาท)</th>
                                        <th class="text-center col-md-2" colspan="2">พลังงานไฟฟ้าและค่าใช้จ่าย</th>
                                        <th class="text-center col-md-1" rowspan="2">ตัวเลือก</th>
                                    </tr>
                                    <tr>
                                        <th class="text-center">On Peak</th>
                                        <th class="text-center">Off Peak</th>
                                        <th class="text-center">Holiday</th>
                                        <th class="text-center">พลังงานไฟฟ้า<br/>(กิโลวัตต์ชั่วโมง)</th>
                                        <th class="text-center">ค่าใช้จ่าย (บาท)</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="sub_item in TI.electric_used_per_years">
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.year">
                                        </td>
                                        <td>
                                            {!! Form::select('month', array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'),null,
                                            ["class"=>"md-form-control", "v-model"=>"sub_item.month"]) !!}
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.on_peak">
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.off_peak">
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.holiday">
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.cost_need">
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.power_used">
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.cost_true">
                                        </td>
                                        <td class="demo-icons">
                                            <a class="electric icon icon-close " title="ลบ" v-on:click="removeMonth(TI,sub_item)"></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-xs-12">
                                <div class="md-form-group md-label-floating">
                                    <button type="button" class="btn btn-sm btn-info " v-on:click="addMonth(TI)">
                                        <span class="icon icon-plus-square-o icon-lg icon-fw"></span>
                                        เพิ่มเดือน
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary btn-block " v-on:click="saveData" v-show="!loading">บันทึกข้อมูล</button>
            <button type="button" class="btn btn-primary btn-block " v-show="loading">บันทึกข้อมูล</button>
        </div>
    </div>

@endsection


@section('script')
    <script src="{{asset("js/immutable.min.js")}}"></script>
    <script src="{{asset("js/vue2.1.8.min.js")}}"></script>
    <script src="{{asset("js/axios.min.js")}}"></script>

    <script>
        var buildingProcess4Url = '{{url("/api/v1/building/process4")}}/';
        var getBuildingAjaxUrl = '{{url("/api/v1/building/process4")}}/';
        var mainId = {{$doc_number}};
    </script>
    <script src="{{asset('custom_assets/js/building/process4_vue.js')}}"></script>

@stop