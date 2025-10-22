<?php

namespace App\Http\Controllers;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use App\Models\Siswa;
use App\Models\DataKelas;
use SimpleSoftwareIO\QrCode\Facades\QrCode;


class SiswaController extends Controller
{
    //
    public function index()
        {
            $siswas = Siswa::with('kelas')
                            ->orderBy('id', 'desc')
                            ->paginate(10);

            $data_kelas = DataKelas::orderByRaw("
                CASE
                    WHEN kelas LIKE 'X %' THEN 1
                    WHEN kelas LIKE 'XI %' THEN 2
                    WHEN kelas LIKE 'XII %' THEN 3
                    ELSE 4
                END, kelas ASC
            ")->get();


            $view_data = [
                'siswas' => $siswas,
                'data_kelas' => $data_kelas,
                'title' => 'Data Siswa',
            ];

            return view('admin.data-master.data-siswa', $view_data);
        }

    //INPUT DATA
    public function store(Request $request)
    {
        $request->validate([
            'nis' => 'required|unique:siswas',
            'nama' => 'required',
            'jk' => 'required',
            'id_kelas' => 'required',
        ]);

        Siswa::create($request->all());

        return redirect()->back()->with('success', 'Data siswa berhasil ditambahkan!');
    }


      //GET WALI KELAS
      public function getWaliKelas($id)
      {
          $kelas = DataKelas::with('waliKelas')->find($id);

          if ($kelas && $kelas->waliKelas) {
              return response()->json([
                  'wali' => [
                      'id' => $kelas->waliKelas->id,
                      'nama' => $kelas->waliKelas->nama_guru
                  ]
              ]);
          } else {
              return response()->json(['wali' => null]);
          }
      }



    // EDIT DATA
        public function edit($id)
        {
            // Ambil data siswa berdasarkan id
            $siswa = Siswa::findOrFail($id);

            // Ambil semua data kelas untuk dropdown (urut berdasarkan X, XI, XII)
            $data_kelas = DataKelas::orderByRaw("
                CASE
                    WHEN kelas LIKE 'X %' THEN 1
                    WHEN kelas LIKE 'XI %' THEN 2
                    WHEN kelas LIKE 'XII %' THEN 3
                    ELSE 4
                END, kelas ASC
            ")->get();

            $view_data = [
                'siswa' => $siswa,
                'data_kelas' => $data_kelas,
                'title' => 'Edit Data Siswa',
            ];

            return view('admin.data-master.edit-data-siswa', $view_data);
        }




        //UPDATE DATA
        public function update(Request $request, $id)
        {
            $request->validate([
                'nis' => 'required|unique:siswas,nis,' . $id,
                'nama' => 'required',
                'jk' => 'required',
                'id_kelas' => 'required|exists:data_kelas,id',
            ]);

            $siswa = Siswa::findOrFail($id);
            $siswa->update($request->all());


            return redirect()->route('admin.data-master.data-siswa')->with('success', 'Data berhasil diupdate.');
        }


    //HAPUS DATA
    public function destroy($id)
        {

            Siswa::where('id', $id)->delete();
            return redirect()->route('admin.data-master.data-siswa')->with('success', 'Data berhasil dihapus.');
        }

    // CARI DATA DENGAN RELASI
    public function search(Request $request)
    {
        $search = $request->input('search');

        if ($search) {
            $siswas = Siswa::with(['kelas.waliKelas']) // relasi biar efisien (hindari N+1)
                ->where('nama', 'like', "%{$search}%")
                ->orWhereHas('kelas', function ($query) use ($search) {
                    $query->where('kelas', 'like', "%{$search}%");
                })
                ->orWhereHas('kelas.waliKelas', function ($query) use ($search) {
                    $query->where('nama', 'like', "%{$search}%");
                })
                ->orderBy('id', 'desc')
                ->paginate(10)
                ->appends(['search' => $search]);
        } else {
            $siswas = Siswa::with(['kelas.waliKelas'])
                ->orderBy('id', 'desc')
                ->paginate(10);
        }

        return view('admin.data-master.cari-siswa', compact('siswas', 'search'));
    }



    //PRINT PDF
    public function printPdf($id)
    {
        $siswa = Siswa::with('kelas')->findOrFail($id);

        $qrcode = \SimpleSoftwareIO\QrCode\Facades\QrCode::size(150)->generate($siswa->nis);

        $pdf = Pdf::loadView('pdf.qr-code', compact('siswa', 'qrcode'))
                ->setPaper('A4', 'portrait');

        return $pdf->stream('QRCode-'.$siswa->nama.'.pdf');

        //  langsung download:
        // return $pdf->download('QRCode-'.$siswa->nama.'.pdf');
    }
}
