<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Rekap Absensi {{ $hariTeks }}</title>
<style>
    * { font-family: DejaVu Sans, sans-serif; }
    @page { margin-left: 3cm; margin-right: 2cm; margin-top: 2cm; margin-bottom: 2cm; }
    body { font-size: 12px; }
    h2, h3 { text-align: center; margin-bottom: 10px; }
    table { width: 100%; border-collapse: collapse; margin-top: 10px; }
    th, td { border: 1px solid #000; padding: 5px; text-align: center; }
    th { background: #f0f0f0; }
    .left { text-align: left; }
    .centang { font-size: 18px; }
    .page-break { page-break-before: always; }
</style>
</head>

<body>

    <!-- ✅ Halaman 1: Rekap Jumlah -->
    <h2>Rekap Jumlah Absensi<br><small>{{ $hariTeks }}</small></h2>
    <table>
        <tr><th>Status</th><th>Jumlah</th></tr>
        <tr><td>Hadir</td><td>{{$totalHadir }}</td></tr>
        <tr><td>Izin</td><td>{{ $totalIzin }}</td></tr>
        <tr><td>Sakit</td><td>{{ $totalSakit }}</td></tr>
        <tr><td>Alpha</td><td>{{ $totalAlpha }}</td></tr>
    </table>

    <p style="margin-top: 30px; text-align: right;">Dicetak: {{ $jamCetak }}</p>

    <!-- ✅ Halaman 2: Terlambat -->
    <div class="page-break"></div>
    <h2>Daftar Siswa Terlambat<br><small>{{ $hariTeks }}</small></h2>
    <table>
        <thead>
            <tr>
                <th>No</th><th>NIS</th><th>Masuk</th><th>Nama</th><th>Kelas</th><th>Keterangan</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($dataTerlambat as $absen)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $absen->nis }}</td>
                    <td>{{ $absen->jam_masuk }}</td>
                    <td class="left">{{ $absen->siswa->nama ?? '-' }}</td>
                    <td>{{ $absen->siswa->kelas->kelas ?? '-' }}</td>
                    <td>{{ $absen->keterangan ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- ✅ Halaman 3: Ketidakhadiran -->
    <div class="page-break"></div>
    <h2>Daftar Ketidakhadiran (Izin, Sakit, Alpha)<br><small>{{ $hariTeks }}</small></h2>
    <table>
        <thead>
            <tr>
                <th>No</th><th>NIS</th><th>Nama</th><th>Kelas</th>
                <th>Sakit</th><th>Izin</th><th>Alpha</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($dataKetidakhadiran as $absen)
                <tr>
                    <td>{{ $no++ }}</td>
                    <td>{{ $absen->nis }}</td>
                    <td class="left">{{ $absen->siswa->nama ?? '-' }}</td>
                    <td>{{ $absen->siswa->kelas->kelas ?? '-' }}</td>
                    <td class="centang">{!! $absen->status == 'Sakit' ? '&#10003;' : '' !!}</td>
                    <td class="centang">{!! $absen->status == 'Izin' ? '&#10003;' : '' !!}</td>
                    <td class="centang">{!! $absen->status == 'Alpha' ? '&#10003;' : '' !!}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

</body>
</html>
