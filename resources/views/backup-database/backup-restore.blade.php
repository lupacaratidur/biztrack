@extends('layouts.app')

@section('content')
<div class="container p-4 bg-light rounded shadow-lg">
    <h2 class="text-center text-primary font-weight-bold">Backup dan Restore Database</h2>
    <hr>

    <!-- Menampilkan pesan sukses atau error -->
    @if (session('success'))
        <div class="alert alert-success text-center shadow-sm">
            {{ session('success') }}
            @if (session('backup_file'))
                <br><a href="{{ asset('storage/backup/' . session('backup_file')) }}" class="btn btn-success mt-3" download>Download Backup</a>
            @endif
        </div>
    @elseif (session('error'))
        <div class="alert alert-danger text-center shadow-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- Section untuk Backup -->
    <div class="mb-5 p-4 rounded bg-white shadow-sm">
        <h4 class="text-secondary font-weight-bold">Backup Database</h4>
        <p>Untuk melakukan backup database, klik tombol di bawah ini. Proses backup akan membuat salinan dari database Anda dalam format .sql.</p>
        <a href="{{ route('backup.database') }}" class="btn btn-primary btn-lg btn-block">Backup Database</a>
    </div>

   <!-- Section untuk Restore -->
    <div class="mb-4 p-4 rounded bg-light shadow-sm">
        <h4 class="text-primary font-weight-bold mb-3">Restore Database</h4>
        <p class="text-muted">Pilih file backup (.sql) yang telah Anda simpan, lalu klik tombol "Restore Database" untuk memulihkan data.</p>
        
        <form action="{{ route('restore.database') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="database_file" class="font-weight-bold d-flex align-items-center">
                    <i class="fa fa-upload mr-2 text-primary" aria-hidden="true"></i> 
                    Pilih File Backup 
                    <small class="ml-2 text-muted" data-toggle="tooltip" data-placement="right" title="Pilih file database dengan format .sql untuk di-restore">
                        <i class="fa fa-info-circle" aria-hidden="true"></i>
                    </small>
                </label>
                <input type="file" name="database_file" id="database_file" class="form-control-file border p-2 rounded" required>
                <small class="form-text text-muted mt-2">Hanya file .sql yang diizinkan.</small>
            </div>
            
            <button type="submit" class="btn btn-primary btn-lg btn-block d-flex align-items-center justify-content-center">
                <i class="fa fa-database mr-2" aria-hidden="true"></i> Restore Database
            </button>
        </form>
    </div>

</div>
@endsection
