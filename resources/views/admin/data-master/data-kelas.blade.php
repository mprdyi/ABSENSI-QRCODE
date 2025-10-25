
@extends('layout.app')
@section('title', 'Data Kelas')

@section('content')


<div class="container-fluid">
<div class="row mt-4">
    <div class="col-12">

    <div class="card shadow-sm border-0 rounded-4">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Data Kelas SMAN 9 Cirebon</h5>


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
            <th>Kode Kelas</th>
            <th>Kelas</th>
            <th>Wali Kelas</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($kelas as $item)
        @php
            $warnakelas = $item->kelas;

            if (\Illuminate\Support\Str::startsWith($warnakelas, 'XII')) {
                $color = 'blue';
            } elseif (\Illuminate\Support\Str::startsWith($warnakelas, 'XI')) {
                $color = 'orange';
            } elseif (\Illuminate\Support\Str::startsWith($warnakelas, 'X')) {
                $color = 'purple';
            } else {
                $color = 'primary';
            }
            @endphp


          <tr>
          <td>{{ $loop->iteration }}</td>
            <td>
             <span>{{ $item->kode_kelas }}</span>
            </td>
            <td><span class="badge-soft {{ $color }} ">{{ $item->kelas }}</span></td>
            <td class="fw-semibold text-dark">{{ $item->waliKelas->nama_guru ?? '-' }}</td>
            <td class="fw-semibold text-success">
                <div class="row">
                    <div class="col-6">
                    <a href="{{ route('data-kelas.edit', $item->id) }} " class="badge-soft orange"><i class="fa fa-edit"></i></a>
                    </div>
                    <div class="col-6">
                    <form action="{{ route('data-kelas.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
        </tbody>
      </table>
      <div class="d-flex justify-content-end align-items-center mt-3">
        {{ $kelas->links('pagination::bootstrap-5') }}
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
        <h5 class="modal-title fw-bold text-dark">Tambah Data Kelas</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">

  <!-- Nav switch -->
  <div class="d-flex border-bottom mb-3">
    <button class="tab-btn active" id="tabManual">Input Manual</button>
    <button class="tab-btn" id="tabCSV">Upload CSV</button>
  </div>

  <!-- Form Input Manual -->
  <form id="formManual" action="{{ route('data-kelas.store') }}" method="POST">
  @csrf
  <div class="form-group mb-3">
    <input type="text" class="form-control modern-input" name="kode_kelas" placeholder="Kode Kelas">
  </div>
  <div class="form-group mb-3">
    <input type="text" class="form-control modern-input" name="kelas" placeholder="Kelas">
  </div>
  <div class="form-group mb-3">
    <select name="id_wali_kelas" id="id_wali_kelas" class="form-control modern-input mb-2">
    <option value="" class="mb-2 p-2">-- Wali Kelas --</option>
    @foreach($guru as $guru)
        <option value="{{ $guru->nip }}">{{ $guru->nama_guru }}</option>
    @endforeach
    </select>
  </div>

  <button type="submit" class="btn btn-primary rounded-3 px-4 py-2">Simpan</button>
</form>



  <!-- Form Upload CSV -->
  <form id="formCSV" class="d-none" action="{{ route('import.kelas') }}" method="POST">
    <div class="form-group mt-3">
    @csrf
      <label for="file" class="form-label fw-semibold text-secondary mb-2">File CSV / Excel</label>
      <div class="custom-file-upload" onclick="document.getElementById('file').click()">
        <i class="fa fa-cloud-upload-alt me-2"></i>
        <span id="file-name">Pilih file atau seret ke sini</span>
      </div>
      <input type="file" id="file" name="file_data_kelas" accept=".csv,.xls,.xlsx" hidden>
      <small class="text-muted d-block mt-2">
        Gunakan format kolom: <span class="fw-semibold text-dark">Kode Kelas, Kelas, Wali Kelas</span>.
      </small>
      <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 w-100">Simpan</button>
    </div>
  </form>

</div>





      </div>
    </div>
  </div>


@endsection

