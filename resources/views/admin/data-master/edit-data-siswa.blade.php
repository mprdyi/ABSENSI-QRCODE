@extends('layout.app')
@section('title', 'Edit Data Siswa')

@section('content')
<div class="form-wrapper">
    <div class="form-card">
        <h3 class="form-title">{{ $title }}</h3>

        <form action="{{ route('edit-data-siswa.update', $siswa->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-row">
                <input type="text" name="nis" placeholder="NIS" value="{{ $siswa->nis }}" readonly>
                <input type="text" name="nama" placeholder="Nama Lengkap" value="{{ $siswa->nama }}">
            </div>

            <div class="form-row">
                <select name="jk">
                    <option value="L" {{ $siswa->jk=='L'?'selected':'' }}>Laki-Laki</option>
                    <option value="P" {{ $siswa->jk=='P'?'selected':'' }}>Perempuan</option>
                </select>
                <input type="text" name="kelas" placeholder="Kelas" value="{{ $siswa->kelas }}">
            </div>

            <div class="form-row">
                <input type="text" name="wali_kelas" placeholder="Wali Kelas" value="{{ $siswa->wali_kelas }}" readonly>
            </div>

            <button type="submit">Update</button>
        </form>
    </div>
</div>

<style>
/* Wrapper full screen & center */
.form-wrapper {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 100vh;
    background: #f5f6fa;
    font-family: 'Nunito', sans-serif;
}

/* Card */
.form-card {
    width: 700px;
    max-width: 95%;
    background: #fff;
    padding: 40px 30px;
    border-radius: 20px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.1);
}

/* Title */
.form-title {
    text-align: center;
    font-weight: 700;
    margin-bottom: 30px;
    color: #333;
}

/* Row for 2 inputs */
.form-row {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
}

/* Inputs & select */
.form-row input,
.form-row select {
    flex: 1;
    padding: 12px 15px;
    border-radius: 12px;
    border: 1px solid #ddd;
    background: #fefefe;
    font-size: 16px;
    transition: all 0.3s ease;
    box-shadow: inset 0 2px 5px rgba(0,0,0,0.03);
}

.form-row input:focus,
.form-row select:focus {
    border-color: #4e73df;
    box-shadow: 0 0 8px rgba(78,115,223,0.2);
    outline: none;
}

/* Button */
button {
    width: 100%;
    padding: 12px 0;
    font-weight: 600;
    border-radius: 15px;
    border: none;
    background: linear-gradient(135deg, #6a11cb, #2575fc);
    color: #fff;
    font-size: 16px;
    cursor: pointer;
    transition: all 0.3s ease;
}

button:hover {
    transform: translateY(-2px);
    box-shadow: 0 8px 20px rgba(78,115,223,0.3);
}
</style>
@endsection
