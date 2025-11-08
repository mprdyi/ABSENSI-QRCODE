@extends('layout.app')
@section('title','Absensi Siswa')
@section('content')

<div class="container-fluid">
  <div class="row mt-4">
    <div class="col-12">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <h5 class="fw-bold mb-3">Absensi Siswa</h5>

          <!-- Tab Switch -->
          <div class="d-flex border-bottom mb-3">
            <button class="tab-btn active" id="tabManual">Input Manual</button>
            <button class="tab-btn" id="tabQR">QR Code</button>
          </div>

          <!-- Form Input Manual -->
          <form id="formManual">
            @csrf
            <div class="form-group mb-3" style="position:relative">
              <input type="text" class="form-control modern-input" name="nis" placeholder="Masukan NIS...">
            </div>
            <div class="button-submit" style="position:absolute; right:15px; z-index:1; transform: translateY(-64px);">
              <button type="submit" class="btn btn-primary rounded-3 px-4 py-2">Simpan</button>
            </div>
          </form>

          <!-- Form QR CODE SCANNER -->
          <div id="formQR" class="d-none mt-3 text-center">
            <div id="qr-reader" style="width:100%; max-width:350px; margin:auto; border-radius:10px; overflow:hidden;"></div>
            <p id="qr-result" class="mt-3 fw-bold text-success" style="display:none;"></p>
            <p class="mt-2 text-muted small">Arahkan QR ke kamera</p>
          </div>

          <!-- Table -->
          <div class="table-responsive mt-5">
          <div id="alertContainer" class="mt-2"></div>
          <p class="text-muted">Total : {{$total_Absensi_Hari_ini}} dari  {{$total_siswa}} Siswa</p>
            <table class="table table-borderless align-middle custom-table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Waktu</th>
                  <th>NIS</th>
                  <th>Nama</th>
                  <th>Kelas</th>
                  <th>Wali Kelas</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                </tr>
              </thead>

              <tbody id="absensi-body">

        </tbody>
            </table>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>

@endsection

{{-- === SCRIPT === --}}
<script src="{{ asset('js/scanner.js') }}"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    let html5QrCode = null;
    let isScanning = false;
    let scannedNIS = {}; // track NIS yang sudah di-scan hari ini
    const tabManual = document.getElementById('tabManual');
    const tabQR = document.getElementById('tabQR');
    const formManual = document.getElementById('formManual');
    const formQR = document.getElementById('formQR');
    const qrResult = document.getElementById('qr-result');

    // --- QR SCANNER ---
    function startQRScanner() {
        if (isScanning) return;
        if (typeof Html5Qrcode === 'undefined') { alert("Library HTML5 QR Code belum termuat!"); return; }

        html5QrCode = new Html5Qrcode("qr-reader");
        isScanning = true;

        html5QrCode.start(
    { facingMode: "user" },
    { fps: 10, qrbox: 250 },
    qrCodeMessage => {
        //  mencegah scan double dalam 3 detik
        if(scannedNIS[qrCodeMessage]) return;
        scannedNIS[qrCodeMessage] = true;
        setTimeout(() => { scannedNIS[qrCodeMessage] = false; }, 3000);

        $.ajax({
            url: "{{ route('absensi.store') }}",
            type: "POST",
            data: {_token: "{{ csrf_token() }}", nis: qrCodeMessage},
            success: function(res){
                qrResult.style.display = "block";

                if(res.success){
                    qrResult.textContent = "✅ Absensi berhasil: " + qrCodeMessage;
                } else if(res.status === 409 || res.message.includes("sudah absen")){
                    qrResult.textContent = "⚠ Siswa sudah absen hari ini!";
                } else {
                    qrResult.textContent = "⚠ " + res.message;
                }

                refreshTable();
            },
            error: function(xhr){
                if(xhr.status === 409){
                    qrResult.style.display = "block";
                    qrResult.textContent = "⚠ Siswa sudah absen hari ini!";
                } else {
                    qrResult.style.display = "block";
                    qrResult.textContent = "❌ NIS Tidak Terdaftar, Gagal menyimpan data.";
                }
            }
        });
    },
    errorMessage => console.log("QR scan error:", errorMessage)
).catch(err => {
    console.error("Tidak bisa mengakses kamera:", err);
    alert("Gagal mengakses kamera! Pastikan sudah klik Allow pada browser.");
});

    }

    function stopQRScanner() {
        if (html5QrCode && isScanning) {
            html5QrCode.stop().then(() => {
                html5QrCode.clear();
                isScanning = false;
                qrResult.style.display = "none";
            }).catch(err => console.warn("Gagal menghentikan kamera:", err));
        }
    }

    // --- TAB HANDLER ---
    tabManual.addEventListener('click', function() {
        tabManual.classList.add('active');
        tabQR.classList.remove('active');
        formManual.classList.remove('d-none');
        formQR.classList.add('d-none');
        stopQRScanner();
    });

    tabQR.addEventListener('click', function() {
        tabQR.classList.add('active');
        tabManual.classList.remove('active');
        formQR.classList.remove('d-none');
        formManual.classList.add('d-none');
        startQRScanner();
    });

    // --- FORM MANUAL AJAX ---
formManual.addEventListener('submit', function(e){
    e.preventDefault();
    $.ajax({
        url: "{{ route('absensi.store') }}",
        type: "POST",
        data: $(this).serialize(),
        beforeSend: function(){
            $('.btn-primary').attr('disabled', true).text('Menyimpan...');
        },
        success: function(response){
            showAlert(response.success ? 'success' : 'warning', response.message);
            formManual.reset();
            refreshTable();
        },
        error: function(xhr){
            if(xhr.status === 409) showAlert('warning', '⚠ Siswa sudah absen hari ini!');
            else showAlert('danger', '❌ NIS Tidak Terdaftar,, Gagal menyimpan data.');
        },
        complete: function(){
            $('.btn-primary').attr('disabled', false).text('Simpan');
        }
    });
});

// --- Fungsi untuk menampilkan alert Bootstrap ---
function showAlert(type, message) {
    const alertBox = `
        <div class="alert alert-${type} alert-dismissible fade show" role="alert">
            ${message}
        </div>
    `;
    $('#alertContainer').html(alertBox);

    // Auto-hide dalam 4 detik
    setTimeout(() => {
        $('.alert').alert('close');
    }, 4000);
}


    // --- AJAX REFRESH TABLE ---
    function refreshTable() {
        $.ajax({
            url: "{{ route('data-absensi-siswa.Qr') }}",
            type: "GET",
            dataType: "json",
            success: function(res){ $('#absensi-body').html(res.html); },
            error: function(){ console.warn('Gagal memuat data absensi.'); }
        });
    }

    // Load pertama kali
    refreshTable();
    // refresh setiap 5 detik
    setInterval(refreshTable, 5000);


});
</script>
