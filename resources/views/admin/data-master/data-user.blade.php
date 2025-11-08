@extends('layout.app')
@section('title', 'Data Users')
@section('content')

<div class="container-fluid">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Form Users</h5>
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif


            <form action="{{ route('data-user.store') }}" method="POST">
                @csrf


               <div class="row">
                <div class="col-6">
                <div class="form-group mb-3">
                    <input type="text" class="form-control modern-input" name="name" placeholder="Nama Lengkap...">
                </div>
                </div>
                <div class="col-6">
                <div class="form-group mb-3">
                    <input type="email" class="form-control modern-input" name="email" placeholder="Email...">
                </div>
                </div>
                <div class="col-6">
                <div class="form-group mb-3">
                    <input type="password" class="form-control modern-input" name="password" placeholder="Password....">
                </div>
                </div>
                <div class="col-6">
                <div class="form-group mb-3">
                    <input type="password" class="form-control modern-input" name="password_confirmation" placeholder="Konfirmasi Password...">
                </div>
                </div>
                <div class="col-12">
                <div class="form-group mb-3">
                    <select name="role" id="" class="form-control modern-input">
                    <option value="">--- Pilih Role ---</option>
                        <option value="admin">Admin</option>
                        <option value="guru">Guru</option>
                        <option value="osis">MPK / Osis</option>
                        <option value="siswa">Siswa</option>
                    </select>
                </div>
                </div>
               </div>

                <button type="submit" class="btn btn-primary">Tambah</button>
            </form>
        </div>
    </div>

<div class="card shadow-sm border-0 rounded-4 mt-4">
<div class="container fluid mt-3 mb-3">
<br>
<h5>Data Users</h5>
<div class="table-responsive">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
<table class="table table-borderless align-middle custom-table">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama </th>
            <th>Email</th>
            <th>Role</th>
            <th>Password</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody style="font-size:14px">
            @foreach($data as $item)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $item->name }}</td>
                <td>{{ $item->email }}</td>
                <td>{{ $item->role }}</td>
                <td><input type="password" value="{{ $item->password }}" readonly
                 style="background:transparent; border:none; outline:none; color:gray"></span></td>
                 <td>
                 <span style="letter-spacing: 1px;">
                <a href="{{ route('data-user.edit', $item->id) }}" class="badge-soft orange" style="margin-right:20px"><i class="fa fa-edit"></i> </a>
                <form action="{{ route('data-user.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus data ini?');" style="display:inline; margin-right:20px">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="badge-soft red" style="border:none">
                        <i class="fa fa-trash"></i>
                    </button>
                </form>
                </span>
                 </td>
            </tr>
            @endforeach
        </tbody>
</table>
{{ $data->onEachSide(1)->links('pagination::bootstrap-5') }}
</div>
</div>
</div>
</div>

@endsection
