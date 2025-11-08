@extends('layout.app')
@section('title', 'Edit Data Guru')

@section('content')


<div class="container-fluid mt-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Edit Data Guru </h5>

            <div class="form-wrapper">
                <div class="form-card">
                    <h3 class="form-title">Edit Data Guru</h3>
                    <form action="{{ route('data-guru.update', $Guru->id) }}" method="POST">
                        @csrf
                        @method('PUT')

                        <div class="form-group mb-3">
                            <input type="text" name="nip" placeholder="NIS" value="{{ $Guru->nip }}" class="form-control modern-input mb-3">
                            <input type="text" name="nama_guru" placeholder="Nama Lengkap" value="{{ $Guru->nama_guru }}" class="form-control modern-input">
                        </div>

                        <div class="form-group mb-3">
                            <input type="text" name="mapel" placeholder="Mapel" value="{{ $Guru->mapel }}" class="form-control modern-input mb-3">
                            <input type="text" name="no_hp" placeholder="No Hp" value="{{ $Guru->no_hp }}" class="form-control modern-input">
                        </div>

                        <div class="form-group mb-3">

                        </div>

                        <button type="submit" class="btn-update">Update Data</button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
