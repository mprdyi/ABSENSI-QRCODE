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
        <a href="{{ route('admin.data-master.data-siswa') }}"
            class="badge-soft blue shadow-sm fw-semibold px-3 py-2 mt-3 position-absolute"
            title="Tambah Data" style="right:0">
            <i class="fa fa-arrow-left"></i> Kembali
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
        <td>{{ $loop->iteration + ($siswas->currentPage() - 1) * $siswas->perPage() }}</td>
        <td><span>{{ $siswa->nis }}</span></td>
        <td>{{ $siswa->nama }}</td>
        <td class="fw-semibold text-dark">{{ $siswa->jk }}</td>

        {{-- KELAS --}}
        <td>
          @if($siswa->kelas)
            <span class="badge-soft purple">{{ $siswa->kelas->kelas }}</span>
          @else
            <span class="text-muted small">-</span>
          @endif
        </td>

        {{-- WALI KELAS --}}
        <td>
          @if($siswa->kelas && $siswa->kelas->waliKelas)
            <span>{{ $siswa->kelas->waliKelas->nama_guru }}</span>
          @else
            <span class="text-muted small">-</span>
          @endif
        </td>

        <td class="fw-semibold text-success">
          <div class="row">
            <div class="col-4">
              <a href="{{ route('edit-data-siswa.edit', $siswa->id) }}" class="badge-soft orange">
                <i class="fa fa-edit"></i>
              </a>
            </div>
            <div class="col-4">
              <form action="{{ route('data-siswa.destroy', $siswa->id) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus data ini?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="badge-soft red" style="border:none">
                  <i class="fa fa-trash"></i>
                </button>
              </form>
            </div>
            <div class="col-4">
            <a href="{{ route('qrcode.show', $siswa->nis) }}" class="badge-soft green" title="Lihat QR">
                <i class="fa fa-qrcode"></i>
            </a>
        </div>
          </div>
        </td>
      </tr>
    @endforeach
  </tbody>
</table>

      <div class="d-flex justify-content-end align-items-center mt-3">
    {{ $siswas->links('pagination::bootstrap-5') }}
</div>

</div>
</div>
</div>






      </div>
    </div>
  </div>


@endsection

