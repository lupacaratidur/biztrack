<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\PDF;
use App\Models\Cabang;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use App\Http\Controllers\Controller;
use Dompdf\Dompdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class RekapPemasukanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user               = auth()->user();
        $userRole           = auth()->user()->role->role;
        $bulanIni           = Carbon::now()->format('m');
        $hariIni            = Carbon::now()->format('Y-m-d');


        if ($userRole === 'administrator' || $userRole === 'owner') {
            $pemasukanBulanIni  = Pembelian::whereMonth('tgl_transaksi', $bulanIni)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
            $pemasukanBulanLalu = Pembelian::whereMonth('tgl_transaksi', '=', Carbon::now()->subMonth()->format('m'))
                ->where('status', '=', 'paid')
                ->sum('total_harga');

            $pemasukanHariIni   = Pembelian::whereDate('tgl_transaksi', $hariIni)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
            $pemasukanKemarin   = Pembelian::whereDate('tgl_transaksi', '=', Carbon::now()->subDay()->format('Y-m-d'))
                ->where('status', '=', 'paid')
                ->sum('total_harga');
        } else {
            $pemasukanBulanIni  = Pembelian::whereMonth('tgl_transaksi', $bulanIni)
                ->where('cabang_id', $user->cabang_id)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
            $pemasukanBulanLalu = Pembelian::whereMonth('tgl_transaksi', '=', Carbon::now()->subMonth()->format('m'))
                ->where('cabang_id', $user->cabang_id)
                ->where('status', '=', 'paid')
                ->sum('total_harga');

            $pemasukanHariIni   = Pembelian::whereDate('tgl_transaksi', $hariIni)
                ->where('cabang_id', $user->cabang_id)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
            $pemasukanKemarin   = Pembelian::whereDate('tgl_transaksi', '=', Carbon::now()->subDay()->format('Y-m-d'))
                ->where('cabang_id', $user->cabang_id)
                ->where('status', '=', 'paid')
                ->sum('total_harga');
        }
        return view('rekap-pemasukan.index', [
            'cabangs'               => Cabang::all(),
            'pemasukanBulanIni'     => $pemasukanBulanIni,
            'pemasukanBulanLalu'    => $pemasukanBulanLalu,
            'pemasukanHariIni'      => $pemasukanHariIni,
            'pemasukanKemarin'      => $pemasukanKemarin,
        ]);
    }

    /**
     * Get Data 
     */
    public function getData(Request $request)
    {
        $user           = auth()->user();
        $cabangId       = $request->input('cabang_id');  // Ganti dari 'opsi' menjadi 'cabang_id'
        $tanggalMulai   = $request->input('tanggal_mulai');
        $tanggalSelesai = $request->input('tanggal_selesai');
    
        try {
            // Query dasar
            $query = Pembelian::with('detailPembelians')->where('status', '=', 'paid')->orderBy('id', 'DESC');
    
            // Filter berdasarkan cabang
            if ($user->role->role === 'administrator' || $user->role->role === 'owner') {
                if ($cabangId && $cabangId !== 'Semua Cabang') {
                    $query->where('cabang_id', $cabangId);
                }
            } else {
                $query->where('cabang_id', $user->cabang_id);
            }
    
            // Filter berdasarkan tanggal jika ada
            if ($tanggalMulai && $tanggalSelesai) {
                $query->whereBetween('tgl_transaksi', [Carbon::parse($tanggalMulai), Carbon::parse($tanggalSelesai)]);
            }
    
            $pembelians = $query->get();

            // Export PDF
            if ($request->has('print_pdf')) {
                $data = [
                    'pembelians'        => $pembelians,
                    'cabangId'          => $cabangId,
                    'tanggalMulai'      => $tanggalMulai,
                    'tanggalSelesai'    => $tanggalSelesai
                ];
                $dompdf = new Dompdf();
                $dompdf->setPaper('A4', 'portrait');
                $html = view('/rekap-pemasukan/print-rekap-pemasukan', compact('data'))->render();
                $dompdf->loadHtml($html);
                $dompdf->render();

                // Stream the PDF directly
                return response()->stream(function () use ($dompdf) {
                    echo $dompdf->output();
                }, 200, [
                    'Content-Type' => 'application/pdf',
                    'Content-Disposition' => 'inline; filename="laporan_penjualan.pdf"',
                ]);
            }

        // Export Excel
            if ($request->has('print_excel')) {
                // Membuat objek spreadsheet
                $spreadsheet = new Spreadsheet();
                $sheet = $spreadsheet->getActiveSheet();

                // Menambahkan header untuk file Excel
                $sheet->setCellValue('A1', 'rekap Pemasukan Restoran');
                $sheet->mergeCells('A1:E1');
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal('center');
                
                // Menambahkan informasi cabang dan periode
                $sheet->setCellValue('A2', 'Cabang');
                 $sheet->setCellValue('B2', $cabangId ? \App\Models\Cabang::find($cabangId)->cabang : auth()->user()->cabang->cabang);
                $sheet->setCellValue('A3', 'Periode');
                $sheet->setCellValue('B3', $tanggalMulai && $tanggalSelesai 
                    ? \Carbon\Carbon::parse($tanggalMulai)->format('d-m-Y') . ' - ' . \Carbon\Carbon::parse($tanggalSelesai)->format('d-m-Y')
                    : 'Semua Periode'
                );

                // Menambahkan informasi tanggal cetak
                $sheet->setCellValue('A4', 'Tanggal Cetak');
                $sheet->setCellValue('B4', now()->format('d-m-Y'));

                // Menambahkan header kolom
                $sheet->setCellValue('A6', 'No');
                $sheet->setCellValue('B6', 'Kode');
                $sheet->setCellValue('C6', 'Total');
                $sheet->setCellValue('D6', 'Tgl. Transaksi');
                $sheet->setCellValue('E6', 'Item');

                // Memulai untuk mengisi data pembelian
                $row = 7; // Baris mulai setelah header
                $no = 1;  // No urut
                foreach ($pembelians as $pembelian) {
                    // Format item sebagai "nama_item (Xqty)"
                    $items = $pembelian->detailPembelians->map(function ($item) {
                        return $item->nama . ' (X' . $item->quantity . ')';
                    })->implode(', ');

                    $sheet->setCellValue('A' . $row, $no++);
                    $sheet->setCellValue('B' . $row, $pembelian->kode_pembelian);
                    $sheet->setCellValue('C' . $row, 'Rp. ' . number_format($pembelian->total_harga, 0, ',', '.'));
                    $sheet->setCellValue('D' . $row, \Carbon\Carbon::parse($pembelian->tgl_transaksi)->format('Y-m-d'));
                    $sheet->setCellValue('E' . $row, $items);

                    $row++;
                }

                // Menyimpan file Excel
                $writer = new Xlsx($spreadsheet);
                $fileName = 'rekap-pemasukan.xlsx';
                
                // Menyimpan file ke dalam response sebagai download
                return response()->stream(
                    function () use ($writer) {
                        $writer->save('php://output');
                    },
                    200,
                    [
                        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
                    ]
                );
            }
             // Default JSON response when not printing PDF or Excel
        return response()->json([
            'success' => true,
            'data' => $pembelians
        ]);
                    
    
        } catch (\Exception $e) {
            // Tangani error atau exception yang terjadi
            return response()->json(['error' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }

       
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}