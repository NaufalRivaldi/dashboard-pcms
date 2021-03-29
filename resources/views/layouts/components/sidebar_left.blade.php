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
            <!-- Start - User link -->
            <li class="panel">
                <li>
                    <a href="{{ route('master.user.index') }}" class="">
                        <i class="ti-user"></i> <span class="title">User</span>
                    </a>
                </li>
            </li>
            <!-- End - User link -->

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

            <!-- Start - Main Link -->
            <li class="menu-group">Main</li>
            <!-- Start - Import File collapse -->
            <li class="panel">
                <a href="#" data-toggle="collapse" data-target="#submenuImportLaporan" data-parent="#sidebar-nav-menu">
                    <i class="ti-files"></i> <span class="title">Import Laporan</span><i class="icon-submenu ti-angle-left"></i>
                </a>
                <div id="submenuImportLaporan" class="collapse">
                    <ul class="submenu">
                        <li><a href="{{ route('import.la03.index') }}">LA03</a></li>
                        <li><a href="{{ route('import.la06.index') }}">LA06</a></li>
                        <li><a href="{{ route('import.la07.index') }}">LA07</a></li>
                        <li><a href="{{ route('import.la09.index') }}">LA09</a></li>
                        <li><a href="{{ route('import.la11.index') }}">LA11</a></li>
                        <li><a href="{{ route('import.la12.index') }}">LA12</a></li>
                    </ul>
                </div>
            </li>
            <!-- End - Import File collapse -->
            <!-- End - Main Link -->
        </ul>
        <button type="button" class="btn-toggle-minified" title="Toggle Minified Menu"><i class="ti-arrows-horizontal"></i></button>
    </nav>
    <!-- End - Nav -->
</div>
<!-- End - Sidebar -->