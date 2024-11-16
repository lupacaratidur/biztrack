@extends('layouts.app')

@section('content')
  <section class="section">
    <div class="section-header">
      <h1>Tren Penjualan Produk</h1>
    </div>

    <div class="section-body">
      <!-- Date Filter Form -->
      <form action="{{ route('tren-penjualan') }}" method="GET" class="mb-4">
        <div class="row">
          <div class="col-md-4">
            <div class="form-group">
              <label for="start_date">Tanggal Mulai</label>
              <input type="date" name="start_date" id="start_date" class="form-control" value="{{ request('start_date') }}">
            </div>
          </div>
          <div class="col-md-4">
            <div class="form-group">
              <label for="end_date">Tanggal Akhir</label>
              <input type="date" name="end_date" id="end_date" class="form-control" value="{{ request('end_date') }}">
            </div>
          </div>
          <div class="col-md-4 d-flex align-items-end">
            <button type="submit" class="btn btn-primary">Filter</button>
          </div>
        </div>
      </form>

      <!-- Product Sales Trend Chart -->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header">
              <h4>Grafik Tren Penjualan Produk</h4>
            </div>
            <div class="card-body">
              <canvas id="grafikTrenProduk"></canvas>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>
@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Product Sales Trend Chart Script -->
<script>
  const trendProdukCtx = document.getElementById('grafikTrenProduk');

  // Define an array of colors for each branch
  const colors = [
    'rgba(255, 99, 132, 1)',   // Red
    'rgba(54, 162, 235, 1)',   // Blue
    'rgba(255, 205, 86, 1)',   // Yellow
    'rgba(75, 192, 192, 1)',   // Green
    'rgba(153, 102, 255, 1)',  // Purple
    'rgba(255, 159, 64, 1)',   // Orange
    'rgba(199, 199, 199, 1)'   // Gray
  ];

  const trendProdukData = {
    labels: [
      @foreach($grafikTrenProduk as $productName => $branches)
        '{{ $productName }}',
      @endforeach
    ],
    datasets: [
      @foreach($cabangNames as $cabangId => $cabangName)
        {
          label: 'Cabang {{ $cabangName }}',
          data: [
            @foreach($grafikTrenProduk as $productName => $branches)
              @php
                $branchData = $branches->firstWhere('cabang_id', $cabangId);
              @endphp
              {{ $branchData ? $branchData->total_sold : 0 }},
            @endforeach
          ],
          fill: false,
          borderColor: colors[{{ $loop->index }} % colors.length],
          backgroundColor: colors[{{ $loop->index }} % colors.length],
          tension: 0.1
        },
      @endforeach
    ]
  };

  new Chart(trendProdukCtx, {
    type: 'line',
    data: trendProdukData,
    options: {
      responsive: true,
      scales: {
        y: {
          beginAtZero: true,
          title: {
            display: true,
            text: 'Jumlah Terjual'
          }
        },
        x: {
          title: {
            display: true,
            text: 'Nama Produk'
          }
        }
      },
      plugins: {
        tooltip: {
          callbacks: {
            label: function(context) {
              const branchName = context.dataset.label;
              const productName = context.label;
              const totalSold = context.raw;
              return `${productName} - ${branchName}: ${totalSold} terjual`;
            }
          }
        },
        legend: {
          position: 'top',
        },
      }
    }
  });
</script>
@endpush
