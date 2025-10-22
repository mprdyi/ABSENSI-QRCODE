<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @page { margin: 15px; }
        body { font-family: Arial, sans-serif; font-size: 11px; }

        table {
            width: 100%;
            border-collapse: separate; /* ubah dari collapse */
            border-spacing: 10px; /* kasih jarak vertikal 10px antar baris */
        }

        td {
            width: 33%;
            text-align: center;
            vertical-align: top;
            border: 1px solid #000;
            border-radius: 8px;
            padding: 6px;
            height: 160px;
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
            width: 80px;
            height: 80px;
        }
    </style>
</head>
<body>
    <h3 style="text-align:center;">DAFTAR QR CODE SISWA</h3>
    <table>
        @foreach ($siswas->chunk(3) as $chunk)
            <tr>
                @foreach ($chunk as $s)
                    @php
                        $qr = base64_encode(QrCode::format('svg')->size(80)->generate($s->nis));
                    @endphp
                    <td>
                        <div class="name">{{ strtoupper($s->nama) }}</div>
                        <div class="kelas">Kelas: {{ $s->kelas->kelas ?? '-' }}</div>
                        <img src="data:image/svg+xml;base64,{{ $qr }}" alt="QR Code">
                        <div class="nis">NIS: {{ $s->nis }}</div>
                    </td>
                @endforeach
                {{-- isi kolom kosong biar rata 3 --}}
                @for ($i = $chunk->count(); $i < 3; $i++)
                    <td></td>
                @endfor
            </tr>
        @endforeach
    </table>
</body>
</html>
