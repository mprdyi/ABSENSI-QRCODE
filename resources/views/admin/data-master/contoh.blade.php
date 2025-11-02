
@extends('layout.app')
@section('title', 'Data Guru')

@section('content')


<div class="container-fluid">
<div class="row mt-4">
    <div class="col-12">

<div class="card shadow-sm border-0 rounded-4">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Data GURU SMAN 9 Cirebon</h5>


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



    <div class="table-responsive">
    <div class="container">
    <div class="row">
        <div class="col-md-8">
        <div class="search-box mt-3 mb-4" style="margin-left:-20px">
        <form action="{{ route('cari-kelas') }}" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Cari data kelas...">
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
            <th>NIP</th>
            <th>Nama Guru</th>
            <th>Mapel</th>
            <th>No Hp</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>



          <tr>
          <td></td>
            <td>
             <span></span>
            </td>
            <td><span class="badge-soft red "></span></td>
            <td class="fw-semibold text-dark"></td>
            <td class="fw-semibold text-success">
                <div class="row">
                    <div class="col-6">
                    <a href="" class="badge-soft orange"><i class="fa fa-edit"></i></a>
                    </div>
                    <div class="col-6">
                    <form action="" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="badge-soft red" style="border:none"><i class="fa fa-trash"></i></a></button>
                    </form>
                    </div>
                </div>

            </td>
          </tr>

        </tbody>
        </tbody>
      </table>
      <div class="d-flex justify-content-end align-items-center mt-3">
    <!--paginatiom disini -->
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
        <h5 class="modal-title fw-bold text-dark">Tambah Data Guru</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

  <!-- Nav switch -->
  <div class="d-flex border-bottom mb-3">
    <button class="tab-btn active" id="tabManual">Input Manual</button>
    <button class="tab-btn" id="tabCSV">Upload CSV</button>
  </div>

  <!-- Form Input Manual -->
  <form id="formManual" action="{{ route('data-guru.store') }}" method="POST">
  @csrf
  <div class="form-group mb-3">
    <input type="text" class="form-control modern-input" name="nip" placeholder="NIP">
  </div>
  <div class="form-group mb-3">
    <input type="text" class="form-control modern-input" name="nama_guru" placeholder="Nama Guru">
  </div>
  <div class="form-group mb-3">
    <input type="text" class="form-control modern-input" name="mapel" placeholder="Mapel">
  </div>
  <div class="form-group mb-3">
    <input type="text" class="form-control modern-input" name="no_hp" placeholder="No Hp">
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
        Gunakan format kolom: <span class="fw-semibold text-dark">nip, nama guru, mapel, no hp</span>.
      </small>
      <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 w-100">Simpan</button>
    </div>
  </form>

</div>





      </div>
    </div>
  </div>


@endsection

