@extends('layout.app')
@section('title', 'Profil User')
@section('content')

<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Profil User</h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('profil-user.update') }}" method="POST" id="profilForm">
                @csrf
                @method('PUT')

                <!-- hidden id -->
                <input type="hidden" name="id" value="">

                <div class="form-group mb-3">
                    <input type="text" class="form-control modern-input" name="nama_user"
                        value="{{ old('nama_user', $user->name) }}"
                        placeholder="Nama Lengkap...">
                </div>

                <div class="form-group mb-3">
                    <input type="email" class="form-control modern-input" name="email"
                        value="{{ old('email', $user->email) }}"
                        placeholder="Masukan Email...">
                </div>

                <div class="form-group mb-3">
                <input type="password" class="form-control modern-input" name="password"
                        placeholder="Masukan password baru (kosongkan jika tidak ganti)">
                </div>
                <div class="form-group mb-3">
                <input type="password" class="form-control modern-input" name="password_confirmation"
                        placeholder="Konfirmasi password baru ...">
                </div>
                <button type="submit" class="btn btn-primary">Update</button>
            </form>
        </div>
    </div>
</div>


@endsection

