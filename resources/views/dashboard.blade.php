@extends('layout.app')

@section('title', 'SMA NEGERI 9 CIREBON')

@section('content')
 <!-- Main Content -->
 <div id="content">

<div class="container-fluid py-4" style="background-color:#f6f8fc; min-height:100vh;">
  <div class="mb-4">
    <h4 class="fw-bold">Hello, Addy Muhamad 👋</h4>
    <p class="text-muted">Here's what's happening in your school this month.</p>
  </div>

  <div class="row g-3 mb-4">
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <p class="text-muted small mb-1">Total Siswa</p>
          <h4 class="fw-bold">{{ $totalSiswa }}</h4>
          <span class="text-success small fw-semibold">{{ $totalLaki }} L,  {{ $totalPerempuan }} P</span>
          <p class="text-muted small mb-0">Total Keseluruhan</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <p class="text-muted small mb-1">Total Absensi</p>
          <h4 class="fw-bold">{{ $totalAbsensiHariIni }}</h4>
          <span class="text-danger small fw-semibold">↓ 2.4%</span>
          <p class="text-muted small mb-0">Absensi Hari Ini</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <p class="text-muted small mb-1">Terlambat</p>
          <h4 class="fw-bold">{{ $totalTerlambat }}</h4>
          <span class="text-danger small fw-semibold">↓ 2.4%</span>
          <p class="text-muted small mb-0">Terlambat Hari Ini</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <p class="text-muted small mb-1">Alpa</p>
          <h4 class="fw-bold">{{ $totalAlpha }}</h4>
          <span class="text-success small fw-semibold">↑ 5.6%</span>
          <p class="text-muted small mb-0">Alpha Hari Ini</p>
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
          <h6 class="fw-bold mb-2">Izin</h6>
          <p class="fs-4 fw-bold text-primary mb-0">{{ $totalIzin }} Siswa</p>
          <p class="text-muted small mb-0">Menunggu konfirmasi guru piket</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm border-0 rounded-4 mb-3">
        <div class="card-body">
          <h6 class="fw-bold mb-2">Sakit</h6>
          <p class="fs-4 fw-bold text-danger mb-0">{{ $totalSakit }} Siswa</p>
          <p class="text-muted small mb-0">Tidak hadir tanpa keterangan</p>
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
      <tbody>
        @foreach($topTerlambat as $index => $item)
          @php
            $kelas = $item->siswa->kelas->kelas ?? '-';
            $warnaBadge = match(true) {
                str_starts_with($kelas, 'X-') => 'orange',   // untuk kelas X
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
                <div class="fw-semibold text-dark">{{ $item->siswa->nama ?? '-' }}</div>
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
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctxBar = document.getElementById('barChart').getContext('2d');
  new Chart(ctxBar, {
    type: 'bar',
    data: {
      labels: ['Senin ', 'Selasa', 'Rabu', 'Kamis', 'Jumat'],
      datasets: [{
        label: 'Jumlah Hadir',
        data: [100, 130, 120, 160, 150],
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

  const ctxPie = document.getElementById('pieChart').getContext('2d');
new Chart(ctxPie, {
  type: 'doughnut',
  data: {
    labels: ['Hadir', 'Terlambat', 'Izin', 'Alpa'],
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
      legend: { display: false }
    }
  }
});
</script>
@endsection
