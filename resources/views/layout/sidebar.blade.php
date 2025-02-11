<!-- Sidebar -->
<div class="sidebar" data-background-color="dark">
  <div class="sidebar-logo">
    <!-- Logo Header -->
    <div class="logo-header text-center" data-background-color="dark">
      <a href="" class="logo text-white">
        {{-- <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" /> --}}
        WebPPG Boyolali Barat
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
          <a data-bs-toggle="collapse" href="#base">
            <i class="fas fa-layer-group"></i>
            <p>Master Data</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="base">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{url('/ortu')}}">
                  <span class="sub-item">Data Orang Tua</span>
                </a>
              </li>
              <li>
                <a href="{{url('/jamaah')}}">
                  <span class="sub-item">Data Jamaah</span>
                </a>
              </li>
              <li>
                <a href="{{url('/siswa')}}">
                  <span class="sub-item">Data Siswa</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a data-bs-toggle="collapse" href="#base1">
            <i class="fas fa-user-cog"></i>
            <p>Setup</p>
            <span class="caret"></span>
          </a>
          <div class="collapse" id="base1">
            <ul class="nav nav-collapse">
              <li>
                <a href="{{url('daerah')}}">
                  <span class="sub-item">Data Daerah</span>
                </a>
              </li>
              <li>
                <a href="{{url('desa')}}">
                  <span class="sub-item">Data Desa</span>
                </a>
              </li>
              <li>
                <a href="{{url('kelompok')}}">
                  <span class="sub-item">Data Kelompok</span>
                </a>
              </li>
              <li>
                <a href="{{url('kls')}}">
                  <span class="sub-item">Data Kelas</span>
                </a>
              </li>
              <li>
                <a href="{{url('pekerjaan')}}">
                  <span class="sub-item">Data Pekerjaan</span>
                </a>
              </li>
            </ul>
          </div>
        </li>
        <li class="nav-item">
          <a href="{{ url('/user') }}">
            <i class="fas fa-user"></i>
            <p>User</p>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
<!-- End Sidebar -->