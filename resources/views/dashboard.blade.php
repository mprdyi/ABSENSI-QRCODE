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
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <p class="text-muted small mb-1">Total Siswa</p>
          <h4 class="fw-bold">{{ $totalSiswa }}</h4>
          <span class="text-success small fw-semibold">↑ 2.6%</span>
          <p class="text-muted small mb-0">This month vs last</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <p class="text-muted small mb-1">Total Absensi</p>
          <h4 class="fw-bold">935</h4>
          <span class="text-danger small fw-semibold">↓ 2.4%</span>
          <p class="text-muted small mb-0">This month vs last</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <p class="text-muted small mb-1">Terlambat</p>
          <h4 class="fw-bold">189</h4>
          <span class="text-danger small fw-semibold">↓ 2.4%</span>
          <p class="text-muted small mb-0">This month vs last</p>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <p class="text-muted small mb-1">Alpa</p>
          <h4 class="fw-bold">123</h4>
          <span class="text-success small fw-semibold">↑ 5.6%</span>
          <p class="text-muted small mb-0">Belum Setujui</p>
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
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <h6 class="fw-bold mb-2">Izin</h6>
          <p class="fs-4 fw-bold text-primary mb-0">17 Siswa</p>
          <p class="text-muted small mb-0">Menunggu konfirmasi guru piket</p>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <h6 class="fw-bold mb-2">Sakit</h6>
          <p class="fs-4 fw-bold text-danger mb-0">12 Siswa</p>
          <p class="text-muted small mb-0">Tidak hadir tanpa keterangan</p>
        </div>
      </div>
    </div>
  </div>
    </div>

    <div class="col-lg-4">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">Attendance by Category</h6>
            <small class="text-muted">This month vs last</small>
          </div>
          <canvas id="pieChart" height="200"></canvas>
          <ul class="mt-3 list-unstyled small">
            <li><span class="me-2" style="color:#715AFF;">■</span> Hadir</li>
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
    <h5 class="fw-bold mb-3">Top Siswa yang Sering Terlambat</h5>
    <div class="table-responsive">
      <table class="table table-borderless align-middle custom-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
            <th>Jumlah</th>
            <th>Rata-rata</th>
            <th>Persentase</th>
          </tr>
        </thead>
        <tbody>
          <tr>
          <td>1</td>
            <td>
              <div>
                <div class="fw-semibold text-dark">Rizki Maulana</div>
                <small class="text-muted">XI RPL 1</small>
              </div>
            </td>
            <td><span class="badge-soft purple">RPL</span></td>
            <td class="fw-semibold text-dark">12x</td>
            <td><span class="text-muted small">5 menit/hari</span></td>
            <td class="fw-semibold text-success">97%</td>
          </tr>
          <tr>
          <td>2</td>
            <td>
              <div>
                <div class="fw-semibold text-dark">Siti Nurhaliza</div>
                <small class="text-muted">XI TKJ 2</small>
              </div>
            </td>
            <td><span class="badge-soft blue">TKJ</span></td>
            <td class="fw-semibold text-dark">9x</td>
            <td><span class="text-muted small">3 menit/hari</span></td>
            <td class="fw-semibold text-danger">68%</td>
          </tr>
          <tr>
            <td>3</td>
            <td>
              <div>
                <div class="fw-semibold text-dark">Budi Santoso</div>
                <small class="text-muted">XII MM 1</small>
              </div>
            </td>
            <td><span class="badge-soft orange">MM</span></td>
            <td class="fw-semibold text-dark">7x</td>
            <td><span class="text-muted small">2 menit/hari</span></td>
            <td class="fw-semibold text-success">88%</td>
          </tr>
        </tbody>
      </table>
       <!-- Pagination -->
    <div class="d-flex justify-content-end align-items-center mt-3">
      <nav>
        <ul class="pagination pagination-sm mb-0">
          <li class="page-item disabled">
            <a class="page-link rounded-circle" href="#" tabindex="-1">&laquo;</a>
          </li>
          <li class="page-item active"><a class="page-link rounded-circle" href="#">1</a></li>
          <li class="page-item"><a class="page-link rounded-circle" href="#">2</a></li>
          <li class="page-item"><a class="page-link rounded-circle" href="#">3</a></li>
          <li class="page-item">
            <a class="page-link rounded-circle" href="#">&raquo;</a>
          </li>
        </ul>
      </nav>
    </div>

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
        data: [70, 10, 12, 8],
        backgroundColor: ['#715AFF', '#FFAA00', '#1CC88A', '#E74A3B'],
        borderWidth: 0
      }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false, // ini penting
        cutout: '70%',
        plugins: { legend: { display: false } }
        }

  });
</script>
@endsection
