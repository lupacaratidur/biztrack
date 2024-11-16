@extends('layouts.app')
 
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>Data Laporan Masalah</h1>
            @if(auth()->user()->role->role !== 'teknisi')
            <div class="ml-auto">
                <a href="javascript:void(0)" class="btn btn-primary" id="button_tambah_laporan" data-toggle="modal" data-target="#modal_tambah_laporan">
                    <i class="fa fa-plus"></i> Tambah Laporan
                </a>
            </div>
            @endif
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="table-responsive">
                                <table id="table_id" class="hover" style="width: 100%">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Judul Laporan</th>
                                            <th>Deskripsi</th>
                                            <th>Penulis</th>
                                            <th>Tanggal Laporan</th>
                                            <th>Bukti</th> <!-- Kolom untuk Bukti -->
                                            
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($issues as $index => $issue)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td>{{ $issue->title }}</td>
                                                <td>{{ \Illuminate\Support\Str::limit($issue->description, 50) }}</td>
                                                <td>{{ $issue->author->name ?? 'Tidak diketahui' }}</td>
                                                <td>{{ $issue->created_at->format('d-m-Y') }}</td>
                                                <td>
                                                    @if($issue->evidence)
                                                        <a href="{{ asset('storage/' . $issue->evidence) }}" target="_blank" class="btn btn-info btn-sm">
                                                            <i class="fa fa-file"></i> Lihat Bukti
                                                        </a>
                                                    @else
                                                        <span class="text-muted">Tidak ada bukti</span>
                                                    @endif
                                                </td>

                                                <td>
                                                   
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Tambah Laporan -->
    <div class="modal fade" id="modal_tambah_laporan" tabindex="-1" role="dialog" aria-labelledby="modalTambahLaporanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahLaporanLabel">Tambah Laporan Masalah</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="form_tambah_laporan" method="POST" action="{{ route('report-issues.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="title">Judul Laporan</label>
                            <input type="text" class="form-control" id="title" name="title" required>
                            <div id="alert-title" class="alert alert-danger mt-2 d-none"></div>
                        </div>
                        <div class="form-group">
                            <label for="description">Deskripsi</label>
                            <textarea class="form-control" id="description" name="description" required></textarea>
                            <div id="alert-description" class="alert alert-danger mt-2 d-none"></div>
                        </div>
                        <div class="form-group">
                            <label for="evidence">Bukti (Opsional)</label>
                            <input type="file" class="form-control" id="evidence" name="evidence" accept=".jpg, .png, .pdf">
                            <div id="alert-evidence" class="alert alert-danger mt-2 d-none"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Keluar</button>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const form = document.getElementById('form_tambah_laporan');

    form.addEventListener('submit', function (event) {
        event.preventDefault();
        
        const formData = new FormData(form);
        const url = form.getAttribute('action');

        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
            },
            body: formData,
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Server error');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                form.reset();
                $('#modal_tambah_laporan').modal('hide');
                location.reload(); // Refresh halaman untuk menampilkan data baru
            } else if (data.errors) {
                // Tampilkan pesan error
                if (data.errors.title) {
                    document.getElementById('alert-title').textContent = data.errors.title[0];
                    document.getElementById('alert-title').classList.remove('d-none');
                }
                if (data.errors.description) {
                    document.getElementById('alert-description').textContent = data.errors.description[0];
                    document.getElementById('alert-description').classList.remove('d-none');
                }
                if (data.errors.evidence) {
                    document.getElementById('alert-evidence').textContent = data.errors.evidence[0];
                    document.getElementById('alert-evidence').classList.remove('d-none');
                }
            }
        })
        .catch(error => {
            console.error('Fetch error:', error);
            alert("Gagal menyimpan data. Periksa koneksi atau coba lagi nanti.");
        });
    });
});
</script>
@endsection
