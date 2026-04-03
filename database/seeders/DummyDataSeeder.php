<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use App\Models\DetailPesanan;
use App\Models\Invoice;
use App\Models\Produk;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class DummyDataSeeder extends Seeder
{
    public function run(): void
    {
        $names = [
            'Bambang Pamungkas', 'Siti Maimunah', 'Agus Prayogo', 'Dewi Sartika', 'Eko Prasetyo',
            'Rini Rahmayanti', 'Andi Mallarangeng', 'Ani Yudhoyono', 'Herry Zudianto', 'Dian Sastrowardoyo'
        ];

        $addresses = [
            'Jl. Kaliurang KM 5, Yogyakarta', 'Jl. Thamrin No. 1, Jakarta', 'Jl. Asia Afrika, Bandung',
            'Jl. Darmo No. 10, Surabaya', 'Jl. Pemuda No. 2, Semarang', 'Jl. Slamet Riyadi, Solo',
            'Jl. Gatot Subroto, Medan', 'Jl. Pettarani, Makassar', 'Jl. Ijen No. 15, Malang',
            'Jl. Sudirman, Palembang'
        ];

        $products = Produk::all();
        if ($products->isEmpty()) {
            Produk::create(['id_produk' => 'PRD001', 'nama_produk' => 'Kaos Polos', 'harga' => 50000]);
            Produk::create(['id_produk' => 'PRD002', 'nama_produk' => 'Hoodie Premium', 'harga' => 150000]);
            $products = Produk::all();
        }

        foreach ($names as $i => $name) {
            DB::transaction(function () use ($name, $addresses, $i, $products) {
                // 1. Create Pelanggan
                $pelanggan = Pelanggan::create([
                    'nama' => $name,
                    'no_hp' => '08' . rand(111111111, 999999999),
                    'alamat' => $addresses[$i],
                ]);
                $pelanggan->update(['created_at' => Carbon::now()->subDays(rand(1, 25))]);

                // 2. Create Pesanan
                $product = $products->random();
                $pesanan = Pesanan::create([
                    'pelanggan_id' => $pelanggan->id_pelanggan,
                    'nama_pesanan' => 'Project ' . $product->nama_produk . ' ' . $name,
                    'status' => 'selesai',
                    'deadline' => Carbon::now()->addDays(rand(5, 14)),
                ]);
                $pesanan->update(['tanggal_pesanan' => Carbon::now()->subDays(rand(10, 20))]);

                // 3. Create DetailPesanan
                $qty_m = rand(5, 25);
                $qty_l = rand(5, 25);
                DetailPesanan::create([
                    'pesanan_id' => $pesanan->id_pesanan,
                    'produk_id' => $product->id_produk,
                    'katalog' => 'Custom',
                    'material' => 'Cotton Combed',
                    'warna' => 'Hitam',
                    'tipe_lengan' => 'Pendek',
                    'jumlah_ukuran' => ['M' => $qty_m, 'L' => $qty_l],
                ]);

                // 4. Create Invoice
                $status = ($i % 2 == 0) ? 'lunas' : 'dp';
                $invoice = new Invoice();
                $invoice->pesanan_id = $pesanan->id_pesanan;
                $invoice->status_pembayaran = $status;
                $invoice->metode_pembayaran = 'Cash';
                $invoice->jumlah_dibayar = 0;
                $invoice->save();

                // Get calculated values
                $invoice = Invoice::find($invoice->id_invoice);
                $total_tagihan = $invoice->total_tagihan;
                $jumlah_dibayar = ($status == 'lunas') ? $total_tagihan : ($total_tagihan * 0.4);

                $invoice->update([
                    'jumlah_dibayar' => $jumlah_dibayar,
                    'tanggal_invoice' => Carbon::now()->subDays(rand(1, 15)),
                ]);
            });
        }
    }
}
