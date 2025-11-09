@extends('layout.app')
@section('title', 'Arsip Data Absensi')
@section('content')


<div class="container-fluid">
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

<!-- Modal  -->
<div class="modal fade" id="cetak-rekap" tabindex="-1" aria-labelledby="cetak rekap" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark">Download  & Rekap</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

        <!-- Form Rekap -->
        <form id="formManual"  action="{{ route('rekap.perkelas') }}" method="POST">
          @csrf
          <div class="form-group mb-3">
            <select name="id_kelas" class="form-control modern-input">
              <option value="">-- kelas --</option>
              @foreach ($kelas as $k)
                  <option value="{{ $k->kode_kelas }}">{{ $k->kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group mb-3 mt-2">
            <input type="text" name="wali_kelas" class="form-control modern-input" placeholder="Wali Kelas" readonly>
          </div>
          <button type="submit" class="btn btn-primary rounded-3 px-4 py-2">Simpan</button>
        </form>


      </div>
    </div>
  </div>
</div>


<div class="container-fluid mt-5">
<div class="download mb-3" style="position:relative">
<a href="#" class="badge-soft purple" style="position:absolute; right:0; transform:translateY(-50px)" data-bs-toggle="modal" data-bs-target="#cetak-rekap"> <i class="fa fa-download"> </i> Absensi & Izin</a>
</div>
</div>
  <div class="row mt-2">
    <div class="col-12">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <h5 class="fw-bold mb-3">ARSIP DATA ABSENSI</h5>
          {{-- 🔹 CARI SISWA --}}
          <form method="GET" action="{{ route('cari-data.arsip') }}" class="filter-kelas">
            <div class="container-fluid">
                    <div class="search-box mt-3 mb-4" style="margin-left:-20px; width:100%">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tersedia  {{ $data_absensi->total() }}  data Absensi....." style="border-radius:15px">
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
                  <th>Nama</th>
                  <th> Kelas
                  </th>
                  <th>Wali Kelas</th>
                  <th>Status</th>
                  <th>Keterangan</th>

                </tr>
              </thead>
              <tbody style="font-size:14px">
                @foreach ($data_absensi as $item_absen)
                  <tr>
                    <td>{{ $loop->iteration }}</td>
                    <td>{{ $item_absen->tanggal ?? '-' }}</td>
                    <td>{{ ucwords(strtolower($item_absen->siswa->nama)) }}</td>
                    <td class="text-center">{{ $item_absen->siswa->kelas->kelas ?? '-' }}</td>
                    <td>{{ $item_absen->siswa->kelas->waliKelas->nama_guru ?? '-' }}</td>
                    <td>{{ $item_absen->status }}</td>
                    <td>{{ $item_absen->keterangan}}</td>
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
