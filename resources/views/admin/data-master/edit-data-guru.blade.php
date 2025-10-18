@extends('layout.app')
@section('title', 'Edit Data Siswa')

@section('content')


<div class="container-fluid mt-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Edit Data Guru SMAN 9 Cirebon</h5>

            <div class="form-wrapper">
                <div class="form-card">
                    <h3 class="form-title">Edit Data {{ $Guru->nama_guru }}</h3>
                    <form action="{{ route('data-guru.update', $Guru->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-row">
                            <input type="text" name="nip" placeholder="NIS" value="{{ $Guru->nip }}">
                            <input type="text" name="nama_guru" placeholder="Nama Lengkap" value="{{ $Guru->nama_guru }}">
                        </div>

                        <div class="form-row">
                            <input type="text" name="mapel" placeholder="Mapel" value="{{ $Guru->mapel }}">
                            <input type="text" name="no_hp" placeholder="No Hp" value="{{ $Guru->no_hp }}">
                        </div>

                        <div class="form-row">

                        </div>

                        <button type="submit" class="btn-update">Update Data</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
