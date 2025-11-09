<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rekap Absensi {{ $kelas->kelas }}</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
    h2, h4, h3 { text-align: center; margin: 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #444; padding: 4px 6px; text-align: center; }
    th { background-color: #f2f2f2; }
    .info { margin-top: 10px; font-size: 12px; }
    .page-break { page-break-after: always; }

    tbody tr:nth-child(even) { background-color: #f9f9f9; }
    tbody tr:nth-child(odd)  { background-color: #ffffff; }
    tbody tr:hover { background-color: #eaf2ff; }
</style>
</head>
<body>



    @php
        // Bagi 2 halaman: Jan–Jun dan Jul–Des
        $bulanSet = [
            ['JAN' => 1, 'FEB' => 2, 'MAR' => 3, 'APR' => 4, 'MEI' => 5, 'JUN' => 6],
            ['JUL' => 7, 'AGU' => 8, 'SEP' => 9, 'OKT' => 10, 'NOV' => 11, 'DES' => 12],
        ];
    @endphp

    @foreach ($bulanSet as $halaman => $bulanList)

    <h2>Rekap Absensi Tahunan</h2>
    <h4>{{ strtoupper($kelas->kelas) }} - Tahun {{ date('Y') }}
    <br> ({{ array_key_first($bulanList) }} - {{ array_key_last($bulanList) }})
    </h4>

    <div class="info">
        <p><strong>Wali Kelas:</strong> {{ $kelas->waliKelas->nama_guru ?? '-' }}</p>
    </div>

        <table>
            <thead>
                <tr>
                    <th rowspan="2" style="width:25px;">No</th>
                    <th rowspan="2" style="width:200px;">Nama Siswa</th>
                    <th colspan="{{ count($bulanList) * 4 }}">DATA KEHADIRAN SISWA</th>
                    <th colspan="4">Total</th>
                </tr>
                <tr>
                    @foreach ($bulanList as $namaBulan => $angka)
                        <th colspan="4">{{ $namaBulan }}</th>
                    @endforeach
                    <th>A</th><th>I</th><th>S</th><th>T</th>
                </tr>
                <tr>
                    <th colspan="2"></th>
                    @foreach ($bulanList as $namaBulan => $angka)
                        <th>A</th><th>I</th><th>S</th><th>T</th>
                    @endforeach
                    <th>A</th><th>I</th><th>S</th><th>T</th>
                </tr>
            </thead>

            <tbody>
                @forelse ($rekap as $data)
                    @php
                        $bgColor = $loop->odd ? '#ffffff' : '#f9f9f9';

                        // Hitung total per halaman (Jan–Jun atau Jul–Des)
                        $totalA = 0;
                        $totalI = 0;
                        $totalS = 0;
                        $totalT = 0;
                    @endphp

                    <tr style="background-color: {{ $bgColor }};">
                        <td>{{ $loop->iteration }}</td>
                        <td style="text-align:left;">{{ $data['nama'] ?? '-' }}</td>

                        {{-- isi bulan per halaman --}}
                        @foreach ($bulanList as $namaBulan => $angka)
                            @php
                                $bulanData = $data['bulan'][$angka] ?? ['A'=>0,'I'=>0,'S'=>0,'T'=>0];
                                $totalA += $bulanData['A'];
                                $totalI += $bulanData['I'];
                                $totalS += $bulanData['S'];
                                $totalT += $bulanData['T'];
                            @endphp
                            <td>{{ $bulanData['A'] }}</td>
                            <td>{{ $bulanData['I'] }}</td>
                            <td>{{ $bulanData['S'] }}</td>
                            <td>{{ $bulanData['T'] }}</td>
                        @endforeach

                        {{-- total per halaman --}}
                        <td>{{ $totalA }}</td>
                        <td>{{ $totalI }}</td>
                        <td>{{ $totalS }}</td>
                        <td>{{ $totalT }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ 2 + (count($bulanList) * 4) + 4 }}">Tidak ada data absensi</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <br><br>
        <div style="text-align:right;">
            <p>Cirebon, {{ now()->translatedFormat('d F Y') }}</p>
            <br>
            <br>
            <br>
            <p><strong>{{ $kelas->waliKelas->nama_guru ?? '____________________' }}</strong></p>
            <p>Wali Kelas</p>
        </div>

        @if (!$loop->last)
            <div class="page-break"></div>
        @endif
    @endforeach

</body>
</html>
