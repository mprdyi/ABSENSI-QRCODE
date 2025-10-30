@extends('layout.app')
@section('title', 'Edit Absensi Siswa')

@section('content')
<style>
  /* --- STYLE CARD SETIAP BARIS --- */
  .absen-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    background: #fff;
    border-radius: 12px;
    padding: 14px 18px;
    margin-bottom: 10px;
    box-shadow: 0 2px 6px rgba(0, 0, 0, 0.06);
    transition: 0.2s ease;
  }

  .absen-item:hover {
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    transform: translateY(-1px);
  }

  .absen-nama {
    flex: 1;
  }

  .absen-nama h6 {
    margin: 0;
    font-weight: 600;
    color: #333;
  }

  .absen-nama small {
    color: #888;
  }

  .absen-select {
    min-width: 130px;
    margin: 0 20px;
  }

  .absen-select select {
    width: 100%;
    padding: 6px 10px;
    border-radius: 8px;
    border: 1px solid #ccc;
    background-color: #fafafa;
    transition: all 0.2s ease;
  }

  .absen-select select:focus {
    outline: none;
    border-color: #7b5bff;
    background-color: #fff;
    box-shadow: 0 0 4px rgba(123, 91, 255, 0.4);
  }

  .absen-action {
    display: flex;
    gap: 8px;
  }

  .absen-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    padding: 6px 10px;
    border-radius: 8px;
    border: none;
    cursor: pointer;
    transition: 0.2s;
    color: white;
  }

  .absen-btn.back {
    background-color: #ffb84c; /* orange */
  }

  .absen-btn.save {
    background-color: #007bff; /* biru */
  }

  .absen-btn:hover {
    transform: scale(1.05);
    opacity: 0.9;
  }

  @media (max-width: 576px) {
    .absen-item {
      flex-direction: column;
      align-items: flex-start;
      gap: 10px;
    }
    .absen-select {
      margin: 0;
      width: 100%;
    }
    .absen-action {
      width: 100%;
      justify-content: flex-end;
    }
  }
</style>

<div class="container mt-3">
  <form action="{{ route('edit-absen.update', $item_absen->id) }}" method="POST">
    @csrf
    @method('PUT')

    <div class="absen-item">
      {{-- NAMA SISWA --}}
      <div class="absen-nama">
        <h6>{{ ucwords(strtolower(optional($item_absen->siswa)->nama ?? '-')) }}</h6>
        <small>Kelas: {{ optional($item_absen->siswa->kelas)->kelas ?? '-' }}</small>
      </div>

      {{-- STATUS ABSENSI --}}
      <div class="absen-select">
        <select name="status" required>
          <option value="Hadir" {{ $item_absen->status == 'Hadir' ? 'selected' : '' }}>Hadir</option>
          <option value="Izin" {{ $item_absen->status == 'Izin' ? 'selected' : '' }}>Izin</option>
          <option value="Sakit" {{ $item_absen->status == 'Sakit' ? 'selected' : '' }}>Sakit</option>
          <option value="Alpha" {{ $item_absen->status == 'Alpha' ? 'selected' : '' }}>Alpa</option>
          <option value="Terlambat" {{ $item_absen->status == 'Terlambat' ? 'selected' : '' }}>Terlambat</option>
        </select>
        {{-- Hidden input untuk keterangan --}}
            <input type="hidden" name="keterangan" class="keterangan-input" value="{{ $item_absen->keterangan }}">
      </div>

      {{-- TOMBOL AKSI --}}
      <div class="absen-action">
        <a href="{{ route('laporan-harian.harian') }}" class="absen-btn back">
          <i class="fa fa-arrow-left"></i>
        </a>
        <button type="submit" class="absen-btn save">
         Update
        </button>
      </div>
    </div>
  </form>
</div>
@endsection
