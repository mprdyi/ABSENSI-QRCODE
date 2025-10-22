@extends('layout.app')
@section('title', 'QR-CODE-SISWA')

@section('content')
<style>
    body {
        background: #f5f7fa;
        font-family: 'Poppins', sans-serif;
    }

    .qrcode-wrapper {
        display: flex;
        justify-content: center;
        align-items: center;
        min-height: calc(100vh - 120px);
    }

    .qrcode-card {
        background: #fff;
        padding: 20px 40px;
        border-radius: 16px;
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
        text-align: center;
        transition: all 0.3s ease-in-out;
    }

    .qrcode-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 12px 30px rgba(0, 0, 0, 0.12);
    }

    h4 {
        font-size: 22px;
        font-weight: 600;
        color: #333;
        margin-bottom: 16px;
    }

    p {
        margin-top: 8px;
        color: #666;
        font-size: 14px;
    }

    .print-button {
        padding: 10px 22px;
        border: none;
        background: linear-gradient(135deg, #4CAF50, #2E7D32);
        color: white;
        border-radius: 8px;
        cursor: pointer;
        font-weight: 500;
        margin-top: 16px;
        transition: all 0.2s ease-in-out;
    }

    .print-button:hover {
        background: linear-gradient(135deg, #66BB6A, #388E3C);
        transform: scale(1.03);
    }

    /* Biar QR code-nya gak nempel */
    .qrcode-card div {
        display: inline-block;
        margin: 12px 0;
    }
</style>

<div class="qrcode-wrapper">
    <div class="qrcode-card">
        <h4>{{ $siswa->nama }}</h4>
        <h5>Kelas : {{ $siswa->kelas->kelas }}</h5>
        <div>{!! $qrcode !!}</div>
        <p>({{ $siswa->nis }})</p>


        <a href="{{ route('qrcode.pdf', $siswa->nis) }}" class="badge-soft blue"> Download</a>

      <a href="{{ route('admin.data-master.data-siswa') }}" class="badge-soft red" >Back</a>
    </div>
</div>
@endsection
