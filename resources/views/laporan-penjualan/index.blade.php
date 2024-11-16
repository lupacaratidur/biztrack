@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Laporan Penjualan</h1>
        <!-- <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-danger" id="print-laporan-penjualan"><i class="fa fa-sharp fa-light fa-print"></i> Print PDF</a>
        </div> -->
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-success">
                    <div class="card-header">
                        <h6>Transaksi Hari Ini</h6>
                    </div>
                    <div class="card-body">
                        <b>{{ number_format($transaksiHariIni), 0, ',', '.' }}</b> kali
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
            <div class="col-md-3">
                <div class="card card-success">
                    <div class="card-header">
                        <h6>Transaksi Kemarin</h6>
                    </div>
                    <div class="card-body">
                        <b>{{ number_format($transaksiKemarin), 0, ',', '.' }}</b> kali
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h6>Transaksi Bulan Ini</h6>
                    </div>
                    <div class="card-body">
                        <b>{{ number_format($transaksiHariIni), 0, ',', '.' }}</b> kali
                        @if ($transaksiHariIni > $transaksiBulanLalu)
                            @if ($transaksiBulanLalu > 0)
                                <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> {{ number_format((($transaksiHariIni - $transaksiBulanLalu) / $transaksiBulanLalu) * 100, 2) }}%</span>
                            @else
                                <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> Naik</span>
                            @endif
                        @elseif($transaksiHariIni < $transaksiBulanLalu)
                            @if ($transaksiBulanLalu > 0)
                                <span class="badge badge-danger"><i class="fa far fa-arrow-down"></i> {{ number_format((($transaksiBulanLalu - $transaksiHariIni) / $transaksiBulanLalu) * 100, 2) }}%</span>
                            @else
                                <span class="badge badge-danger"><i class="fa far fa-arrow-down"></i> Turun</span>
                            @endif
                        @else
                            <span class="badge badge-secondary">Sama</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h6>Transaksi Bulan Lalu</h6>
                    </div>
                    <div class="card-body">
                        <b>{{ number_format($transaksiBulanLalu), 0, ',', '.' }}</b> kali
                    </div>
                </div>
            </div>
        </div>
        <div class="row"> 
            @if (auth()->user()->role->role === 'administrator' || auth()->user()->role->role === 'owner' )
            <div class="col-lg-12">
                <div class="card">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-4">
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
                            <div class="col-md-4">
                                <form id="filter_form" action="/laporan-penjualan/get-data" method="GET">
                                    <div class="form-group">
                                        <label>Tanggal Mulai:</label>
                                        <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Tanggal Selesai:</label>
                                        <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai">
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="d-flex align-items-end justify-content-end mt-4">
                                        <button type="submit" class="btn btn-primary">Filter</button>
                                        <button type="button" class="btn btn-danger ml-2" id="refresh_btn">Refresh</button>
                                    </div>
                                </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @else
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-group">
                                <form id="filter_form" action="/laporan-penjualan/get-data" method="GET">
                                    <div class="row">
                                        <div class="col-md-5">
                                            <label>Pilih Tanggal Mulai :</label>
                                            <input type="date" class="form-control" name="tanggal_mulai" id="tanggal_mulai">
                                        </div>
                                        <div class="col-md-5">
                                            <label>Pilih Tanggal Selesai :</label>
                                            <input type="date" class="form-control" name="tanggal_selesai" id="tanggal_selesai">
                                        </div>
                                        <div class="col-md-2 d-flex align-items-end">
                                            <button type="submit" class="btn btn-primary">Filter</button>
                                            <button type="button" class="btn btn-danger" id="refresh_btn">Refresh</button>
                                        </div>
                                    </div>
                                </form>
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
                                        <th>Item</th>
                                        <th>Tgl. Transaksi</th>
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
    $(document).ready(function(){
        $('#table_id').DataTable();
    });
</script>


<!-- Option Select -->
<script>
$(document).ready(function() {
    loadData();

    $('#filter_form').submit(function(event) {
        event.preventDefault();
        loadData(); // Panggil fungsi loadData saat tombol filter ditekan
    });

    $('#refresh_btn').on('click', function() {
        refreshTable();
    });

    function loadData() {
        var selectedOption  = $('#select-cabang').val();
        var tanggalMulai    = $('#tanggal_mulai').val();
        var tanggalSelesai  = $('#tanggal_selesai').val();

        $.ajax({
            url: "/laporan-penjualan/get-data",
            type: "GET",
            dataType: 'JSON',
            data: {
                opsi: selectedOption,
                tanggal_mulai: tanggalMulai,
                tanggal_selesai: tanggalSelesai
            },
            success: function(response) {
                let counter = 1;
                $('#table_id').DataTable().clear();
                $.each(response.data, function(key, value) {
                    let detailItems = '';
                    $.each(value.detail_pembelians, function(index, detailItem) {
                        detailItems += `${detailItem.nama} (${detailItem.quantity}), `;
                    });
                    detailItems = detailItems.slice(0, -2);

                    let penjualan = `
                        <tr class="penjualan-row" id="index_${value.id}">
                            <td>${counter++}</td>
                            <td>${value.kode_pembelian}</td>
                            <td>Rp. ${value.total_harga}</td>
                            <td>${detailItems}</td>
                            <td>${value.tgl_transaksi}</td>
                        </tr>
                    `;

                    $('#table_id').DataTable().row.add($(penjualan)).draw(false);
                });
            }
        });
    }

    // Fungsi Refresh Tabel
    function refreshTable() {
        $('#filter_form')[0].reset();
        loadData();
    }

    // Print Laporan Penjualan
    $('#print-laporan-penjualan').on('click', function() {
        var selectedOption  = $('#select-cabang').val();
        var tanggalMulai    = $('#tanggal_mulai').val();
        var tanggalSelesai  = $('#tanggal_selesai').val();
        var url             = '/laporan-penjualan/print-laporan-penjualan';

        if(selectedOption && selectedOption !== 'Semua Cabang'){
            url += '?opsi=' + selectedOption;
            if (tanggalMulai && tanggalSelesai) {
                url += '&tanggal_mulai=' + tanggalMulai + '&tanggal_selesai=' + tanggalSelesai;
            }
        }
        if (tanggalMulai && tanggalSelesai) {
            url += '?tanggal_mulai=' + tanggalMulai + '&tanggal_selesai=' + tanggalSelesai;
        }

        // Menggunakan Ajax untuk mencetak tampilan PDF
        $.ajax({
            url: url,
            method: 'GET',
            dataType: 'json',
            data: { print_pdf: true }, // Mengirimkan parameter print_pdf sebagai true
            success: function(response) {
                if (response.success) {
                    console.log('PDF telah dicetak');
                }
            },
            error: function(xhr, status, error) {
                console.log(error);
            }
        });
    });

});

</script>
@endsection