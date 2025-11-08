@extends('layout.app')
@section('title', 'Data Siswa')
@section('content')

<style>
   @media (max-width: 860px) {

        .btn-tambah{
            width:100%;
        }
        .btn-print{
            width:100%;
            posistion:absolute;
            right:20px;
            padding-top:34px;
            padding-bottom:34px;
            display:inline-block;
            height:40px;
        }
   }
   @media (min-width: 1200px) {
    .btn-print{
        transform:translateX(135px);
        display:inline-block;
        height:40px;
    }
    }
</style>
<div class="container-fluid">
<div class="card shadow-sm border-0 rounded-4">
  <div class="card-body">

        <h5 class="fw-bold mb-3">Data Siswa SMAN 9 Cirebon</h5>
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif



    <div class="row  mb-3">
        <div class="col-md-8">
        <div class="search-box mt-3">
        <form action="{{ route('cari-siswa') }}" method="GET">
            <input type="text" name="search" class="form-control" placeholder="Tersedia {{$hitung_data }} data siswa..." style="width:100%">
            <button type="submit"><i class="fa fa-search"></i></button>
        </form>
        </div>
        </div>
        <div class="col-md-4 position-relative">
            <div class="row">
                <div class="col-6">
                <a href="#" class="badge-soft blue mt-4 py-2 btn-print" title="Print QrCode" role="button" aria-label="download semua data"  data-bs-toggle="modal" data-bs-target="#modalPrintQr">
                    <i class="fa fa-qrcode"></i> Print
                    </a>
                </div>
                <div class="col-6">
                <a href="#" class="btn-tambah shadow-sm fw-semibold px-3 py-2 mt-4" title="Tambah Data" role="button" aria-label="Tambah data" data-bs-toggle="modal" data-bs-target="#modalTambah"> <i class="fa fa-plus"></i> Siswa
                </a>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
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
        <tbody style="font-size:14px">
@foreach ($siswas as $siswa)
    @php
        $kelasObj = $siswa->kelas;
        $kelasNama = $kelasObj->kelas ?? '-';
        $waliNama = $kelasObj->waliKelas->nama_guru ?? '-';

        if (Str::startsWith($kelasNama, 'XII')) {
            $color = 'blue';
        } elseif (Str::startsWith($kelasNama, 'XI')) {
            $color = 'orange';
        } elseif (Str::startsWith($kelasNama, 'X')) {
            $color = 'purple';
        } else {
            $color = 'primary';
        }
    @endphp

    <tr>
        <td>{{ $loop->iteration + ($siswas->currentPage() - 1) * $siswas->perPage() }}</td>
        <td><span>{{ $siswa->nis }}</span></td>
        <td>{{ ucwords(strtolower($siswa->nama ?? '-')) }}</td>
        <td class="fw-semibold text-dark">{{ $siswa->jk }}</td>
        <td><span class="badge-soft {{ $color }}">{{ $kelasNama }}</span></td>
        <td><span>{{ $waliNama }}</span></td>
        <td class="fw-semibold text-success">
        <span style="letter-spacing: 1px;">
        <a href="{{ route('edit-data-siswa.edit', $siswa->id) }}" class="badge-soft orange" style="margin-right:20px"><i class="fa fa-edit"></i> </a>
        <form action="{{ route('data-siswa.destroy', $siswa->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline; margin-right:20px">
            @csrf
            @method('DELETE')
            <button type="submit" class="badge-soft red" style="border:none">
                <i class="fa fa-trash"></i>
            </button>
        </form>
        <a href="{{ route('qrcode.show', $siswa->nis) }}" class="badge-soft green" title="Lihat QR"><i class="fa fa-qrcode"></i></a>
        </span>
        </td>
    </tr>
@endforeach
</tbody>
      </table>
      <div class="d-flex justify-content-end align-items-center mt-3">
      {{ $siswas->onEachSide(1)->links('pagination::bootstrap-5') }}
      </div>
</div>
</div>
</div>

