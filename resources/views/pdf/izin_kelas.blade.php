<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rekap Izin Meninggalkan Kelas {{ $kelas->kelas }}</title>
<style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #222; }
    h2, h4, h3 { text-align: center; margin: 0; }
    table { width: 100%; border-collapse: collapse; margin-top: 15px; }
    th, td { border: 1px solid #444; padding: 5px 7px; text-align: center; }
    th { background-color: #c7d2e8; } /* versi lebih gelap dari #dce3f0 */
    .info { margin-top: 10px; font-size: 12px; }
    .page-break { page-break-after: always; }

    tbody tr:nth-child(even) { background-color: #f8f9fc; }
    tbody tr:nth-child(odd)  { background-color: #ffffff; }
    tbody tr:hover { background-color: #eaf2ff; }
</style>
</head>
<body>

<h2>Rekap Izin Meninggalkan Kelas</h2>
<h4>{{ strtoupper($kelas->kelas) }} - Tahun {{ date('Y') }}</h4>
<table>
    <thead style="background:gray">
        <tr style="background:gray">
            <th rowspan="2" style="width:25px;">No</th>
            <th rowspan="2" style="width:70px;">NIS</th>
            <th rowspan="2" style="width:160px;">Nama Siswa</th>
            <th rowspan="2" style="width:40px;">JK</th>
            <th rowspan="2" style="width:80px;">Kelas</th>
            <th colspan="2">Izin Meninggalkan Kelas</th>
            <th rowspan="2" style="width:50px;">Total</th>
        </tr>
        <tr>
            <th>Keperluan Pribadi</th>
            <th>Keperluan Sekolah</th>
        </tr>
    </thead>

    <tbody>
        @forelse ($izin_kelas as $izin)
            @php
            $bgColor = $loop->odd ? '#ffffff' : '#f1f1f1';
            @endphp
            <tr style="background-color: {{ $bgColor }};">
                <td>{{ $loop->iteration }}</td>
                <td>{{ $izin['nis'] }}</td>
                <td style="text-align:left;">{{ $izin['nama'] }}</td>
                <td>{{ $izin['jk'] }}</td>
                <td>{{ $izin['kelas'] }}</td>
                <td>{{ $izin['pribadi'] }}</td>
                <td>{{ $izin['sekolah'] }}</td>
                <td>{{ $izin['total'] }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="8">Tidak ada data izin meninggalkan kelas</td>
            </tr>
        @endforelse
    </tbody>
</table>

<br><br>
<div style="text-align:right;">
    <p>Cirebon, {{ now()->translatedFormat('d F Y') }}</p>
    <br><br><br>
    <p><strong>{{ $kelas->waliKelas->nama_guru ?? '____________________' }}</strong></p>
    <p>Wali Kelas</p>
</div>

</body>
</html>
