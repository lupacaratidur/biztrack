<!-- MODAL QR -->
<div class="modal fade" id="qrCodeModal" tabindex="-1" aria-labelledby="qrCodeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="qrCodeModalLabel">Pembayaran QR Code</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body d-flex justify-content-center">
                <!-- Placeholder untuk QR Code -->
                <div id="qrCodeContainer"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" data-bs-dismiss="modal" onclick="updateStatusPembayaranQR()">Selesai</button>
            </div>
        </div>
    </div>
</div>

<!-- QRCode.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- JavaScript untuk generate QR Code dan cek status pembayaran -->
<script>
    $(document).ready(function() {
        // Event ketika modal QR Code ditampilkan
        $('#qrCodeModal').on('show.bs.modal', function (event) {
            var transactionId = document.getElementById('id') ? document.getElementById('id').value : null;

            if (!transactionId) {
                console.log('Transaction ID tidak ditemukan');
                return;
            }

            // Generate URL untuk endpoint pembayaran selesai
            var paymentUrl = "{{ url('/complete-payment') }}/" + transactionId;
            console.log('Payment URL:', paymentUrl);

            // Hapus QR Code lama jika ada
            $("#qrCodeContainer").html('');

            // Generate QR Code dengan URL pembayaran
            new QRCode(document.getElementById("qrCodeContainer"), {
                text: paymentUrl,
                width: 250,
                height: 250
            });
        });
    });

    // Fitur Set Unpaid ke Paid (terbayar)
    function updateStatusPembayaranQR() {
        var pembelianId = document.getElementById('id') ? document.getElementById('id').value : null;
        console.log('Pembelian ID:', pembelianId);

        if (!pembelianId) {
            console.log('ID Pembelian tidak ditemukan!');
            return;
        }

        $.ajax({
            url: '/menu-kasir/paid',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                id: pembelianId
            },
            success: function(response) {
                console.log('Status berhasil diubah menjadi "paid".');
                printStrukqr();  // Panggil fungsi printStruk
                refreshKasir();
            },
            error: function(xhr, status, error) {
                console.log('Error:', error);
            }
        });
    }

    // Fungsi untuk mencetak struk
    function printStrukqr() {
        console.log('Mencetak struk...');

        // Mendapatkan detail Pembelian
        var checkoutItems = document.getElementById('checkoutItems') ? document.getElementById('checkoutItems').innerHTML : '';
        var total = document.getElementById('checkoutTotalPayment') ? document.getElementById('checkoutTotalPayment').innerText : '0';
        var uang_pelanggan = document.getElementById('uang_pelanggan') ? document.getElementById('uang_pelanggan').value : "QR Code Payment";
        var kembalian = document.getElementById('kembalian') ? document.getElementById('kembalian').value : "0";
        var kodePembelian = document.getElementById('kodePembelian') ? document.getElementById('kodePembelian').value : 'N/A';

        // Debugging data yang dikumpulkan
        console.log('Checkout Items:', checkoutItems);
        console.log('Total:', total);
        console.log('Uang Pelanggan:', uang_pelanggan);
        console.log('Kembalian:', kembalian);
        console.log('Kode Pembelian:', kodePembelian);

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
                <p><strong>Metode Pembayaran Qris</strong></p>
                <hr style="border-top: 1px dashed #000;">
                <h5> LUNAS !! </h5>
            </div>
        `;

        // Membuka jendela baru untuk mencetak struk
        var printWindow = window.open('', '_blank', 'height=500, width=500');
        printWindow.document.write(receiptContent);
        printWindow.document.close();
        printWindow.print();
    }
</script>
