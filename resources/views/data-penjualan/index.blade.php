@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
        <h1>Data Penjualan</h1>
    </div>

    <div class="section-body">
        <div class="row">
            @if (auth()->user()->role->role === 'administrator' || auth()->user()->role->role === 'owner')
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Pilih Cabang</label>
                                        <select class="form-control selectric" id="select-cabang">
                                            <option value="Semua Cabang">Semua Cabang</option>
                                            @foreach ($cabangs as $cabang)
                                                <option value="{{ $cabang->id }}">{{ $cabang->cabang }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>&nbsp;</label><br>
                                        <button class="btn btn-success" id="export-excel"><i class="fa fa-file-excel"></i> Export Excel</button>
                                        <button class="btn btn-danger" id="export-pdf"><i class="fa fa-file-pdf"></i> Export PDF</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="table_id" class="hover" style="width: 100%">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Total</th>
                                        <th>Status</th>
                                        <th>Tgl. Transaksi</th>
                                        <th>Item</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
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
   $(document).ready(function () {
    var table = $('#table_id').DataTable();

    // Fetch data saat halaman dimuat
    loadData();

    // Filter berdasarkan cabang
    $('#select-cabang').on('change', function () {
        var selectedOption = $(this).val();
        loadData(selectedOption);
    });

    // Fungsi untuk memuat data
    function loadData(selectedOption = 'Semua Cabang') {
        $.ajax({
            url: '/data-penjualan/get-data',
            type: 'GET',
            dataType: 'JSON',
            data: {
                opsi: selectedOption,
            },
            success: function (response) {
                table.clear();
                let counter = 1;

                $.each(response.data, function (key, value) {
                    var badgeClass = value.status === 'paid' ? 'badge-success' : 'badge-warning';
                    var badgeText = value.status === 'paid' ? 'Paid' : 'Unpaid';
                    var itemDetails = value.detail_pembelians.map(function (detail) {
                        return detail.nama + ' (Qty: ' + detail.quantity + ')';
                    }).join(', ');

                    table.row.add([
                        counter++,
                        value.kode_pembelian,
                        'Rp. ' + value.total_harga,
                        `<span class="badge ${badgeClass}">${badgeText}</span>`,
                        value.tgl_transaksi,
                        itemDetails,
                    ]).draw(false);
                });
            },
            error: function () {
                alert('Gagal memuat data!');
            },
        });
    }

    // Export PDF
    $('#export-pdf').on('click', function () {
        var selectedOption = $('#select-cabang').val();
        var exportUrl = `/data-penjualan/export-pdf?opsi=${selectedOption}`;
        window.open(exportUrl, '_blank');
    });

    // Export Excel
    $('#export-excel').on('click', function () {
        var selectedOption = $('#select-cabang').val();
        var exportUrl = `/data-penjualan/export-excel?opsi=${selectedOption}`;
        window.open(exportUrl, '_blank');
    });
});

</script>

@endsection
