<nav class="navbar navbar-expand fixed-top">
    <div class="navbar-brand d-none d-lg-block">
        <a href="index.html" class="font-wight-bold text-white">
            PURWA CARAKA
        </a>
    </div>
    <div class="container-fluid p-0">
        <button type="button" class="btn btn-default btn-toggle-fullwidth"><i class="ti-menu"></i></button>
        <div id="navbar-menu">
            <ul class="nav navbar-nav align-items-center">
                <li class="dropdown">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><img src="https://i.imgur.com/M701HZb.jpg" class="user-picture"" alt="Avatar"> <span>{{ Auth::user()->nama }}</span></a>
                    <ul class="dropdown-menu dropdown-menu-right logged-user-menu">
                        <li><a href="{{ route('password-user.index') }}"><i class="ti-key"></i> <span>Ubah Password</span></a></li>
                        <li><a href="{{ route('login.logout') }}"><i class="ti-power-off"></i> <span>Logout</span></a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>