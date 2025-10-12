@extends('layout.app')
@section('title', 'Data Siswa')

@section('content')


<div class="container-fluid">
<div class="row mt-4">
    <div class="col-12">

    <div class="card shadow-sm border-0 rounded-4">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Data Siswa SMAN 9 Cirebon</h5>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
        @endif
    <div class="table-responsive">
    <div class="container">
    <div class="row">
        <div class="col-md-8">
        <div class="search-box mt-3 mb-4" style="margin-left:-20px">
        <form action="{{ route('cari-siswa') }}" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Cari data siswa...">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
        </div>
        </div>

        <div class="col-md-4 position-relative">
        <a href="#"
            class="btn-tambah shadow-sm fw-semibold px-3 py-2 mt-3"
            title="Tambah Data"
            role="button"
            aria-label="Tambah data" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="fa fa-plus"></i> Tambah Data
        </a>
        </div>

    </div>
    </div>
      <table class="table table-borderless align-middle custom-table">
        <thead>
          <tr>
            <th>No</th>
            <th>NIS</th>
            <th>Nama</th>
            <th>Gender</th>
            <th>Kelas</th>
            <th>Wali Kelas</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($siswas as $siswa)
          <tr>
          <td>{{ $loop->iteration }}</td>
            <td>
             <span>{{ $siswa->nis }}</span>
            </td>
            <td>{{ $siswa->nama }}</td>
            <td class="fw-semibold text-dark">{{ $siswa->jk}}</td>
            <td><span class="badge-soft purple">{{ $siswa->kelas }}</span></td>
            <td><span class="text-muted small">{{ $siswa->wali_kelas }}</span></td>
            <td class="fw-semibold text-success">
                <div class="row">
                    <div class="col-6">
                    <a href="{{ route('edit-data-siswa.edit', $siswa->id) }} " class="badge-soft orange"><i class="fa fa-edit"></i></a>
                    </div>
                    <div class="col-6">
                    <form action="{{ route('data-siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="badge-soft red" style="border:none"><i class="fa fa-trash"></i></a></button>
                    </form>
                    </div>
                </div>

            </td>
          </tr>
          @endforeach
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





 <!-- Modal -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal shadow-lg">

      <!-- Header -->
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark">Tambah Data Siswa</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

  <!-- Nav switch -->
  <div class="d-flex border-bottom mb-3">
    <button class="tab-btn active" id="tabManual">Input Manual</button>
    <button class="tab-btn" id="tabCSV">Upload CSV</button>
  </div>

  <!-- Form Input Manual -->
  <form id="formManual" action="{{ route('data-siswa.store') }}" method="POST">
  @csrf
  <div class="form-group mb-3">
    <input type="text" class="form-control modern-input" name="nis" placeholder="NIS">
  </div>
  <div class="form-group mb-3">
    <input type="text" class="form-control modern-input" name="nama" placeholder="Nama Lengkap">
  </div>
  <div class="form-group mb-3">
    <select name="jk" id="jk" class="form-control modern-input">
        <option value="L">Laki-Laki</option>
        <option value="P">Perempuan</option>
    </select>
  </div>

  <div class="form-group mb-3">
    <input type="text" class="form-control modern-input" name="kelas" placeholder="Kelas">
  </div>
  <div class="form-group mb-4">
    <input type="text" class="form-control modern-input" name="wali_kelas" placeholder="Wali Kelas">
  </div>

  <button type="submit" class="btn btn-primary rounded-3 px-4 py-2">Simpan</button>
</form>



  <!-- Form Upload CSV -->
  <form id="formCSV" class="d-none">
    <div class="form-group mt-3">
      <label for="file" class="form-label fw-semibold text-secondary mb-2">File CSV / Excel</label>
      <div class="custom-file-upload" onclick="document.getElementById('file').click()">
        <i class="fa fa-cloud-upload-alt me-2"></i>
        <span id="file-name">Pilih file atau seret ke sini</span>
      </div>
      <input type="file" id="file" name="file" accept=".csv,.xls,.xlsx" hidden>
      <small class="text-muted d-block mt-2">
        Gunakan format kolom: <span class="fw-semibold text-dark">NIS, Nama, Kelas</span>.
      </small>
      <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 w-100">Simpan</button>
    </div>
  </form>

</div>





      </div>
    </div>
  </div>


@endsection

