@extends('layouts.app')

@section('content')
<section class="section">
    <div class="section-header">
      <h1>Transaksi Kasir</h1>
      <div class="ml-auto">
        <a href="javascript:void(0)" class="btn btn-primary" id="refresh_btn"></i> Refresh</a>
      </div>
    </div>

    <div class="section-body">
        {{-- Menampilkan Detail Pembelian --}}
        <div class="row">
            <div class="col-lg-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h4>Detail Pembelian</h4>
                </div>
                <div class="card-body">
                  <table class="table">
                    <thead>
                      <tr>
                        <th>Item</th>
                        <th>Jumlah</th>
                        <th>Harga</th>
                      </tr>
                    </thead>
                    <tbody id="checkoutItems">
                    </tbody>
                  </table>
                </div>
                <div class="card-footer">
                  <h6>Sub-Total: Rp. <span id="checkoutTotalDetail"></span></h6>
                </div>
              </div>
            </div>

            {{-- Menampilkan proses pembayaran --}}
            <div class="col-lg-6">
              <div class="card card-primary">
                <div class="card-header">
                  <h4>Pembayaran</h4>
                </div>
                <div class="card-body">
                  <h3>Sub-Total: Rp. <span id="checkoutTotalPayment"></span></h3>
                  <div class="payment-section">
                    <input type="hidden" id="id">

                    <div class="form-group">
                      <label for="uang_pelanggan">Kode Transaksi : </label>
                      <input type="text" class="form-control" id="kodePembelian" disabled>
                    </div>
                    
                    <div class="form-group">
                      <div class="row">
                        <div class="col">
                          <label for="uang_pelanggan">Jumlah Uang : </label>
                          <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">Rp.</span>
                            <input type="number" class="form-control" id="uang_pelanggan">
                          </div>
                        </div>
                        <div class="col">
                          <label for="kembalian">Kembalian : </label>
                          <div class="input-group">
                            <span class="input-group-text" id="basic-addon1">Rp.</span>
                            <input type="number" class="form-control" id="kembalian" disabled>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div id="paymentMessage"></div>
                    <button class="btn btn-primary" onclick="calculateChange()">Bayar</button>

                  </div>
                </div>
              </div>
            </div>

            {{-- Menampilkan List Daftar Produk makanan dan minuman --}}
            <div class="col-lg-8">
                <div class="card card-primary">
                    <div class="card-header">
                      <h4>Daftar Makanan</h4>
                      <div class="card-header-action">
                        <a href="/makanan" class="btn btn-primary">
                          Lihat Semua
                        </a>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="row">
                        @foreach ($makanans as $makanan)
                            <div class="col-md-2">
                                <div class="menu-item" onclick="CartManager.addToCart('{{ $makanan->nama_makanan }}', {{ $makanan->harga }})">
                                    <img src="{{ asset('storage/'. $makanan->gambar) }}" alt="Gambar Makanan" width="100%;" height="auto;">
                                    {{ $makanan->nama_makanan }}
                                </div>
                            </div>
                         @endforeach
                      </div>
                    </div>
                    <hr>
                    <div class="card-header">
                      <h4>Daftar Minuman</h4>
                        <div class="card-header-action">
                          <a href="/minuman" class="btn btn-primary">
                            Lihat Semua
                          </a>
                        </div>
                      </div>
                      <div class="card-body">
                        <div class="row">
                            @foreach ($minumans as $minuman)
                            <div class="col-md-2">
                                <div class="menu-item" onclick="CartManager.addToCart('{{ $minuman->nama_minuman }}', {{ $minuman->harga }})">
                                    <img src="{{ asset('storage/'. $minuman->gambar) }}" alt="Gambar minuman" width="100%;" height="auto;">
                                    {{ $minuman->nama_minuman }}
                                </div>
                            </div>
                         @endforeach
                        </div>
                      </div>
                </div>
            </div>

            {{-- Menampilkan Cart / Keranjang --}}
            <div class="col-lg-4">
                <div class="card card-primary">
                    <div class="card-header">
                      <h4>Keranjang</h4>
                    </div>
                    <div class="card-body">
                        <div id="cartMessage"></div>
                        <ul class="list-group list-group-flush" id="cart">
                        </ul>
                    </div>
                    <div class="card-footer bg-primary d-flex justify-content-between">
                        <div id="total" class="text-white">Total : 0</div>
                        <div class="checkout">
                          <button class="btn btn-warning" onclick="checkout()" type="submit">Lanjutkan</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
  </section>

