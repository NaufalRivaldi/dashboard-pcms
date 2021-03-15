<!-- Start - Sidebar -->
<div id="sidebar-nav" class="sidebar">
    <!-- Start - Nav -->
    <nav>
        <ul class="nav" id="sidebar-nav-menu">
            <li class="menu-group">Main</li>
            <!-- Start - Dashboard link -->
            <li class="panel">
                <li>
                    <a href="{{ route('dashboard.index') }}" class="">
                        <i class="ti-dashboard"></i> <span class="title">Dashboard</span>
                    </a>
                </li>
            </li>
            <!-- End - Dashboard link -->

            <!-- Start - Master Link -->
            <li class="menu-group">Master</li>
            <!-- Start - Cabang collapse -->
            <li class="panel">
                <a href="#" data-toggle="collapse" data-target="#submenuCabang" data-parent="#sidebar-nav-menu">
                    <i class="ti-location-pin"></i> <span class="title">Cabang</span><i class="icon-submenu ti-angle-left"></i>
                </a>
                <div id="submenuCabang" class="collapse">
                    <ul class="submenu">
                        <li><a href="{{ route('master.cabang.index') }}">Cabang</a></li>
                        <li><a href="{{ route('master.wilayah.index') }}">Wilayah</a></li>
                        <li><a href="{{ route('master.sub-wilayah.index') }}">Sub Wilayah</a></li>
                    </ul>
                </div>
            </li>
            <!-- End - Cabang collapse -->

            <!-- Start - Materi collapse -->
            <li class="panel">
                <a href="#" data-toggle="collapse" data-target="#submenuMateri" data-parent="#sidebar-nav-menu">
                    <i class="ti-book"></i> <span class="title">Materi</span><i class="icon-submenu ti-angle-left"></i>
                </a>
                <div id="submenuMateri" class="collapse">
                    <ul class="submenu">
                        <li><a href="{{ route('master.kategori.index') }}">Kategori</a></li>
                        <li><a href="{{ route('master.materi.index') }}">Materi</a></li>
                        <li><a href="{{ route('master.grade.index') }}">Grade</a></li>
                    </ul>
                </div>
            </li>
            <!-- End - Materi collapse -->
            <!-- End - Master Link -->
        </ul>
        <button type="button" class="btn-toggle-minified" title="Toggle Minified Menu"><i class="ti-arrows-horizontal"></i></button>
    </nav>
    <!-- End - Nav -->
</div>
<!-- End - Sidebar -->