@extends('LayOut')

@section('content')
    <div class="panel panel-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="#">Home</a></li>
            <li class="breadcrumb-item"><a href="#">อาคาร</a></li>
            @if(isset($main))
                <li class="breadcrumb-item"><a href="#">{{$main->code}}</a></li>
            @endif
            <li class="breadcrumb-item active">ข้อมูลทั่วไป</li>
        </ol>
    </div>

    @include('partials._breadcrumb_steps',['progress_type'=> \App\Main::PROGRESS_BUILDING,'progressNo'=>1])

    <div class="panel panel-body">
        <h4><span class="bg-danger rounded sq-28"><span class=" icon icon-building"></span></span> ข้อมูลทั่วไป</h4>

        <!-- Form Section -->
        @if(isset($doc_number))
            {!! Form::open(['url' => 'building/process1/'.$doc_number,'method' => 'put','class' => 'form-horizontal', 'id' => 'p1_edit', 'data-toggle' => 'md-validator', 'novalidate' => 'novalidate'])!!}
            <div class="card">
                <div class="card-body">
                    <div class="md-form-group md-label-floating">
                        {!! Form::text("corporation_name", $main->person_name, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                        {!! Form::label('corporation_name_label',"ชื่อนิติบุคคล", ["class" => "md-control-label"]) !!}
                        <small class="md-help-block">คำอธิบาย</small>
                    </div>
                    <div class="md-form-group md-label-floating">
                        {!! Form::text("building_name", $main->place_name, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                        {!! Form::label('building_name_label',"ชื่ออาคาร", ["class" => "md-control-label"]) !!}
                        <small class="md-help-block">คำอธิบาย</small>
                    </div>
                </div>
            </div>
            @forelse($place as $place_data)
                @if($place_data->type=="อาคาร")
                    <div class="card">
                        <div class="card-body">
                            <h4><span class="icon icon-pencil-square-o"></span> ที่อยู่อาคาร</h4>
                            <div>
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="md-form-group md-label-static">
                                            {{--{!! Form::text("building_province", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}--}}
                                            {!! Form::select("building_province",$province_select, $place_data->province, ["class"=>"md-form-control select","id" => "province-select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
                                            {!! Form::label('building_province_label',"จังหวัด", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="md-form-group md-label-floating">
                                            {{--{!! Form::text("building_district", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}--}}
                                            {!! Form::select("building_district",$district_select, $place_data->district, ["class"=>"md-form-control select","id" => "district-select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
                                            {!! Form::label('building_district_label',"อำเภอ/เขต", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="md-form-group md-label-floating">
                                            {{--{!! Form::text("building_sub_district", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}--}}
                                            {{--{!! Form::select("building_sub_district",$sub_district_select,null,["class"=>"select-search","spellcheck"=>"false","aria-required"=>"true"]) !!}--}}
                                            {!! Form::select("building_sub_district",$sub_district_select, $place_data->sub_district, ["class"=>"md-form-control select","id" => "sub-district-select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
                                            {!! Form::label('building_sub_district_label',"ตำบล/แขวง", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_number", $place_data->house_number, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_number_label',"เลขที่", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_village_number", $place_data->village_number, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_village_number_label',"หมู่", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_road", $place_data->road, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_road_label',"ถนน", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_postal_code", $place_data->post_code, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_postal_code_label',"รหัสไปรษณีย์", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_phone_number", $place_data->phone_number, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('buildingy_phone_number_label',"โทรศัพท์", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_fax", $place_data->fax, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_fax_label',"โทรสาร", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_email", $place_data->email, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_email_number_label',"E-mail", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_latitude", $place_data->latitude, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_latitude_label',"Latitude", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_longitude", $place_data->longitude, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_longitude_label',"Longitude", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                @endif
            @empty

            @endforelse


            <div class="card">
                <div class="card-body">
                    <h4><span class="icon icon-pencil-square-o"></span> ประเภทอาคาร</h4>
                    <div class="">
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="md-form-group">
                                    <label class="custom-control custom-control-success custom-radio">
                                        @if(in_array("1",$place_type["name"]))
                                            {!! Form::radio("building_type_select",1, true, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @else
                                            {!! Form::radio("building_type_select",1, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @endif
                                        <span class="custom-control-indicator"></span>
                                        <small class="custom-control-label">สำนักงานเอกชน</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="md-form-group">
                                    <label class="custom-control custom-control-success custom-radio">
                                        @if(in_array("2",$place_type["name"]))
                                            {!! Form::radio("building_type_select",2, true, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @else
                                            {!! Form::radio("building_type_select",2, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @endif
                                        <span class="custom-control-indicator"></span>
                                        <small class="custom-control-label">สำนักงานรัฐบาล</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="md-form-group">
                                    <label class="custom-control custom-control-success custom-radio">
                                        @if(in_array("3",$place_type["name"]))
                                            {!! Form::radio("building_type_select",3, true, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @else
                                            {!! Form::radio("building_type_select",3, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @endif
                                        <span class="custom-control-indicator"></span>
                                        <small class="custom-control-label">โรงแรม</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="md-form-group">
                                    <label class="custom-control custom-control-success custom-radio">
                                        @if(in_array("4",$place_type["name"]))
                                            {!! Form::radio("building_type_select",4, true, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @else
                                            {!! Form::radio("building_type_select",4, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @endif
                                        <span class="custom-control-indicator"></span>
                                        <small class="custom-control-label">โรงพยาบาล</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xs-3">
                                <div class="md-form-group">
                                    <label class="custom-control custom-control-success custom-radio">
                                        @if(in_array("5",$place_type["name"]))
                                            {!! Form::radio("building_type_select",5, true, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @else
                                            {!! Form::radio("building_type_select",5, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @endif
                                        <span class="custom-control-indicator"></span>
                                        <small class="custom-control-label">ห้างสรรพสินค้า</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="md-form-group">
                                    <label class="custom-control custom-control-success custom-radio">
                                        @if(in_array("6",$place_type["name"]))
                                            {!! Form::radio("building_type_select",6, true, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @else
                                            {!! Form::radio("building_type_select",6, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @endif
                                        <span class="custom-control-indicator"></span>
                                        <small class="custom-control-label">ฟาร์มปศุสัตว์</small>
                                    </label>
                                </div>
                            </div>
                            <div class="col-xs-3">
                                <div class="md-form-group">
                                    <label class="custom-control custom-control-success custom-radio">
                                        @if(in_array("7",$place_type["name"]))
                                            {!! Form::radio("building_type_select",7, true, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @else
                                            {!! Form::radio("building_type_select",7, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        @endif
                                        <span class="custom-control-indicator"></span>
                                        <small class="custom-control-label">โรงเรียน</small>
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-body">
                        <div class="">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="md-form-group md-label-floating">
                                        {!! Form::text("total_employee", $main['employee_amount'], ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        {!! Form::label('total_employee_label',"จำนวนพนักงาน", ["class" => "md-control-label"]) !!}
                                        <small class="md-help-block">คน</small>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="md-form-group md-label-floating">
                                        {!! Form::number("total_building", $main['building_amount'], ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                        {!! Form::label('total_building_label',"จำนวนอาคารทั้งหมด", ["class" => "md-control-label"]) !!}
                                        <small class="md-help-block">อาคาร</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card">
                        <div class="card-body">
                            <div class="">
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("name", $main['contact_name'], ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('name_label',"ผู้ประสานงาน", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("tel", $main['contact_number'], ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('tel_label',"โทร", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <a class="btn btn-primary btn-block " id="save_edit">บันทึกข้อมูล</a>
                </div>


                @else


                    {!! Form::open(['method' => 'post','url' => 'building/process1','class' => 'form-horizontal', 'id' => 'p1', 'data-toggle' => 'md-validator', 'novalidate' => 'novalidate'])!!}

                    <div class="card">
                        <div class="card-body">
                            <div class="md-form-group md-label-floating">
                                {!! Form::text("corporation_name", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                {!! Form::label('corporation_name_label',"ชื่อนิติบุคคล", ["class" => "md-control-label"]) !!}
                                <small class="md-help-block">คำอธิบาย</small>
                            </div>

                            <div class="md-form-group md-label-floating">
                                {!! Form::text("building_name", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                {!! Form::label('building_name_label',"ชื่ออาคาร", ["class" => "md-control-label"]) !!}
                                <small class="md-help-block">คำอธิบาย</small>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4><span class="icon icon-pencil-square-o"></span> ที่อยู่อาคาร</h4>

                            <div class="">
                                <div class="row">
                                    <div class="col-xs-4">
                                        <div class="md-form-group md-label-static">
                                            {{--{!! Form::text("building_province", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}--}}
                                            {!! Form::select("building_province",$province_select, null, ["class"=>"md-form-control select","id" => "province-select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
                                            {!! Form::label('building_province_label',"จังหวัด", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="md-form-group md-label-floating">
                                            {{--{!! Form::text("building_district", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}--}}
                                            {!! Form::select("building_district",$district_select, null, ["class"=>"md-form-control select","id" => "district-select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
                                            {!! Form::label('building_district_label',"อำเภอ/เขต", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-4">
                                        <div class="md-form-group md-label-floating">
                                            {{--{!! Form::text("building_sub_district", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}--}}
                                            {{--{!! Form::select("building_sub_district",$sub_district_select,null,["class"=>"select-search","spellcheck"=>"false","aria-required"=>"true"]) !!}--}}
                                            {!! Form::select("building_sub_district",$sub_district_select, null, ["class"=>"md-form-control select","id" => "sub-district-select","spellcheck"=>"false","data-live-seaech" => "true"]) !!}
                                            {!! Form::label('building_sub_district_label',"ตำบล/แขวง", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_number", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_number_label',"เลขที่", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_village_number", null, ["class"=>"md-form-control","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_village_number_label',"หมู่", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_road", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_road_label',"ถนน", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_postal_code", null, ["class"=>"md-form-control", "required" =>"true",'min' => '0',"spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_postal_code_label',"รหัสไปรษณีย์", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_phone_number", null, ["class"=>"md-form-control","maxlength"=> "15", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_phone_number_label',"โทรศัพท์", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_fax", null, ["class"=>"md-form-control","maxlength"=> "15","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_fax_label',"โทรสาร", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-12">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::email("building_email", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_email_number_label',"E-mail", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_latitude", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_latitude_label',"Latitude", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                    <div class="col-xs-6">
                                        <div class="md-form-group md-label-floating">
                                            {!! Form::text("building_longitude", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                            {!! Form::label('building_longitude_label',"Longitude", ["class" => "md-control-label"]) !!}
                                            <small class="md-help-block">คำอธิบาย</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4><span class="icon icon-pencil-square-o"></span> ประเภทอาคาร</h4>
                            <div class="">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="md-form-group">
                                            <label class="custom-control custom-control-primary custom-radio">
                                                {!! Form::radio("building_type_select",1, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                                <span class="custom-control-indicator"></span>
                                                <small class="custom-control-label">สำนักงานเอกชน</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group">
                                            <label class="custom-control custom-control-primary custom-radio">
                                                {!! Form::radio("building_type_select",2, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                                <span class="custom-control-indicator"></span>
                                                <small class="custom-control-label">สำนักงานรัฐบาล</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group">
                                            <label class="custom-control custom-control-primary custom-radio">
                                                {!! Form::radio("building_type_select",3, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                                <span class="custom-control-indicator"></span>
                                                <small class="custom-control-label">โรงแรม</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group">
                                            <label class="custom-control custom-control-primary custom-radio">
                                                {!! Form::radio("building_type_select",4, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                                <span class="custom-control-indicator"></span>
                                                <small class="custom-control-label">โรงพยาบาล</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="md-form-group">
                                            <label class="custom-control custom-control-primary custom-radio">
                                                {!! Form::radio("building_type_select",5, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                                <span class="custom-control-indicator"></span>
                                                <small class="custom-control-label">ห้างสรรพสินค้า</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group">
                                            <label class="custom-control custom-control-primary custom-radio">
                                                {!! Form::radio("building_type_select",6, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                                <span class="custom-control-indicator"></span>
                                                <small class="custom-control-label">ฟาร์มปศุสัตว์</small>
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-xs-3">
                                        <div class="md-form-group">
                                            <label class="custom-control custom-control-primary custom-radio">
                                                {!! Form::radio("building_type_select",7, null, ["class"=>"custom-control-input chkbox", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                                <span class="custom-control-indicator"></span>
                                                <small class="custom-control-label">โรงเรียน</small>
                                            </label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <h4><span class="icon icon-pencil-square-o"></span> สำหรับอาคารทุกประเภท</h4>
                            <div class="col-xs-6">
                                <div class="md-form-group md-label-floating">
                                    <label class="md-control-label col-md-2">จำนวนพนักงานทั้งหมด</label>
                                    <input class="md-form-control" type="number" name="total_employee"
                                           spellcheck="false" required="true" min="0" maxlength="10">
                                    <small class="md-help-block col-md-6">คน
                                    </small>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="md-form-group md-label-floating">
                                    <label class="md-control-label col-md-2">จำนวนอาคารทั้งหมด</label>
                                    <label class="md-control-label col-md-2">จำนวนอาคารทั้งหมด</label>
                                    <input class="md-form-control" type="number" name="total_building"
                                           spellcheck="false" required=true" min="0" maxlength="10">
                                    <small class="md-help-block col-md-6">อาคาร
                                    </small>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="md-form-group md-label-floating hidden" id="total_room">
                                    <label class="md-control-label col-md-2">สำหรับอาคารประเภทโรงแรม
                                        จำนวนห้องพักทั้งหมด</label>
                                    <input class="md-form-control" type="number" name="total-room" id="total_room_text"
                                           spellcheck="false" required maxlength="10">
                                    <small class="md-help-block col-md-6">ห้อง</small>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="md-form-group md-label-floating hidden" id="total_bed">
                                    <label class="md-control-label col-md-2">สำหรับอาคารประเภทโรงพยาบาล
                                        จำนวนเเตียงคนไข้ทั้งหมด</label>
                                    <input class="md-form-control" type="number" name="total-bed" id="total_bed_text"
                                           spellcheck="false" required maxlength="10">
                                    <small class="md-help-block col-md-6">เตียง</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body">
                            <div class="col-xs-6">
                                <div class="md-form-group md-label-floating">
                                    {!! Form::text("contact_name", null, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                    {!! Form::label('contact_name_label',"ผู้ประสานงาน", ["class" => "md-control-label"]) !!}
                                    <small class="md-help-block">คำอธิบาย</small>
                                </div>
                            </div>
                            <div class="col-xs-6">
                                <div class="md-form-group md-label-floating">
                                    {!! Form::text("contact_tel", null, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                                    {!! Form::label('contact_tel_label',"โทร", ["class" => "md-control-label"]) !!}
                                    <small class="md-help-block">คำอธิบาย</small>
                                </div>
                            </div>
                        </div>
                    </div>

                    <a class="btn btn-primary btn-block " id="save">บันทึกข้อมูล</a>
                @endif
                {!! Form::close()!!}
            </div>
            @endsection


        @section('script')
            <script type="text/javascript" src="{{ asset('custom_assets/js/building/process1st.js')}}"></script>
@stop