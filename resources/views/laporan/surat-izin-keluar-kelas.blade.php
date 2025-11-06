<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Surat Izin Meninggalkan Kelas</title>
  <style>
     @page {
    margin-top: 30px;
    margin-bottom: 10px;
    margin-left: 50px;
    margin-right: 50px;
  }

    body {
      font-family: DejaVu Sans, sans-serif;
        font-size: 9px;
        line-height: 1.2;
        margin: 15px;
    }
    .kop {
      text-align: center;
      border-bottom: 2px solid black;
      padding-bottom: 5px;
      margin-bottom: 10px;
      position: relative;
    }
    .kop img {
      width: 40px;
      height: 40px;
      position: absolute;
      top: 0;
    }
    .kop .logo-left {
      left: 0;
    }
    .kop .logo-right {
      right: 0;
    }
    .title {
      text-align: center;
      font-weight: bold;
      text-decoration: underline;
      margin-top: 10px;
      margin-bottom: 5px;
    }
    .content {
      margin-top: 15px;
    }
    .checkbox {
      display: inline-block;
      margin-right: 10px;
    }
    .footer {
      margin-top: 30px;
      display: flex;
      justify-content: space-between;
      text-align: right;
        margin-top: 5px;
        font-size: 8px;
    }
    .footer div {
      width: 45%;
      text-align: center;
    }
    table {
      width: 100%;
        font-size: 8.5px;
    }
    td {
      vertical-align: top;
    }



  </style>
</head>
<body>
  <div class="kop">
    <img src="{{ public_path('image/logo-jabar.jpg') }}" class="logo-left" alt="">
    <img src="{{ public_path('image/logo-sma.png') }}" class="logo-right" alt="">
    <div>
      PEMERINTAH DAERAH PROVINSI JAWA BARAT<br>
      DINAS PENDIDIKAN WILAYAH X<br>
      <strong>SMA NEGERI 9 CIREBON</strong>
    </div>
  </div>

  <div class="title">
    SURAT IJIN MASUK / MENINGGALKAN KELAS
  </div>

  <div class="content">
    <p>Yang bertanda tangan di bawah ini menerangkan bahwa:</p>
    <table border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td style="width: 120px;">Nama</td>
        <td>: {{ $siswa->nama }}</td>
      </tr>
      <tr>
        <td>Kelas</td>
        <td>: {{ $siswa->kelas->kelas }}</td>
      </tr>
      <tr>
        <td colspan="2">
        <p>Mohon diijinkan untuk masuk / meninggalkan kelas pada jam {{ $izin->waktu_izin }} s.d {{ $izin->waktu_habis }}, dengan keperluan:</p>
        </td>
      </tr>
      <tr>
        <td>
        <span class="checkbox">
            {!! $izin->keperluan == 'Sekolah' ? '☑' : '☐' !!}
        </span> Tugas dari sekolah <br>

        <span class="checkbox">
            {!! $izin->keperluan == 'Pribadi' ? '☑' : '☐' !!}
        </span> Urusan pribadi
        </td>
        <td>
        <span style="text-align:center">Keterangan: {{ $izin->keterangan }}</span>
        </td>
      </tr>
      <tr>
        <td colspan="2">
        <p style="text-align:right">
        Cirebon, {{ \Carbon\Carbon::parse($izin->created_at)->translatedFormat('d F Y') }}<br>
        Guru Piket,
        <br><br><br><br>
        <u>_____________________</u><br>
        <span style="font-weight:bold; text-transform:uppercase; text-align:right"> {{ Auth::user()->name }}</span>
        </p>
        </td>
      </tr>
    </table>
  </div>

  <div class="footer">
    <div>

    </div>
  </div>
</body>
</html>
