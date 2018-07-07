@extends('LayOut')

@section('content')
    <div class="panel panel-body">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Home</a></li>
            <li class="breadcrumb-item active"><a href="/">ข้อมูลจำนวนแบบสอบถามทั้งหมด</a></li>
        </ol>
    </div>
        <div class="panel panel-body">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="building-home-table" class="table table-middle nowrap dataTable no-footer" role="grid">
                            <thead>
                            <tr>
                                <th class="col-md-3">เขตที่รับผิดชอบ</th>
                                <th class="col-md-2">อาคาร</th>
                                <th class="col-md-2">โรงงาน</th>
                            </tr>
                            </thead>
                            <tr>
                                <th>ม.เชียงใหม่</th>
                                <th>{{$areasbuildingsArray[0]}}</th>
                                <th>{{$areasindustrysArray[0]}}</th>
                            </tr>
                            <tr>
                                <th>ม.ขอนแก่น</th>
                                <th>{{$areasbuildingsArray[1]}}</th>
                                <th>{{$areasindustrysArray[1]}}</th>
                            </tr>
                            <tr>
                                <th>ม.สุรนารี</th>
                                <th>{{$areasbuildingsArray[2]}}</th>
                                <th>{{$areasindustrysArray[2]}}</th>
                            </tr>
                            <tr>
                                <th>ม.เกษตร</th>
                                <th>{{$areasbuildingsArray[3]}}</th>
                                <th>{{$areasindustrysArray[3]}}</th>
                            </tr>
                            <tr>
                                <th>จุฬาฯ</th>
                                <th>{{$areasbuildingsArray[4]}}</th>
                                <th>{{$areasindustrysArray[4]}}</th>
                            </tr>
                            <tr>
                                <th>ม.พระจอมเกล้าฯ</th>
                                <th>{{$areasbuildingsArray[5]}}</th>
                                <th>{{$areasindustrysArray[5]}}</th>
                            </tr>
                            <tr>
                                <th>ม.สงขลา</th>
                                <th>{{$areasbuildingsArray[6]}}</th>
                                <th>{{$areasindustrysArray[6]}}</th>
                            </tr>
                            <tr>
                                <th>รวม</th>
                                <th>{{$areasbuildingsArray[0]+$areasbuildingsArray[1]+$areasbuildingsArray[2]+$areasbuildingsArray[3]
                                +$areasbuildingsArray[4]+$areasbuildingsArray[5]+$areasbuildingsArray[6]}}</th>
                                <th>{{$areasindustrysArray[0]+$areasindustrysArray[1]+$areasindustrysArray[2]+$areasindustrysArray[3]
                                +$areasindustrysArray[4]+$areasindustrysArray[5]+$areasindustrysArray[6]}}</th>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>
        </div>

@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('custom_assets/js/sweetalert/sweetalert.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('custom_assets/css/sweetalert/sweetalert.css')}}">
    <script type="text/javascript" src="{{ asset('custom_assets/js/index_page_share_function.js')}}"></script>

    <script type="text/javascript" src="{{ asset('custom_assets/js/home/index.js')}}"></script>

    @include('partials._pnotify')
@stop