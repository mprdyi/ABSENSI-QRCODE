<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Siswa;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Storage;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\Image\ImagickImageBackEnd;
use BaconQrCode\Renderer\Image\GdImageBackEnd;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;

class QrController extends Controller
{
    public function index()
    {
        $text = 'Halo Sayang  Ini QR Code dari Laravel!';
        $qrcode = QrCode::encoding('UTF-8')
        ->size(200)
        ->generate('Halo Ini QR Code dari Laravel!');


        return view('qrcode', compact('qrcode'));
    }
    public function show($nis)
    {
        // Cari siswa berdasarkan NIS
        $siswa = \App\Models\Siswa::where('nis', $nis)->firstOrFail();

        // Generate QR yang isinya cuma NIS
        $qrcode = QrCode::size(250)
            ->margin(2)
            ->encoding('UTF-8')
            ->generate($siswa->nis);


        return view('admin.data-master.qrcode-siswa', compact('siswa', 'qrcode'));
    }



    public function downloadPdf($nis)
    {
        $siswa = Siswa::where('nis', $nis)->firstOrFail();

        // ✅ QR-nya pakai format SVG biar gak butuh Imagick atau GD
        $qrcode = base64_encode(QrCode::format('svg')->size(200)->generate($siswa->nis));

        $pdf = Pdf::loadView('pdf.qr-code', [
            'siswa' => $siswa,
            'qrcode' => $qrcode,
        ])->setPaper([0, 0, 220, 200], 'portrait'); // ukuran KTP 54x85.6mm


        return $pdf->download('QR-' . $siswa->nama . '.pdf');
    }


    public function downloadAllPdf()
    {
        // Ambil semua data siswa
        $siswas = Siswa::orderBy('nama', 'asc')->get();

        // Kirim data ke view pdf.qr-code-all
        $pdf = Pdf::loadView('pdf.qr-code-all', [
            'siswas' => $siswas,
        ])->setPaper('a4', 'portrait'); // bisa ubah ke landscape kalau mau muat lebih banyak

        return $pdf->download('QR-Semua-Siswa.pdf');
    }




}
