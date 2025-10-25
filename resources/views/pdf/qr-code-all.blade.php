<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
    @page { margin: 15px; }
    body { font-family: Arial, sans-serif; font-size: 11px; margin: 0; }

    table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 8px 14px; /* jarak antar kolom dan baris */
    }

    td {
        width: 33%;
        text-align: center;
        vertical-align: top;
        border: 1px solid #000;
        border-radius: 8px;
        padding: 6px;
        height: 250px; /* pas 4 baris di A4 */
    }

    .name {
        font-weight: bold;
        font-size: 12px;
        margin-bottom: 2px;
    }
    .kelas {
        font-size: 11px;
        margin-bottom: 5px;
    }
    .nis {
        font-size: 10px;
        margin-top: 3px;
    }
    img {
        width: 170px;   /* QR lebih besar */
        height: 170px;  /* QR lebih besar */
    }
</style>

</head>
<body>
    <h3>DAFTAR QR CODE SISWA</h3>

    <table>
        @foreach ($siswas->chunk(3) as $chunk)
            <tr>
                @foreach ($chunk as $s)
                    @php
                        $qr = base64_encode(QrCode::format('svg')->size(80)->generate($s->nis));
                    @endphp
                    <td>
                        <br>
                        <div class="name">{{ strtoupper($s->nama) }}</div>
                        <div class="kelas">Kelas: {{ $s->kelas->kelas ?? '-' }}</div>
                        <img src="data:image/svg+xml;base64,{{ $qr }}" alt="QR Code">
                        <div class="nis">NIS: {{ $s->nis }}</div>
                    </td>
                @endforeach

                {{-- biar tetap 3 kolom di baris terakhir --}}
                @for ($i = $chunk->count(); $i < 3; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach
    </table>
</body>
</html>
