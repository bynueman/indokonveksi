<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Laba Bersih</title>
    <style>
        @page { size: A4; margin: 20mm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            line-height: 1.4;
            text-align: center;
        }
        h1, h3 { margin: 10px 0; }
        p { margin: 0 0 20px; }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10px;
        }
        th, td {
            padding: 5px;
            border: 1px solid #000;
            text-align: center;
            vertical-align: middle;
        }
        th {
            background-color: #f0f0f0;
        }
        .summary {
            margin-top: 20px;
            text-align: right;
        }
    </style>
</head>
<body>
    <h1>Laporan Laba Bersih</h1>
    <p>Periode: {{ date('d M Y', strtotime($tanggalMulai)) }} - {{ date('d M Y', strtotime($tanggalSelesai)) }}</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama Pesanan</th>
                <th>Nama Produk</th>
                <th>Jumlah</th>
                <th>Tagihan</th>
                <th>Dibayar</th>
                <th>Kurang</th>
                <th>Laba Kotor</th>
                <th>Laba Bersih</th>
                <th>Biaya Tambahan</th>
                <th>Diskon</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totalPendapatan = 0;
                $totalLabaKotor = 0;
                $totalLabaBersih = 0;
                $totalBiayaDiskon = 0;
            @endphp
            @foreach($pesananList as $i => $item)
                @php
                    $dibayar = $item->jumlah_dibayar ?? 0;
                    $labaKotor = $dibayar;
                    $biayaProduksi = $item->jumlah_total * (($item->biaya_produksi ?? 0) + ($item->harga_bahan_baku ?? 0));
                    $labaBersih = $labaKotor - $biayaProduksi;

                    $totalPendapatan += $dibayar;
                    $totalLabaKotor += $labaKotor;
                    $totalLabaBersih += $labaBersih;
                    $totalBiayaDiskon += ($item->biaya_tambahan ?? 0) - ($item->diskon ?? 0);
                @endphp
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->nama_pesanan }}</td>
                    <td>{{ $item->nama_produk }}</td>
                    <td>{{ $item->jumlah_total }}</td>
                    <td>Rp{{ number_format($item->total_tagihan ?? 0, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($dibayar, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format(($item->total_tagihan ?? 0) - $dibayar, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($labaKotor, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($labaBersih, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->biaya_tambahan ?? 0, 0, ',', '.') }}</td>
                    <td>Rp{{ number_format($item->diskon ?? 0, 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Total Pendapatan: Rp{{ number_format($totalPendapatan, 0, ',', '.') }}</h3>
        <h3>Total Laba Kotor: Rp{{ number_format($totalLabaKotor, 0, ',', '.') }}</h3>
        <h3>Total Laba Bersih: Rp{{ number_format($totalLabaBersih, 0, ',', '.') }}</h3>
        <h3>Total Biaya Tambahan: Rp{{ number_format($totalBiayaDiskon, 0, ',', '.') }}</h3>
    </div>
</body>
</html>
