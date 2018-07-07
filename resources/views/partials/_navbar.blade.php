<div class="navbar navbar-default">
    <div class="navbar-header">
        <a class="navbar-brand navbar-brand-center" href="/">
            <!--<img class="navbar-brand-logo" src="{{asset('assets/img/logomoen.png')}}" alt="Survey-EE" height="50px">-->
            <!--<img class="navbar-brand-logo" src="{{asset('assets/img/logo_eppo.png')}}" alt="Survey-EE" height="50px">-->
        </a>
        <button class="navbar-toggler visible-xs-block collapsed" type="button" data-toggle="collapse" data-target="#sidenav">
            <span class="sr-only">Toggle navigation</span>
            <span class="bars">
              <span class="bar-line bar-line-1 out"></span>
              <span class="bar-line bar-line-2 out"></span>
              <span class="bar-line bar-line-3 out"></span>
            </span>
            <span class="bars bars-x">
              <span class="bar-line bar-line-4"></span>
              <span class="bar-line bar-line-5"></span>
            </span>
        </button>
        <button class="navbar-toggler visible-xs-block collapsed" type="button" data-toggle="collapse" data-target="#navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="arrow-up"></span>
            <span class="ellipsis ellipsis-vertical">
              <img class="ellipsis-object" width="32" height="32" src="{{asset('assets/img/5903180211.png')}}" alt="Teddy Wilson">
            </span>
        </button>
    </div>
    <div class="navbar-toggleable">
		
        <nav id="navbar" class="navbar-collapse collapse">
            <button class="sidenav-toggler hidden-xs" title="Collapse sidenav ( [ )" aria-expanded="true" type="button">
                <span class="sr-only">Toggle navigation</span>
                <span class="bars">
                <span class="bar-line bar-line-1 out"></span>
                <span class="bar-line bar-line-2 out"></span>
                <span class="bar-line bar-line-3 out"></span>
                <span class="bar-line bar-line-4 in"></span>
                <span class="bar-line bar-line-5 in"></span>
                <span class="bar-line bar-line-6 in"></span>
              </span>
            </button>
			
            <ul class="nav navbar-nav navbar-right">
                <li class="visible-xs-block">
                    <h4 class="navbar-text text-center">{{ \Session::get('user.name','Supawat Kamsao')}}</h4>
                </li>
                <li class="dropdown hidden-xs">
                    <button class="navbar-account-btn" data-toggle="dropdown" aria-haspopup="true">
                        <img class="rounded" width="36" height="36" src="{{asset('assets/img/user-red.png')}}" alt="{{ \Session::get('user.name','Supawat Kamsao')}}">
                            {{ \Session::get('user.name','Supawat Kamsao')}}
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-right">
                        <li><a href="/change-password">เปลี่ยนรหัสผ่าน</a></li>
                        <li class="divider"></li>
                        <li><a href="/logout">Sign out</a></li>
                    </ul>
                </li>

                <li class="visible-xs-block">
                    <a href="/change-password">
                        <span class="icon icon-user icon-lg icon-fw"></span>
                        เปลี่ยนรหัสผ่าน
                    </a>
                </li>
                <li class="visible-xs-block">
                    <a href="/logout">
                        <span class="icon icon-power-off icon-lg icon-fw"></span>
                        Sign out
                    </a>
                </li>
            </ul>
        </nav>
    </div>
</div>