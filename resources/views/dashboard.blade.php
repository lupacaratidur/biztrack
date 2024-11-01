@extends('layouts.app')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Dashboard</h1>
    </div>

    <div class="section-body">
        <div class="row">
          <div class="col-lg-12">
            <div class="alert alert-primary alert-has-icon">
              <div class="alert-icon"><i class="far fa-lightbulb"></i></div>
              <div class="alert-body">
                <div class="alert-title">Selamat Datang, {{ auth()->user()->role->role }}</div>
                Sekarang, anda sedang Login di aplikasi Cabang {{ auth()->user()->cabang->cabang }}
              </div>
            </div>
          </div>
      
            <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-danger">
                    <i class="fa far fa-exchange-alt"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Total Transaksi</h4>
                    </div>
                    <div class="card-body">
                      {{ $totalTransaksi }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-warning">
                    <i class="far fa-file"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Pemasukan Hari Ini</h4>
                    </div>
                    <div class="card-body">
                      Rp. {{ number_format($pemasukanHariIni, 0, ',', '.') }}
                    </div>
                  </div>
                </div>
              </div>
              <div class="col-lg-4 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                  <div class="card-icon bg-success">
                    <i class="fa fal fa-file-alt"></i>
                  </div>
                  <div class="card-wrap">
                    <div class="card-header">
                      <h4>Total Pemasukan</h4>
                    </div>
                    <div class="card-body">
                      Rp. {{ number_format($semuaPemasukan, 0, ',', '.') }}
                    </div>
                  </div>
                </div>
              </div>                  
            </div>
        </div>

        <div class="row">
          <div class="col-lg-8">
            <div class="card">
              <div class="card-header">
                <h4>Grafik Transaksi Harian</h4>
              </div>
              <div class="card-body">
                <canvas id="grafikPenjualan"></canvas>
              </div>
            </div>
          </div>
          
          <div class="col-lg-4">
            <div class="card">
              <div class="card-header">
                <h4>Pemasukan per-Cabang</h4>
              </div>
              <div class="card-body">
                <canvas id="pemasukanCabang"></canvas>
              </div>
            </div>
          </div>
          </div>
        </div>
    </div>
  </section>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('grafikPenjualan');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [
        @foreach($grafikPenjualan as $data)
          '{{ \Carbon\Carbon::parse($data->date)->locale('id')->dayName }}',
        @endforeach
      ],
      datasets: [{
        label: 'Grafik Harian',
        data: [
          @foreach($grafikPenjualan as $data)
            {{ $data->total }},
          @endforeach
        ],
        borderWidth: 1
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true,
          precision: 0,
          stepSize: 1,
          ticks: {
            callback: function(value) {
              if (value % 1 === 0) {
                return value;
              }
            }
          }
        }
      }
    }
  });
</script>

<script>
  const pemasukanCabang = document.getElementById('pemasukanCabang');
  const data = {
      labels: [
          @foreach($pemasukanPerCabang as $cabangId => $pemasukan)
              '{{ $cabangNames[$cabangId ]}}',
          @endforeach
      ],
      datasets: [{
          label: 'Pemasukan Cabang',
          data: [
              @foreach($pemasukanPerCabang as $cabangId => $pemasukan)
                  {{ $pemasukan }},
              @endforeach
          ],
          backgroundColor: [
              'rgb(255, 99, 132)',
              'rgb(54, 162, 235)',
              'rgb(255, 205, 86)'
              // Tambahkan warna latar belakang tambahan sesuai dengan jumlah cabang
          ],
          hoverOffset: 4
      }]
  };

  new Chart(pemasukanCabang, {
      type: 'pie',
      data: data,
      options: {
          responsive: true,
          plugins: {
              legend: {
                  position: 'bottom',
              },
          }
      },
  });
</script>

@endpush 
