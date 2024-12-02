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


use function Symfony\Component\String\b;

class DataPenjualanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('data-penjualan.index', [
            'cabangs'   => Cabang::all()
        ]);
    }

    /**
     * Get Data Penjualan
     */
    public function getData(Request $request)
    {
        $user = auth()->user();
        $selectedOption = $request->input('opsi');

        if ($user->role->role === 'administrator' || $user->role->role === 'owner') {
            if ($selectedOption == '' || $selectedOption === 'Semua Cabang') {
                $pembelians = Pembelian::with('detailPembelians')->orderBy('id', 'DESC')->get();
            } else {
                $pembelians = Pembelian::with('detailPembelians')->where('cabang_id', $selectedOption)->orderBy('id', 'DESC')->get();
            }
        } else {
            $pembelians = Pembelian::with('detailPembelians')->where('cabang_id', auth()->user()->cabang_id)->orderBy('id', 'DESC')->get();
        }

        // Response JSON
        return response()->json([
            'success' => true,
            'data' => $pembelians
        ]);
    }

    public function exportPDF(Request $request)
    {
        $user = auth()->user();
        $selectedOption = $request->input('opsi');

        if ($user->role->role === 'administrator' || $user->role->role === 'owner') {
            if ($selectedOption == '' || $selectedOption === 'Semua Cabang') {
                $pembelians = Pembelian::with('detailPembelians')->orderBy('id', 'DESC')->get();
            } else {
                $pembelians = Pembelian::with('detailPembelians')->where('cabang_id', $selectedOption)->orderBy('id', 'DESC')->get();
            }
        } else {
            $pembelians = Pembelian::with('detailPembelians')->where('cabang_id', auth()->user()->cabang_id)->orderBy('id', 'DESC')->get();
        }

        // Initialize Dompdf
        $dompdf = new Dompdf();

        // Set paper size and orientation
        $dompdf->setPaper('A4', 'portrait');

        // Load HTML view into Dompdf
        $html = view('data-penjualan.data-penjualan', compact('pembelians'))->render();
        $dompdf->loadHtml($html);

        // Render the HTML as PDF
        $dompdf->render();

        // Stream the PDF to browser
        return $dompdf->stream('data-penjualan.pdf');
    }

    

    public function exportExcel(Request $request)
{
    $user = auth()->user();
    $selectedOption = $request->input('opsi');

    // Menentukan data pembelian berdasarkan filter
    if ($user->role->role === 'administrator' || $user->role->role === 'owner') {
        if ($selectedOption == '' || $selectedOption === 'Semua Cabang') {
            $pembelians = Pembelian::with('detailPembelians')->orderBy('id', 'DESC')->get();
        } else {
            $pembelians = Pembelian::with('detailPembelians')->where('cabang_id', $selectedOption)->orderBy('id', 'DESC')->get();
        }
    } else {
        $pembelians = Pembelian::with('detailPembelians')->where('cabang_id', auth()->user()->cabang_id)->orderBy('id', 'DESC')->get();
    }

    // Membuat Spreadsheet baru
    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', 'No');
    $sheet->setCellValue('B1', 'Kode');
    $sheet->setCellValue('C1', 'Total');
    $sheet->setCellValue('D1', 'Status');
    $sheet->setCellValue('E1', 'Tgl. Transaksi');
    $sheet->setCellValue('F1', 'Item');

    $row = 2;
    foreach ($pembelians as $key => $pembelian) {
        $sheet->setCellValue('A' . $row, $key + 1);
        $sheet->setCellValue('B' . $row, $pembelian->kode_pembelian);
        $sheet->setCellValue('C' . $row, $pembelian->total_harga);
        $sheet->setCellValue('D' . $row, $pembelian->status);
        $sheet->setCellValue('E' . $row, $pembelian->tgl_transaksi);
        $sheet->setCellValue('F' . $row, implode(', ', $pembelian->detailPembelians->pluck('nama')->toArray()));
        $row++;
    }

    $writer = new Xlsx($spreadsheet);

    // Download file Excel
    $fileName = 'data-penjualan.xlsx';
    return response()->stream(function() use ($writer) {
        $writer->save('php://output');
    }, 200, [
        'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        'Content-Disposition' => 'attachment; filename="' . $fileName . '"',
    ]);
}

}