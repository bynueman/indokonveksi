<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Omzet</title>
    <style>
        @page {
            size: A4;
            margin: 20mm;
        }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 12px;
            line-height: 1.5;
        }
        h1, h3 {
            text-align: center;
            margin: 10px 0;
        }
        p {
            text-align: center;
            margin: 0 0 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 11px;
            margin-top: 10px;
        }
        th, td {
            padding: 6px;
            border: 1px solid #333;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
        .summary {
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <h1>Laporan Omzet</h1>
    <p>Periode: {{ date('d M Y', strtotime($tanggalMulai)) }} - {{ date('d M Y', strtotime($tanggalSelesai)) }}</p>

    <table>
        <thead>
            <tr>
                <th style="width: 5%;">No</th>
                <th style="width: 12%;">ID Pesanan</th>
                <th style="width: 18%;">Nama Pesanan</th>
                <th style="width: 18%;">Nama Pelanggan</th>
                <th style="width: 10%;">Jumlah Order</th>
                <th style="width: 12%;">Tagihan</th>
                <th style="width: 12%;">Dibayar</th>
                <th style="width: 10%;">Kurang</th>
                <th style="width: 10%;">Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($pesananList as $index => $pesanan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $pesanan->id_pesanan }}</td>
                <td>{{ $pesanan->nama_pesanan }}</td>
                <td>{{ $pesanan->nama_pelanggan }}</td>
                <td>{{ $pesanan->jumlah_order }}</td>
                <td>Rp{{ number_format($pesanan->total_tagihan, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($pesanan->jumlah_dibayar, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($pesanan->total_tagihan - $pesanan->jumlah_dibayar, 0, ',', '.') }}</td>
                <td>{{ ucfirst($pesanan->status_pembayaran) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="summary">
        <h3>Total Omzet: Rp{{ number_format($omzet ?? 0, 0, ',', '.') }}</h3>
        <h3>Total Kurang: Rp{{ number_format($totalKurang ?? 0, 0, ',', '.') }}</h3>
    </div>
</body>
</html>
