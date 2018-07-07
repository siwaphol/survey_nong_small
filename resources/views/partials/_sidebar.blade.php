<div class="layout-sidebar">
    <div class="layout-sidebar-backdrop"></div>
    <div class="layout-sidebar-body">
        <div class="custom-scrollbar">
            <nav id="sidenav" class="sidenav-collapse collapse">
                <ul class="sidenav">
                    <li class="sidenav-search hidden-md hidden-lg">
                        <form class="sidenav-form" action="/">
                            <div class="form-group form-group-sm">
                                <div class="input-with-icon">
                                    <input class="form-control" type="text" placeholder="Search…">
                                    <span class="icon icon-search input-icon"></span>
                                </div>
                            </div>
                        </form>
                    </li>
                    <li class="sidenav-heading">แบบสอบถาม</li>
                    <li class="sidenav-item has-subnav" aria-expanded="false">
                        <a href="#">
                            <span class="sidenav-icon icon icon-institution"></span>
                            <span class="sidenav-label">อาคาร</span>
                        </a>
                        <ul class="sidenav-subnav collapse">
                            <li><a href="{{ url('/building') }}"><span class="sidenav-icon icon icon-file-text-o"></span> แบบสอบถามที่บันทึกแล้ว</a></li>
                        </ul>
                    </li>
                    @if(\Session::get('user.level') != 'user')
                     <li class="sidenav-item has-subnav">
                        <a href="#" aria-expanded="false">
                            <span class="sidenav-icon icon icon-user"></span>
                            <span class="sidenav-label">จัดการผู้ใช้งาน</span>
                        </a>
                        <ul class="sidenav-subnav collapse">
                            <li><a href="{{ url('/user') }}"><span class="sidenav-icon icon icon-list"></span> รายชื่อผู้ใช้งาน</a></li>
                            <li><a href="{{ url('/user/create') }}"><span class="sidenav-icon icon icon-plus"></span> เพิ่มผู้ใช้งาน</a></li>
                            <li><a href="{{ url('/user') }}"><span class="sidenav-icon icon icon-pencil"></span> แก้ไขผู้ใช้งาน</a></li>
                        </ul>
                    </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>