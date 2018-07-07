@extends('LayOut')

@section('content')
<div class="panel panel-body">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="/">Home</a></li>
    </ol>
</div>
<div class="panel panel-body">
   <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-xs-6">
                    <h4><span class="bg-danger rounded sq-28"><span class="icon icon-institution"></span></span> อาคาร </h4>
                    </div>
                    <div class="col-xs-3">
                        <a href="{{ url('/building/')}}/excelall" class="btn btn-sm btn-info" type="button"><span
                                    class="icon icon-plus-square-o icon-lg icon-fw"></span> Export all</a>
                    </div>
                    <div class="col-xs-3">
                        <a href="{{ url('/building/process1') }}" class="btn btn-sm btn-info pull-right " type="button"><span
                                    class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มชุดแบบสอบถาม</a>
                    </div>
                </div>
            <div class="table-responsive">
                <table id="building-home-table" class="table table-middle nowrap dataTable no-footer" role="grid">
                    <thead>
                        <tr>
                            <th class="col-md-2">#เลขชุดแบบสอบถาม</th>
                            <th class="col-md-3">ชื่อนิติบุคคล</th>
                            <th class="col-md-2">เขตที่สังกัด</th>
                            <th class="col-md-2">แก้ไขล่าสุด</th>
                            <th class="col-md-2">ผู้บันทึก</th>
                            <th class="col-md-2">สถานะข้อมูล</th>
                            <th class="col-md-1">ตัวเลือก</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($buildings as $data)
                        @if(isset($data->mains{0}))
                            @if(($data->mains{0}->user_area == $area))
                        <tr>
                            {!! Form::open(['url' => '/building/'.$data->mains{0}->id,'method'=>'DELETE', 'id'=>'form'.$data->mains{0}->id])!!}
                            <td><a href="{{ url('/building/process1/') }}/{{ $data->mains{0}->id }}/edit">{{ $data->mains{0}->code }}</a></td>
                            <th><a href="{{ url('/building/process1/') }}/{{ $data->mains{0}->id }}/edit">{{ $data->mains{0}->person_name }}</a></th>
                            <th>{{ $data->mains{0}->user_area }}</th>
                            <th>{{ $data->mains{0}->updated_at }}</th>
                            <th>{{ $data->mains{0}->user_name }}</th>
                            <th>
                                @if ( count($data->mains{0}->progress['building'])  == '7')
                                    <a> กรอกครบแล้ว </a>
                                @else
                                    <a> กรอกยังไม่ครบ </a>
                                @endif
                            </th>
                            <td>
                                <div class="btn-group dropdown">
                                    <button class="btn btn-link link-muted" aria-haspopup="true" data-toggle="dropdown" type="button">
                                    <span class="icon icon-ellipsis-h icon-lg icon-fw"></span>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-right">
                                        <li><a href="{{ url('/building/process1/') }}/{{ $data->mains{0}->id }}/edit">ดูเพิ่มเติม</a></li>
                                        <li><a href="#" act="delete"
                                               uid="{{$data->mains{0}->code}}"
                                               pid="{{ $data->mains{0}->id }}"
                                               onclick="deleteMain('{{$data->mains{0}->code}}','{{ $data->mains{0}->id }}')"
                                            >ลบ</a></li>
                                    </ul>
                                </div>
                            </td>
                            {!! Form::close()!!}
                        </tr>
                                @elseif(($area == "0" ))
                                <tr>
                                    {!! Form::open(['url' => '/building/'.$data->mains{0}->id,'method'=>'DELETE', 'id'=>'form'.$data->mains{0}->id])!!}
                                    <td><a href="{{ url('/building/process1/') }}/{{ $data->mains{0}->id }}/edit">{{ $data->mains{0}->code }}</a></td>
                                    <th><a href="{{ url('/building/process1/') }}/{{ $data->mains{0}->id }}/edit">{{ $data->mains{0}->person_name }}</a></th>
                                    <th>{{ $data->mains{0}->user_area }}</th>
                                    <th>{{ $data->mains{0}->updated_at }}</th>
                                    <th>{{ $data->mains{0}->user_name }}</th>
                                    <th>
                                        @if ( count($data->mains{0}->progress['building'])  == '7')
                                            <a> กรอกครบแล้ว </a>
                                        @else
                                            <a> กรอกยังไม่ครบ </a>
                                        @endif
                                    </th>
                                    <td>
                                        <div class="btn-group dropdown">
                                            <button class="btn btn-link link-muted" aria-haspopup="true" data-toggle="dropdown" type="button">
                                                <span class="icon icon-ellipsis-h icon-lg icon-fw"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{ url('/building/process1/') }}/{{ $data->mains{0}->id }}/edit">ดูเพิ่มเติม</a></li>
                                                <li><a href="#"
                                                       act="delete"
                                                       uid="{{$data->mains{0}->code}}"
                                                       pid="{{ $data->mains{0}->id }}"
                                                       onclick="deleteMain('{{$data->mains{0}->code}}','{{ $data->mains{0}->id }}')"
                                                    >ลบ</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    {!! Form::close()!!}
                                </tr>
                            @endif
                        @endif
                        @endforeach
                    </tbody>
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