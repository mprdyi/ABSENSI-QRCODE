<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>QR {{ $siswa->nama }}</title>
    <style>
        @page {
            margin: 8px; /* kasih margin luar biar gak mepet */
        }
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background: #fff;
        }
        .card {
            border: 1px solid #000;
            display: inline-block;
            padding: 12px 10px;
            width: 210px;
            border-radius: 6px;
        }
        h3 {
            margin: 0;
            font-size: 14px;
            font-weight: bold;
        }
        p {
            margin: 4px 0;
            font-size: 12px;
        }
        img {
            width: 120px;
            height: 120px;
            margin: 8px 0;
        }
    </style>
</head>
<body>
    <div class="card">
        <h3>{{ strtoupper($siswa->nama) }}</h3>
        <p>Kelas: {{ $siswa->kelas->kelas }}</p>
        <img src="data:image/svg+xml;base64,{{ $qrcode }}" alt="QR Code">
        <p>NIS: {{ $siswa->nis }}</p>
    </div>
</body>
</html>
