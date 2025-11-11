@extends('layout.app')

@section('title', 'SMA NEGERI 9 CIREBON')

@section('content')

<style>
  body {
    background-color: #f8f9fa;
    font-family: "Poppins", sans-serif;
  }

  .dashboard-card {
    border-radius: 20px;
    color: #fff;
    padding: 25px;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    transition: all 0.3s ease;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    height: 160px;
  }

  .dashboard-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15);
  }

  .icon-circle {
    width: 60px;
    height: 60px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.25);
    backdrop-filter: blur(6px);
    display: flex;
    justify-content: center;
    align-items: center;
    box-shadow: inset 0 0 10px rgba(255,255,255,0.2);
  }

  .icon-circle i {
    font-size: 28px;
    color: #fff;
  }

  .card-title {
    font-weight: 600;
    font-size: 17px;
    opacity: 0.9;
  }

  .card-value {
    font-size: 26px;
    font-weight: 700;
  }

  .card-value small {
    font-size: 16px;
    opacity: 0.9;
  }

  .card-footer {
    font-size: 0.9rem;
    color: rgba(255, 255, 255, 0.85);
    border-top: 1px solid rgba(255,255,255,0.25);
    margin-top: 12px;
    padding-top: 8px;
    background: transparent;
  }

  .kelas10-card { background: linear-gradient(135deg, #56CCF2, #2F80ED); }
  .kelas11-card { background: linear-gradient(135deg, #6EE7B7, #3B82F6); }
  .kelas12-card { background: linear-gradient(135deg, #F472B6, #EC4899); }
  .total-card  { background: linear-gradient(135deg, #FBBF24, #F59E0B); }


  .filter-kelas select {
    border-radius: 3px;
    border:none;
    background-color: !important transparent;
  }
  .filter-kelas select:focus {
    outline: none;
    border-color: #7b5bff;
    background-color: #fff;
  }

  .badge-soft.orange {
    background-color: #ffb74d;
    color: #fff;
    border-radius: 8px;
    padding: 5px 10px;
    text-decoration: none;
  }
</style>
 <!-- Main Content -->
 <div id="content">

<div class="container-fluid py-4" style="background-color:#f6f8fc; min-height:100vh;">
@if (Auth::user()->role === 'admin' || Auth::user()->role === 'guru' || Auth::user()->role === 'osis')
  <div class="mb-4">
    <h4 class="fw-bold">Hello, {{ Auth::user()->name }}</h4>
    <p class="text-muted">Selamat datang di aplikasi management absensi neglan cirebon</p>
  </div>
 <!--
  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <p class="text-muted small mb-1">Hadir</p>
          <h4 class="fw-bold">{{ $totalHadir + $totalTerlambat }}</h4>
          <span class="text-success small fw-semibold">{{ $totalLaki }} L,  {{ $totalPerempuan }} P</span>
          <p class="text-muted small mb-0"> {{ $totalHadir}} Ontime | {{ $totalTerlambat }} Terlambat</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <p class="text-muted small mb-1">Sakit </p>
          <h4 class="fw-bold">{{ $totalSakit }}</h4>
          <span class="text-danger small fw-semibold">↓ 2.4%</span>
          <p class="text-muted small mb-0">Sakit Hari Ini</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <p class="text-muted small mb-1">Izin</p>
          <h4 class="fw-bold">{{ $totalIzin }}</h4>
          <span class="text-danger small fw-semibold">↓ 2.4%</span>
          <p class="text-muted small mb-0">Izin Hari Ini</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <p class="text-muted small mb-1">Alpa</p>
          <h4 class="fw-bold">{{ $totalAlpha }}</h4>
          <span class="text-success small fw-semibold">↑ 5.6%</span>
          <p class="text-muted small mb-0">Alpa Hari Ini</p>
        </div>
      </div>
    </div>
  </div> -->



  <div class="row g-4 mb-3">
    <!-- HADIR  -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="dashboard-card kelas10-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="card-title">HADIR</div>
            <div class="card-value">{{$totalHadir + $totalTerlambat}} <small>↑</small></div>
          </div>
          <div class="icon-circle">
            <i class="fa fa-user-check"></i>
          </div>
        </div>
        <div class="card-footer">
        {{ $totalHadir}} Ontime | {{ $totalTerlambat }} Terlambat
        </div>
      </div>
    </div>

    <!-- IZIN -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="dashboard-card kelas11-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="card-title">IZIN</div>
            <div class="card-value">{{ $totalIzin}} <small>↑</small></div>
          </div>
          <div class="icon-circle">
            <i class="fa fa-file-signature"></i>
          </div>
        </div>
        <div class="card-footer">
        Izin hari ini
        </div>
      </div>
    </div>

    <!-- SAKIT -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="dashboard-card kelas12-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="card-title">SAKIT</div>
            <div class="card-value">{{ $totalSakit}} <small>↑</small></div>
          </div>
          <div class="icon-circle">
            <i class="fa fa-head-side-cough"></i>
          </div>
        </div>
        <div class="card-footer">
        Sakit hari ini
        </div>
      </div>
    </div>

    <!-- Total Siswa -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="dashboard-card total-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="card-title">ALPA</div>
            <div class="card-value">{{ $totalAlpha }} <small>↑</small></div>
          </div>
          <div class="icon-circle">
            <i class="fa fa-user-slash"></i>
          </div>
        </div>
        <div class="card-footer">
       Alpa hari ini
        </div>
      </div>
    </div>

  </div>


  <div class="row g-3">
    <div class="col-lg-8">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Grafik Kehadiran</h6>
            <small class="text-muted">1 minggu terakhir</small>
          </div>
          <canvas id="barChart" height="100"></canvas>
        </div>
      </div>
      <div class="row g-3 mt-4">
    <div class="col-md-6">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <h6 class="fw-bold mb-2">Total Siswa</h6>
          <p class="fs-4 fw-bold text-primary mb-0">{{ $totalSiswa }}</p>
          <p class="text-muted small mb-0"> {{$totalPerempuan}} Perempuan | {{$totalLaki}} Laki-Laki</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <h6 class="fw-bold mb-2">Total Absensi</h6>
          <p class="fs-4 fw-bold text-primary mb-0">{{ $totalAbsensiHariIni }} </p>
          <p class="text-muted small mb-0">Absensi Hari Ini</p>
        </div>
      </div>
    </div>
  </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Kategori Absensi</h6>
            <small class="text-muted">Hari Ini</small>
          </div>
          <canvas id="pieChart" height="200"></canvas>
          <ul class="mt-3 list-unstyled small">
            <li><span class="me-2" style="color:#715AFF;">■</span> On time</li>
            <li><span class="me-2" style="color:#FFAA00;">■</span> Terlambat</li>
            <li><span class="me-2" style="color:#1CC88A;">■</span> Izin</li>
            <li><span class="me-2" style="color:#E74A3B;">■</span> Alpa</li>
          </ul>
        </div>
      </div>
    </div>
  </div>
 <div class="row mt-4">
    <div class="col-12">
    <div class="card shadow-sm border-0 rounded-4">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Top 10 Siswa yang Sering Terlambat</h5>
    <div class="table-responsive">
    <table class="table table-borderless align-middle custom-table">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Siswa</th>
          <th>Kelas</th>
          <th>Jumlah</th>
          <th>Rata-rata</th>
          <th>Wali Kelas</th>
        </tr>
      </thead>
      <tbody style="font-size:14px">
        @foreach($topTerlambat as $index => $item)
          @php
            $kelas = $item->siswa->kelas->kelas ?? '-';
            $warnaBadge = match(true) {
                str_starts_with($kelas, 'X-') => 'green',   // untuk kelas X
                str_starts_with($kelas, 'XI') => 'blue',   // untuk kelas XI
                str_starts_with($kelas, 'XII') => 'purple', // untuk kelas XII
                default => 'secondary',
            };

            // Warna teks berdasarkan jumlah terlambat
            // contoh: 0–5 = Abu, 6–10 = oranye, >10 = merah
            $warnaPersen = match(true) {
                $item->jumlah <= 5 => 'text-secondary',
                $item->jumlah <= 10 => 'text-warning',
                default => 'text-danger',
            };

            // Warna teks berdasarkan rata-rata menit keterlambatan
            $warnaRata = match(true) {
                $item->rata_rata <= 5 => 'blue',     // cepat (masih aman)
                $item->rata_rata <= 10 => 'orange',    // lumayan sering
                default => 'red',                    // sering banget terlambat
            };

          @endphp

          <tr>
            <td>{{ $index + 1 }}</td>
            <td>
              <div>
                <div class="fw-semibold text-dark" style="text-transform:capitalize"> {{ ucwords(strtolower($item->siswa->nama ?? '-')) }}</div>
              </div>
            </td>
            <td><span class="badge-soft {{ $warnaBadge }}">{{ $kelas }}</span></td>
            <td class="fw-semibold {{ $warnaPersen }}">{{ $item->jumlah }}x</td>
            <td><span class="badge-soft {{ $warnaRata }} small"><span class="{{ $warnaRata }}">{{ $item->rata_rata ?? 0 }}</span> menit/hari</span></td>
            <td class="fw-semibold">{{ $item->siswa?->kelas?->waliKelas?->nama_guru ?? '-' }}</td>
          </tr>
        @endforeach
      </tbody>
    </table>


    </div>
  </div>
</div>



</div>
</div>
@endif

@if(Auth::user()->role === 'siswa')
<div class="container mt-5">
    <div class="card rounded-4 shadow-lg"
         style="background-color: #ffffff; border: none; box-shadow: 0 10px 30px rgba(0,0,0,0.15);">
        <div class="card-body p-5">
            <h2 class="fw-bold mb-3" style="background: linear-gradient(135deg, #667eea, #764ba2);
                                            -webkit-background-clip: text;
                                            -webkit-text-fill-color: transparent;">
             Hello, {{ Auth::user()->name }}
            </h2>
            <p class="fst-italic mb-4" style="color: #4b5563; font-size: 1.1rem;">
                “Selamat datang di kelas kami! Semoga setiap kegiatan belajar mengajar
                memberikan pengalaman yang menyenangkan, penuh ilmu, dan inspirasi.
                Mari kita bersama-sama menjaga semangat, kedisiplinan, dan kerja sama
                untuk mencapai prestasi terbaik.”
            </p>
            <p class="mb-0 fw-semibold" style="color: #1f2937;">
                — {{ Auth::user()->name }}
            </p>
            <small class="text-muted">Koordinator Kelas 2025/2026</small>
        </div>
    </div>
</div>


@endif
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
   document.addEventListener('DOMContentLoaded', function() {
    const ctxBar = document.getElementById('barChart').getContext('2d');
    new Chart(ctxBar, {
      type: 'bar',
      data: {
        labels: @json($labels),
        datasets: [{
          label: 'Jumlah Hadir',
          data: @json($dataHadir),
          backgroundColor: '#715AFF',
          borderRadius: 6
        }]
      },
      options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
          y: { beginAtZero: true, grid: { drawBorder: false } },
          x: { grid: { display: false } }
        }
      }
    });
  });
  const ctxPie = document.getElementById('pieChart').getContext('2d');
new Chart(ctxPie, {
  type: 'doughnut',
  data: {
    labels: ['Ontime', 'Terlambat', 'Izin', 'Alpa'],
    datasets: [{
      data: [{{ $totalHadir }}, {{ $totalTerlambat }}, {{ $totalIzin }}, {{ $totalAlpha }}],
      backgroundColor: ['#715AFF', '#FFAA00', '#1CC88A', '#E74A3B'],
      borderWidth: 0
    }]
  },
  options: {
    responsive: true,
    maintainAspectRatio: false,
    cutout: '70%',
    plugins: {
      legend: { display: false },
      tooltip: {
        callbacks: {
          label: function(context) {
            const dataset = context.dataset;
            const total = dataset.data.reduce((a, b) => a + b, 0);
            const value = dataset.data[context.dataIndex];
            const percentage = ((value / total) * 100).toFixed(1);
            return `${context.label}: ${percentage}%`;
          }
        }
      }
    }
  }
});
</script>
@endsection
