@extends('LayOut')

@section('head')
    <link rel="stylesheet" href="{{asset('assets/css/errors.min.css')}}">
@endsection

@section('content')
    <div class="error">
        <div class="error-body">
            <h1 class="error-heading">ไม่พบหน้าที่ต้องการ</h1>
            <h4 class="error-subheading">ไม่พบหน้าที่ต้องการ กรุณาตรวจสอบ</h4>
            <p><small>ลิงก์ที่ท่านกรอกไม่ถูกต้อง หรือไม่มีลิงก์ที่ร้องขอในระบบ กรุณาตรวจสอบลิงก์ให้ถูกต้อง</small></p>
            <p><a class="btn btn-primary btn-pill btn-thick" href="{{url('/')}}">กลับไปยังหน้าแรก</a></p>
        </div>
    </div>
@stop