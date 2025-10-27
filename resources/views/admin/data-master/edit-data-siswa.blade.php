@extends('layout.app')
@section('title', 'Edit Data Siswa')

@section('content')
<style>
/* Wrapper dan Card */
.form-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    margin: 40px auto;
    max-width: 600px;
}

.form-card {
    background: #ffffff;
    padding: 35px 40px;
    border-radius: 16px;
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    width: 100%;
    transition: 0.3s;
}

.form-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 10px 28px rgba(0, 0, 0, 0.12);
}

/* Judul Form */
.form-title {
    text-align: center;
    margin-bottom: 25px;
    font-size: 22px;
    font-weight: 700;
    color: #4e54c8;
}

/* Input dan Select */
.form-row {
    display: flex;
    flex-wrap: wrap;
    gap: 15px;
    margin-bottom: 15px;
}

.form-row input,
.form-row select {
    flex: 1;
    padding: 12px 14px;
    border: 1px solid #dcdcdc;
    border-radius: 10px;
    font-size: 14px;
    background-color: #fafafa;
    transition: all 0.25s ease-in-out;
}

.form-row input:focus,
.form-row select:focus {
    border-color: #8f94fb;
    background: #fff;
    box-shadow: 0 0 0 3px rgba(143, 148, 251, 0.2);
    outline: none;
}

/* Tombol Submit */
.btn-update {
    width: 100%;
    padding: 12px;
    background: linear-gradient(90deg, #4e54c8, #8f94fb);
    border: none;
    border-radius: 10px;
    color: #fff;
    font-weight: 600;
    font-size: 15px;
    transition: 0.3s;
}

.btn-update:hover {
    opacity: 0.9;
    transform: translateY(-1px);
}

/* Responsif */
@media (max-width: 576px) {
    .form-card {
        padding: 25px;
    }
    .form-row {
        flex-direction: column;
    }
}
</style>

<div class="container-fluid mt-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Edit Data Siswa SMAN 9 Cirebon</h5>

            <div class="form-wrapper">
                <div class="form-card">
                    <h3 class="form-title">{{ $title }}</h3>

                    <form action="{{ route('edit-data-siswa.update', $siswa->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        {{-- Baris 1: NIS dan Nama --}}
                        <div class="form-row">
                            <input type="text" name="nis" placeholder="NIS"
                                   value="{{ $siswa->nis }}" readonly>
                            <input type="text" name="nama" placeholder="Nama Lengkap"
                                   value="{{ $siswa->nama }}">
                        </div>

                        {{-- Baris 2: Gender dan Kelas --}}
                        <div class="form-row">
                            <select name="jk">
                                <option value="L" {{ $siswa->jk == 'L' ? 'selected' : '' }}>Laki-Laki</option>
                                <option value="P" {{ $siswa->jk == 'P' ? 'selected' : '' }}>Perempuan</option>
                            </select>

                            <select name="id_kelas" class="form-control" id="kelasSelect">
                                <option value="">-- Pilih Kelas --</option>
                                @foreach ($data_kelas as $kelas)
                                    <option value="{{ $kelas->kode_kelas}}"
                                        {{ $siswa->id_kelas == $kelas->kode_kelas ? 'selected' : '' }}>
                                        {{ $kelas->kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Baris 3: Wali Kelas otomatis (readonly) --}}
                        <div class="form-row">
                            <input type="text" name="wali_kelas" id="waliKelasInput"
                                   placeholder="Wali Kelas"
                                   value="{{ $siswa->kelas && $siswa->kelas->waliKelas ? $siswa->kelas->waliKelas->nama : '' }}"
                                   readonly>
                        </div>

                        <button type="submit" class="btn-update">Update Data</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
