<!-- Start - Sidebar -->
<div id="sidebar-nav" class="sidebar">
    <!-- Start - Nav -->
    <nav>
        <ul class="nav" id="sidebar-nav-menu">
            <li class="menu-group">Main</li>
            <li class="panel">
                <!-- Start - Dashboard link -->
                <li>
                    <a href="{{ route('dashboard.index') }}" class="">
                        <i class="ti-dashboard"></i> <span class="title">Dashboard</span>
                    </a>
                </li>
                <!-- End - Dashboard link -->
            </li>
            <li class="panel">
                <a href="#" data-toggle="collapse" data-target="#submenuDemo" data-parent="#sidebar-nav-menu">
                    <i class="ti-menu"></i> <span class="title">Multilevel Menu</span><i class="icon-submenu ti-angle-left"></i>
                </a>
                <div id="submenuDemo" class="collapse">
                    <ul class="submenu">
                        <li class="panel">
                            <a href="#" data-toggle="collapse" data-target="#submenuDemoLv2">Submenu 1 <i class="icon-submenu ti-angle-left"></i></a>
                            <div id="submenuDemoLv2" class="collapse">
                                <ul class="submenu">
                                    <li><a href="#">Another menu level</a></li>
                                    <li><a href="#" class="active">Another menu level</a></li>
                                    <li><a href="#">Another menu level</a></li>
                                </ul>
                            </div>
                        </li>
                        <li><a href="#">Submenu 2</a></li>
                        <li><a href="#">Submenu 3</a></li>
                    </ul>
                </div>
            </li>
        </ul>
        <button type="button" class="btn-toggle-minified" title="Toggle Minified Menu"><i class="ti-arrows-horizontal"></i></button>
    </nav>
    <!-- End - Nav -->
</div>
<!-- End - Sidebar -->