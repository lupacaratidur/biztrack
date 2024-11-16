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
                                    <div class="col-md-6">
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
                                </div>
                            </div>
                        </div>
                    </div>
                @else
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

    <!-- Datatable Jquery -->
    <script>
        $(document).ready(function() {
            // Inisialisasi DataTable
            var table = $('#table_id').DataTable();

            // Fetch Data
            $.ajax({
                url: "/data-penjualan/get-data",
                type: "GET",
                dataType: 'JSON',
                success: function(response) {
                    let counter = 1;
                    // Bersihkan tabel jika sudah diinisialisasi sebagai DataTable
                    if ($.fn.DataTable.isDataTable('#table_id')) {
                        table.clear();
                    }
                    $.each(response.data, function(key, value) {
                        var badgeClass = value.status === 'paid' ? 'badge-success' :
                            'badge-warning';
                        var badgeText = value.status === 'paid' ? 'Paid' : 'Unpaid';

                        let detailItems = '';
                        $.each(value.detail_pembelians, function(index, detailItem) {
                            detailItems +=
                                `${detailItem.nama} (${detailItem.quantity}), `;
                        });
                        detailItems = detailItems.slice(0, -2);

                        let penjualan = `
                            <tr class="penjualan-row" id="index_${value.id}">
                                <td>${counter++}</td>
                                <td>${value.kode_pembelian}</td>
                                <td>Rp. ${value.total_harga}</td>
                                <td>
                                    <span class="badge ${badgeClass}">${badgeText}</span>
                                </td>
                                <td>${value.tgl_transaksi}</td>
                                <td>${detailItems}</td>
                            </tr>
                        `;

                        table.row.add($(penjualan)).draw(false);
                    });
                }
            });

            // Option Select
            $('#select-cabang').on('change', function() {
                var selectedOption = $(this).val();
                loadData(selectedOption);
            });

            function loadData(selectedOption) {
                $.ajax({
                    url: "/data-penjualan/get-data",
                    type: "GET",
                    dataType: 'JSON',
                    data: {
                        opsi: selectedOption
                    },
                    success: function(response) {
                        let counter = 1;
                        if ($.fn.DataTable.isDataTable('#table_id')) {
                            table.clear();
                        }
                        $.each(response.data, function(key, value) {
                            var badgeClass = value.status === 'paid' ? 'badge-success' :
                                'badge-warning';
                            var badgeText = value.status === 'paid' ? 'Paid' : 'Unpaid';

                            let detailItems = '';
                            $.each(value.detail_pembelians, function(index, detailItem) {
                                detailItems +=
                                    `${detailItem.nama} (${detailItem.quantity}), `;
                            });
                            detailItems = detailItems.slice(0, -2);

                            let penjualan = `
                                <tr class="penjualan-row" id="index_${value.id}">
                                    <td>${counter++}</td>
                                    <td>${value.kode_pembelian}</td>
                                    <td>Rp. ${value.total_harga}</td>
                                    <td>
                                        <span class="badge ${badgeClass}">${badgeText}</span>
                                    </td>
                                    <td>${value.tgl_transaksi}</td>
                                    <td>${detailItems}</td>
                                </tr>
                            `;

                            table.row.add($(penjualan)).draw(false);
                        });
                    }
                });
            }
        });
    </script>


@endsection
