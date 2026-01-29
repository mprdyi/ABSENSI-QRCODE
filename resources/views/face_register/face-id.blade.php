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
            @if($statusHadir)
        @if($statusHadir->status === 'Alpha')
            {{-- TAMPILAN JIKA KENA AUTO ALPHA --}}
            <div class="alert alert-danger rounded-4 p-4 text-center">
                <i class="fas fa-user-times fa-3x mb-3"></i>
                <h4 class="fw-bold">Waktu Absensi Berakhir</h4>
                <p class="mb-0">Kamu tercatat <b>Alpha</b> oleh sistem karena melewati batas waktu scan.</p>
                <hr>
                <a href="{{ url('/') }}" class="btn btn-outline-danger btn-sm">Kembali ke Dashboard</a>
            </div>
        @else
            {{-- TAMPILAN JIKA SUDAH HADIR / TERLAMBAT --}}
            <div class="alert alert-success rounded-4 p-4">
                <h4 class="fw-bold">🎉 Kamu Sudah Absen!</h4>
                <p class="mb-0">Status: <b>{{ $statusHadir->status }}</b></p>
                <p>Tercatat pada jam: <b>{{ $statusHadir->jam_masuk ?? '--:--' }}</b></p>
                <p class="small text-muted">{{ $statusHadir->keterangan }}</p>
                <a href="{{ url('/') }}" class="btn btn-success btn-sm mt-2">Kembali ke Dashboard</a>
            </div>
        @endif

        @else
        <h6 id="challengeText" class="text-danger fw-bold mb-2" style="display:none;"></h6>
        <div class="video-wrapper mx-auto shadow rounded-3">
            <video id="video" autoplay muted playsinline class="w-100"></video>
        </div>

        <p id="face-result" class="mt-3 fw-bold text-success" style="display:none;"></p>
        <button id="btnScanFace" class="btn btn-secondary px-4 py-2 mt-2" disabled>Kirim Absensi</button>
    @endif
    </div>
          <!-- Table -->
          <div class="table-responsive mt-5">
          <div id="alertContainer" class="mt-2"></div>
          <p class="text-muted">Total : {{$total_absensi_saya }} Data</p>
            <table class="table table-borderless align-middle custom-table">
              <thead>
                <tr>
                  <th>No</th>
                  <th>Tanggal</th>
                   <th>Masuk</th>
                  <th>Nama</th>
                  <th>Kelas</th>
                  <th>Status</th>
                  <th>Keterangan</th>
                </tr>
              </thead>
              <tbody id="absensi-body">
                @forelse($riwayat_absensi as $index => $absen)
               <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ \Carbon\Carbon::parse($absen->tanggal)->format('d/m/Y') }}</td>
                <td>{{ $absen->jam_masuk }}</td>
                <td>{{ ucwords(strtolower($siswa->nama)) }}</td>
                <td>{{ data_get($siswa->kelas, 'kelas', '-') }}</td>
                <td>
                    <span class="badge {{ $absen->status == 'Hadir' ? 'bg-success text-white' : ($absen->status == 'Terlambat' ? 'bg-warning text-white' : 'bg-danger text-white') }}">
                        {{ $absen->status }}
                    </span>
                </td>
                <td>{{ $absen->keterangan }}</td>
            </tr>
            @empty
            <tr>
                <td colspan="7" class="text-center text-muted">Belum ada riwayat absensi.</td>
            </tr>
            @endforelse

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
    // JIKA SUDAH HADIR, STOP SEMUA SCRIPT
    @if($statusHadir)
        console.log("Siswa sudah absen, script dihentikan.");
        return;
    @endif

    const video = document.getElementById('video');
    const btnScan = document.getElementById('btnScanFace');
    const faceResult = document.getElementById('face-result');
    const challengeText = document.getElementById("challengeText");

    // DATA DARI DATABASE
    const rawDescriptor = JSON.parse('{!! $face_descriptor !!}');
    const labeledDescriptor = new Float32Array(rawDescriptor);

    let modelsLoaded = false;
    let cameraStarted = false;
    let faceMatched = false;
    let schoolMode = false;
    let baseline = null;
    let successStart = 0;
    let currentChallenge = null;
    let detectInterval = null;

    const BASELINE_TIME = 400;
    const HOLD_MS = 300;
    const MOVE_PX = 8;

    // --- BANK TANTANGAN ---
    const challenges = [
        { text: "👄 Buka mulut", type: "mouth" },
        { text: "⬅ Hadap ke kiri", type: "left" },
        { text: "➡ Hadap ke kanan", type: "right" },
        //{ text: "⬇ Anggukkan kepala", type: "nod" }
    ];

    function dist(a, b) { return Math.hypot(a.x - b.x, a.y - b.y); }

    // --- LOAD MODELS ---
    async function loadModels() {
        console.log("Loading Models...");
        try {
            await faceapi.nets.tinyFaceDetector.loadFromUri('/models');
            await faceapi.nets.faceLandmark68TinyNet.loadFromUri('/models');
            await faceapi.nets.faceRecognitionNet.loadFromUri('/models');
            modelsLoaded = true;
            console.log("Models Loaded!");
        } catch (e) {
            console.error("Gagal load model AI", e);
        }
    }

    // --- START CAMERA ---
    async function startCamera() {
        try {
            const stream = await navigator.mediaDevices.getUserMedia({ video: { width: 320, height: 240 } });
            video.srcObject = stream;
            cameraStarted = true;
        } catch (err) { alert("Kamera Error: " + err); }
    }

    // --- LIVENESS LOGIC ---
    function captureBaseline(landmarks) {
        const mouth = landmarks.getMouth();
        const nose = landmarks.getNose()[0];
        const jaw = landmarks.getJawOutline();
        const chin = jaw[Math.floor(jaw.length / 2)];
        baseline = {
            noseX: nose.x, noseY: nose.y,
            mouthOpen: dist(mouth[13], mouth[19]),
            chinY: chin.y, time: Date.now()
        };
    }

    function verifyMotion(landmarks) {
        const mouth = landmarks.getMouth();
        const nose = landmarks.getNose()[0];
        const jaw = landmarks.getJawOutline();
        const chin = jaw[Math.floor(jaw.length / 2)];

        if (!baseline) { captureBaseline(landmarks); return false; }
        if (Date.now() - baseline.time < BASELINE_TIME) return false;

        let moved = false;
        if (currentChallenge.type === "left" && baseline.noseX - nose.x > MOVE_PX) moved = true;
        if (currentChallenge.type === "right" && nose.x - baseline.noseX > MOVE_PX) moved = true;
        if (currentChallenge.type === "mouth" && (dist(mouth[13], mouth[19]) - baseline.mouthOpen > MOVE_PX)) moved = true;
        //if (currentChallenge.type === "nod" && chin.y - baseline.chinY > MOVE_PX) moved = true;

        if (moved) {
            if (!successStart) successStart = Date.now();
            if (Date.now() - successStart > HOLD_MS) return true;
        } else { successStart = 0; }
        return false;
    }

    // --- DETECT LOOP ---
    function startRealtimeDetect() {
        detectInterval = setInterval(async () => {
            if (!modelsLoaded || !cameraStarted || video.videoWidth === 0) return;

            const result = await faceapi
                .detectSingleFace(video, new faceapi.TinyFaceDetectorOptions({ inputSize: 128 }))
                .withFaceLandmarks(true)
                .withFaceDescriptor();

            if (result) {
                const distance = faceapi.euclideanDistance(result.descriptor, labeledDescriptor);

                if (distance < 0.5) {
                    faceMatched = true;
                    faceResult.style.display = "block";
                    faceResult.innerText = "Wajah Cocok: {{ $siswa->nama }}";
                    faceResult.className = "fw-bold text-success mt-3";
                    btnScan.disabled = false;
                    btnScan.className = "btn btn-success px-4 py-2";
                } else {
                    faceMatched = false;
                    faceResult.style.display = "block";
                    faceResult.innerText = "Wajah Tidak Dikenali!";
                    faceResult.className = "fw-bold text-danger mt-3";
                    btnScan.disabled = true;
                    btnScan.className = "btn btn-secondary px-4 py-2";
                }

                if (schoolMode && result.landmarks) {
                    if (verifyMotion(result.landmarks)) {
                        processAbsensi();
                    }
                }
            } else {
                faceResult.innerText = "Mencari wajah...";
                btnScan.disabled = true;
            }
        }, 300);
    }

    async function processAbsensi() {
        schoolMode = false;
        clearInterval(detectInterval);
        challengeText.innerText = "✅ Berhasil! Sedang mengirim...";

        try {
            const res = await fetch('/absensi-faceid/store', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ nis: '{{ $siswa->nis }}' })
            });
            const data = await res.json();
            alert(data.message);
            location.reload();
        } catch (err) {
            alert("Gagal kirim data");
            location.reload();
        }
    }

    // --- BUTTON EVENT ---
    btnScan.addEventListener("click", () => {
        if (!faceMatched) return;
        schoolMode = true;
        baseline = null;
        successStart = 0;
        currentChallenge = challenges[Math.floor(Math.random() * challenges.length)];
        challengeText.style.display = "block";
        challengeText.innerText = `🔐 Verifikasi: ${currentChallenge.text}\nTahan wajah diam sebentar lalu gerakkan...`;
    });

    // --- INITIALIZE ---


    await loadModels();
    await startCamera();
    startRealtimeDetect();
});
</script>

@endsection
