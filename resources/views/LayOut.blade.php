<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Survey EE</title>

    <meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=no">
    <meta name="description" content="โครงการศึกษาศักยภาพอนุรักษ์พลังงานและพลังงานทดแทน พื้นที่ภาคเหนือ และพัฒนาระบบฐานข้อมูลศักยภาพอนุรักษ์พลังงาน">
    <meta property="og:url" content="http://survey-ee.ete.eng.cmu.ac.th/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="โครงการศึกษาศักยภาพอนุรักษ์พลังงานและพลังงานทดแทน พื้นที่ภาคเหนือ และพัฒนาระบบฐานข้อมูลศักยภาพอนุรักษ์พลังงาน">
    <meta property="og:description" content="โครงการศึกษาศักยภาพอนุรักษ์พลังงานและพลังงานทดแทน พื้นที่ภาคเหนือ และพัฒนาระบบฐานข้อมูลศักยภาพอนุรักษ์พลังงาน">
    <meta property="og:image" content="http://survey-ee.ete.eng.cmu.ac.th/assets/img/logomoen.png">
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:site" content="@naksoid">
    <meta name="twitter:creator" content="@naksoid">
    <meta name="twitter:title" content="โครงการศึกษาศักยภาพอนุรักษ์พลังงานและพลังงานทดแทน พื้นที่ภาคเหนือ และพัฒนาระบบฐานข้อมูลศักยภาพอนุรักษ์พลังงาน">
    <meta name="twitter:description" content="โครงการศึกษาศักยภาพอนุรักษ์พลังงานและพลังงานทดแทน พื้นที่ภาคเหนือ และพัฒนาระบบฐานข้อมูลศักยภาพอนุรักษ์พลังงาน">
    <meta name="twitter:image" content="http://demo.naksoid.com/elephant/img/ae165ef33d137d3f18b7707466aa774d.jpg">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="apple-touch-icon" sizes="180x180" href="{{asset('assets/apple-touch-icon.png')}}">
    <link rel="icon" type="image/png" href="{{asset('assets/favicon-32x32.png')}}" sizes="32x32">
    <link rel="icon" type="image/png" href="{{asset('assets/favicon-16x16.png')}}" sizes="16x16">
    <link rel="manifest" href="{{asset('assets/manifest.json')}}">
    <link rel="mask-icon" href="{{asset('assets/safari-pinned-tab.svg')}}" color="#2c3e50">
    <meta name="theme-color" content="#ffffff">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:300,400,400italic,500,700">
    <link rel="stylesheet" href="{{asset('assets/css/vendor.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/elephant.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/application.min.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/demo.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/sweetalert2.min.css')}}">
    
    <link rel="stylesheet" href="{{asset('css/bootstrap-select.css')}}">
    <link rel="stylesheet" href="{{asset('assets/css/pnotify.css')}}">

    <!-- Custom CSS by LEFTARM -->
    <link rel="stylesheet" href="{{ asset('custom_assets/css/custom.css') }}">



    @yield('head')
</head>
<body class="layout layout-header-fixed">

<div class="layout-header">
@include('partials._navbar')
</div>

<div class="layout-main">
    @include('partials._sidebar')
    <div class="layout-content">
        <div class="layout-content-body">
            <!--<div class="alert alert-info">
                <ul>
                    <li>ขอร่วมมือผู้ใช้งานทุกท่าน กรุณาช่วยตรวจเช็คข้อมูล ที่กรอกเข้าระบบก่อนวันที่ 15 ก.พ. 60 รายละเอียดตามไฟล์  <a href="{{asset('ข้อมูลที่ต้องเช็ค2.pdf')}}" style="color: black" target="_blank">[[ ข้อมูลที่ต้องเช็ค2.pdf ]]</a></li>
                    <li>แจ้งเปลี่ยนแปลงเลขที่อ้างอิงแบบสอบถามอาคาร BUD-X-YYY <a href="{{asset('change_bud_ref.xlsx')}}" target="_blank" style="color: black">[[ change_bud_ref.xlsx ]]</a> โดยข้อมูลส่วนอื่นๆยังคงเดิม</li>
                    <li>แจ้งเปลี่ยนแปลงเลขที่อ้างอิงแบบสอบถามโรงงาน IND-X-YYY <a href="{{asset('change_ind_ref.xlsx')}}" target="_blank" style="color: black">[[ change_ind_ref.xlsx ]]</a> โดยข้อมูลส่วนอื่นๆยังคงเดิม</li>
                    <li>ตั้งแต่วันที่ 15 มิ.ย. 60 เป็นต้นไป  ระบบจะปิดไม่ให้ทำการแก้ไขข้อมูล เพื่อทำการประมวลผล</li
                </ul>
            </div>-->
			<div style="background-color:white; text-align:center; margin-top:10px;">
						<img src="{{asset('assets/img/logomoen_wt.jpg')}}" alt="Survey-EE" height="35px"  width="140px">
						<img src="{{asset('assets/img/logo_eppo.png')}}" alt="Survey-EE" height="60px" style="margin-left:10px;">
			</div>
            <div class="title-bar">
                @yield('title-bar')
            </div>

            @yield('content')

        </div>
    </div>
</div>

<script src="{{asset('assets/js/vendor.min.js')}}"></script>
<script src="{{asset('assets/js/elephant.min.js')}}"></script>
<script src="{{asset('assets/js/application.min.js')}}"></script>
{{--<script src="{{asset('assets/js/demo.min.js')}}"></script>--}}
<script src="{{ asset('js/sweetalert2.min.js')}}"></script>
<script src="{{asset('js/bootstrap-select.js')}}"></script>
<script type="text/javascript" src="{{ asset('assets/js/pnotify.min.js')}}"></script>

<script src="{{asset('custom_assets/js/main_share_functions.js')}}"></script>

@yield('script')

<script>
    $(function () {
        $('a[disabled]').click(function (e) {
            e.preventDefault();
        })
    })
</script>

</body>
</html>
