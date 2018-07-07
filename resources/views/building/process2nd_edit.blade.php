@extends('LayOut')

@section('content')
    <div class="panel panel-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">อาคาร</a></li>
            <li class="breadcrumb-item"><a href="#">{{$data->code}}</a></li>
            <li class="breadcrumb-item active">ข้อมูลทั่วไปอาคาร</li>
        </ol>
    </div>

    @include('partials._breadcrumb_steps',['progress_type'=> \App\Main::PROGRESS_BUILDING,'progressNo'=>2])

    <div class="panel panel-body">

        <!-- Form Section -->
        {!! Form::open(['class' => 'form-horizontal','method' => 'put' ,'id'=>'p2','url'=>'building/process2/'.$doc_number, 'data-toggle' => 'md-validator', 'novalidate' => 'novalidate'])!!}

        {!! Form::hidden("doc_number", $doc_number,[])!!}

        <h4><span class="sidenav-icon icon icon-building"></span> อาคาร : รายละเอียกการใช้งานอาคารสำหรับอาคารทุกประเภท
            <button class="btn btn-default pull-right " type="button" id="add-building-btn"></span> เพิ่มอาคาร</button>
        </h4>

        @if(count($building_data)>0)
            @foreach($building_data as $building_info)
                <div class="card building-template">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xs-6">
                                <div class="md-form-group md-label-static">
                                    {!! Form::text('building_name[]',
                                    $building_info->name,
                                    ["class"=>"md-form-control text-building_name",
                                    "required" =>"","spellcheck"=>"false"
                                    ,"aria-required"=>"true"]) !!}
                                    {!! Form::label('building_name_label'
                                    , 'ชื่ออาคาร', [
                                    "class" => "md-control-label"]) !!}
                                    {!! Form::hidden('b_id[]',
                                    $building_info->id,
                                    ["class"=>"text-b_id"]) !!}
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="md-form-group md-label-static">
                                    {!! Form::number('openy[]',$building_info->open, ["class"=>"md-form-control text-openy", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                    {!! Form::label('openy_text', 'เปิดใช้งานปี พ.ศ.', ["class" => "md-control-label"]) !!}
                                </div>
                            </div>
                            <div class="col-xs-2 text-right">
                                <button class="btn btn-default pull-right " data-building-id="{{$building_info->id}}"
                                        type="button"
                                        onclick="removeHtmlWithAjax($(this),'{{$doc_number}}','{{$building_info->id}}')"
                                >ลบอาคาร
                                </button>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-1">
                                <div class="md-form-group md-label-static">
                                    {!! Form::label('workh_text', 'เวลาทำงาน', ["class" => "md-control-label"]) !!}
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="md-form-group md-label-static">
                                    {!! Form::number('work_hour[]', $building_info->work_hour_hr_d ,['id'=>'work_hour', 'class' => 'md-form-control text-work_hour','required' => '', 'aria-required' => 'true']) !!}
                                    <label class="md-control-label">ชั่วโมงการทำงาน</label>
                                    <small class="md-help-block">(ชั่วโมง/วัน)</small>
                                </div>
                            </div>
                            <div class="col-xs-4">
                                <div class="md-form-group md-label-static">
                                    {!! Form::number('work_day[]', $building_info->work_hour_day_y ,['id'=>'work_day', 'class' => 'md-form-control text-work_day','required' => '', 'aria-required' => 'true']) !!}
                                    <label class="md-control-label">ชั่วโมงการทำงาน</label>
                                    <small class="md-help-block">(วัน/ปี)</small>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-2">
                                <div class="md-form-group md-label-static">
                                    {!! Form::number('airspace[]',$building_info->air_conditioned, ["class"=>"md-form-control text-airspace", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                    {!! Form::label('airspace_text', 'พื้นที่ปรับอากาศ', ["class" => "md-control-label"]) !!}
                                    <small class="md-help-block">(ตารางเมตร)</small>
                                </div>
                            </div>

                            <div class="col-xs-2">
                                <div class="md-form-group md-label-static">
                                    {!! Form::number('nonairspace[]',$building_info->non_air_conditioned, ["class"=>"md-form-control text-nonairspace", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                    {!! Form::label('nonairspace_text', 'พื้นที่ไม่ปรับอากาศ', ["class" => "md-control-label"]) !!}
                                    <small class="md-help-block">(ตารางเมตร)</small>
                                </div>
                            </div>

                            <div class="col-xs-2">
                                <div class="md-form-group md-label-static">
                                    {!! Form::number('a_na[]',$building_info->total_1, ["class"=>"md-form-control text-a_na", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                    {!! Form::label('a_na_text', 'รวม', ["class" => "md-control-label"]) !!}
                                    <small class="md-help-block">(ตารางเมตร)</small>
                                </div>
                            </div>

                            <div class="col-xs-2">
                                <div class="md-form-group md-label-static">
                                    {!! Form::number('carspace[]',$building_info->parking_space, ["class"=>"md-form-control text-carspace", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                    {!! Form::label('carspace_text', 'พื้นที่จอดรถในตัวอาคาร', ["class" => "md-control-label"]) !!}
                                    <small class="md-help-block">(ตารางเมตร)</small>
                                </div>
                            </div>
                            <div class="col-xs-2">
                                <div class="md-form-group md-label-static">
                                    {!! Form::number('all[]',$building_info->total_2, ["class"=>"md-form-control text-all", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                    {!! Form::label('all_text', 'รวมทั้งหมด', ["class" => "md-control-label"]) !!}
                                    <small class="md-help-block">(ตารางเมตร)</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @else
            <div class="card building-template" id="building-template">
                <div class="card-body">
                    <div class="row">
                        <div class="col-xs-6">
                            <div class="md-form-group md-label-static">
                                {!! Form::text('building_name[]',null, ["class"=>"md-form-control text-building_name", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                {!! Form::label('building_name_label', 'ชื่ออาคาร', ["class" => "md-control-label"]) !!}
                                {!! Form::hidden('b_id[]',null,["class"=>"text-b_id"]) !!}
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="md-form-group md-label-static">
                                {!! Form::number('openy[]',null, ["class"=>"md-form-control text-openy", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                {!! Form::label('openy_text', 'เปิดใช้งานปี พ.ศ.', ["class" => "md-control-label"]) !!}
                            </div>
                        </div>
                        {{--<div class="col-xs-2 text-right">--}}
                        {{--<button class="btn btn-default pull-right"  type="button" v-on:click="deletebuilding_e(building.id)">ลบอาคาร</button>--}}
                        {{--</div>--}}
                    </div>
                    <div class="row">
                        <div class="col-xs-1">
                            <div class="md-form-group md-label-static">
                                {!! Form::label('workh_text', 'เวลาทำงาน', ["class" => "md-control-label"]) !!}
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="md-form-group md-label-static">
                                {!! Form::number('work_hour[]', null ,['id'=>'work_hour', 'class' => 'md-form-control text-work_hour','required' => '', 'aria-required' => 'true']) !!}
                                <label class="md-control-label">ชั่วโมงการทำงาน</label>
                                <small class="md-help-block">(ชั่วโมง/วัน)</small>
                            </div>
                        </div>
                        <div class="col-xs-4">
                            <div class="md-form-group md-label-static">
                                {!! Form::number('work_day[]', null ,['id'=>'work_day', 'class' => 'md-form-control text-work_day','required' => '', 'aria-required' => 'true']) !!}
                                <label class="md-control-label">ชั่วโมงการทำงาน</label>
                                <small class="md-help-block">(วัน/ปี)</small>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-xs-2">
                            <div class="md-form-group md-label-static">
                                {!! Form::number('airspace[]',null, ["class"=>"md-form-control text-airspace", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                {!! Form::label('airspace_text', 'พื้นที่ปรับอากาศ', ["class" => "md-control-label"]) !!}
                                <small class="md-help-block">(ตารางเมตร)</small>
                            </div>
                        </div>

                        <div class="col-xs-2">
                            <div class="md-form-group md-label-static">
                                {!! Form::number('nonairspace[]',null, ["class"=>"md-form-control text-nonairspace", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                {!! Form::label('nonairspace_text', 'พื้นที่ไม่ปรับอากาศ', ["class" => "md-control-label"]) !!}
                                <small class="md-help-block">(ตารางเมตร)</small>
                            </div>
                        </div>

                        <div class="col-xs-2">
                            <div class="md-form-group md-label-static">
                                {!! Form::number('a_na[]',null, ["class"=>"md-form-control text-a_na", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                {!! Form::label('a_na_text', 'รวม', ["class" => "md-control-label"]) !!}
                                <small class="md-help-block">(ตารางเมตร)</small>
                            </div>
                        </div>

                        <div class="col-xs-2">
                            <div class="md-form-group md-label-static">
                                {!! Form::number('carspace[]',null, ["class"=>"md-form-control text-carspace", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                {!! Form::label('carspace_text', 'พื้นที่จอดรถในตัวอาคาร', ["class" => "md-control-label"]) !!}
                                <small class="md-help-block">(ตารางเมตร)</small>
                            </div>
                        </div>
                        <div class="col-xs-2">
                            <div class="md-form-group md-label-static">
                                {!! Form::number('all[]',null, ["class"=>"md-form-control text-all", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                {!! Form::label('all_text', 'รวมทั้งหมด', ["class" => "md-control-label"]) !!}
                                <small class="md-help-block">(ตารางเมตร)</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{--<div id="example">--}}
            {{--<!-- <component is="my-component"></component> -->--}}
        {{--</div>--}}

        <button class="btn btn-primary btn-block " id="save">Save Changes</button>
        {!! Form::close()!!}
    </div>
    <!-- ******************************************* -->

    <div class="hidden">
        <div class="card" pid="building-template" id="building_clone">
            <div class="card-body">
                <div class="row">
                    <div class="col-xs-6">
                        <div class="md-form-group md-label-static">
                            {!! Form::text('building_name[]',null, ["class"=>"md-form-control text-building_name", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                            {!! Form::label('building_name_label', 'ชื่ออาคาร', ["class" => "md-control-label"]) !!}
                            {!! Form::hidden('b_id[]','new',["class"=>"text-b_id"]) !!}
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="md-form-group md-label-static">
                            {!! Form::number('openy[]',null, ["class"=>"md-form-control text-openy", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                            {!! Form::label('openy_text', 'เปิดใช้งานปี พ.ศ.', ["class" => "md-control-label"]) !!}
                        </div>
                    </div>
                    <div class="col-xs-2 text-right">
                    <button class="btn btn-default pull-right " type="button" onclick="removeHtml($(this))">ลบอาคาร</button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-1">
                        <div class="md-form-group md-label-static">
                            {!! Form::label('workh_text', 'เวลาทำงาน', ["class" => "md-control-label"]) !!}
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="md-form-group md-label-static">
                            {!! Form::number('work_hour[]', null ,['id'=>'work_hour', 'class' => 'md-form-control text-work_hour','required' => '', 'aria-required' => 'true']) !!}
                            <label class="md-control-label">ชั่วโมงการทำงาน</label>
                            <small class="md-help-block">(ชั่วโมง/วัน)</small>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="md-form-group md-label-static">
                            {!! Form::number('work_day[]', null ,['id'=>'work_day', 'class' => 'md-form-control text-work_day','required' => '', 'aria-required' => 'true']) !!}
                            <label class="md-control-label">ชั่วโมงการทำงาน</label>
                            <small class="md-help-block">(วัน/ปี)</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-xs-2">
                        <div class="md-form-group md-label-static">
                            {!! Form::number('airspace[]',null, ["class"=>"md-form-control text-airspace", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                            {!! Form::label('airspace_text', 'พื้นที่ปรับอากาศ', ["class" => "md-control-label"]) !!}
                            <small class="md-help-block">(ตารางเมตร)</small>
                        </div>
                    </div>

                    <div class="col-xs-2">
                        <div class="md-form-group md-label-static">
                            {!! Form::number('nonairspace[]',null, ["class"=>"md-form-control text-nonairspace", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                            {!! Form::label('nonairspace_text', 'พื้นที่ไม่ปรับอากาศ', ["class" => "md-control-label"]) !!}
                            <small class="md-help-block">(ตารางเมตร)</small>
                        </div>
                    </div>

                    <div class="col-xs-2">
                        <div class="md-form-group md-label-static">
                            {!! Form::number('a_na[]',null, ["class"=>"md-form-control text-a_na", "required" =>"","spellcheck"=>"false","aria-required"=>"true",'readonly']) !!}
                            {!! Form::label('a_na_text', 'รวม', ["class" => "md-control-label"]) !!}
                            <small class="md-help-block">(ตารางเมตร)</small>
                        </div>
                    </div>

                    <div class="col-xs-2">
                        <div class="md-form-group md-label-static">
                            {!! Form::number('carspace[]',null, ["class"=>"md-form-control text-carspace", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                            {!! Form::label('carspace_text', 'พื้นที่จอดรถในตัวอาคาร', ["class" => "md-control-label"]) !!}
                            <small class="md-help-block">(ตารางเมตร)</small>
                        </div>
                    </div>
                    <div class="col-xs-2">
                        <div class="md-form-group md-label-static">
                            {!! Form::number('all[]',null, ["class"=>"md-form-control text-all", "required" =>"","spellcheck"=>"false","aria-required"=>"true",'readonly']) !!}
                            {!! Form::label('all_text', 'รวมทั้งหมด', ["class" => "md-control-label"]) !!}
                            <small class="md-help-block">(ตารางเมตร)</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    {{--<script src="{{asset('js/vue.js')}}"></script>--}}
    {{--<script src="{{asset('js/vue-resource.js')}}"></script>--}}
    <script type="text/javascript" src="{{ asset('custom_assets/js/building/process2_share.js')}}"></script>
    <script type="text/javascript" src="{{ asset('custom_assets/js/building/process2nd_edit.js')}}"></script>
@stop