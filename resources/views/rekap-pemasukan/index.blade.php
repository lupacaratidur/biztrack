@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Rekap Pemasukan</h1>
        <!-- <div class="ml-auto">
            <a href="javascript:void(0)" class="btn btn-danger" id="print-rekap-pemasukan"><i class="fa fa-sharp fa-light fa-print"></i> Print PDF</a>
        </div> -->
    </div>

    <div class="section-body">
        <div class="row">
            <div class="col-md-3">
                <div class="card card-success">
                    <div class="card-header">
                        <h6>Pemasukan Hari Ini</h6>
                    </div>
                    <div class="card-body">
                        <b>Rp. {{ number_format($pemasukanHariIni, 0, ',', '.') }}</b>
                        @if($pemasukanHariIni > $pemasukanKemarin)
                            @if($pemasukanKemarin > 0)
                                <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> {{ number_format((($pemasukanHariIni - $pemasukanKemarin) / $pemasukanKemarin) * 100, 2) }}%</span>
                            @else
                                <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> Naik</span>
                            @endif
                        @elseif($pemasukanHariIni < $pemasukanKemarin)
                            @if($pemasukanKemarin > 0)
                                <span class="badge badge-danger"><i class="fa far fa-arrow-down"></i> {{ number_format((($pemasukanKemarin - $pemasukanHariIni) / $pemasukanKemarin) * 100, 2) }}%</span>
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
                        <h6>Pemasukan Kemarin</h6>
                    </div>
                    <div class="card-body">
                        <b>Rp. {{ number_format($pemasukanKemarin, 0, ',', '.') }}</b>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card card-primary">
                  <div class="card-header">
                    <h6>Pemasukan Bulan Ini</h6>
                  </div>
                  <div class="card-body">
                    <b>Rp. {{ number_format($pemasukanBulanIni, 0, ',', '.') }}</b>
                    @if ($pemasukanBulanLalu > 0)
                      @if ($pemasukanBulanIni > $pemasukanBulanLalu)
                        <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> {{ number_format((($pemasukanBulanIni - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100, 2) }}%</span>
                      @elseif ($pemasukanBulanIni < $pemasukanBulanLalu)
                        <span class="badge badge-danger"><i class="fa far fa-arrow-down"></i> {{ number_format((($pemasukanBulanLalu - $pemasukanBulanIni) / $pemasukanBulanLalu) * 100, 2) }}%</span>
                      @else
                        <span class="badge badge-secondary">Sama</span>
                      @endif
                    @elseif ($pemasukanBulanLalu == 0 && $pemasukanBulanIni > 0)
                      <span class="badge badge-success"><i class="fa far fa-arrow-up"></i> Naik</span>
                    @else
                      <span class="badge badge-secondary">Tidak tersedia</span>
                    @endif
                  </div>
                </div>
              </div>
              
            <div class="col-md-3">
                <div class="card card-primary">
                    <div class="card-header">
                        <h6>Pemasukan Bulan Lalu</h6>
                    </div>
                    <div class="card-body">
                       <b>Rp. {{ number_format($pemasukanBulanLalu, 0, ',', '.') }}</b>
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
                                <form id="filter_form" action="/rekap-pemasukan/get-data" method="GET">
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
                                <form id="filter_form" action="/rekap-pemasukan/get-data" method="GET">
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
                                        <th>Tgl. Transaksi</th>
                                        <th>Pemasukan</th>
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
<!-- <script>
    $(document).ready(function(){
        $('#table_id').DataTable();
    })
</script> -->

<!-- Select Option -->
<script>
    $(document).ready(function() {
        var table = $('#table_id').DataTable({
            destroy: true,
            autoWidth: false
        });

        // Load semua data awal
        loadData();

        // Event untuk tombol filter
        $('#filter_form').on('submit', function(e) {
            e.preventDefault();
            loadData(); // Panggil fungsi loadData saat tombol filter ditekan
        });

        // Tombol refresh
        $('#refresh_btn').on('click', function() {
            $('#filter_form')[0].reset(); // Reset form filter
            loadData(); // Muat ulang semua data
        });

        // Fungsi untuk memuat data
        function loadData() {
            var cabangId = $('#select-cabang').val() || '';
            var tanggalMulai = $('#tanggal_mulai').val() || '';
            var tanggalSelesai = $('#tanggal_selesai').val() || '';

            $.ajax({
                url: '/rekap-pemasukan/get-data',
                type: 'GET',
                dataType: 'json',
                data: {
                    opsi: cabangId,
                    tanggal_mulai: tanggalMulai,
                    tanggal_selesai: tanggalSelesai
                },
                success: function(response) {
                    // Bersihkan tabel
                    table.clear();

                    // Jika data kosong, render tabel kosong
                    if (response.success && (!response.data || response.data.length === 0)) {
                        table.draw();
                        return;
                    }

                    // Tambahkan data ke tabel
                    let counter = 1;
                    $.each(response.data, function(index, item) {
                        let detailItems = item.detail_pembelians.map(function(detail) {
                            return `${detail.nama} (${detail.quantity})`;
                        }).join(', ');

                        table.row.add([
                            counter++,
                            item.kode_pembelian,
                            item.tgl_transaksi,
                            `Rp. ${item.total_harga}`,
                            detailItems
                        ]);
                    });

                    table.draw(false); // Render ulang tabel
                },
                error: function(xhr) {
                    console.error('Error:', xhr.responseText);
                    alert('Terjadi kesalahan saat memuat data.');
                }
            });
        }
    });
</script>



@endsection