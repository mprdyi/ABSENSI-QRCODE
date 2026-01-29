@extends('layout.app')
@section('title','Absensi Siswa')
@section('content')
<style>
.video-wrapper {
  max-width: 320px;
  width: 100%;
  aspect-ratio: 4 / 3;
  overflow: hidden;
}

.video-wrapper video {
  width: 100%;
  height: 100%;
  object-fit: cover;
}

</style>
<div class="container-fluid">
  <div class="row mt-4">
    <div class="col-12">
      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
          <h5 class="fw-bold mb-3">Absensi Siswa</h5>
          <div class="text-center mb-4">
          <h6 id="challengeText" class="text-danger fw-bold mb-2" style="display:none;"></h6>
          <div class="video-wrapper mx-auto">
            <video id="video" autoplay muted playsinline class="rounded-3shadow w-100 mb-5"></video>
            </div>
            <p id="face-result" class="mt-5 fw-bold text-success" style="display:none;margin-top:15px"></p>
            <p class="mt-2 text-muted small">
            <button id="btnScanFace" class="btn btn-secondary px-4 py-2" disabled>
                 Kirim Absensi
            </button>
            </p>


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
<script src="{{ asset('js/face_id.js') }}"></script>
<script>
document.addEventListener("DOMContentLoaded", async () => {

const video = document.getElementById('video');
const btnScan = document.getElementById('btnScanFace');
const faceResult = document.getElementById('face-result');
const challengeText = document.getElementById("challengeText");

// DATA DARI DATABASE (dikirim dari controller)
const rawDescriptor = JSON.parse('{!! $face_descriptor !!}');
const labeledDescriptor = new Float32Array(rawDescriptor);

let modelsLoaded = false;
let cameraStarted = false;
let faceMatched = false; // Status apakah wajah cocok
let schoolMode = false;
let baseline = null;
let detectInterval = null;

// --- LOAD MODELS ---
async function loadModels() {
    console.log("Loading Models...");
    await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
    await faceapi.nets.faceLandmark68TinyNet.loadFromUri('/models');
    await faceapi.nets.faceRecognitionNet.loadFromUri('/models'); // Penting untuk mencocokkan wajah
    modelsLoaded = true;
}

// --- START CAMERA ---
async function startCamera() {
    try {
        const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } });
        video.srcObject = stream;
        cameraStarted = true;
    } catch (err) { alert("Kamera Error"); }
}

// --- REALTIME MATCHING ---
function startRealtimeDetect() {
    detectInterval = setInterval(async () => {
        if (!modelsLoaded || !cameraStarted || video.videoWidth === 0) return;

        const result = await faceapi
            .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 128 }))
            .withFaceLandmarks(true)
            .withFaceDescriptor();

        if (result) {
            // BANDINGKAN WAJAH KAMERA DENGAN DATABASE
            const distance = faceapi.euclideanDistance(result.descriptor, labeledDescriptor);

            if (distance < 0.5) { // 0.5 adalah ambang batas (semakin kecil semakin mirip)
                faceMatched = true;
                faceResult.innerText = "Wajah Cocok: {{ $siswa->nama }}";
                faceResult.className = "fw-bold text-success";
                btnScan.disabled = false;
            } else {
                faceMatched = false;
                faceResult.innerText = "Wajah Tidak Dikenali!";
                faceResult.className = "fw-bold text-danger";
                btnScan.disabled = true;
            }

            // JIKA SEDANG MODE TANTANGAN (LIVENESS)
            if (schoolMode && result.landmarks) {
                if (verifyMotion(result.landmarks)) {
                    processAbsensi(); // Wajah cocok + Gerakan ok = Absen!
                }
            }
        }
    }, 300);
}

// --- PROSES KIRIM DATA ---
async function processAbsensi() {
    schoolMode = false;
    clearInterval(detectInterval); // Stop scan

    challengeText.innerText = "⏳ Sedang memproses absensi...";

    try {
        const res = await fetch('/absensi/store', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ nis: '{{ $siswa->nis }}' })
        });

        const data = await res.json();
        if (data.success) {
            alert(data.message);
            location.reload(); // Refresh untuk update tabel
        }
    } catch (err) {
        alert("Gagal mengirim data.");
        location.reload();
    }
}

// --- LOGIKA LIVENESS (GERAKAN) ---
// (Fungsi verifyMotion, pickChallenge, dist, captureBaseline tetap pakai yang kamu punya sebelumnya)
// ... copy paste fungsi verifyMotion dkk kamu disini ...

btnScan.addEventListener("click", () => {
    if (!faceMatched) return;

    // Mulai tantangan gerakan (Liveness Detection)
    schoolMode = true;
    currentChallenge = challenges[Math.floor(Math.random() * challenges.length)];
    challengeText.style.display = "block";
    challengeText.innerText = `Verifikasi Keamanan: ${currentChallenge.text}`;
});

// INIT
await loadModels();
await startCamera();
startRealtimeDetect();
});
</script>


@endsection
