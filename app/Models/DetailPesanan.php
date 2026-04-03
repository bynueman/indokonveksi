<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailPesanan extends Model
{
    protected $table = 'detail_pesanan';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'pesanan_id',
        'produk_id',
        'katalog',
        'material',
        'warna',
        'tipe_lengan',
        'jumlah_ukuran',
        'jumlah_total',
        'file_desain',
    ];

    protected $casts = [
        'jumlah_ukuran' => 'array',
    ];

    /**
     * Relasi ke model Pesanan
     */
    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id_pesanan');
    }

    /**
     * Relasi ke model Produk
     */
    public function produk()
    {
        return $this->belongsTo(Produk::class, 'produk_id');
    }
    
public function getFileDesainPathAttribute()
{
    return $this->file_desain
        ? storage_path('app/public/desain/' . $this->file_desain)
        : null;
}

    /**
     * Auto hitung jumlah_total sebelum menyimpan
     */
    protected static function booted()
    {
        static::saving(function ($detail) {
            $detail->jumlah_total = collect($detail->jumlah_ukuran)
                ->filter(fn ($v) => is_numeric($v)) // pastikan hanya angka
                ->sum();
        });
    }
}
