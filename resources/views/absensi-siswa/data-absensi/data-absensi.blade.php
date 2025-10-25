@extends('layout.app')
@section('title', 'Absensi Siswa')
@section('content')

<div class="container-fluid">
  <div class="row mt-4">
    <div class="col-12">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <h5 class="fw-bold mb-3">Absensi Siswa</h5>

          <!-- Body -->
          <div class="modal-body">

            <!-- Tab Switch -->
            <div class="d-flex border-bottom mb-3">
              <button class="tab-btn active" id="tabManual">Input Manual</button>
              <button class="tab-btn" id="tabQR">QR Code</button>
            </div>

            <!-- Form Input Manual -->
            <form id="formManual" action="" method="POST">
              @csrf
              <div class="form-group mb-3" style="position:relative">
                <input type="text" class="form-control modern-input" name="nis" placeholder="Masukan Nis...">
              </div>
              <div class="button-submit" style="position:absolute; right:15px; z-index:1; transform: translateY(-64px);">
                <button type="submit" class="btn btn-primary rounded-3 px-4 py-2">Simpan</button>
              </div>
            </form>

            <!-- Form QR CODE SCANNER -->
            <form id="formQR" class="d-none" method="POST">
              @csrf
              <div class="form-group mt-3 text-center">
                <div id="qr-reader" style="width:100%; max-width:350px; margin:auto; border-radius:10px; overflow:hidden;"></div>
                <p id="qr-result" class="mt-3 fw-bold text-success" style="display:none;"></p>
                <p class="mt-2 text-muted small">Arahkan QR ke kamera</p>
              </div>
            </form>

          </div>
          <!-- End Body -->

          <div class="table-responsive">
            <div class="container">
              <div class="row">
                <div class="col-md-12">
                  <div class="search-box mt-3 mb-4" style="margin-left:-20px">
                    <form action="{{ route('cari-kelas') }}" method="GET">
                      <input type="text" name="search" class="form-control" placeholder="Cari data kelas...">
                      <button type="submit"><i class="fa fa-search"></i></button>
                    </form>
                  </div>
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
                <!-- isi data -->
              </tbody>
            </table>

            <div class="d-flex justify-content-end align-items-center mt-3"></div>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection


{{-- === SCRIPT === --}}
<script src="https://cdn.jsdelivr.net/npm/html5-qrcode@2.3.8/minified/html5-qrcode.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>
<script>
  document.addEventListener("DOMContentLoaded", function () {

      let html5QrCode = null;
      let isScanning = false;
      const tabManual = document.getElementById('tabManual');
      const tabQR = document.getElementById('tabQR');
      const formManual = document.getElementById('formManual');
      const formQR = document.getElementById('formQR');
      const qrResult = document.getElementById('qr-result');

      // --- Mulai Scanner ---
      function startQRScanner() {
          if (isScanning) return;

          if (typeof Html5Qrcode === 'undefined') {
              alert("Library HTML5 QR Code belum termuat!");
              return;
          }

          html5QrCode = new Html5Qrcode("qr-reader");
          isScanning = true;

          html5QrCode.start(
              { facingMode: "environment" },
              { fps: 10, qrbox: 250 },
              qrCodeMessage => {
                  qrResult.style.display = "block";
                  qrResult.textContent = "✅ QR Code berhasil di-scan: " + qrCodeMessage;
              },
              errorMessage => console.log("QR scan error:", errorMessage)
          ).catch(err => {
              console.error("Tidak bisa mengakses kamera:", err);
              alert("Gagal mengakses kamera! Pastikan sudah klik Allow pada izin kamera browser.");
          });
      }

      // --- Hentikan Scanner ---
      function stopQRScanner() {
          if (html5QrCode && isScanning) {
              html5QrCode.stop()
                  .then(() => {
                      html5QrCode.clear();
                      isScanning = false;
                      qrResult.style.display = "none";
                  })
                  .catch(err => console.warn("Gagal menghentikan kamera:", err));
          }
      }

      // --- Tab Manual ---
      tabManual.addEventListener('click', function() {
          tabManual.classList.add('active');
          tabQR.classList.remove('active');
          formManual.classList.remove('d-none');
          formQR.classList.add('d-none');
          stopQRScanner();
      });

      // --- Tab QR ---
      tabQR.addEventListener('click', function() {
          tabQR.classList.add('active');
          tabManual.classList.remove('active');
          formQR.classList.remove('d-none');
          formManual.classList.add('d-none');
          startQRScanner();
      });
  });
</script>
