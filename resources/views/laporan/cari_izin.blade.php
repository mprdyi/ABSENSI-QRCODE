@extends('layout.app')
@section('title', 'CARI  DATA IZIN ')
@section('content')

<div class="container-fluid">
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif


    <div class="card shadow-sm border-0 rounded-4 mt-3">
        <div class="card-body">
        <div class="row">
                <div class="col-md-8"  ><h5 class="fw-bold mb-3">IZIN MENINGGALKAN KELAS</h5></div>
                <div class="col-md-4">

                     <a href="{{ route('izin.kelas') }}" class="btn-back badge-soft blue"> <i class="fa fa-arrow-left"></i> Kembali</a>

                </div>
            </div>

          {{-- 🔹 CARI SISWA --}}
          <form method="GET" action="{{ route('cari-data.izin') }}" class="filter-kelas">
            <div class="container-fluid">
                    <div class="search-box mt-3 mb-4" style="margin-left:-20px; width:100%">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Tersedia {{ $hitung_data }} data izin....." style="border-radius:15px">
                        <button type="submit"  style="right:-25px; border-radius:7px"><i class="fa fa-search"></i></button>
                    </div>
                </div>
            </form>

          <div class="table-responsive">
            <table class="table table-borderless align-middle custom-table">
              <thead style="white-space:nowrap">
                <tr>
                  <th>No</th>
                  <th>Nama</th>
                  <th>Wali Kelas</th>
                  <th> Kelas</th>
                  <th>Waktu Izin</th>
                  <th>Waktu Habis</th>
                  <th>Keperluan</th>
                  <th>Keterangan</th>
                  <th>Surat</th>

                </tr>
              </thead>
              <tbody style="font-size:14px">
              @foreach ($izin as $item)
                <tr>
                    <td>{{  $loop->iteration + ($izin->currentPage() - 1) * $izin->perPage() }}</td>
                    <td>{{ ucwords(strtolower($item->siswa->nama ?? '-' ))}}</td>
                    <td>{{ $item->siswa->kelas->waliKelas->nama_guru ?? '-' }}</td>
                    <td>{{ $item->siswa->kelas->kelas ?? '-' }}</td>
                    <td>Jam ke-{{ $item->waktu_izin  ??'-' }}</td>
                    <td> Jam Ke-{{ $item->waktu_habis ?? '-' }}</td>
                    <td>{{ $item->keperluan }}</td>
                    <td>{{ $item->keterangan }}</td>
                    <td> <a href="{{ route('surat-izin.download', $item->nis) }}" class="badge-soft blue" title="Download Suarat"><i class="fa fa-file"></i></a></td>

                </tr>
                @endforeach
              </tbody>
              <div class="d-flex justify-content-end align-items-center mt-3">
                {{ $izin->onEachSide(1)->links('pagination::bootstrap-5') }}
                </div>
            </table>
        </div>
    </div>
</div>

<!-- Script AJAX -->
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const kelasSelect = document.querySelector('select[name="kode_kelas"]');
    const siswaSelect = document.querySelector('select[name="nis"]');

    kelasSelect.addEventListener('change', function() {
      const idKelas = this.value;
      siswaSelect.innerHTML = '<option value="">-- Memuat siswa... --</option>';

      if (idKelas) {
        fetch(`/get-siswa-by-kelas/${idKelas}`)
          .then(response => response.json())
          .then(data => {
            siswaSelect.innerHTML = '<option value="">-- Nama ---</option>';
            data.forEach(siswa => {
              siswaSelect.innerHTML += `<option value="${siswa.nis}">${siswa.nama}</option>`;
            });
          })
          .catch(error => {
            console.error(error);
            siswaSelect.innerHTML = '<option value="">Gagal memuat siswa</option>';
          });
      } else {
        siswaSelect.innerHTML = '<option value="">-- Nama ---</option>';
      }
    });
  });
</script>

@endsection