<!-- Modal Tambah Siswa -->
<div class="modal fade" id="modalTambah" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal shadow-lg">
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
              <option value="L">-- Jenis Kelamin -- </option>
              <option value="L">Laki-Laki</option>
              <option value="P">Perempuan</option>
            </select>
          </div>
          <div class="form-group mb-3">
            <select name="id_kelas" class="form-control modern-input">
              <option value="">-- kelas --</option>
              @foreach ($data_kelas as $k)
                  <option value="{{ $k->kode_kelas }}">{{ $k->kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group mb-3 mt-2">
            <input type="text" name="wali_kelas" class="form-control modern-input" placeholder="Wali Kelas" readonly>
          </div>
          <button type="submit" class="btn btn-primary rounded-3 px-4 py-2">Simpan</button>
        </form>

        <!-- Form Upload CSV -->
        <form id="formCSV" class="d-none" action="{{ route('import.siswa') }}" method="POST" enctype="multipart/form-data">
          @csrf
          <div class="form-group mt-3">
            <label for="file" class="form-label fw-semibold text-secondary mb-2">File CSV / Excel</label>
            <div class="custom-file-upload" onclick="document.getElementById('file').click()">
              <i class="fa fa-cloud-upload-alt me-2"></i>
              <span id="file-name">Pilih file atau seret ke sini</span>
            </div>
            <input type="file" id="file" name="csv_file_siswa" accept=".csv,.xls,.xlsx" hidden>
            <small class="text-muted d-block mt-2">
              Gunakan format kolom: <span class="fw-semibold text-dark">NIS, Nama, JK, Kelas, Wali Kelas</span>.
            </small>
            <button type="submit" class="btn btn-primary rounded-3 px-4 py-2 w-100 mt-2">Simpan</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>



<!-- Modal QR CODE PRINT -->
<div class="modal fade" id="modalPrintQr" tabindex="-1" aria-labelledby="modalTambahLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content custom-modal shadow-lg">
      <div class="modal-header border-0 pb-0">
        <h5 class="modal-title fw-bold text-dark">Print Qr Code </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body">
        <form id="formManual" action="{{ route('download.qrcode.kelas') }}" method="POST">
          @csrf

          <div class="form-group mb-3">
            <select name="id_kelas" class="form-control modern-input">
              <option value="">-- kelas --</option>
              @foreach ($data_kelas as $k)
                  <option value="{{ $k->kode_kelas }}">{{ $k->kelas }}</option>
              @endforeach
            </select>
          </div>
          <div class="form-group mb-3 mt-2">
            <input type="text" name="wali_kelas" class="form-control modern-input" placeholder="Wali Kelas" readonly>
          </div>
          <button type="submit" class="btn btn-primary rounded-3 px-4 py-2">Print</button>
        </form>


      </div>
    </div>
  </div>
</div>


@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', () => {
    const modalTambah = document.getElementById('modalTambah');

    modalTambah.addEventListener('shown.bs.modal', () => {
        const tabManual = document.getElementById('tabManual');
        const tabCSV = document.getElementById('tabCSV');
        const formManual = document.getElementById('formManual');
        const formCSV = document.getElementById('formCSV');
        const fileInput = document.getElementById('file');
        const fileName = document.getElementById('file-name');
        const dropZone = document.querySelector('.custom-file-upload');

        // inisialisasi tab default
        formManual.classList.remove('d-none');
        formCSV.classList.add('d-none');
        tabManual.classList.add('active');
        tabCSV.classList.remove('active');

        tabManual.addEventListener('click', () => {
            tabManual.classList.add('active');
            tabCSV.classList.remove('active');
            formManual.classList.remove('d-none');
            formCSV.classList.add('d-none');
        });

        tabCSV.addEventListener('click', () => {
            tabCSV.classList.add('active');
            tabManual.classList.remove('active');
            formCSV.classList.remove('d-none');
            formManual.classList.add('d-none');
        });

        // file input
        fileInput.addEventListener('change', () => {
            fileName.textContent = fileInput.files[0]?.name || 'Pilih file atau seret ke sini';
        });

        // drag & drop
        dropZone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
        dropZone.addEventListener('dragleave', () => dropZone.classList.remove('dragover'));
        dropZone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropZone.classList.remove('dragover');
            fileInput.files = e.dataTransfer.files;
            fileName.textContent = e.dataTransfer.files[0]?.name || 'Pilih file atau seret ke sini';
        });
    });
});
</script>
@endsection
