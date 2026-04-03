<?php

use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Invoice;
use App\Models\Produk;
use Illuminate\Support\Carbon;

$names = [
    'Budi Santoso', 'Siti Aminah', 'Agus Prayitno', 'Dewi Lestari', 'Eko Saputra',
    'Rini Handayani', 'Andi Wijaya', 'Ani Suryani', 'Herry Kurniawan', 'Dian Pratama'
];

$addresses = [
    'Jl. Malioboro No. 12, Yogyakarta', 'Jl. Sudirman No. 45, Jakarta', 'Jl. Merdeka No. 8, Bandung',
    'Jl. Diponegoro No. 22, Surabaya', 'Jl. Gajah Mada No. 5, Semarang', 'Jl. Ahmad Yani No. 102, Solo',
    'Jl. Imam Bonjol No. 34, Medan', 'Jl. Veteran No. 7, Makassar', 'Jl. Kartini No. 15, Malang',
    'Jl. Pahlawan No. 56, Palembang'
];

$products = Produk::all();
if ($products->isEmpty()) {
    echo "No products found!\n";
    exit;
}

foreach ($names as $i => $name) {
    // 1. Create Pelanggan
    $pelanggan = Pelanggan::create([
        'nama' => $name,
        'no_hp' => '08' . rand(111111111, 999999999),
        'alamat' => $addresses[$i],
        'created_at' => Carbon::now()->subDays(rand(1, 20)),
    ]);

    // 2. Create Pesanan
    $pesanan = Pesanan::create([
        'pelanggan_id' => $pelanggan->id_pelanggan,
        'nama_pesanan' => 'Pemesanan ' . $products->random()->nama_produk,
        'status' => 'proses',
        'deadline' => Carbon::now()->addDays(rand(5, 14)),
    ]);

    // 3. Create DetailPesanan
    $product = $products->random();
    $qty = rand(10, 50);
    $detail = DetailPesanan::create([
        'pesanan_id' => $pesanan->id_pesanan,
        'produk_id' => $product->id_produk,
        'jumlah' => $qty,
        'ukuran' => 'L',
        'warna' => 'Hitam',
        'catatan' => 'Dummy data',
        'jumlah_total' => $qty,
    ]);

    // 4. Create Invoice
    $status = (rand(0, 1) == 1) ? 'lunas' : 'dp';
    $total_tagihan = $qty * $product->harga;
    $jumlah_dibayar = ($status == 'lunas') ? $total_tagihan : ($total_tagihan * 0.5);

    Invoice::create([
        'pesanan_id' => $pesanan->id_pesanan,
        'pelanggan_id' => $pelanggan->id_pelanggan,
        'produk_id' => $product->id_produk,
        'tanggal_invoice' => Carbon::now()->subDays(rand(0, 10)),
        'biaya_tambahan' => 0,
        'diskon' => 0,
        'jumlah_dibayar' => $jumlah_dibayar,
        'status_pembayaran' => $status,
        'metode_pembayaran' => 'Transfer',
    ]);
}

echo "Successfully generated 10 customers with matching orders/invoices!\n";
