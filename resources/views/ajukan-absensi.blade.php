<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Ajukan Absensi | SMAN 9 Cirebon</title>
<style>
* {margin:0;padding:0;box-sizing:border-box;font-family:"Poppins",sans-serif;}
body {height:100vh;display:flex;justify-content:center;align-items:center;background:linear-gradient(135deg,#1E40AF,#3B82F6);}
.glass-card {background: rgba(255,255,255,0.15);backdrop-filter: blur(10px);border-radius:20px;padding:40px;width:90%;max-width:400px;color:#fff;text-align:center;box-shadow:0 8px 32px rgba(0,0,0,0.2);}
h2 {font-size:2rem;margin-bottom:15px;}
p {opacity:0.9;margin-bottom:20px;}
select, textarea {width:100%;padding:12px 15px;margin-bottom:20px;border-radius:10px;border:none;outline:none; background: rgba(255,255,255,0.3); color:#000;}
button {width:100%;padding:12px;background:#2563EB;border:none;border-radius:10px;color:white;font-weight:600;cursor:pointer;transition:0.3s;}
button:hover {background:#1E40AF;}
.alert {padding:12px;margin-bottom:15px;border-radius:10px; font-size:12px;}
.alert-danger {background: rgba(255,77,79,0.1); color: #b71c1c; font-size:12px;}
.alert-success {background: rgba(34,197,94,0.1); color:#065f46; font-size:12px;}
a {color:#fff;text-decoration:none; font-size:14px;}
</style>
<script src="{{ asset('Asset/jquery/jquery.min.js') }}"></script>
</head>
<body>
<div class="glass-card">
  <h2>Ajukan Absensi</h2>
  <p style="font-size:12px;">Ajukan absensi sebelum jam ke-2 07:15</p>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">{{ $errors->first() }}</div>
  @endif

  <form method="POST" action="{{ route('ajukan-absensi.show') }}">
    @csrf
    <div class="form-group">
    <select name="kelas_ajukan" id="kelas_ajukan" required>
        <option value="">-- Pilih Kelas --</option>
        @foreach ($data_kelas as $kelas)
          <option value="{{ $kelas->kode_kelas }}">{{ $kelas->kelas }}</option>
        @endforeach
      </select>
    </div>

    <div class="form-group">
    <select name="siswa_ajukan" id="siswa_ajukan" required>
        <option value="">-- Nama Siswa --</option>
      </select>
    </div>

    <div class="form-group">
      <select name="status_ajukan" required>
        <option value="">-- Status --</option>
        <option value="izin">Izin</option>
        <option value="sakit">Sakit</option>
      </select>
    </div>

    <div class="form-group">
      <textarea name="keterangan_ajukan" cols="30" rows="5" placeholder="Masukan keterangan..." required></textarea>
    </div>

    <button type="submit">Ajukan</button>
  </form>

  <div style="margin-top:15px;"><a href="{{ route('login') }}">Back to Login</a></div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
    const kelasSelect = document.querySelector('select[name="kelas_ajukan"]');
    const siswaSelect = document.querySelector('select[name="siswa_ajukan"]');

    kelasSelect.addEventListener('change', function() {
        const idKelas = this.value;
        console.log('Kelas dipilih:', idKelas);
        siswaSelect.innerHTML = '<option value="">-- Memuat siswa... --</option>';

        if (idKelas) {
            fetch(`/get-siswa-by-kelas-2/${idKelas}`)
                .then(res => {
                    console.log('Response status:', res.status);
                    return res.json();
                })
                .then(data => {
                    console.log('Data diterima:', data);
                    siswaSelect.innerHTML = '<option value="">-- Nama --</option>';
                    data.forEach(siswa => {
                        siswaSelect.innerHTML += `<option value="${siswa.nis}">${siswa.nama}</option>`;
                    });
                })
                .catch(err => {
                    console.error('Error fetch:', err);
                    siswaSelect.innerHTML = '<option value="">Gagal memuat siswa</option>';
                });
        } else {
            siswaSelect.innerHTML = '<option value="">-- Nama --</option>';
        }
    });
});

</script>
</body>
</html>
