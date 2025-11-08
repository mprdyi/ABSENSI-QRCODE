@extends('layout.app')
@section('title', 'Izin Meninggalkan Kelas')
@section('content')
<style>
/* Responsif */
@media (max-width: 800px) {

    .search-box{
    transform:translateY(30px);
    }

    .btn-tambah{
        width:95%;
        margin-left:8px;
        padding:10px;
    }

}
</style>
<div class="container-fluid">
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif


<!-- Modal -->
<div class="modal fade" id="izinModal" tabindex="-1" aria-labelledby="izinModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content rounded-4 border-0 shadow py-3 px-3">

      <div class="modal-header">
        <h5 class="modal-title fw-bold" id="izinModalLabel">Izin Meninggalkan Kelas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body container">
        <form action="{{ route('izin.store') }}" method="POST">
          @csrf

          <div class="form-group mb-3">
            <select name="kode_kelas" id="kode_kelas" class="form-control modern-input">
            @foreach ($data_kelas as $kelas)
            <option value="{{ $kelas->kode_kelas }}">{{ $kelas->kelas }}</option>
            @endforeach
            </select>
          </div>

          <div class="form-group mb-3">
            <select name="nis" id="nis" class="form-control modern-input">
              <option value="">-- Nama  --- </option>
            </select>
          </div>

          <div class="form-group mb-3">
            <div class="row">
              <div class="col-6">
                <select name="jam_izin" id="" class="form-control modern-input">
                  <option value="">-- Jam Ke --- </option>
                  @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                </select>
              </div>
              <div class="col-6">
                <select name="jam_expired" id="" class="form-control modern-input">
                <option value="">-- Sd --- </option>
                  @for ($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }}</option>
                  @endfor
                </select>
              </div>
            </div>
          </div>

          <div class="form-group mb-3">
            <label for="keperluan">Keperluan</label><br>
            <input type="checkbox" name="sekolah"> Keperluan Sekolah
            <input type="checkbox" name="pribadi"> Keperluan Pribadi
            <p>
              <input type="text" name="keterangan" placeholder="Sebutkan keperluan..." class="form-control modern-input" required>
            </p>
          </div>

          <div class="text-end">
            <button type="submit" class="btn btn-primary">Update</button>
          </div>
        </form>
      </div>

    </div>
  </div>
</div>


    <div class="card shadow-sm border-0 rounded-4 mt-3">
        <div class="card-body">
            <div class="row">
                <div class="col-md-8"  ><h5 class="fw-bold mb-3">IZIN MENINGGALKAN KELAS</h5></div>
                <div class="col-md-4 mb-2">
                     <!-- Tombol untuk buka modal -->
                    <button type="button" class="btn btn-tambah mb-5" data-bs-toggle="modal" data-bs-target="#izinModal">
                    +  Izin
                    </button>
                </div>
            </div>



          {{-- 🔹 CARI SISWA --}}
          <form method="GET" action="{{ route('cari-data.izin') }}" class="filter-kelas">
            <div class="container-fluid">
                    <div class="search-box mt-3 mb-4 " style="margin-left:-20px; width:100%">
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
