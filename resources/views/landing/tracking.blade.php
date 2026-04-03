<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cek Status Pesanan</title>
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background:rgb(0, 0, 0);
            color: #fff;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 40px 20px;
            min-height: 100vh;
        }

        h1 {
            margin-bottom: 10px;
            color:rgb(255, 255, 255);
        }

        p {
            margin-bottom: 30px;
            font-size: 14px;
            color:rgb(255, 255, 255);
        }

        form {
            margin-bottom: 30px;
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
            max-width: 400px;
        }

        input[type="text"] {
            padding: 12px;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            background-color: #fff;
            color: #000;
        }

        button {
            padding: 12px;
            background-color: #b22222;
            color: white;
            border: none;
            border-radius: 8px;
            font-weight: bold;
            cursor: pointer;
        }

        .card {
            background-color:rgb(255, 255, 255);
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,0.4);
            width: 100%;
            max-width: 500px;
        }

        .card h2 {
            color:rgb(0, 0, 0);
            margin-bottom: 20px;
        }

        .item {
            margin-bottom: 15px;
            border-bottom: 1px solid rgb(190, 190, 190);
            padding-bottom: 10px;
        }

        .item label {
            font-weight: bold;
            display: block;
            color:rgb(0, 0, 0);
        }

        .item span {
            color: #000;
        }

        .not-found {
            margin-top: 20px;
            background: #8b0000;
            color: white;
            padding: 12px;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <h1>Cek Status Pesanan</h1>
    <p>Masukkan ID Tracking untuk melihat status pesanan Anda.</p>

    <form method="GET" action="{{ url('/tracking') }}">
        <input type="text" name="search" placeholder="Contoh: TRK-00123" value="{{ request('search') }}" required>
        <button type="submit">Cek Status</button>
    </form>

    @if(request('search'))
        @if($trackings->count())
            @foreach($trackings as $tracking)
                <div class="card">
                    <h2>Status Pesanan</h2>

                    <div class="item">
                        <label>ID Tracking:</label>
                        <span>{{ $tracking->id_tracking }}</span>
                    </div>

                    <div class="item">
                        <label>Nama Pesanan:</label>
                        <span>{{ $tracking->pesanan->nama_pesanan ?? '-' }}</span>
                    </div>

                    <div class="item">
                        <label>Tanggal Masuk:</label>
                        <span>{{ \Carbon\Carbon::parse($tracking->tanggal)->translatedFormat('d F Y') }}</span>
                    </div>

                   <div class="item" style="text-align: center; border: none; padding-top: 20px;">
    <label style="font-size: 16px; color:rgb(0, 0, 0); margin-bottom: 8px; display: block;">Status</label>
    <span style="
        display: inline-block;
        padding: 10px 20px;
        background-color:#740503;
        color: white;
        border-radius: 5px;
        font-weight: bold;
        font-size: 20px;
        text-transform: uppercase;
        letter-spacing: 1px;
    ">
        {{ strtoupper($tracking->status) }}
    </span>
</div>


                </div>
            @endforeach
        @else
            <div class="not-found">
                Tracking ID tidak ditemukan atau belum tersedia.
            </div>
        @endif
    @endif
</body>
</html>
