@extends('LayOut')

@section('content')
    <div class="panel panel-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item"><a href="{{ url('/user') }}">ผู้ใช้งาน</a></li>
            <li class="breadcrumb-item active"><a href="#">แก้ไขข้อมูลผู้ใช้งาน</a></li>  
        </ol>
    </div>
    <div class="panel panel-body">
        <div class="row">
            <div class="col-xs-6">
                <h4><span class="bg-danger rounded sq-28"><span class=" icon icon-users"></span></span> แก้ไขข้อมูลผู้ใช้งาน
                </h4>
            </div>
            <div class="col-xs-6">

            </div>
        </div>
        <!-- Form Section -->
        <!-- creat  -->
        {!! Form::model($user, [ 'method'=>'PUT', 'route' => ['user.update', $user] ,'class' => 'form-horizontal', 'data-toggle' => 'md-validator', 'novalidate' => 'novalidate'])!!}
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
                            <small class="md-help-block">* Email</small>
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
                            {!! Form::text("tel", null, ["class"=>"md-form-control", "required" =>"","spellcheck"=>"false","aria-required"=>"true"]) !!}
                            <small class="md-help-block">* เบอร์โทร</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form-group md-label-floating">
                            {!! Form::text("newPassword", null, ['id'=>'newPassword', "class"=>"md-form-control", "spellcheck"=>"false","aria-required"=>"false"]) !!}
                            <small class="md-help-block">รหัสผ่านใหม่ (<span class="text-red">กรณีต้องการเปลี่ยนรหัสผ่าน</span>)</small>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="md-form-group md-label-floating">
                            {!! Form::text("confirmNewPassword", null, ["class"=>"md-form-control", "equalTo" =>"#newPassword","spellcheck"=>"false","aria-required"=>"true"]) !!}
                            <small class="md-help-block">ยืนยันรหัสผ่าน (<span class="text-red">กรณีต้องการเปลี่ยนรหัสผ่าน</span>)</small>
                        </div>
                    </div>
                </div>


            </div>
        </div>
        <button type="submit" class="btn btn-primary btn-block">บันทึกข้อมูล</button>
    {!! Form::close()!!}
    <!-- ***
    **************************************** -->
    </div>
    <template id="insert-template">

    </template>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('custom_assets/js/user/user_edit.js')}}"></script>
@stop