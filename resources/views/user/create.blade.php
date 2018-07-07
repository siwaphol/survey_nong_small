@extends('LayOut')

@section('content')
<div class="panel panel-body">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item"><a href="{{ url('/user') }}">ผู้ใช้งาน</a></li>  
        <li class="breadcrumb-item active"><a href="{{ url('/user/create') }}">เพิ่มผู้ใช้งาน</a></li>  
    </ol>
</div>
<div class="panel panel-body">
    <div class="row">
        <div class="col-xs-6">
            <h4><span class="bg-danger rounded sq-28"><span class=" icon icon-users"></span></span> เพิ่มผู้ใช้งาน
            </h4>
        </div>
        <div class="col-xs-6">

        </div>
    </div>
    <!-- Form Section -->
    <!-- creat  -->
    {!! Form::open(['url' => '/user','class' => 'form-horizontal', 'data-toggle' => 'md-validator', 'novalidate' => 'novalidate'])!!}
    <div class="card">
        <div class="card-body">
            @if (count($errors) > 0)
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            <div class="row">
                <div class="col-md-6">
                    <div class="md-form-group md-label-floating">
                        {!! Form::email("email", null, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                        <small class="md-help-block text-red">* Email จะต้องไม่ซ้ำกับคนอื่น</small>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="md-form-group md-label-floating">
                        {!! Form::select("level", $level,null, ["class"=>"md-form-control"]) !!}
                        <small class="md-help-block">* ประเภทของผู้ใช้งาน</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="md-form-group md-label-floating">
                        {!! Form::text("name", null, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                        <small class="md-help-block">* ชื่อ - นามสกุล</small>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="md-form-group md-label-floating">
                        {!! Form::select("area", $area,null, ["class"=>"md-form-control"]) !!}
                        <small class="md-help-block">* เขตที่รับผิดชอบ</small>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="md-form-group md-label-floating">
                        {!! Form::text("tel", null, ["class"=>"md-form-control", "required" =>"true","spellcheck"=>"false","aria-required"=>"true"]) !!}
                        <small class="md-help-block">* เบอร์โทร</small>
                    </div>
                </div>
            </div>
            <!--div class="row">
                <div class="col-md-6">
                    <div class="md-form-group md-label-floating">
                        <input type="password" name="password" id="password" class="md-form-control" required  >
                        <small class="md-help-block">* รหัสผ่าน</small>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="md-form-group md-label-floating">
                        <input type="password" name="confirm-password" class="md-form-control" required equalTo="#password" >
                        <small class="md-help-block">* ยืนยันรหัสผ่าน</small>
                    </div>
                </div>
            </div-->
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-block">บันทึกข้อมูล</button>
{!! Form::close()!!}
<!-- ***
**************************************** -->
</div>

@endsection

@section('script')

@stop