<script>
  var CartManager = {
    cartItems: [],

    addToCart: function(itemNama, itemHarga) {
      // mengecek apakah item sudah ada di kerajang atau belum
      var existingItem = this.cartItems.find(function(item) {
        return item.nama === itemNama;
      });

      // Apabila di keranjang sudah ada item yang sama, maka tambahkan jumlahnya
      if (existingItem) {
        existingItem.quantity++;
      } else {
        this.cartItems.push({
          nama: itemNama,
          harga: itemHarga,
          quantity: 1
        });
      }
      this.renderCart();
    },

    removeFromCart: function(itemNama) {
      // Mencari tem yang akan di remove/hapus
      var itemIndex = this.cartItems.findIndex(function(item) {
        return item.nama === itemNama;
      });

      // Hapus item dari Cart
      if (itemIndex !== -1) {
        this.cartItems.splice(itemIndex, 1);
      }
      this.renderCart();
    },

    renderCart: function() {
      var cartContainer = document.getElementById('cart');
      cartContainer.innerHTML = '';

      this.cartItems.forEach(function(item) {
        var cartItem = document.createElement('li');
        cartItem.className = 'list-group-item d-flex justify-content-between align-items-center';
        cartItem.innerHTML = `
            <span> <b>${item.nama}</b> <br> Rp. ${item.harga} </span>
    
                <button class="btn btn-sm btn-primary" onclick="CartManager.decreaseQuantity('${item.nama}')">-</button>
                <span class="quantity">${item.quantity}</span>
                <button class="btn btn-sm btn-primary" onclick="CartManager.increaseQuantity('${item.nama}')">+</button>
          
            <button class="btn btn-sm btn-danger" onclick="CartManager.removeFromCart('${item.nama}')"><i class="fa fa-regular fa-times"></i></button>
        `;
        cartContainer.appendChild(cartItem);
      });

      var total = this.cartItems.reduce(function(acc, item) {
        return acc + item.harga * item.quantity;
      }, 0);

      document.getElementById('total').textContent = 'Total: Rp. ' + total;
    },

    increaseQuantity: function(itemNama) {
      var item = this.cartItems.find(function(item) {
        return item.nama === itemNama;
      });

      if (item) {
        item.quantity++;
        this.renderCart();
      }
    },

    decreaseQuantity: function(itemNama) {
      var item = this.cartItems.find(function(item) {
        return item.nama === itemNama;
      });

      if (item && item.quantity > 1) {
        item.quantity--;
        this.renderCart();
      }
    }
  };


    function checkout(){
      // Mendapatkan elemen keranjang
      var cartElement = document.getElementById('cart');
      var cartMessage = document.getElementById('cartMessage');

      // Memeriksa apakah keranjang kosong atau tidak
      if(cartElement.children.length === 0){
        cartMessage.innerHTML = '<div class="alert alert-danger">Pilih Produk Terlebih Dahulu !</div>';
        setTimeout(function() {
          cartMessage.innerHTML = ''; // Menghapus pesan peringatan
        }, 3000);
        return;
      }

      // Mengambil data pembelian dari Cart
      var checkoutItems = CartManager.cartItems.map(function(item){
        return {
          nama: item.nama,
          harga: item.harga,
          quantity: item.quantity
        };
      });

      // Menghitung total_harga
      var total = checkoutItems.reduce(function (acc, item) {
        return acc + item.harga * item.quantity;
      }, 0);

      $.ajax({
          url: '/menu-kasir', 
          method: 'POST',
          data: {
            _token: '{{ csrf_token() }}', 
            pembelian: checkoutItems,
            total_harga: total
          },
          success: function(response) {
              // Menampilkan kode_pembelian dari respons server
              var kodePembelianElement = document.getElementById('kodePembelian');
              if (kodePembelianElement) {
                kodePembelianElement.value = response.kode_pembelian;
              }

              var idElement = document.getElementById('id');
              if (idElement) {
                idElement.value = response.id;
              }
          },
          error: function(xhr, status, error) {
            console.log(error);
          }
      });
        // Menampilkan Data Pembelian di Menu Pembayaran
        var checkoutContainer = document.getElementById('checkoutItems');
            if(checkoutContainer){
              checkoutContainer.innerHTML = '';

              checkoutItems.forEach(function(item){
                var checkoutItemRow = document.createElement('tr');
                checkoutItemRow.innerHTML = `
                <td>${item.nama}</td>
                <td>X ${item.quantity}</td>
                <td>Rp. ${item.harga}</td>
              `;

              checkoutContainer.appendChild(checkoutItemRow);
              });
            }

            var checkoutTotalElement = document.getElementById('checkoutTotalDetail');
            if(checkoutTotalElement){
              checkoutTotalElement.textContent = total;
            }

            var checkoutTotalPaymentElement = document.getElementById('checkoutTotalPayment');
            if (checkoutTotalPaymentElement) {
              checkoutTotalPaymentElement.textContent = total;
            }     
            
      // Membersihkan keranjang setelah pembayaran
      CartManager.cartItems = [];
      CartManager.renderCart();
    }

    // Fitur menghitung Pembayaran
    function calculateChange() {
      var uang_pelanggan = parseFloat(document.getElementById('uang_pelanggan').value);
      var total = parseFloat(document.getElementById('checkoutTotalPayment').innerText);
      var kembalian = uang_pelanggan - total;

      document.getElementById('kembalian').value = kembalian.toFixed();
      var paymentMessage = document.getElementById('paymentMessage');
      // Tampilkan pesan pembayaran sukses
      if (kembalian >= 0) {
        updateStatusPembayaran();
        paymentMessage.innerHTML = '<div class="alert alert-success">Pembayaran Sukses!</div>';
        printStruk();

      } else {
        paymentMessage.innerHTML = '<div class="alert alert-danger">Jumlah uang yang diberikan tidak mencukupi!</div>';
      }
    }

    // Fitur Set Unpaid ke Paid (terbayar)
    function updateStatusPembayaran(){
      var pembelianId = document.getElementById('id').value;
      $.ajax({
        url: '/menu-kasir/paid',
        method: 'POST',
        data:{
          _token: '{{ csrf_token() }}',
          id: pembelianId
        },
        success: function(response) {
        // Tindakan setelah berhasil mengubah status
        console.log('Status berhasil diubah menjadi "paid".');
        },
        error: function(xhr, status, error) {
          console.log(error);
        }
      });
    }
  


    // Fitur Cetak Struk Pembelian
    function printStruk(){
      // Mendapatkan detail Pembelian
      var checkoutItems   = document.getElementById('checkoutItems').innerHTML;
      var total           = document.getElementById('checkoutTotalPayment').innerText;
      var uang_pelanggan  = document.getElementById('uang_pelanggan').value;
      var kembalian       = document.getElementById('kembalian').value;
      var kodePembelian   = document.getElementById('kodePembelian').value;

      // Tampilan Struk
      var receiptContent = `
        <div style="width: 300px; margin: 0 auto; text-align: center; border: 1px solid #000; padding: 10px;">
          <h3>Struk Pembayaran</h3>
          <p><strong>Kode Pembelian:</strong> ${kodePembelian}</p>
          <hr style="border-top: 1px dashed #000;">
          <table style="width: 100%; text-align: left;">
            <tr>
              <th>Item</th>
              <th>Jumlah</th>
              <th>Harga</th>
            </tr>
            ${checkoutItems}
          </table>
          <hr style="border-top: 1px dashed #000;">
          <p><strong>Total:</strong> Rp. ${total}</p>
          <p><strong>Uang Masuk:</strong> Rp. ${uang_pelanggan}</p>
          <p><strong>Kembalian:</strong> Rp. ${kembalian}</p>
          <hr style="border-top: 1px dashed #000;">
          <h5> LUNAS !! </h5>
        </div>
      `;

      var printWindow = window.open('', '_blank', 'height=500, width=500');
      printWindow.document.write(receiptContent);
      printWindow.document.close();
      printWindow.print();
    }

    // Fitur Refresh Menu Kasir
    var refreshButton = document.getElementById('refresh_btn');

    // Menambahkan event listener pada tombol Refresh
    refreshButton.addEventListener('click', function() {
      // Mendapatkan referensi elemen-elemen yang ingin di-refresh
      var formElements          = document.getElementsByTagName('input');
      var tableRows             = document.getElementsByTagName('td');
      var alerts                = document.getElementsByClassName('alert');
      var totalElement          = document.getElementById('checkoutTotalDetail');
      var totalElement2         = document.getElementById('checkoutTotalPayment');
      var kodePembelianElement  = document.getElementById('kodePembelian');

      // Mengosongkan nilai pada setiap elemen input
      for (var i = 0; i < formElements.length; i++) {
        formElements[i].value = '';
      }

      // Menghapus isi pada setiap baris tabel
      for (var j = 0; j < tableRows.length; j++) {
        tableRows[j].innerHTML = '';
      }

      // Menghapus elemen alert
      while (alerts.length > 0) {
        alerts[0].remove();
      }

      // Memperbarui nilai pada elemen total
      if (totalElement && totalElement2 && kodePembelianElement) {
        totalElement.textContent = '0';
        totalElement2.textContent = '0';
        kodePembelianElement.textContent = '';
      } 
    });


</script>


@endsection

