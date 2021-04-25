<!-- Start - Sidebar -->
<div id="sidebar-nav" class="sidebar">
    <!-- Start - Nav -->
    <nav>
        <ul class="nav" id="sidebar-nav-menu">
            <li class="menu-group">Main</li>
            <!-- Start - Dashboard link -->
            <li class="panel">
                <li>
                    <a href="{{ route('dashboard.index') }}" class="@if(strpos(Route::currentRouteName(), 'dashboard') !== false) active @endif">
                        <i class="ti-dashboard"></i> <span class="title">Dashboard</span>
                    </a>
                </li>
            </li>
            <!-- End - Dashboard link -->

            @if(Auth::user()->level_id == 1)
            <!-- Start - Master Link -->
            <li class="menu-group">Master</li>
            <!-- Start - User link -->
            <li class="panel">
                <li>
                    <a href="{{ route('master.user.index') }}" class="@if(strpos(Route::currentRouteName(), 'user') !== false) active @endif">
                        <i class="ti-user"></i> <span class="title">User</span>
                    </a>
                </li>
            </li>
            <!-- End - User link -->

            <!-- Start - Cabang collapse -->
            <li class="panel">
                <a href="#" data-toggle="collapse" data-target="#submenuCabang" data-parent="#sidebar-nav-menu" class="@if(strpos(Route::currentRouteName(), 'cabang') !== false) active @endif @if(strpos(Route::currentRouteName(), 'wilayah') !== false) active @endif @if(strpos(Route::currentRouteName(), 'sub-wilayah') !== false) active @endif">
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
                <a href="#" data-toggle="collapse" data-target="#submenuMateri" data-parent="#sidebar-nav-menu" class="@if(strpos(Route::currentRouteName(), 'kategori') !== false) active @endif @if(strpos(Route::currentRouteName(), 'materi') !== false) active @endif @if(strpos(Route::currentRouteName(), 'grade') !== false) active @endif">
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
            @endif

            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 3 || Auth::user()->level_id == 5)
            <!-- Start - Main Link -->
            <li class="menu-group">Main</li>

            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 2 || Auth::user()->level_id == 5)
            <!-- Start - Analisa collapse -->
            <li class="panel">
                <a href="#" data-toggle="collapse" data-target="#submenuAnalisa" data-parent="#sidebar-nav-menu" class="@if(strpos(Route::currentRouteName(), 'analisa') !== false) active @endif @if(strpos(Route::currentRouteName(), 'compare') !== false) active @endif @if(strpos(Route::currentRouteName(), 'grade') !== false) active @endif">
                    <i class="ti-stats-up"></i> <span class="title">Analisa Data</span><i class="icon-submenu ti-angle-left"></i>
                </a>
                <div id="submenuAnalisa" class="collapse">
                    <ul class="submenu">
                        <li><a href="{{ route('main.analisa.index') }}">Analisa</a></li>
                        <li><a href="{{ route('main.compare.index') }}">Compare</a></li>
                    </ul>
                </div>
            </li>
            <!-- End - Analisa collapse -->
            @endif

            @if(Auth::user()->level_id != 4)
            <!-- Start - Report collapse -->
            <li class="panel">
                <a href="#" data-toggle="collapse" data-target="#submenuReport" data-parent="#sidebar-nav-menu" class="@if(strpos(Route::currentRouteName(), 'report') !== false) active @endif">
                    <i class="ti-receipt"></i> <span class="title">Report</span><i class="icon-submenu ti-angle-left"></i>
                </a>
                <div id="submenuReport" class="collapse">
                    <ul class="submenu">
                        @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 3)
                        <li><a href="{{ route('main.report.unreport.index') }}">Report Cabang Belum Import Data</a></li>
                        @endif
                        <li><a href="{{ route('main.report.top5.index') }}">Top 5</a></li>
                        <li><a href="{{ route('main.report.under5.index') }}">Under 5</a></li>
                        <li><a href="{{ route('main.report.all.index') }}">All Cabang</a></li>
                    </ul>
                </div>
            </li>
            <!-- End - Report collapse -->
            @endif

            <!-- End - Main Link -->
            @endif


            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 3 || Auth::user()->level_id == 4)
            <!-- Start - Import data Link -->
            <li class="menu-group">Import Data</li>

            <!-- Start - summary link -->
            <li class="panel">
                <li>
                    <a href="{{ route('import.summary.index') }}" class="@if(strpos(Route::currentRouteName(), 'summary') !== false) active @endif">
                        <i class="ti-file"></i> <span class="title">Summary Import</span>
                        <span class="badge badge-warning">{{ ImportHelper::notifSummary() }}</span>
                    </a>
                </li>
            </li>
            <!-- End - summary link -->
            @endif

            @if(Auth::user()->level_id == 1 || Auth::user()->level_id == 4)
            <!-- Start - Import File collapse -->
            <li class="panel">
                <a href="#" data-toggle="collapse" data-target="#submenuImportLaporan" data-parent="#sidebar-nav-menu" class="@if(strpos(Route::currentRouteName(), 'la03') !== false) active @endif @if(strpos(Route::currentRouteName(), 'la06') !== false) active @endif @if(strpos(Route::currentRouteName(), 'la07') !== false) active @endif @if(strpos(Route::currentRouteName(), 'la09') !== false) active @endif @if(strpos(Route::currentRouteName(), 'la012') !== false) active @endif @if(strpos(Route::currentRouteName(), 'la11') !== false) active @endif">
                    <i class="ti-files"></i> <span class="title">Import Laporan</span><i class="icon-submenu ti-angle-left"></i>
                </a>
                <div id="submenuImportLaporan" class="collapse">
                    <ul class="submenu">
                        <li><a href="{{ route('import.la03.index') }}">LA03</a></li>
                        <li><a href="{{ route('import.la06.index') }}">LA06</a></li>
                        <li><a href="{{ route('import.la07.index') }}">LA07</a></li>
                        <li><a href="{{ route('import.la09.index') }}">LA09</a></li>
                        <li><a href="{{ route('import.la12.index') }}">LA12</a></li>
                        <li><a href="{{ route('import.la11.index') }}">LA13</a></li>
                    </ul>
                </div>
            </li>
            <!-- End - Import File collapse -->
            @endif
            <!-- End - Import data Link -->
        </ul>
        <button type="button" class="btn-toggle-minified" title="Toggle Minified Menu"><i class="ti-arrows-horizontal"></i></button>
    </nav>
    <!-- End - Nav -->
</div>
<!-- End - Sidebar -->