<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
    <style>
        @page { size: A4 landscape; margin: 1.2cm; }
        body {
            font-family: DejaVu Sans, sans-serif;
            font-size: 11px;
            color: #000;
        }

        h3 {
            text-align: center;
            margin-top: 0;
        }

        .header-table td {
            vertical-align: top;
            padding: 2px 4px;
        }

        .main-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .main-table th, .main-table td {
            border: 1px solid #000;
            padding: 4px 6px;
            text-align: center;
        }

        .amount-table {
            width: 280px;
            float: right;
            margin-top: 10px;
            font-size: 11px;
        }

        .amount-table td {
            padding: 2px 6px;
        }

        .notes {
            font-size: 10px;
            margin-top: 60px;
        }

        .signature-table {
            width: 100%;
            margin-top: 50px;
            text-align: center;
        }

        .logo {
            height: 60px;
        }
    </style>
</head>
<body>

    <table width="100%" class="header-table">
    <tr>
        <td width="50%">
            <img src="{{ public_path('storage/logo/logo-kotak.png') }}" class="logo" alt="Logo"><br>
            <small>
                Jl. Jati Pratama No 245, Sindudad, Mlati, Sleman - YK<br>
                Telp : 085 881 211 266 | 085 787 026 797<br>
                Email : marketing.indokonveksi@gmail.com<br>
                Web : www.indokonveksi.id
            </small>
        </td>
        <td style="text-align: right; padding-top: 30px;">
            <table>
                <tr><td><strong>Pelanggan</strong></td><td>: {{ $invoice->pelanggan->nama }}</td></tr>
                <tr><td><strong>Alamat</strong></td><td>: {{ $invoice->pelanggan->alamat }}</td></tr>
                <tr><td><strong>Telp</strong></td><td>: {{ $invoice->pelanggan->no_hp }}</td></tr>
                <tr><td><strong>Nama Pesanan</strong></td><td>: {{ $invoice->pesanan->nama_pesanan }}</td></tr>
            </table>
        </td>
    </tr>
</table>


    <table width="100%" style="margin-top: 5px;">
        <tr>
            <td><strong>NPWP:</strong></td><td>{{ $invoice->npwp ?? '-' }}</td>
            <td style="text-align: right;" rowspan="2">
                Pembayaran Via Transfer<br>
                No. BCA : 2399200193<br>
                A/n : CV INDO JAYA GROUP
            </td>
        </tr>
        <tr>
            <td><strong>Tanggal:</strong></td><td>{{ \Carbon\Carbon::parse($invoice->tanggal_invoice)->format('d M Y') }}</td>
        </tr>
    </table>

    <table class="main-table">
        <thead>
            <tr>
                <th>No</th>
                <th>Jenis Pesanan</th>
                <th>Quantity</th>
                <th>Harga</th>
                <th>Biaya Tambahan</th>
                <th>Jumlah</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>1</td>
                <td>{{ $invoice->pesanan->nama_pesanan }}</td>
                <td>{{ $invoice->detailPesanan->sum('jumlah_total') }}</td>
                <td>Rp{{ number_format($invoice->jumlah_bayar, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($invoice->biaya_tambahan ?? 0, 0, ',', '.') }}</td>
                <td>Rp{{ number_format($invoice->total_tagihan, 0, ',', '.') }}</td>
            </tr>
        </tbody>
    </table>

    <table class="amount-table">
        <tr>
            <td>Jumlah</td>
            <td style="text-align: right;">Rp{{ number_format($invoice->jumlah_bayar, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td style="text-align: right;">Rp{{ number_format($invoice->diskon ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td><strong>Total</strong></td>
            <td style="text-align: right;"><strong>Rp{{ number_format($invoice->total_tagihan, 0, ',', '.') }}</strong></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td style="text-align: right;">Rp{{ number_format($invoice->jumlah_dibayar ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td>Kurang</td>
            <td style="text-align: right;">Rp{{ number_format($invoice->kurang ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>

    <div style="clear: both;"></div>

    <div class="notes">
        <strong>Ket:</strong><br>
        1. Nota ini sebagai bukti pembayaran transaksi<br>
        2. Uang diterima secara cash / transfer<br>
        3. Barang yang sudah dipesan tidak dapat ditukar atau dikembalikan<br>
        4. Barang yang sudah jadi wajib untuk langsung dilunasi<br>
        5. Barang yang belum dilunasi dalam waktu 3 hari akan kami simpan di gudang<br>
        6. Kerusakan barang digudang sudah bukan tanggung jawab manajemen INDOKONVEKSI<br>
        7. Menandatangani invoice ini berarti telah setuju dengan point-point di atas
    </div>

    <table class="signature-table">
        <tr>
            <td>Pelanggan</td>
            <td>Manajemen</td>
        </tr>
        <tr><td colspan="2" style="height: 40px;"></td></tr>
    </table>

</body>
</html>
