@extends('layout.app')
@section('title', 'Hasil Data')

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
        <form action="" method="GET">
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






      </div>
    </div>
  </div>


@endsection

