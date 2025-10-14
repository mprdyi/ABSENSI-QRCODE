<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
    <div class="sidebar-brand-icon">
      <img src="{{ asset('image/logo-sma.png')  }}" alt="" width="40px">
    </div>
    <div class="sidebar-brand-text mx-3">SMAN <sup>9 Cirebon</sup></div>
  </a>

  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item active">
    <a class="nav-link" href="{{ url('/') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">AREA ADMIN</div>

  <!-- Nav Item - Data Master -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseMaster"
       aria-expanded="true" aria-controls="collapseMaster">
      <i class="fa fa-database"></i>
      <span>Data Master</span>
    </a>
    <div id="collapseMaster" class="collapse" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="{{ url('data-siswa') }}">Data Siswa</a>
        <a class="collapse-item" href="{{ url('data-guru') }}">Data Guru Piket</a>
        <a class="collapse-item" href="{{ url('data-osis') }}">Data Osis / MPK</a>
        <a class="collapse-item" href="{{ url('data-kelas') }}">Data Kelas</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Laporan Rekap -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporanAdmin"
       aria-expanded="true" aria-controls="collapseLaporanAdmin">
      <i class="fa fa-envelope-open-text"></i>
      <span>Laporan Rekap</span>
    </a>
    <div id="collapseLaporanAdmin" class="collapse" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="#">Harian</a>
        <a class="collapse-item" href="#">Mingguan</a>
        <a class="collapse-item" href="#">Bulanan</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Cetak QR -->
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa fa-qrcode"></i>
      <span>Cetak QR</span>
    </a>
  </li>

    <!-- Nav Item - Cetak QR -->
    <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa fa-qrcode"></i>
      <span>Surat Izin</span>
    </a>
  </li>

  <!-- Nav Item - Pengaturan -->
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa fa-cogs"></i>
      <span>Pengaturan</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">AREA PIKET</div>

  <!-- Nav Item - Daftar Hadir -->
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fas fa-clipboard-list"></i>
      <span>Daftar Hadir</span>
    </a>
  </li>

  <!-- Nav Item - Laporan Rekap -->
  <li class="nav-item">
    <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseLaporanPiket"
       aria-expanded="true" aria-controls="collapseLaporanPiket">
      <i class="fa fa-envelope-open-text"></i>
      <span>Laporan Rekap</span>
    </a>
    <div id="collapseLaporanPiket" class="collapse" data-parent="#accordionSidebar">
      <div class="bg-white py-2 collapse-inner rounded">
        <a class="collapse-item" href="#">Harian</a>
        <a class="collapse-item" href="#">Mingguan</a>
        <a class="collapse-item" href="#">Bulanan</a>
      </div>
    </div>
  </li>

  <!-- Nav Item - Cetak QR -->
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa fa-qrcode"></i>
      <span>Cetak QR</span>
    </a>
  </li>

    <!-- Nav Item - Cetak QR -->
    <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa fa-qrcode"></i>
      <span>Surat Izin</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">AREA OSIS</div>

  <!-- Nav Item - Riwayat Scan -->
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa fa-clock"></i>
      <span>Riwayat Scan</span>
    </a>
  </li>

  <!-- Nav Item - Laporan Harian -->
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa fa-calendar-day"></i>
      <span>Laporan Harian</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  <!-- Heading -->
  <div class="sidebar-heading">AREA KOORDINATOR KELAS</div>

  <!-- Nav Item - Daftar Hadir -->
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa fa-clipboard-list"></i>
      <span>Daftar Hadir</span>
    </a>
  </li>

  <!-- Nav Item - Ajukan Izin -->
  <li class="nav-item">
    <a class="nav-link" href="#">
      <i class="fa fa-file-signature"></i>
      <span>Ajukan Izin</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">

  <!-- Sidebar Toggler -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>
<!-- End of Sidebar -->
