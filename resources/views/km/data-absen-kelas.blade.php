@extends('layout.app')

@section('title', 'Absensi Kelas ' . $siswaLogin->kelas->kelas)

@section('content')
<div class="container-fluid mt-4">
    <div class="card shadow-sm border-0 rounded-4">
        <div class="card-body">
            <h5 class="fw-bold mb-3">Absensi Hari Ini - Kelas: {{ $siswaLogin->kelas->kelas }}</h5>

            <div class="table-responsive">
                <table class="table table-borderless align-middle text-center custom-table">
                    <thead class="table-light" style="white-space:nowrap; text-align:left;">
                        <tr>
                            <th>No</th>
                            <th>NIS</th>
                            <th>Nama Siswa</th>
                            <th>Status Absensi</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody style="font-size:14px; text-align:left; white-space:nowrap">
                        @foreach($siswas as $siswa)
                        @php
                            $absensi = $siswa->absensi->first();
                            $status = $absensi?->status ?? 'belum absen';
                            $badgeClass = match(strtolower($status)) {
                                'izin' => 'badge-soft  green',
                                'sakit' => 'badge-soft  purple',
                                'alpha' => 'badge-soft  red',
                                'terlambat' => 'badge-soft orange',
                                'hadir' => 'badge-soft blue',
                                default => 'badge-soft gray'
                            };
                        @endphp
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $siswa->nis }}</td>
                            <td>{{ ucwords(strtolower($siswa->nama)) }}</td>
                            <td>
                                <span class="badge {{ $badgeClass }}">{{ ucwords($status) }}</span>
                            </td>
                            <td>{{ $absensi?->keterangan ?? '-' }}</td>
                            <td>
                            @php
                                $status = $siswa->absensi->first()?->status ?? 'belum absen';
                                // Cek boleh edit atau tidak
                                $bolehEdit = !in_array(strtolower($status), ['terlambat', 'belum absen']);
                                $btnClass = $bolehEdit ? 'badge-soft orange' : 'badge-soft gray';
                            @endphp

                            <button class="btn btn-sm {{ $btnClass }}"
                                    {{ $bolehEdit ? 'data-bs-toggle=modal data-bs-target=#editAbsensiModal'.$siswa->id : 'disabled' }}>
                                <i class="fa fa-edit"></i>
                            </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Semua modal diletakkan di sini, di luar table -->
@foreach($siswas as $siswa)
@php
    $absensi = $siswa->absensi->first();
@endphp
<div class="modal fade" id="editAbsensiModal{{ $siswa->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('absensi-update-to-siswa.update', $absensi?->id ?? 0) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Absensi - {{ ucwords(strtolower($siswa->nama)) }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Status Absensi</label>
                        <select name="status" class="form-select modern-input" required>
                            <option value="izin" {{ ($absensi?->status ?? '') == 'izin' ? 'selected' : '' }}>Izin</option>
                            <option value="sakit" {{ ($absensi?->status ?? '') == 'sakit' ? 'selected' : '' }}>Sakit</option>
                            <option value="alpha" {{ ($absensi?->status ?? '') == 'alpha' ? 'selected' : '' }}>Alpa</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>Keterangan</label>
                        <textarea name="keterangan" class="form-control" rows="2">{{ $absensi?->keterangan ?? '' }}</textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endforeach

@endsection
