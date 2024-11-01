
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Kasirresto</title>

  <!-- General CSS Files -->
  <link rel="stylesheet" href="assets/modules/bootstrap/css/bootstrap.min.css">
  <link rel="stylesheet" href="assets/modules/fontawesome/css/all.min.css">

  <!-- Template CSS -->
  <link rel="stylesheet" href="assets/css/style.css">
  <link rel="stylesheet" href="assets/css/components.css">

  <!-- Jquery CDN -->
  <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"></script>
  
  <!-- Datatables Jquery -->
  <link rel="stylesheet" href="//cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">

  <!-- @TODO: replace SET_YOUR_CLIENT_KEY_HERE with your client key -->
  <script type="text/javascript"
  src="https://app.sandbox.midtrans.com/snap/snap.js"
  data-client-key="{{ config('midtrans.client_key') }}"></script>
  <!-- Note: replace with src="https://app.midtrans.com/snap/snap.js" for Production environment -->
</head>

<body>
  <div id="app">
    <div class="main-wrapper main-wrapper-1">
      <div class="navbar-bg"></div>
      <nav class="navbar navbar-expand-lg main-navbar">
        <form class="form-inline mr-auto">
          <ul class="navbar-nav mr-3">
            <li><a href="#" data-toggle="sidebar" class="nav-link nav-link-lg"><i class="fas fa-bars"></i></a></li>
          </ul>
          <div class="search-element">
            <input class="form-control" type="text" placeholder="Cabang : {{ auth()->user()->cabang->cabang }}" data-width="250" disabled>
          </div>
        </form>
        <ul class="navbar-nav navbar-right">
          <li class="dropdown"><a href="#" data-toggle="dropdown" class="nav-link dropdown-toggle nav-link-lg nav-link-user">
            <img alt="image" src="assets/img/avatar/avatar-1.png" class="rounded-circle mr-1">
            <div class="d-sm-none d-lg-inline-block">{{ auth()->user()->role->role }}</div></a>
            <div class="dropdown-menu dropdown-menu-right">
              
              <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="{{ route('logout') }}"
                onclick="event.preventDefault();
                            Swal.fire({
                                title: 'Konfirmasi Keluar',
                                text: 'Apakah Anda yakin ingin keluar?',
                                icon: 'warning',
                                showCancelButton: true,
                                confirmButtonColor: '#3085d6',
                                cancelButtonColor: '#d33',
                                confirmButtonText: 'Ya, Keluar!'
                              }).then((result) => {
                                if (result.isConfirmed) {
                                  document.getElementById('logout-form').submit();
                                }
                              });">
                          <i class="fas fa-sign-out-alt"></i> {{ __('Keluar') }}
                          </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
              </a>
            </div>
          </li>
        </ul>
      </nav>
      <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
          <div class="sidebar-brand">
            <a href="/">KASIRRESTO</a>
          </div>
          <div class="sidebar-brand sidebar-brand-sm">
            <a href="/">KASIR</a>
          </div>
          <ul class="sidebar-menu">
            @if(auth()->user()->role->role === 'administrator')
              <li class="menu-header">Dashboard</li>
              <li><a class="nav-link" href="/"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
              
              <li class="menu-header">Data Master</li>
              <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Menu</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="/makanan"><i class="fa fa-solid fa-circle fa-xs"></i> <span>Makanan</span></a></li>
                  <li><a class="nav-link" href="/minuman"><i class="fa fa-solid fa-circle fa-xs"></i> <span>Minuman</span></a></li>
                </ul>
              </li>
              <li><a class="nav-link" href="/cabang"><i class="fa far fa-code-branch"></i> <span>Cabang</span></a></li>

              <li class="menu-header">Transaksi</li>
              <li><a class="nav-link" href="/menu-kasir"><i class="fa fal fa-credit-card"></i><span>Menu kasir</span></a></li>
              <li><a class="nav-link" href="/data-penjualan"><i class="fa fal fa-money-check-alt"></i><span>Data Penjualan</span></a></li>

              <li class="menu-header">Laporan</li>
              <li><a class="nav-link" href="/laporan-penjualan"><i class="fa fal fa-file-invoice-dollar"></i><span>Laporan Penjualan</span></a></li>
              <li><a class="nav-link" href="/rekap-pemasukan"><i class="fa fal fa-receipt"></i><span>Rekap Pemasukan</span></a></li>

              <li class="menu-header">Manajemen User</li>
              <li><a class="nav-link" href="/pengguna"><i class="fa fal fa-users"></i><span>Pengguna</span></a></li>
              <li><a class="nav-link" href="/hak-akses"><i class="fa fal fa-user-shield"></i><span>Hak Akses</span></a></li>
            @endif

            @if(auth()->user()->role->role === 'kepala restoran')
              <li class="menu-header">Dashboard</li>
              <li><a class="nav-link" href="/"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
              
              <li class="menu-header">Data Master</li>
              <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Menu</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="/makanan"><i class="fa fa-solid fa-circle fa-xs"></i> <span>Makanan</span></a></li>
                  <li><a class="nav-link" href="/minuman"><i class="fa fa-solid fa-circle fa-xs"></i> <span>Minuman</span></a></li>
                </ul>
              </li>

              <li><a class="nav-link" href="/cabang"><i class="fa far fa-code-branch"></i> <span>Cabang</span></a></li>

              <li class="menu-header">Transaksi</li>
              <li><a class="nav-link" href="/data-penjualan"><i class="fa fal fa-money-check-alt"></i><span>Data Penjualan</span></a></li>
            
              <li class="menu-header">Laporan</li>
              <li><a class="nav-link" href="/laporan-penjualan"><i class="fa fal fa-file-invoice-dollar"></i><span>Laporan Penjualan</span></a></li>
              <li><a class="nav-link" href="/rekap-pemasukan"><i class="fa fal fa-receipt"></i><span>Rekap Pemasukan</span></a></li>
            @endif

            @if(auth()->user()->role->role === 'admin')
              <li class="menu-header">Dashboard</li>
              <li><a class="nav-link" href="/"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>
              
              <li class="menu-header">Data Master</li>
              <li class="dropdown">
                <a href="#" class="nav-link has-dropdown" data-toggle="dropdown"><i class="fas fa-columns"></i> <span>Menu</span></a>
                <ul class="dropdown-menu">
                  <li><a class="nav-link" href="/makanan"><i class="fa fa-solid fa-circle fa-xs"></i> <span>Makanan</span></a></li>
                  <li><a class="nav-link" href="/minuman"><i class="fa fa-solid fa-circle fa-xs"></i> <span>Minuman</span></a></li>
                </ul>
              </li>

              <li class="menu-header">Transaksi</li>
              <li><a class="nav-link" href="/data-penjualan"><i class="fa fal fa-money-check-alt"></i><span>Data Penjualan</span></a></li>
            
              <li class="menu-header">Laporan</li>
              <li><a class="nav-link" href="/laporan-penjualan"><i class="fa fal fa-file-invoice-dollar"></i><span>Laporan Penjualan</span></a></li>
              <li><a class="nav-link" href="/rekap-pemasukan"><i class="fa fal fa-receipt"></i><span>Rekap Pemasukan</span></a></li>
            @endif

            @if (auth()->user()->role->role === 'kasir')
              <li class="menu-header">Dashboard</li>
              <li><a class="nav-link" href="/"><i class="fas fa-fire"></i> <span>Dashboard</span></a></li>

              <li class="menu-header">Transaksi</li>
              <li><a class="nav-link" href="/menu-kasir"><i class="fa fal fa-credit-card"></i><span>Menu kasir</span></a></li>
              <li><a class="nav-link" href="/data-penjualan"><i class="fa fal fa-money-check-alt"></i><span>Data Penjualan</span></a></li>
            @endif
          </ul>
        </aside>
      </div>

      <!-- Main Content -->
      <div class="main-content">
          @yield('content')
      </div>
      <footer class="main-footer">
        <div class="footer-left">
          Copyright &copy; 2023 
        </div>
        <div class="footer-right">
          
        </div>
      </footer>
    </div>
  </div>

  <script>
    // Mendapatkan URL dasar halaman saat ini
    var currentPageUrl = window.location.pathname;
  
    // Mendapatkan daftar semua elemen <a> dengan class "nav-link"
    var navLinks = document.querySelectorAll('.nav-link');
  
    // Loop melalui setiap elemen <a> untuk memeriksa URL-nya
    for (var i = 0; i < navLinks.length; i++) {
      var link = navLinks[i];
  
      // Jika URL elemen <a> sama dengan URL dasar halaman saat ini
      if (link.getAttribute('href') === currentPageUrl) {
        // Tambahkan kelas "active" pada elemen <li> terdekat
        link.parentNode.classList.add('active');
        break; // Hentikan perulangan setelah menemukan elemen yang sesuai
      }
    }
  </script>
  
  
  
  <!-- General JS Scripts -->
  <script src="assets/modules/jquery.min.js"></script>
  <script src="assets/modules/popper.js"></script>
  <script src="assets/modules/tooltip.js"></script>
  <script src="assets/modules/bootstrap/js/bootstrap.min.js"></script>
  <script src="assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="assets/modules/moment.min.js"></script>
  <script src="assets/js/stisla.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/dayjs@1/dayjs.min.js"></script>

  <!-- JS Libraries -->
  <script src="//cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

  <!-- Sweet Alert -->
  @include('sweetalert::alert')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
  
  <!-- Template JS File -->
  <script src="assets/js/scripts.js"></script>
  <script src="assets/js/custom.js"></script>

  @stack('script')

</body>
</html>