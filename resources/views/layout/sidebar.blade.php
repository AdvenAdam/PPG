<!-- Sidebar -->
<div class="sidebar" data-background-color="blue">
    <div class="sidebar-logo">
        <!-- Logo Header -->
        <div class="logo-header text-center" data-background-color="blue">
            <a href="" class="logo text-white">
                Generus Boyolali Barat
            </a>
            <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                    <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                    <i class="gg-menu-left"></i>
                </button>
            </div>
            <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
            </button>
        </div>
        <!-- End Logo Header -->
    </div>
    <div class="sidebar-wrapper scrollbar scrollbar-inner">
        <div class="sidebar-content">
            <ul class="nav nav-secondary">
                <li class="nav-item active">
                    <a href="{{ url('/') }}">
                        <i class="fas fa-home"></i>
                        <p>Dashboard</p>
                    </a>
                </li>
                <li class="nav-section">
                    <span class="sidebar-mini-icon">
                        <i class="fa fa-ellipsis-h"></i>
                    </span>
                    <h4 class="text-section">Components</h4>
                </li>
                <li class="nav-item">
                    <a data-bs-toggle="collapse" aria-expanded="true" href="#base">
                        <i class="fas fa-layer-group"></i>
                        <p>Master Data</p>
                        <span class="caret"></span>
                    </a>
                    <div class="collapse show" id="base">
                        <ul class="nav nav-collapse">
                            <li>
                                <a href="{{ url('/generus') }}">
                                    <span class="sub-item">Data Generus</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/pengajian') }}">
                                    <span class="sub-item">Absensi</span>
                                </a>
                            </li>
                            <li>
                                <a href="{{ url('/kurikulum') }}">
                                    <span class="sub-item">Kurikulum</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>
                @if (Auth::user()->jabatan === 'daerah')
                    <li class="nav-item">
                        <a data-bs-toggle="collapse" href="#base1">
                            <i class="fas fa-user-cog"></i>
                            <p>Setup</p>
                            <span class="caret"></span>
                        </a>
                        <div class="collapse show" id="base1">
                            <ul class="nav nav-collapse">
                                <li>
                                    <a href="{{ url('daerah') }}">
                                        <span class="sub-item">Data Daerah</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('desa') }}">
                                        <span class="sub-item">Data Desa</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('kelompok') }}">
                                        <span class="sub-item">Data Kelompok</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('kls') }}">
                                        <span class="sub-item">Data Kelas</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ url('/musyawarah') }}">
                                        <span class="sub-item">Hasil Musyawarah</span>
                                    </a>
                                </li>
                                <!-- FIXME : Pekerjaan now is optional inside generus -->
                            </ul>
                        </div>
                    </li>
                @endif
                @if (Auth::user()->jabatan == 'daerah')
                    <li class="nav-item">
                        <a href="{{ url('/user') }}">
                            <i class="fas fa-user"></i>
                            <p>User</p>
                        </a>
                    </li>
                @endif
            </ul>
        </div>
    </div>
</div>
<!-- End Sidebar -->
