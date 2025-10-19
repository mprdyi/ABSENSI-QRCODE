@extends('layout.app')
@section('title', 'Hasil Data')

@section('content')


<div class="container-fluid">
<div class="row mt-4">
    <div class="col-12">

    <div class="card shadow-sm border-0 rounded-4">
  <div class="card-body">
    <h5 class="fw-bold mb-3">Data Kelas SMAN 9 Cirebon</h5>
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
            <th>Nama Kelas</th>
            <th>Wali Kelas</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
        @foreach ($kelas as $data_kelas)
        @php
        $warnakelas = $data_kelas->kelas;
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
          <td>{{ $loop->iteration + ($kelas->currentPage() - 1) * $kelas->perPage() }}</td>
            <td>
             <span>{{ $data_kelas->kode_kelas }}</span>
            </td>
            <td><span  class="badge-soft {{ $color }}">{{ $data_kelas->kelas }}</span></td>
            <td><span class="badge-soft purple">{{ $data_kelas->waliKelas->nama_guru ?? '-' }}</span></td>
            <td class="fw-semibold text-success">
                <div class="row">
                    <div class="col-6">
                    <a href="{{ route('data-kelas.edit', $data_kelas->id) }} " class="badge-soft orange"><i class="fa fa-edit"></i></a>
                    </div>
                    <div class="col-6">
                    <form action="{{ route('data-kelas.destroy', $data_kelas->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');">
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
      <div class="d-flex justify-content-end align-items-center mt-3">
    {{ $kelas->links('pagination::bootstrap-5') }}
</div>

</div>
</div>
</div>






      </div>
    </div>
  </div>


@endsection

