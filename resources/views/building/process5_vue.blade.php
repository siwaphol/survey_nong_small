@extends('LayOut')

@section('content')
    <div class="panel panel-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('building') }}">อาคาร</a></li>
            @if(isset($main->code))
                <li class="breadcrumb-item">{{$main->code}}</li>
                <li class="breadcrumb-item active">การใช้เชื้อเพลิงและพลังงานหมุนเวียน ในรอบปี</li>
            @else
                <li class="breadcrumb-item active">การใช้เชื้อเพลิงและพลังงานหมุนเวียน ในรอบปี</li>
            @endif
        </ol>
    </div>

    @include('partials._breadcrumb_steps',['progress_type'=> \App\Main::PROGRESS_BUILDING,'progressNo'=>5])

    <div id="root">
        <div class="panel panel-body">
            {{ csrf_field() }}
            <div class="row">
                <div class="col-xs-12">
                    <h4><span class="bg-danger rounded sq-28"><span class="icon icon-industry"></span></span> การใช้เชื้อเพลิงและพลังงานหมุนเวียน ในรอบปี</h4>
                </div>
            </div>

            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#menu1">การใช้เชื้อเพลิง</a></li>
                <li><a data-toggle="tab" href="#menu2">การใช้พลังงานหมุนเวียน</a></li>
            </ul>

            {{--TAB CONTENT --}}
            <div class="tab-content">
                <div id="menu1" class="tab-pane fade in active">
                    <my-card energy_type="{{\App\Energy_type::TYPE_NONRENEWABLE_ENERGY}}" ref="nonrenewable_energy"></my-card>
                </div>

                <div id="menu2" class="tab-pane fade">
                    <my-card energy_type="{{\App\Energy_type::TYPE_RENEWABLE_ENERGY}}" ref="renewable_energy"></my-card>
                </div>
            </div>

            <button type="button" class="btn btn-primary btn-block " v-on:click="saveData" v-show="!loading">บันทึกข้อมูล</button>
            <button type="button" class="btn btn-primary btn-block " v-show="loading">บันทึกข้อมูล</button>
        </div>
    </div>

    {{--Template Section--}}
    <script type="text/x-template" id="my-content">
        <div>
            <div class="row">
                <div class="col-xs-6">
                    <h4><span class="bg-danger rounded sq-24"><span class=" icon icon-fire"></span></span> @{{energy_type}}</h4>
                </div>
                <div class="col-xs-6">
                    <h4><button class="btn btn-sm btn-info pull-right " type="button" v-on:click="addCard"><span class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มเชื้อเพลิง</button></h4>
                </div>
            </div>
            <div v-for="NRE in energy_and_juristic">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <button class="btn btn-sm btn-warning " type="button" v-on:click="removeCard(NRE)"><span class="icon icon-minus-square-o icon-lg icon-fw"></span> ลบเชื้อเพลิง</button>
                            </div>
                            <div class="col-xs-6">
                                <div class="md-form-group md-label-floating">
                                    <select2 :options="options" v-model="NRE.et_id">
                                        <option disabled value="0">เลือกประเภทเชื้อเพลิง</option>
                                    </select2>
                                    <small class="md-help-block">ชนิดพลังงาน</small>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="md-form-group md-label-floating">
                                    {{--ได้มาจาก energy_type->heat_rate ซึ่งได้จาก selectbox--}}
                                    <input type="text" class="md-form-control text-red" required aria-required="true" readonly v-bind:value="getEnergyTypeData(NRE,'heat_rate')">
                                    <small class="md-help-block">ค่าความร้อนเฉลี่ย (MJ/หน่วย)</small>
                                </div>
                            </div>
                        </div>
                        <div class="row" >
                            <div class="col-xs-12">
                                <table class="table table-bordered table-hover">
                                    <thead>
                                    <tr>
                                        <th class="text-center col-md-1" rowspan="2">ปี</th>
                                        <th class="text-center col-md-2" rowspan="2"><center>ปริมาณการใช้<br/>ต่อเดือน</center></th>
                                        <th class="text-center" colspan="4"><center>เชื้อเพลิงสิ้นเปลือง</center></th>
                                        <th class="text-center col-md-1" rowspan="2"><center>ตัวเลือก</center></th>
                                    </tr>
                                    <tr>
                                        <th class="text-center col-md-2"><center><div class="text-red"> @{{getEnergyTypeData(NRE, 'unit')}} </div></center></th>
                                        <th class="text-center col-md-2"><center>บาท/หน่วย</center></th>
                                        <th class="text-center col-md-2"><center>เป็นเงิน (บาท)</center></th>
                                        <th class="text-center col-md-2"><center>เป็นพลังงาน (MJ)</center></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr v-for="sub_item in NRE.energy_used_per_years">
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.year">
                                        </td>
                                        <td>
                                            {!! Form::select('month', array('1' => 'มกราคม','2' => 'กุมภาพันธ์','3' => 'มีนาคม','4' => 'เมษายน','5' => 'พฤษภาคม','6' => 'มิถุนายน','7' => 'กรกฎาคม','8' => 'สิงหาคม','9' => 'กันยายน','10' => 'ตุลาคม','11' => 'พฤศจิกายน','12' => 'ธันวาคม'),null,
                                            ["class"=>"md-form-control", "v-model"=>"sub_item.month"]) !!}
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.unit">
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.cost_unit">
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.total_cost" readonly>
                                        </td>
                                        <td>
                                            <input type="number" class="md-form-control" required aria-required="true" v-model="sub_item.mj" readonly>
                                        </td>
                                        <td class="demo-icons">
                                            <a class="electric icon icon-close " title="ลบ" v-on:click="removeMonth(NRE,sub_item)"></a>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="col-xs-12">
                                <div class="md-form-group md-label-floating">
                                    <button type="button" class="btn btn-sm btn-info " v-on:click="addMonth(NRE)">
                                        <span class="icon icon-plus-square-o icon-lg icon-fw"></span>
                                        เพิ่มเดือน
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </script>

    <script type="text/x-template" id="select2-template">
        <select>
            <slot></slot>
        </select>
    </script>

@endsection

@section('script')
    <script src="{{asset("js/immutable.min.js")}}"></script>
    <script src="https://unpkg.com/vue/dist/vue.js"></script>
    <script src="{{asset("js/axios.min.js")}}"></script>

    <script>
        var energyAndJuristicUrl = '{{url("/api/v1/energy-and-juristic")}}/'
        var energyTypeUrl = '{{url("/api/v1/energy-type")}}'
        var process5Url = '{{url("/api/v1/building/process5")}}/'
        var mainId = {{$doc_number}};
    </script>
    <script src="{{asset('custom_assets/js/building/process5_vue.js')}}"></script>

@stop