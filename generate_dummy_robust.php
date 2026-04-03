<?php

use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Invoice;
use App\Models\Produk;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

$names = [
    'Siti Aminah', 'Agus Prayitno', 'Dewi Lestari', 'Eko Saputra',
    'Rini Handayani', 'Andi Wijaya', 'Ani Suryani', 'Herry Kurniawan', 
    'Dian Pratama', 'Joko Widodo'
];

$addresses = [
    'Jl. Sudirman No. 45, Jakarta', 'Jl. Merdeka No. 8, Bandung',
    'Jl. Diponegoro No. 22, Surabaya', 'Jl. Gajah Mada No. 5, Semarang', 
    'Jl. Ahmad Yani No. 102, Solo', 'Jl. Imam Bonjol No. 34, Medan', 
    'Jl. Veteran No. 7, Makassar', 'Jl. Kartini No. 15, Malang',
    'Jl. Pahlawan No. 56, Palembang', 'Jl. Slamet Riyadi No. 88, Solo'
];

$products = Produk::all();
if ($products->isEmpty()) {
    echo "No products found!\n";
    exit;
}

foreach ($names as $i => $name) {
    try {
        DB::transaction(function () use ($name, $addresses, $i, $products) {
            // 1. Create Pelanggan
            $pelanggan = Pelanggan::create([
                'nama' => $name,
                'no_hp' => '08' . rand(111111111, 999999999),
                'alamat' => $addresses[$i],
            ]);
            // Manually force created_at if it was overwritten to now() by boot
            $pelanggan->update(['created_at' => Carbon::now()->subDays(rand(1, 20))]);

            // 2. Create Pesanan
            $product = $products->random();
            $pesanan = Pesanan::create([
                'pelanggan_id' => $pelanggan->id_pelanggan,
                'nama_pesanan' => 'Pemesanan ' . $product->nama_produk,
                'status' => 'proses',
                'deadline' => Carbon::now()->addDays(rand(5, 14)),
            ]);
            // Force date
            $pesanan->update(['tanggal_pesanan' => Carbon::now()->subDays(rand(0, 10))]);

            // 3. Create DetailPesanan
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
            // total_tagihan will be calculated in Invoice saving boot
            // We just need to trigger it
            $invoice = new Invoice();
            $invoice->pesanan_id = $pesanan->id_pesanan;
            $invoice->status_pembayaran = $status;
            $invoice->metode_pembayaran = 'Transfer';
            $invoice->jumlah_dibayar = 0; // Will be set next
            $invoice->save();

            // Refetch to get calculated total_tagihan from model saving logic
            $invoice = Invoice::find($invoice->id_invoice);
            $total_tagihan = $invoice->total_tagihan;
            $jumlah_dibayar = ($status == 'lunas') ? $total_tagihan : ($total_tagihan * 0.5);
            
            $invoice->update([
                'jumlah_dibayar' => $jumlah_dibayar,
                'tanggal_invoice' => Carbon::now()->subDays(rand(0, 5)),
            ]);
        });
        echo "Created customer: $name\n";
    } catch (\Exception $e) {
        echo "Error creating customer $name: " . $e->getMessage() . "\n";
    }
}

echo "Done!\n";
