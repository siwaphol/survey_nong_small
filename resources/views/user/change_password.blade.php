@extends('LayOut')

@section('content')
    <div class="panel panel-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active"><a href="{{ url('/change-password') }}">เปลี่ยนรหัสผ่าน</a></li>
        </ol>
    </div>
    <div class="panel panel-body">
        <div class="row">
            <div class="col-xs-6">
                <h4><span class="bg-danger rounded sq-28"><span class=" icon icon-users"></span></span> เปลี่ยนรหัสผ่าน
                </h4>
            </div>
            <div class="col-xs-6">

            </div>
        </div>
        <!-- Form Section -->
        <!-- creat  -->
        {!! Form::open(['url' => '/change-password','class' => 'form-horizontal', 'data-toggle' => 'md-validator', 'novalidate' => 'novalidate'])!!}
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
                @if (session('status'))
                    <div class="alert alert-success">
                        <ul>
                            <li>{{ session('status') }}</li>
                        </ul>
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form-group md-label-floating">
                            <input type="password" name="password" id="password" class="md-form-control" required>
                            <small class="md-help-block">* รหัสผ่านปัจจุบัน</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form-group md-label-floating">
                            <input type="password" name="new_password" id="new_password" class="md-form-control"
                                   required>
                            <small class="md-help-block">* รหัสผ่านใหม่</small>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="md-form-group md-label-floating">
                            <input type="password" name="confirm_new_password" class="md-form-control" required
                                   equalTo="#new_password">
                            <small class="md-help-block">* ยืนยันรหัสผ่านใหม่</small>
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

@endsection

@section('script')

@stop