@extends('LayOut')

@section('content')
    <div class="panel panel-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">อาคาร</a></li>
            <li class="breadcrumb-item"><a href="#">{{isset($data)?$data->code:''}}</a></li>
            <li class="breadcrumb-item active"><a href="#">การใช้งานอาคารในแต่ละเดือน</a></li>
        </ol>
    </div>

    @include('partials._breadcrumb_steps',['progress_type'=> \App\Main::PROGRESS_BUILDING,'progressNo'=>3])

    <div class="panel panel-body">
        {{ csrf_field() }}
        <div class="panel panel-body">
            <div class="row">
                <div class="col-xs-6">
                    <h4><span class="bg-danger rounded sq-28"><span class=" icon icon-building"></span></span> การใช้งานอาคารในแต่ละเดือน</h4>
                </div>
                <div class="col-xs-6"></div>
            </div>

            <div id="test-vue2">
                <div>
                    <div class="card" v-for="b_info in building_information">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-xs-10">
                                    <div class="md-form-group md-label-floating">
                                        {!! Form::label('building_name', "@{{b_info.name}}", ["class"=>"md-form-control"]) !!}
                                        <small class="md-help-block">ชื่ออาคาร</small>
                                    </div>
                                </div>
                                <div class="col-xs-2">
                                    <div class="md-form-group md-label-floating pull-right">
                                        <button class="btn btn-default " type="button" v-on:click="addMonth(b_info.id)">เพิ่มเดือน</button>
                                    </div>
                                </div>

                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-body">
                                            <table class="table table-bordered table-hover">
                                                <thead>
                                                <tr>
                                                    <th class="text-center col-md-1">ปี</th>
                                                    <th class="text-center col-md-1">เดือน</th>
                                                    <th class="text-center">พื้นที่ปรับอากาศ<br/>(ตารางเมตร)</th>
                                                    <th class="text-center">พื้นที่ไม่ปรับอากาศ<br/>(ตารางเมตร)</th>
                                                    <th class="text-center">รวม<br/>(ตารางเมตร)</th>
                                                    <th class="text-center">จำนวนห้องพัก<br/>(ห้อง-วัน)</th>
                                                    <th class="text-center">จำนวนคนไข้ใน<br/>(เตียง-วัน)</th>
                                                    <th class="text-center">ตัวเลือก</th>
                                                </tr>
                                                </thead>
                                                <tbody v-for="wpm in b_info.working_per_months">
                                                <tr>
                                                    <td><input type="number" v-model="wpm.year" class="md-form-control"></td>
                                                    <td>{!! Form::select("month[]", array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'), null, ["class"=>"md-form-control", "v-model"=>"wpm.month"]) !!}</td>
                                                    <td><input type="number" v-model="wpm.air_conditioned" class="md-form-control" required aria-required="true"></td>
                                                    <td><input type="number" v-model="wpm.non_air_conditioned" class="md-form-control" required aria-required="true"></td>
                                                    <td><input type="number" v-bind:value="parseFloat(wpm.air_conditioned)+parseFloat(wpm.non_air_conditioned)" class="md-form-control" required aria-required="true" readonly></td>
                                                    <td><input type="number" v-model="wpm.hotel" class="md-form-control" required aria-required="true"></td>
                                                    <td><input type="number" v-model="wpm.hospital" class="md-form-control" required aria-required="true"></td>
                                                    <td class="demo-icons">
                                                        <a class="electric icon icon-sm icon-close icon-fw " title="ลบ" v-on:click="removeMonth(b_info.id, wpm)"></a>
                                                    </td>
                                                </tr>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-xs-6">
                                                <div class="md-form-group md-label-static">
                                                    <input type="number" class="md-form-control" v-bind:value="getAverage(b_info.id, ['air_conditioned', 'non_air_conditioned'])" readonly>
                                                    <small class="md-help-block">เฉลี่ยพื้นที่<br/>(ตารางเมตร)</small>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="md-form-group md-label-static">
                                                    <input type="number" class="md-form-control" v-bind:value="getSum(b_info.id, ['air_conditioned', 'non_air_conditioned'])" readonly>
                                                    <small class="md-help-block">รวมพื้นที่<br/>(ตารางเมตร)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-xs-6">
                                                <div class="md-form-group md-label-static">
                                                    <input type="number" class="md-form-control" v-bind:value="getAverage(b_info.id, ['hotel'])" readonly>
                                                    <small class="md-help-block">เฉลี่ยจำนวนห้องพัก<br/>(ห้อง-วัน)</small>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="md-form-group md-label-static">
                                                    <input type="number" class="md-form-control" v-bind:value="getSum(b_info.id, ['hotel'])" readonly>
                                                    <small class="md-help-block">รวมจำนวนห้องพัก<br/>(ห้อง-วัน)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card">
                                        <div class="card-body">
                                            <div class="col-xs-6">
                                                <div class="md-form-group md-label-static">
                                                    <input type="number" class="md-form-control" v-bind:value="getAverage(b_info.id, ['hospital'])" readonly>
                                                    <small class="md-help-block">เฉลี่ยจำนวนคนไข้ใน<br/>(เตียง-วัน)</small>
                                                </div>
                                            </div>
                                            <div class="col-xs-6">
                                                <div class="md-form-group md-label-static">
                                                    <input type="number" class="md-form-control" v-bind:value="getSum(b_info.id, ['hospital'])" readonly>
                                                    <small class="md-help-block">รวมจำนวนคนไข้ใน<br/>(เตียง-วัน)</small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-primary btn-block " v-show="!loading" v-on:click="saveData">บันทึกข้อมูล</button>
                <button type="button" class="btn btn-primary btn-block " v-show="loading">บันทึกข้อมูล</button>
            </div>
        </div>
    </div>
@endsection


@section('script')
    <script src="{{asset("js/immutable.min.js")}}"></script>
    <script src="{{asset("js/vue2.1.8.min.js")}}"></script>
    <script src="{{asset("js/axios.min.js")}}"></script>

    <script>
        var buildingProcess3Url = '{{url("api/v1/building/process3")}}/';
        var getBuildingAjaxUrl = '{{url("/api/v1/get-building-ajax")}}/';
        var mainId = {{$doc_number}};
    </script>
    <script src="{{asset('custom_assets/js/building/process3_vue.js')}}"></script>
@stop

