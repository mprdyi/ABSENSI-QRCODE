

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
<!-- Tombol Close (muncul hanya di HP) -->

  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ url('/') }}">
    <div class="sidebar-brand-icon">
      <img src="{{ asset('image/logo-sma.png')  }}" alt="logo" width="40px">
    </div>
    <div class="sidebar-brand-text mx-3">SMAN <sup>9 <span id="hi">Cirebon</span></sup></div>
  </a>
  <div class="sidebar-close d-md-none text-right p-2">
  <button id="closeSidebar" class="btn btn-light btn-sm" style="background:transparent; color:white; border:none">
    <i class="fa fa-times"></i>
  </button>
</div>
  <!-- Divider -->
  <hr class="sidebar-divider my-0">

  <!-- Nav Item - Dashboard -->
  <li class="nav-item">
    <a class="nav-link " href="{{ url('/') }}">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard</span>
    </a>
  </li>


  <!-- Divider -->
  <hr class="sidebar-divider">

  {{-- ========================== --}}
  {{-- AREA ADMIN --}}
  {{-- ========================== --}}
  @if (Auth::user()->role === 'admin')

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
        <a class="collapse-item" href="{{ url('data-guru') }}">Data Guru</a>
        <a class="collapse-item" href="{{ url('data-kelas') }}">Data Kelas</a>
        <a class="collapse-item" href="{{ route('user.data') }}">Data User</a>
      </div>
    </div>
  </li>


  <!-- Nav Item - Cetak QR -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('data-absensi-siswa.data') }}">
      <i class="fa fa-qrcode"></i>
      <span>Absensi QR</span>
    </a>
  </li>

    <!-- Nav Item - izin keluar -->
    <li class="nav-item">
    <a class="nav-link" href="{{ route('izin.kelas') }}">
      <i class="fa fa-file"></i>
      <span>Surat Izin</span>
    </a>
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
        <a class="collapse-item" href="{{ route('laporan-harian.harian') }}">Harian</a>
        <a class="collapse-item" href="{{ route('arsip-absensi.all') }}">Arsip</a>
      </div>
    </div>
  </li>


  <!-- Nav Item - Pengaturan -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('profil-sekolah.index') }}">
      <i class="fa fa-cogs"></i>
      <span>Pengaturan</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">
  @endif
  {{-- ========================== --}}
  {{-- AREA GURU / PIKET --}}
  {{-- ========================== --}}
  @if (Auth::user()->role === 'guru')
  <!-- Heading -->
  <div class="sidebar-heading">AREA GURU</div>

  <!-- Nav Item -Data siswa -->
  <li class="nav-item">
    <a class="nav-link" href="{{ url('data-siswa') }}">
      <i class="fa fa-users"></i>
      <span>Data siswa</span>
    </a>
  </li>



  <!-- Nav Item - Cetak QR -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('data-absensi-siswa.data') }}">
      <i class="fa fa-qrcode"></i>
      <span>Absensi Qr</span>
    </a>
  </li>

    <!-- Nav Item - Cetak QR -->
    <li class="nav-item">
    <a class="nav-link" href="{{ route('izin.kelas') }}">
      <i class="fa fa-file"></i>
      <span>Surat Izin</span>
    </a>
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
        <a class="collapse-item" href="{{ route('laporan-harian.harian') }}">Harian</a>
        <a class="collapse-item" href="{{ route('arsip-absensi.all') }}">Arsip</a>
      </div>
    </div>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">
  @endif

{{-- ========================== --}}
{{-- AREA OSIS --}}
{{-- ========================== --}}
@if (Auth::user()->role === 'osis')

  <!-- Heading -->
  <div class="sidebar-heading">AREA OSIS</div>

   <!-- Nav Item -Data siswa -->
   <li class="nav-item">
    <a class="nav-link" href="{{ url('data-siswa') }}">
      <i class="fa fa-users"></i>
      <span>Data siswa</span>
    </a>
  </li>

  <!-- Nav Item - QR -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('data-absensi-siswa.data') }}">
      <i class="fa fa-qrcode"></i>
      <span>Absensi QR</span>
    </a>
  </li>

  <!-- Nav Item - Riwayat Scan -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('arsip-absensi.all') }}">
      <i class="fa fa-clock"></i>
      <span>Riwayat Scan</span>
    </a>
  </li>

  <!-- Nav Item - Laporan Harian -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('laporan-harian.harian') }}">
      <i class="fa fa-calendar-day"></i>
      <span>Laporan Harian</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider">

  @endif

{{-- ========================== --}}
{{-- AREA SISWA / KOORDINATOR KELAS --}}
{{-- ========================== --}}
@if (Auth::user()->role === 'siswa')

  <!-- Heading -->
  <div class="sidebar-heading">KOORDINATOR KELAS</div>

  <!-- Nav Item - Daftar Hadir -->
  <li class="nav-item">
    <a class="nav-link" href="{{ url('/absensi-faceid') }}">
      <i class="fas fa-camera"></i>
      <span>Absensi</span>
    </a>
  </li>

  <!-- Nav Item - Daftar Hadir -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('data-absen.kelas') }}">
      <i class="fa fa-signature"></i>
      <span>Pengajuan Izin</span>
    </a>
  </li>

  <!-- Nav Item - Daftar Hadir -->
  <li class="nav-item">
   <a class="nav-link" href="{{ url('face/register', ['nis' => auth()->user()->siswa->nis]) }}">
      <i class="fa fa-smile"></i>
      <span>Registrasi Wajah</span>
    </a>
  </li>


  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">
  @endif

{{-- ========================== --}}
{{-- AREA WALKES --}}
{{-- ========================== --}}
@if (Auth::user()->role === 'walkes')

  <!-- Heading -->
  <div class="sidebar-heading">AREA WALKES</div>
   <!-- Nav Item - Daftar Hadir -->
   <li class="nav-item">
    <a class="nav-link" href="{{ route('data-absen.kelas') }}">
      <i class="fa fa-clipboard-list"></i>
      <span>Daftar Hadir</span>
    </a>
  </li>
 <!-- Nav Item - Daftar Hadir -->
 <li class="nav-item">
    <a class="nav-link" href="{{ route('data-absen.kelas') }}">
      <i class="fa fa-clipboard-list"></i>
      <span>Pengajuan</span>
    </a>
  </li>
   <!-- Nav Item - QR -->
   <li class="nav-item">
    <a class="nav-link" href="{{ route('data-absensi-siswa.data') }}">
      <i class="fa fa-qrcode"></i>
      <span>Absensi QR</span>
    </a>
  </li>
  <!-- Nav Item - QR -->
  <li class="nav-item">
    <a class="nav-link" href="{{ route('downloadRekap.walkes') }}">
      <i class="fa fa-book-open"></i>
      <span>Laporan Kelas</span>
    </a>
  </li>

  <!-- Divider -->
  <hr class="sidebar-divider d-none d-md-block">
  @endif


  <!-- Sidebar Toggler -->
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>

</ul>

<!-- End of Sidebar -->





