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

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Form submission event
    document.getElementById('form_tambah_laporan').addEventListener('submit', function (event) {
        event.preventDefault();
        
        const form = this;
        const formData = new FormData(form);
        const url = form.getAttribute('action');
        
        fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('input[name="_token"]').value,
            },
            body: formData,
        })
        .then(response => response.json())
        .then(data => {
            if (data.errors) {
                // Show validation errors
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
            } else {
                // Success, reset form and close modal
                form.reset();
                $('#modal_tambah_laporan').modal('hide');
                location.reload(); // Reload page to reflect new data
            }
        })
        .catch(error => console.error('Error:', error));
    });
});
</script>
