<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ReportIssue;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class ReportIssueController extends Controller
{
    // Tampilkan daftar laporan yang dibuat oleh user yang sedang login
    public function index()
    {
        if (Auth::user()->role->role === 'teknisi') {
        $issues = ReportIssue::where('author_id', '!=', Auth::id())->get(); // Hanya laporan dari user lain
        return view('laporan-masalah.index', compact('issues'));
    }

        $issues = ReportIssue::where('author_id', Auth::id())->get();
        return view('laporan-masalah.index', compact('issues'));
    }

    // Tampilkan form untuk membuat laporan
    public function create()
    {
        return view('laporan-masalah.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'evidence' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);
    
        // Simpan file evidence jika ada
        $evidencePath = $request->file('evidence') ? $request->file('evidence')->store('evidences', 'public') : null;
    
        ReportIssue::create([
            'title' => $request->title,
            'description' => $request->description,
            'evidence' => $evidencePath,
            'author_id' => Auth::id(),
        ]);
    
        // Redirect ke route laporan-masalah.index dengan pesan sukses
        return redirect()->route('report-issues.index')->with('success', 'Laporan berhasil dikirim.');
    }
    

    // Tampilkan form edit laporan
    public function edit($id)
    {
        $issue = ReportIssue::where('id', $id)->where('author_id', Auth::id())->firstOrFail();
        return view('laporan-masalah.edit', compact('issue'));
    }

    // Update laporan yang ada
    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'evidence' => 'nullable|file|mimes:jpg,png,pdf|max:2048',
        ]);

        $issue = ReportIssue::where('id', $id)->where('author_id', Auth::id())->firstOrFail();

        // Jika ada file baru yang diunggah, ganti file yang lama
        if ($request->hasFile('evidence')) {
            if ($issue->evidence) {
                Storage::delete($issue->evidence); // Hapus file lama
            }
            $issue->evidence = $request->file('evidence')->store('evidences');
        }

        // Update data laporan
        $issue->title = $request->title;
        $issue->description = $request->description;
        $issue->save();

        return redirect()->route('report-issues.index')->with('success', 'Laporan berhasil diperbarui.');
    }

    // Hapus laporan
    public function destroy($id)
    {
        $issue = ReportIssue::where('id', $id)->where('author_id', Auth::id())->firstOrFail();

        // Hapus file evidence jika ada
        if ($issue->evidence) {
            Storage::delete($issue->evidence);
        }

        $issue->delete();

        return redirect()->route('report-issues.index')->with('success', 'Laporan berhasil dihapus.');
    }
}
