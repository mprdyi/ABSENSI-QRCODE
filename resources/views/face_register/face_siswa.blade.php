@extends('layout.app')
@section('title', 'Registrasi Wajah')
@section('content')

<style>
/* BLUR BACKDROP */
#overlay {
  position: fixed;
  inset: 0;
  background: rgba(0,0,0,0.4);
  backdrop-filter: blur(6px);
  display: none;
  align-items: center;
  justify-content: center;
  z-index: 9999;
}

/* CENTER CARD */
#progressCard {
  background: white;
  border-radius: 1rem;
  padding: 1.5rem;
  width: 90%;
  max-width: 320px;
  text-align: center;
  box-shadow: 0 10px 40px rgba(0,0,0,0.3);
}

#progressText {
  font-weight: bold;
  margin-bottom: 0.75rem;
}

.progress {
  height: 10px;
}
</style>

<div class="container mt-4">
  <div class="row justify-content-center">
    <div class="col-md-6">

      <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body text-center">

          <h5 class="fw-bold mb-2">Daftar Wajah</h5>
          <p class="mb-3">
            <b>{{ $siswa->nama }}</b><br>
            <span class="text-muted">{{ $siswa->nis }}</span>
          </p>

          <div class="mx-auto mb-3 text-center" style="max-width:320px;">
            <video id="video" autoplay muted playsinline class="rounded-3 border w-100" style="display:none;"></video>
            <div id="registeredText" class="fw-bold text-success py-4" style="display:none;">
              Wajah sudah terdaftar
            </div>
          </div>

          <div id="status" class="text-info py-2 mb-3" style="display:none;"></div>

          <button id="btnCapture" class="btn btn-secondary w-100 py-2" disabled>
            Simpan Wajah
          </button>

        </div>
      </div>

    </div>
  </div>
</div>

<!-- OVERLAY PROGRESS -->
<div id="overlay">
  <div id="progressCard">
    <div id="progressText">Menghubungkan ke server pusat...</div>
    <div class="progress mb-3">
      <div id="progressBar" class="progress-bar progress-bar-striped progress-bar-animated" style="width:0%"></div>
    </div>
    <div id="progressPercent" class="small text-muted">0%</div>
  </div>
</div>

<script src="{{ asset('js/face_id.js') }}"></script>

<script>
document.addEventListener("DOMContentLoaded", async () => {

const video = document.getElementById('video');
const btn = document.getElementById('btnCapture');
const statusText = document.getElementById('status');
const registeredText = document.getElementById("registeredText");

const overlay = document.getElementById("overlay");
const progressBar = document.getElementById("progressBar");
const progressText = document.getElementById("progressText");
const progressPercent = document.getElementById("progressPercent");

let FACE_REGISTERED = {{ $siswa->face_descriptor ? 'true' : 'false' }};
let modelsReady = false;
let lastDescriptor = null; // Simpan descriptor terakhir di sini
let cameraStream = null;
let scanInterval = null;

// --- UI HELPERS ---
function showStatus(text, type = "info") {
  statusText.style.display = "block";
  statusText.className = "alert alert-" + type + " py-2 mb-3 text-small";
  statusText.innerText = text;
}

function setButton(state, text, color) {
  btn.disabled = (state !== "ready");
  btn.innerHTML = text;
  btn.className = "btn w-100 py-2 btn-" + color;
}

function setMode(registered) {
  if (registered) {
    video.style.display = "none";
    registeredText.style.display = "block";
    setButton("ready", "Update Wajah Baru", "primary");
  } else {
    video.style.display = "block";
    registeredText.style.display = "none";
    setButton("disabled", "Mencari Wajah...", "secondary");
  }
}

// --- CAMERA & MODELS ---
async function startCamera() {
  try {
    cameraStream = await navigator.mediaDevices.getUserMedia({
      video: { width: 320, height: 240, frameRate: { ideal: 15 } }
    });
    video.srcObject = cameraStream;
    return true;
  } catch (err) {
    showStatus("Kamera error/ditolak", "danger");
    return false;
  }
}

async function loadModels() {
  if (modelsReady) return;
  showStatus("Loading AI...", "warning");
  await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
  await faceapi.nets.faceLandmark68Net.loadFromUri('/models');
  await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
  modelsReady = true;
}

// --- DETECTION LOGIC (OPTIMIZED) ---
async function detectLoop() {
  if (!modelsReady || video.paused || video.ended) return;

  const result = await faceapi
    .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 160 })) // Ukuran kecil biar kenceng
    .withFaceLandmarks()
    .withFaceDescriptor();

  if (result) {
    // Cek posisi wajah (isFacingFront)
    const nose = result.landmarks.getNose()[0];
    const jaw = result.landmarks.getJawOutline();
    const centerJaw = (jaw[0].x + jaw[jaw.length - 1].x) / 2;

    if (Math.abs(nose.x - centerJaw) < 15) {
      lastDescriptor = Array.from(result.descriptor); // Simpan ke variabel global
      showStatus("Tahan 3 detik! Klik tombol simpan.", "success");
      setButton("ready", "Simpan Wajah Sekarang", "success");
    } else {
      lastDescriptor = null;
      showStatus("Posisi miring! Hadapkan lurus ke kamera.", "warning");
      setButton("disabled", "Wajah Miring", "secondary");
    }
  } else {
    lastDescriptor = null;
    showStatus("Wajah tidak terdeteksi...", "danger");
    setButton("disabled", "Mencari Wajah...", "secondary");
  }
}

// --- BUTTON ACTION ---
btn.addEventListener("click", async (e) => {
  e.preventDefault();

  // Jika mode Update (Ganti Wajah)
  if (FACE_REGISTERED) {
    FACE_REGISTERED = false;
    setMode(false);
    await loadModels();
    await startCamera();
    if(scanInterval) clearInterval(scanInterval);
    scanInterval = setInterval(detectLoop, 500); // 2 frame per detik biar nggak lemot
    return;
  }

  // Jika mode Simpan
  if (!lastDescriptor) {
    alert("Wajah belum siap! Pastikan status berwarna hijau.");
    return;
  }

  // Eksekusi Langsung
  overlay.style.display = "flex";
  progressBar.style.width = "50%";
  progressText.innerText = "Mengirim data...";

  try {
    const res = await fetch('/face/save', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': '{{ csrf_token() }}'
      },
      body: JSON.stringify({
        nis: '{{ $siswa->nis }}',
        face_descriptor: lastDescriptor
      })
    });

    if (!res.ok) throw new Error("Gagal simpan.");

    progressBar.style.width = "100%";
    progressText.innerText = "Berhasil!";

    setTimeout(() => {
      location.reload();
    }, 800);

  } catch (err) {
    overlay.style.display = "none";
    alert("Gagal koneksi ke server!");
  }
});

// --- START ---
setMode(FACE_REGISTERED);
if (!FACE_REGISTERED) {
  await loadModels();
  if (await startCamera()) {
    scanInterval = setInterval(detectLoop, 500);
  }
}
});
</script>

@endsection
