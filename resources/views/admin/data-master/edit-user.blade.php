@extends('layout.app')
@section('title', 'Edit User')
@section('content')
<div class="container-fluid">
  <div class="card shadow-sm border-0 rounded-4">
    <div class="card-body">
      <h5 class="fw-bold mb-3">Edit Form Users</h5>

      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <form action="{{ route('data-user.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
          <div class="col-6">
            <div class="form-group mb-3">
              <input type="text" class="form-control modern-input" name="name"
                value="{{ old('name', $user->name) }}" placeholder="Nama Lengkap...">
            </div>
          </div>

          <div class="col-6">
            <div class="form-group mb-3">
              <input type="text" class="form-control modern-input" name="email"
                value="{{ old('email', $user->email) }}" placeholder="Email...">
            </div>
          </div>

          <div class="col-6">
            <div class="form-group mb-3">
              <input type="password" class="form-control modern-input" name="password"
                placeholder="Password (kosongkan jika tidak diubah)">
            </div>
          </div>

          <div class="col-6">
            <div class="form-group mb-3">
              <input type="password" class="form-control modern-input" name="password_confirmation"
                placeholder="Konfirmasi Password...">
            </div>
          </div>

          <div class="col-12">
            <div class="form-group mb-3">
              <select name="role" class="form-control modern-input">
                <option value="">--- Pilih Role ---</option>
                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="guru" {{ $user->role == 'guru' ? 'selected' : '' }}>Guru</option>
                <option value="osis" {{ $user->role == 'osis' ? 'selected' : '' }}>MPK / Osis</option>
                <option value="siswa" {{ $user->role == 'siswa' ? 'selected' : '' }}>Siswa</option>
                <option value="walkes" {{ $user->role == 'walkes' ? 'selected' : '' }}>Wali Kelas</option>
              </select>
            </div>
          </div>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
      </form>
    </div>
  </div>
</div>
@endsection
