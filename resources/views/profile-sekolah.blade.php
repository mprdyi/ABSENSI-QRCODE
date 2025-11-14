@extends('layout.app')
@section('title', 'Profil Sekolah')
@section('content')

<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Profil Sekolah</h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('profil-sekolah.update') }}" method="POST">
                @csrf
                @method('PUT')

                <!-- hidden id -->
                <input type="hidden" name="id" value="{{ $sekolah->id }}">

                <div class="form-group mb-3">
                    <input type="text" class="form-control modern-input" name="nama_sekolah"
                        value="{{ old('nama_sekolah', $sekolah->nama_sekolah) }}"
                        placeholder="Nama Sekolah...">
                </div>

                <div class="form-group mb-3">
                    <input type="text" class="form-control modern-input" name="kepsek"
                        value="{{ old('kepsek', $sekolah->kepsek) }}"
                        placeholder="Nama Kepsek...">
                </div>

                <div class="form-group mb-3">
                    <input type="time" class="form-control modern-input" name="jam_masuk" step="1"
                        value="{{ old('jam_masuk', $sekolah->jam_masuk) }}">
                </div>
                <div class="form-group mb-3">
                    <label for="auto-alpa">Auto Alpa</label>
                    <input type="time" class="form-control modern-input" name="auto_alpa" step="1"
                        value="{{ old('auto_alpa', $sekolah->auto_alpa) }}">
                </div>
                <div class="form-group mb-3">
                    <label for="notif">Notifikasi siswa terlambat</label>
                    <input type="time" class="form-control modern-input" name="notif_wa" step="1"
                        value="{{ old('notif_wa', $sekolah->notif_wa) }}">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>

@endsection
