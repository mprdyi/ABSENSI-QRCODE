@extends('layout.app')
@section('title', 'Laporan Data Absensi')
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

<div class="container-fluid py-5">
<div class="download mb-3" style="position:relative">
        <a href="{{ route('download-backup.laporan') }}" class="badge-soft purple" style="position:absolute; right:0; transform:translateY(-50px)"> <i class="fa fa-download"> </i> Backup & Rekap</a>
    </div>
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
    </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                {{ session('error') }}
            </div>
        @endif
  <div class="row g-4">
    <!-- Kelas X -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="dashboard-card kelas10-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="card-title">Kelas X</div>
            <div class="card-value">120 <small>↑</small></div>
          </div>
          <div class="icon-circle">
            <i class="fa fa-graduation-cap"></i>
          </div>
        </div>
        <div class="card-footer">
          A: 25 &nbsp;&nbsp; S: 14 &nbsp;&nbsp; I: 4 &nbsp;&nbsp; T: {{ $rekapKelasX->total_terlambat ?? 0 }}
        </div>
      </div>
    </div>

    <!-- Kelas XI -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="dashboard-card kelas11-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="card-title">Kelas XI</div>
            <div class="card-value">44 <small>↑</small></div>
          </div>
          <div class="icon-circle">
            <i class="fa fa-book"></i>
          </div>
        </div>
        <div class="card-footer">
          A: 23 &nbsp;&nbsp; S: 15 &nbsp;&nbsp; I: 2 &nbsp;&nbsp; T: {{ $rekapKelasXI->total_terlambat ?? 0 }}
        </div>
      </div>
    </div>

    <!-- Kelas XII -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="dashboard-card kelas12-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="card-title">Kelas XII</div>
            <div class="card-value">87 <small>↑</small></div>
          </div>
          <div class="icon-circle">
            <i class="fa fa-trophy"></i>
          </div>
        </div>
        <div class="card-footer">
          A: 19 &nbsp;&nbsp; S: 10 &nbsp;&nbsp; I: 3 &nbsp;&nbsp; T: {{ $rekapKelasXII->total_terlambat ?? 0 }}
        </div>
      </div>
    </div>

    <!-- Total Siswa -->
    <div class="col-12 col-md-6 col-lg-3">
      <div class="dashboard-card total-card mb-3">
        <div class="d-flex justify-content-between align-items-center">
          <div>
            <div class="card-title">Total Absensi</div>
            <div class="card-value">{{ $totalAbsensiHariIni }} <small>↑</small></div>
          </div>
          <div class="icon-circle">
            <i class="fa fa-users"></i>
          </div>
        </div>
        <div class="card-footer">
       A: {{ $totalAlpha }} &nbsp;&nbsp; S: {{ $totalSakit }} &nbsp;&nbsp; I: {{ $totalIzin }} &nbsp;&nbsp; T: {{ $totalTerlambat }}
        </div>
      </div>
    </div>

  </div>
</div>

<div class="container-fluid">
  <div class="row mt-2">
    <div class="col-12">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <h5 class="fw-bold mb-3">Data Absensi Siswa</h5>
          {{-- 🔹 FILTER KELAS --}}
          <form method="GET" action="{{ route('Filter.kelas') }}" class="filter-kelas">
            <div class="container-fluid">
                    <div class="search-box mt-3 mb-4" style="margin-left:-20px; width:100%">
                    <select name="kelas" id="" class="form-control" width="100%">
                    <option value="">-- Semua kelas --</option>
                        @foreach ($tampil_kelas as $k)
                            <option value="{{ $k->kode_kelas }}">{{ $k->kelas }}</option>
                        @endforeach
                    </select>
                        <button type="submit"  style="right:-25px; border-radius:7px"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

          <div class="table-responsive">
            <table class="table table-borderless align-middle custom-table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Waktu</th>
                  <th>NIS</th>
                  <th>Nama</th>
                  <th> Kelas
                  </th>
                  <th>Wali Kelas</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                  <th>Aksi</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($data_absensi as $item_absen)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item_absen->jam_masuk ?? '-' }}</td>
                    <td>{{ $item_absen->nis }}</td>
                    <td>{{ ucwords(strtolower($item_absen->siswa->nama)) }}</td>
                    <td class="text-center">{{ $item_absen->siswa->kelas->kelas ?? '-' }}</td>
                    <td>{{ $item_absen->siswa->kelas->waliKelas->nama_guru ?? '-' }}</td>
                    <td>{{ $item_absen->status }}</td>
                    <td>{{ $item_absen->keterangan}}</td>
                    <td>
                      <a href="{{ route('edit-absen.data', $item_absen->id) }}" class="badge-soft orange">
                        <i class="fa fa-edit"></i>
                      </a>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <div class="d-flex justify-content-end align-items-center mt-3">
      {{ $data_absensi->onEachSide(1)->links('pagination::bootstrap-5') }}
      </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
