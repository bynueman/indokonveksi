<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Pelanggan;
use App\Models\Produk;
use App\Models\Pesanan;
use App\Models\DetailPesanan;

class Invoice extends Model
{
    protected $table = 'invoice';
    protected $primaryKey = 'id_invoice';
    public $incrementing = false;
    public $timestamps = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id_invoice',
        'pesanan_id',
        'produk_id',
        'pelanggan_id',
        'tanggal_invoice',
        'npwp',
        'biaya_tambahan',
        'diskon',
        'jumlah_bayar',
        'jumlah_dibayar',
        'total_tagihan',
        'kurang',
        'status_pembayaran',
        'metode_pembayaran',
        'keterangan',
    ];

    protected static function boot()
{
    parent::boot();

    static::creating(function ($model) {
        $model->tanggal_invoice = now();
    });

    static::creating(function ($invoice) {
        // Generate kode invoice otomatis
        $last = self::orderBy('id_invoice', 'desc')->first();
        $number = $last ? ((int) substr($last->id_invoice, 3)) + 1 : 1;
        $invoice->id_invoice = 'INV' . str_pad($number, 3, '0', STR_PAD_LEFT);
    });

    static::saving(function ($invoice) {
        // Auto isi pelanggan_id jika kosong
        if (!$invoice->pelanggan_id && $invoice->pesanan_id) {
            $invoice->pelanggan_id = Pesanan::find($invoice->pesanan_id)?->pelanggan_id;
        }

        // Auto isi produk_id jika kosong dari detail pesanan pertama
        if (!$invoice->produk_id && $invoice->pesanan_id) {
            $invoice->produk_id = DetailPesanan::where('pesanan_id', $invoice->pesanan_id)
                ->first()?->produk_id;
        }

        // Hitung jumlah total pesanan dari detailPesanan
        $jumlah = DetailPesanan::where('pesanan_id', $invoice->pesanan_id)->sum('jumlah_total');
        $harga = (float) Produk::find($invoice->produk_id)?->harga ?? 0;

        $invoice->jumlah_bayar = $jumlah * $harga;

        // Pastikan semua angka dikonversi ke float untuk mencegah error operand types
        $biayaTambahan = (float) ($invoice->biaya_tambahan ?? 0);
        $diskon = (float) ($invoice->diskon ?? 0);
        $jumlahDibayar = (float) ($invoice->jumlah_dibayar ?? 0);

        // Hitung total tagihan
        $invoice->total_tagihan = $invoice->jumlah_bayar + $biayaTambahan - $diskon;

        // Cegah nilai negatif
        if ($invoice->total_tagihan < 0) {
            $invoice->total_tagihan = 0;
        }

        if ($invoice->status_pembayaran === 'lunas') {
            if (empty($invoice->jumlah_dibayar) || $invoice->jumlah_dibayar < $invoice->total_tagihan) {
                $invoice->jumlah_dibayar = $invoice->total_tagihan;
            }
            $invoice->kurang = 0;
        } elseif ($invoice->status_pembayaran === 'dp') {
            $invoice->kurang = max(0, $invoice->total_tagihan - $jumlahDibayar);
        } else {
            $invoice->kurang = 0;
        }

        // Pastikan tidak ada nilai negatif
        if ($invoice->kurang < 0) {
            $invoice->kurang = 0;
        }
    });
}

    // RELATIONSHIPS
    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id');
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id', 'pesanan_id');
    }
}
