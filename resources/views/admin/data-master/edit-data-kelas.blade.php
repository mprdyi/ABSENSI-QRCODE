@extends('layout.app')
@section('title', 'Edit Data Kelas')

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
            <h5 class="fw-bold mb-3">Edit Data Kelas SMAN 9 Cirebon</h5>
            @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-warning">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <div class="form-wrapper">
                <div class="form-card">
                    <h3 class="form-title">Edit Data Kelas</h3>
                    <form action="{{ route('data-kelas.update', $kelas->id)}}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <input type="text" name="kode_kelas" placeholder="NIS" value="{{ $kelas->kode_kelas }}">
                            <input type="text" name="kelas" placeholder="Nama Lengkap" value="{{ $kelas->kelas }}">
                        </div>

                        <div class="form-row">
                        <select name="id_wali_kelas">
                            <option value="">-- Wali Kelas --</option>
                            @foreach($guru as $g)
                                <option value="{{ $g->nip }}" {{ $kelas->id_wali_kelas == $g->nip ? 'selected' : '' }}>
                                    {{ $g->nama_guru }}
                                </option>
                            @endforeach
                        </select>



                        </div>


                        <button type="submit" class="btn-update">Update Data</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
