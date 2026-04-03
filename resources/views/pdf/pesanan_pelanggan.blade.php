<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Form Order Pelanggan</title>
    <style>
        @page { size: A4 landscape; margin: 1.5cm; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; line-height: 1.4; color: #000; }

        h2 { text-align: center; font-size: 16px; margin-bottom: 15px; }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            text-align: center;
            margin-bottom: 10px;
        }

        .logo {
            height: 60px;
        }

        .box { border: 1px solid #333; padding: 8px; margin-bottom: 10px; }
        .title-bar { background: #911f27; color: #fff; padding: 6px 10px; font-weight: bold; font-size: 13px; margin-bottom: 8px; text-align: center; }

        .grid-2 { display: table; width: 100%; table-layout: fixed; }
        .grid-2 .col { display: table-cell; vertical-align: top; padding: 6px; }

        table.detail { width: 100%; border-collapse: collapse; font-size: 11px; }
        table.detail td { padding: 3px 5px; }

        .image-box { text-align: center; border: 1px solid #aaa; padding: 6px; }
        .image-box img { max-width: 100%; max-height: 180px; }

        .ukuran-table { width: 100%; border-collapse: collapse; margin-top: 5px; font-size: 10px; }
        .ukuran-table th, .ukuran-table td { border: 1px solid #333; text-align: center; padding: 2px 4px; }

        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 10px;
            border-top: 1px solid #999;
            padding-top: 10px;
            line-height: 1.6;
        }
    </style>
</head>
<body>

    <div class="header">
        <img src="{{ public_path('storage/logo/logo-panjang.png') }}" alt="Logo" class="logo">
    </div>

    <h2>FORM ORDER PELANGGAN</h2>

    @foreach ($pesanans as $pesanan)
    <div class="box">
        <div class="title-bar">{{ strtoupper($pesanan->nama_pesanan) }}</div>

        <div class="grid-2">
            <div class="col image-box">
                @php
                    $file = $pesanan->detailPesanan->first()?->file_desain;
                    $filePath = $file ? public_path('storage/'.$file) : null;
                @endphp

                @if ($file && file_exists($filePath))
                    <img src="{{ $filePath }}" alt="Desain">
                @else
                    <span>FILE DESAIN TIDAK TERSEDIA</span>
                @endif
            </div>
            <div class="col">
                <table class="detail">
                    <tr><td><strong>Pelanggan</strong></td><td>: {{ $pesanan->pelanggan->nama }}</td></tr>
                    <tr><td><strong>Tanggal Masuk</strong></td><td>: {{ \Carbon\Carbon::parse($pesanan->tanggal_pesanan)->format('d M Y') }}</td></tr>
                    <tr><td><strong>Deadline</strong></td><td>: {{ \Carbon\Carbon::parse($pesanan->deadline)->format('d M Y') }}</td></tr>
                    <tr><td><strong>Status</strong></td><td>: {{ ucfirst($pesanan->status) }}</td></tr>
                    <tr><td><strong>Catatan</strong></td><td>: {{ $pesanan->catatan ?? '-' }}</td></tr>
                </table>
            </div>
        </div>

        @php $detail = $pesanan->detailPesanan->first(); @endphp
        <div style="margin: 10px 6px 0; font-size: 11px;">
            <strong>Tipe Lengan:</strong> {{ ucfirst($detail?->tipe_lengan ?? '-') }}
        </div>

        <div class="grid-2" style="margin-top: 8px;">
            <div class="col">
                <table class="ukuran-table">
                    <thead>
                        <tr>
                            <th>XS</th><th>S</th><th>M</th><th>L</th><th>XL</th>
                            <th>XXL</th><th>XXXL</th><th>XXXXL</th><th>XXXXXL</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>{{ $detail->jumlah_ukuran['xs'] ?? 0 }}</td>
                            <td>{{ $detail->jumlah_ukuran['s'] ?? 0 }}</td>
                            <td>{{ $detail->jumlah_ukuran['m'] ?? 0 }}</td>
                            <td>{{ $detail->jumlah_ukuran['l'] ?? 0 }}</td>
                            <td>{{ $detail->jumlah_ukuran['xl'] ?? 0 }}</td>
                            <td>{{ $detail->jumlah_ukuran['xxl'] ?? 0 }}</td>
                            <td>{{ $detail->jumlah_ukuran['xxxl'] ?? 0 }}</td>
                            <td>{{ $detail->jumlah_ukuran['xxxxl'] ?? 0 }}</td>
                            <td>{{ $detail->jumlah_ukuran['xxxxxl'] ?? 0 }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="col">
                <table class="detail">
                    <tr><td><strong>Produk</strong></td><td>: {{ $detail->produk->nama_produk ?? '-' }}</td></tr>
                    <tr><td><strong>Katalog</strong></td><td>: {{ $detail->katalog ?? '-' }}</td></tr>
                    <tr><td><strong>Material</strong></td><td>: {{ $detail->material ?? '-' }}</td></tr>
                    <tr><td><strong>Warna</strong></td><td>: {{ $detail->warna ?? '-' }}</td></tr>
                </table>
            </div>
        </div>
    </div>
    @endforeach

    <div class="footer">
        <img src="{{ public_path('storage/logo/footer.png') }}" alt="Logo" class="logo">
    </div>

</body>
</html>
