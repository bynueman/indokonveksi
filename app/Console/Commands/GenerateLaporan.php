<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Invoice;
use App\Models\Laporan;
use Illuminate\Support\Str;
use Carbon\Carbon;

class GenerateLaporan extends Command
{
    protected $signature = 'laporan:generate {--bulan=} {--tahun=}';

    protected $description = 'Generate laporan omzet, laba, produk terlaris, dan status pesanan';

    public function handle()
    {
        $bulan = $this->option('bulan') ?? date('m');
        $tahun = $this->option('tahun') ?? date('Y');

        $this->info("Generate laporan untuk bulan: $bulan, tahun: $tahun");

        // 1. Omzet Bulanan
        $totalOmzet = Invoice::whereYear('tanggal_invoice', $tahun)
            ->whereMonth('tanggal_invoice', $bulan)
            ->where('status_pembayaran', 'lunas')
            ->sum('jumlah_dibayar');

        Laporan::updateOrCreate(
            [
                'jenis_laporan' => 'omzet',
                'bulan' => $bulan,
                'tahun' => $tahun,
            ],
            [
                'id_laporan' => Str::uuid(),
                'nilai_angka' => $totalOmzet,
                'deskripsi' => 'Total omzet bulan ' . $bulan . '/' . $tahun,
            ]
        );

        $this->info("Laporan omzet selesai, total: $totalOmzet");

        // TODO: tambahkan logika generate laba bersih, produk terlaris, status pesanan di sini

        return 0;
    }
}
