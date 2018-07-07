@extends('LayOut')

@section('content')
<div class="panel panel-body">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Home</a></li>
        <li class="breadcrumb-item active"><a href="{{ url('/user') }}">ผู้ใช้งาน</a></li>
    </ol>
</div>
<div class="panel panel-body">
    <div class="row">
        <div class="col-xs-6">
            <h4><span class="bg-danger rounded sq-28"><span class=" icon icon-users"></span></span> รายชื่อผู้ใช้งาน
            </h4>
        </div>
        <div class="col-xs-6">
            @if (\Session::get('user.level', 'admin') != 'user')
                <a href="{{ url('/user/create') }}" class="btn btn-sm btn-info pull-right" type="button">
                    <span class="icon icon-plus-square-o icon-lg icon-fw"></span> เพิ่มผู้ใช้งาน
                </a>
            @endif
        </div>
        <div class="col-xs-12">
            <div class="card">
                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            <ul>
                                <li>{{ session('status') }}</li>
                            </ul>
                        </div>
                    @endif
                    <div class="table-responsive">
                        <table id="user-list-table" class="table table-middle nowrap dataTable no-footer" role="grid">
                            <thead>
                            <tr rowspan="2">

                                <th class="col-md-3">Email/Username</th>
                                <th class="col-md-2">ชื่อ-นามสกุล</th>
                                <th class="col-md-2">โทร</th>
                                <th class="col-md-2">ประเภท</th>
                                <th class="col-md-2">เขตที่รับผิดชอบ</th>
                                <th class="col-md-1">ตัวเลือก</th>
                            </tr>
                            </thead>
                            <tbody>
                            @forelse($allUsers as $user)

                                <tr>
                                    {!! Form::open(['url' => '/user/'.$user->id,'method'=>'DELETE', 'id'=>'form'.$user->id])!!}
                                    <td>{{$user->email}}</td>
                                    <td>{{$user->name}}</td>
                                    <td>{{$user->tel}}</td>
                                    <td>{{$user->level}}</td>
                                    <td>{{$user->inArea->name}}</td>
                                    <td>
                                        <div class="btn-group pull-right dropdown">
                                            <button class="btn btn-link link-muted" aria-haspopup="true"
                                                    data-toggle="dropdown" type="button">
                                                <span class="icon icon-ellipsis-h icon-lg icon-fw"></span>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-right">
                                                <li><a href="{{ url('/user/'.$user->id) }}/edit">แก้ไข</a></li>
                                                <li><a href="#" act="delete" uid="{{$user->id}}">ลบ</a></li>
                                            </ul>
                                        </div>
                                    </td>
                                    {!! Form::close()!!}
                                </tr>

                            @empty

                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
    <script type="text/javascript" src="{{ asset('custom_assets/js/sweetalert/sweetalert.min.js')}}"></script>
    <link rel="stylesheet" type="text/css" href="{{ asset('custom_assets/css/sweetalert/sweetalert.css')}}">
    <script type="text/javascript" src="{{ asset('custom_assets/js/user/user_index.js')}}"></script>
@stop