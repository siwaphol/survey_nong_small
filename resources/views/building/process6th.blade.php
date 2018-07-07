@extends('LayOut')

@section('content')
    <div class="panel panel-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">อาคาร</a></li>
            <li class="breadcrumb-item"><a href="#">{{$data->code}}</a></li>
            <li class="breadcrumb-item active">การประเมินการใช้ระดับพลังงาน</li>
        </ol>
    </div>

    @include('partials._breadcrumb_steps',['progress_type'=> \App\Main::PROGRESS_BUILDING,'progressNo'=>6])

    <div class="panel panel-body">
        <h4><span class="bg-danger rounded sq-28"><span class="icon icon-industry"></span></span>
            การประเมินการใช้ระดับพลังงาน</h4>
        <hr/>

        <div class="row">
            <!-- Form Section -->
            @if(isset($edit))
                {!! Form::open(['url' => '/building/process6/'.$doc_number,'method' => 'put','class' => 'form-horizontal'])!!}
            @else
                {!! Form::open(['url' => '/building/process6','method' => 'post','class' => 'form-horizontal'])!!}
            @endif
            {!! Form::hidden("doc_number", $doc_number,[])!!}
            <div class="col-xs-6">
                <h4><span class="bg-danger rounded sq-28"><span class="icon icon-bolt"></span></span> การใช้พลังงานไฟฟ้า
                </h4>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-sm btn-info pull-right " type="button" id="add_machine_e"><span
                            class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มเครื่องจักร
                </button>
            </div>
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="e_machine_table">
                            <thead>
                            <tr>
                                <th rowspan="2">
                                    <center>ชื่อเครื่องจักร/<br/>อุปกรณ์หลัก</center>
                                </th>
                                <th colspan="2" width="15%">
                                    <center>พิกัด</center>
                                </th>
                                <th rowspan="2" width="5%">
                                    <center>จำนวน<br/>(หน่วย)</center>
                                </th>
                                <th rowspan="2" width="10%">
                                    <center>อายุการใช้งาน<br/>(ปี)</center>
                                </th>
                                <th rowspan="2">
                                    <center>ชัวโมงการใช้งาน<br/>เฉลี่ยต่อปี<br/>(ชั่วโมง/ปี)</center>
                                </th>
                                <th rowspan="2">
                                    <center>ปริมาณการใช้<br/>พลังงานไฟฟ้า<br/>(กิโลวัตต์-ชั่วโมง/ปี)</center>
                                </th>
                                <th rowspan="2">
                                    <center>สัดส่วนการใช้<br/>พลังงานในระบบ</center>
                                </th>
                                <th rowspan="2">
                                    <center>หมายเหตุ</center>
                                </th>
                                <th rowspan="2" width="5%">
                                    <center>ตัวเลือก</center>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <center>ขนาด</center>
                                </th>
                                <th>
                                    <center>หน่วย</center>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($edit))
                                @if(count($EUFM) > 0 )
                                    @for($i=0;$i < count($EUFM);$i++)
                                        {!! Form::hidden("EUFM_id[]", $EUFM[$i]['id'],[])!!}
                                        <tr>
                                            <td>
                                                {!! Form::select("e_machine_id[]",$MAMT, $EUFM[$i]['machine_and_main_tools']['id'], ["class"=>"md-form-control select"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("e_machine_size[]", $EUFM[$i]['size'], ["class"=>"md-form-control","step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::text("e_machine_size_unit[]", $EUFM[$i]['machine_and_main_tools']['unit'], ["class"=>"md-form-control ",'readonly']) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("e_machine_amount[]", $EUFM[$i]['amount'], ["class"=>"md-form-control", "step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("e_machine_life_time[]", $EUFM[$i]['life_time'], ["class"=>"md-form-control ","step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("e_hour_per_year[]", $EUFM[$i]['work_hous'], ["class"=>"md-form-control ","step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("e_electric_using[]", $EUFM[$i]['average_per_year'], ["class"=>"md-form-control ","step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::text("e_system_energy[]", $EUFM[$i]['persentage'], ["class"=>"md-form-control "]) !!}
                                            </td>
                                            <td>
                                                {!! Form::text("e_ps[]", $EUFM[$i]['note'], ["class"=>"md-form-control "]) !!}
                                            </td>
                                            <td class="demo-icons">
                                                <a class="electric icon icon-close " title="ลบ"></a>
                                            </td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr>
                                        <td>
                                            {!! Form::select("e_machine_id[]", $MAMT,0, ["class"=>"md-form-control select"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("e_machine_size[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text("e_machine_size_unit[]", null, ["class"=>"md-form-control ",'readonly']) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("e_machine_amount[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("e_machine_life_time[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("e_hour_per_year[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("e_electric_using[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text("e_system_energy[]", null, ["class"=>"md-form-control "]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text("e_ps[]", null, ["class"=>"md-form-control "]) !!}
                                        </td>
                                        <td class="demo-icons">
                                            <a class="electric icon icon-close " title="ลบ"></a>
                                        </td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td>
                                        {!! Form::select("e_machine_id[]", $MAMT,0, ["class"=>"md-form-control select"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("e_machine_size[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::text("e_machine_size_unit[]", null, ["class"=>"md-form-control ",'readonly']) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("e_machine_amount[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("e_machine_life_time[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("e_hour_per_year[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("e_electric_using[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::text("e_system_energy[]", null, ["class"=>"md-form-control "]) !!}
                                    </td>
                                    <td>
                                        {!! Form::text("e_ps[]", null, ["class"=>"md-form-control "]) !!}
                                    </td>
                                    <td class="demo-icons">
                                        <a class="electric icon icon-clos " title="ลบ"></a>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-6">
                <h4><span class="bg-danger rounded sq-28"><span class="icon icon-fire"></span></span>
                    การใช้พลังงานความร้อน</h4>
            </div>
            <div class="col-xs-6">
                <button class="btn btn-sm btn-info pull-right " type="button" id="add_machine_f"><span
                            class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มเครื่องจักร
                </button>
            </div>
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-hover" id="f_machine_table">
                            <thead>
                            <tr>
                                <th rowspan="2">
                                    <center>ชื่อเครื่องจักร/<br/>อุปกรณ์หลัก</center>
                                </th>
                                <th colspan="2" width="15%">
                                    <center>พิกัด</center>
                                </th>
                                <th rowspan="2" width="5%">
                                    <center>จำนวน<br/>(หน่วย)</center>
                                </th>
                                <th rowspan="2" width="10%">
                                    <center>อายุการใช้งาน<br/>(ปี)</center>
                                </th>
                                <th rowspan="2">
                                    <center>ชัวโมงการใช้งาน<br/>เฉลี่ยต่อปี<br/>(ชั่วโมง/ปี)</center>
                                </th>
                                <th colspan="2">
                                    <center>การใช้เชื้อเพลิง</center>
                                </th>
                                <th rowspan="2">
                                    <center>ปริมาณการใช้<br/>พลังงานไฟฟ้า<br/>(เมกะจูล/ปี)</center>
                                </th>
                                <th rowspan="2">
                                    <center>สัดส่วนการใช้<br/>พลังงานในระบบ</center>
                                </th>
                                <th rowspan="2">
                                    <center>หมายเหตุ</center>
                                </th>
                                <th rowspan="2" width="5%">
                                    <center>ตัวเลือก</center>
                                </th>
                            </tr>
                            <tr>
                                <th>
                                    <center>ขนาด</center>
                                </th>
                                <th>
                                    <center>หน่วย</center>
                                </th>
                                <th>
                                    <center>ชนิด</center>
                                </th>
                                <th>
                                    <center>หน่วย</center>
                                </th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(isset($edit))
                                @if(count($HPUFM) > 0)
                                    @for($i=0;$i < count($HPUFM);$i++)
                                        {!! Form::hidden("HPUFM_id[]", $HPUFM[$i]['id'],[])!!}
                                        <tr>
                                            <td>
                                                {!! Form::select("f_machine_id[]",$MAMT, $HPUFM[$i]['machine_and_main_tools']['id'], ["class"=>"md-form-control select"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("f_machine_size[]", $HPUFM[$i]['size'], ["class"=>"md-form-control ","step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::text("f_machine_size_unit[]", $HPUFM[$i]['machine_and_main_tools']['unit'], ["class"=>"md-form-control ", "required" =>"",'readonly']) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("f_machine_amount[]", $HPUFM[$i]['amount'], ["class"=>"md-form-control ","step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("f_machine_life_time[]", $HPUFM[$i]['life_time'], ["class"=>"md-form-control ","step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("f_hour_per_year[]", $HPUFM[$i]['work_hous'], ["class"=>"md-form-control ","step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::select("energy_type[]",$energy4select, $HPUFM[$i]['machine_and_main_tools']['id'], ["class"=>"md-form-control select"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::text("unit_en[]", $HPUFM[$i]['unit_en'], ["class"=>"md-form-control ",'readonly']) !!}
                                            </td>
                                            <td>
                                                {!! Form::number("f_electric_using[]", $HPUFM[$i]['average_per_year'], ["class"=>"md-form-control ","step"=>"any"]) !!}
                                            </td>
                                            <td>
                                                {!! Form::text("f_system_energy[]", $HPUFM[$i]['persentage'], ["class"=>"md-form-control "]) !!}
                                            </td>
                                            <td>
                                                {!! Form::text("f_ps[]", $HPUFM[$i]['note'], ["class"=>"md-form-control "]) !!}
                                            </td>
                                            <td class="demo-icons">
                                                <a class="fire icon icon-close " title="ลบ"></a>
                                            </td>
                                        </tr>
                                    @endfor
                                @else
                                    <tr>
                                        <td>
                                            {!! Form::select("f_machine_id[]", $MAMT,0, ["class"=>"md-form-control select "]) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("f_machine_size[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text("f_machine_size_unit[]", null, ["class"=>"md-form-control ",'readonly']) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("f_machine_amount[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("f_machine_life_time[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("f_hour_per_year[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::select("energy_type[]", $energy4select,0, ["class"=>"md-form-control select "]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text("unit_en[]", 0, ["class"=>"md-form-control ",'readonly']) !!}
                                        </td>
                                        <td>
                                            {!! Form::number("f_electric_using[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text("f_system_energy[]", null, ["class"=>"md-form-control "]) !!}
                                        </td>
                                        <td>
                                            {!! Form::text("f_ps[]", null, ["class"=>"md-form-control "]) !!}
                                        </td>
                                        <td class="demo-icons">
                                            <a class="fire icon icon-close " title="ลบ"></a>
                                        </td>
                                    </tr>
                                @endif
                            @else
                                <tr>
                                    <td>
                                        {!! Form::select("f_machine_id[]", $MAMT,0, ["class"=>"md-form-control select "]) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("f_machine_size[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::text("f_machine_size_unit[]", null, ["class"=>"md-form-control ",'readonly']) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("f_machine_amount[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("f_machine_life_time[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("f_hour_per_year[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::select("energy_type[]", $energy4select,0, ["class"=>"md-form-control select "]) !!}
                                    </td>
                                    <td>
                                        {!! Form::text("unit_en[]", 0, ["class"=>"md-form-control ",'readonly']) !!}
                                    </td>
                                    <td>
                                        {!! Form::number("f_electric_using[]", null, ["class"=>"md-form-control ","step"=>"any"]) !!}
                                    </td>
                                    <td>
                                        {!! Form::text("f_system_energy[]", null, ["class"=>"md-form-control "]) !!}
                                    </td>
                                    <td>
                                        {!! Form::text("f_ps[]", null, ["class"=>"md-form-control "]) !!}
                                    </td>
                                    <td class="demo-icons">
                                        <a class="fire icon icon-close " title="ลบ"></a>
                                    </td>
                                </tr>
                            @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <input type="submit" class="btn btn-primary btn-block " value="บันทึกข้อมูล">
            </div>
        {!! Form::close() !!}
        <!-- ******************************************* -->


        </div>
        @endsection


        @section('script')
            <script type="text/javascript" src="{{ asset('custom_assets/js/building/process6th.js')}}"></script>
@stop