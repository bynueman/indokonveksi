<!DOCTYPE html>
<html>
<head>
    <title>Laporan Produk Terlaris</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }
        .content {
            padding: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 10px;
            border: 1px solid #ddd;
        }
        th {
            background-color: #f2f2f2;
        }
    </style>
</head>
<body>
    <div class="content">
        <h1>Laporan Produk Terlaris - {{ $bulan }} / {{ $tahun }}</h1>
        <table>
            <thead>
                <tr>
                    <th>Produk ID</th>
                    <th>Jumlah Terjual</th>
                </tr>
            </thead>
            <tbody>
                @foreach($produkList as $produk)
                <tr>
                    <td>{{ $produk->produk_id }}</td>
                    <td>{{ $produk->jumlah }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html>
