<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Terpilih</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        img { max-width: 100px; max-height: 100px; margin-top: 5px; }
    </style>
</head>
<body>
    <h2>Daftar Pesanan Terpilih</h2>
    @foreach ($pesanans as $pesanan)
        <h3>{{ $pesanan->nama_pesanan }} ({{ $pesanan->pelanggan->nama }})</h3>
        <p><strong>Tanggal:</strong> {{ $pesanan->tanggal_pesanan }}</p>
        <p><strong>Deadline:</strong> {{ $pesanan->deadline }}</p>
        <p><strong>Status:</strong> {{ $pesanan->status }}</p>

        <table>
            <thead>
                <tr>
                    <th>Produk</th>
                    <th>Ukuran</th>
                    <th>Material</th>
                    <th>Warna</th>
                    <th>File Desain</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pesanan->detailPesanan as $detail)
                    <tr>
                        <td>{{ $detail->produk->nama_produk ?? '-' }}</td>
                        <td>
                            @if ($detail->ukuran_pendek)
                                <div><strong>Pendek:</strong> {{ $detail->ukuran_pendek }}</div>
                            @endif

                            @if ($detail->ukuran_panjang)
                                <div><strong>Panjang:</strong> {{ $detail->ukuran_panjang }}</div>
                            @endif
                        </td>

                        <td>{{ $detail->material }}</td>
                        <td>{{ $detail->warna }}</td>
                        <td>
                            @if ($detail->file_desain)
                                <img src="{{ public_path('storage/' . $detail->file_desain) }}" alt="File Desain">
                            @else
                                Tidak ada
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <hr>
    @endforeach
</body>
</html>
