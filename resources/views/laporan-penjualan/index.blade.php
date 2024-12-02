@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Laporan Penjualan</h1>
        <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-danger" id="print-laporan-penjualan"><i class="fa fa-sharp fa-light fa-print"></i> Print PDF</a>
            <a href="javascript:void(0)" class="btn btn-success" id="export-laporan-excel"><i class="fa fa-file-excel"></i> Export Excel</a> <!-- Tombol untuk Excel -->
        </div>
    </div>

    <div class="section-body">
        <div class="row">
            <!-- Transaksi Hari Ini -->
            <div class="col-md-3">
                <div class="card card-success">
                    <div class="card-header">
                        <h6>Transaksi Hari Ini</h6>
                    </div>
                    <div class="card-body">
                        <b>{{ number_format($transaksiHariIni, 0, ',', '.') }}</b> kali
                        @if ($transaksiHariIni > $transaksiKemarin)
                            @if ($transaksiKemarin > 0)
                                <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> {{ number_format((($transaksiHariIni - $transaksiKemarin) / $transaksiKemarin) * 100, 2) }}%</span>
                            @else
                                <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> Naik</span>
                            @endif
                        @elseif($transaksiHariIni < $transaksiKemarin)
                            @if ($transaksiKemarin > 0)
                                <span class="badge badge-danger"><i class="fa far fa-arrow-down"></i> {{ number_format((($transaksiKemarin - $transaksiHariIni) / $transaksiKemarin) * 100, 2) }}%</span>
                            @else
                                <span class="badge badge-danger"><i class="fa far fa-arrow-down"></i> Turun</span>
                            @endif
                        @else
                            <span class="badge badge-secondary">Sama</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Transaksi Kemarin -->
            <div class="col-md-3">
                <div class="card card-success">
                    <div class="card-header">
                        <h6>Transaksi Kemarin</h6>
                    </div>
                    <div class="card-body">
                        <b>{{ number_format($transaksiKemarin, 0, ',', '.') }}</b> kali
                    </div>
                </div>
            </div>

            <!-- Transaksi Bulan Ini -->
            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h6>Transaksi Bulan Ini</h6>
                    </div>
                    <div class="card-body">
                        <b>{{ number_format($transaksiBulanIni, 0, ',', '.') }}</b> kali
                        @if ($transaksiBulanIni > $transaksiBulanLalu)
                            @if ($transaksiBulanLalu > 0)
                                <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> {{ number_format((($transaksiBulanIni - $transaksiBulanLalu) / $transaksiBulanLalu) * 100, 2) }}%</span>
                            @else
                                <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> Naik</span>
                            @endif
                        @elseif($transaksiBulanIni < $transaksiBulanLalu)
                            @if ($transaksiBulanLalu > 0)
                                <span class="badge badge-danger"><i class="fa far fa-arrow-down"></i> {{ number_format((($transaksiBulanLalu - $transaksiBulanIni) / $transaksiBulanLalu) * 100, 2) }}%</span>
                            @else
                                <span class="badge badge-danger"><i class="fa far fa-arrow-down"></i> Turun</span>
                            @endif
                        @else
                            <span class="badge badge-secondary">Sama</span>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Transaksi Bulan Lalu -->
            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h6>Transaksi Bulan Lalu</h6>
                    </div>
                    <div class="card-body">
                        <b>{{ number_format($transaksiBulanLalu, 0, ',', '.') }}</b> kali
                    </div>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <form id="filter_form">
                        <div class="row">
                            @if(auth()->user()->role->role === 'administrator' || auth()->user()->role->role === 'owner')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="select-cabang">Pilih Cabang</label>
                                        <select class="form-control selectric" id="select-cabang" name="cabang_id">
                                            <option value="Semua Cabang">Semua Cabang</option>
                                            @foreach ($cabangs as $cabang)
                                                <option value="{{ $cabang->id }}">{{ $cabang->cabang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal_mulai">Tanggal Mulai</label>
                                    <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal_selesai">Tanggal Selesai</label>
                                    <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai">
                                </div>
                            </div>
                            <div class="col-md-12 d-flex justify-content-end">
                                <button type="submit" class="btn btn-primary mr-2">Filter</button>
                                <button type="button" class="btn btn-danger" id="refresh_btn">Refresh</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Table Section -->
        <div class="row">
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table_id" class="table table-hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Total</th>
                                        <th>Item</th>
                                        <th>Tgl. Transaksi</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- JavaScript -->
<script>
 $(document).ready(function() {
    // Inisialisasi DataTable
    var table = $('#table_id').DataTable({
        destroy: true // Pastikan tabel dapat diinisialisasi ulang
    });

    // Load semua data pertama kali
    loadData(); // Panggil tanpa parameter filter

    // Event untuk tombol filter
    $('#filter_form').on('submit', function(event) {
        event.preventDefault();
        loadData(); // Panggil fungsi loadData saat tombol filter ditekan
    });

    // Tombol Refresh untuk mengatur ulang filter
    $('#refresh_btn').on('click', function() {
        $('#filter_form')[0].reset(); // Reset form filter
        loadData(); // Tampilkan semua data setelah refresh
    });

    // Fungsi untuk memuat data dari server
    function loadData() {
        // Ambil nilai filter (kosongkan untuk muat semua data)
        var cabangId = $('#select-cabang').val() || '';
        var tanggalMulai = $('#tanggal_mulai').val() || '';
        var tanggalSelesai = $('#tanggal_selesai').val() || '';

        $.ajax({
            url: '/laporan-penjualan/get-data',
            type: 'GET',
            data: {
                cabang_id: cabangId,
                tanggal_mulai: tanggalMulai,
                tanggal_selesai: tanggalSelesai
            },
            success: function(response) {
                // Bersihkan tabel
                table.clear();

                // Jika data kosong, render tabel kosong
                if (response.success && response.data.length === 0) {
                    table.draw();
                    return;
                }

                // Tambahkan data baru ke tabel
                let counter = 1;
                $.each(response.data, function(index, item) {
                    let detailItems = item.detail_pembelians.map(function(detail) {
                        return `${detail.nama} (${detail.quantity})`;
                    }).join(', ');

                    table.row.add([
                        counter++,
                        item.kode_pembelian,
                        `Rp. ${item.total_harga}`,
                        detailItems,
                        item.tgl_transaksi
                    ]);
                });

                table.draw(false); // Render ulang tabel
            },
            error: function(xhr, status, error) {
                console.error('Error:', error);
            }
        });
    }

    // Export PDF
    $(document).ready(function () {
        $('#print-laporan-penjualan').click(function () {
            // Ambil nilai filter saat ini
            var cabangId = $('#select-cabang').val(); // Tidak perlu nilai default kosong
            var tanggalMulai = $('#tanggal_mulai').val();
            var tanggalSelesai = $('#tanggal_selesai').val();

            // Tentukan URL dan data untuk request
            var url = '/laporan-penjualan/get-data?print_pdf=true';
            var data = {};

            // Hanya kirimkan data filter yang valid
            if (cabangId && cabangId !== 'Semua Cabang') {
                data.cabang_id = cabangId;
            }
            if (tanggalMulai) {
                data.tanggal_mulai = tanggalMulai;
            }
            if (tanggalSelesai) {
                data.tanggal_selesai = tanggalSelesai;
            }

            // Kirim request untuk menghasilkan PDF
            $.ajax({
                url: url, // URL controller untuk generate PDF
                type: 'GET', // Method GET
                data: data, // Kirimkan data filter (jika ada)
                xhrFields: {
                    responseType: 'blob' // Mengatur tipe respons agar mendapatkan file PDF
                },
                success: function (response) {
                    var link = document.createElement('a');
                    var fileURL = URL.createObjectURL(response);
                    link.href = fileURL;
                    link.download = 'laporan_penjualan.pdf'; // Nama file PDF yang akan diunduh
                    link.click(); // Trigger download otomatis
                },
                error: function (xhr, status, error) {
                    console.error("Error generating PDF:", error); // Menangani error jika ada
                }
            });
        });
    });


    // Export Excel
    $('#export-laporan-excel').click(function () {
        // Ambil nilai filter saat ini
        var cabangId = $('#select-cabang').val();
        var tanggalMulai = $('#tanggal_mulai').val();
        var tanggalSelesai = $('#tanggal_selesai').val();

        // Tentukan URL dan data untuk request
        var url = '/laporan-penjualan/get-data?print_excel=true';
        var data = {};

        // Hanya kirimkan data filter yang valid
        if (cabangId && cabangId !== 'Semua Cabang') {
            data.cabang_id = cabangId;
        }
        if (tanggalMulai) {
            data.tanggal_mulai = tanggalMulai;
        }
        if (tanggalSelesai) {
            data.tanggal_selesai = tanggalSelesai;
        }

        // Kirim request untuk menghasilkan file Excel
        $.ajax({
            url: url, // URL controller untuk generate Excel
            type: 'GET', // Method GET
            data: data, // Kirimkan data filter (jika ada)
            xhrFields: {
                responseType: 'blob' // Mengatur tipe respons agar mendapatkan file Excel
            },
            success: function (response) {
                var link = document.createElement('a');
                var fileURL = URL.createObjectURL(response);
                link.href = fileURL;
                link.download = 'laporan_penjualan.xlsx'; // Nama file Excel yang akan diunduh
                link.click(); // Trigger download otomatis
            },
            error: function (xhr, status, error) {
                console.error('Error:', error); // Menangani error jika ada
            }
        });
    });

});
</script>


@endsection
