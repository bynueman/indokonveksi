<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Pesanan extends Model
{
    protected $table = 'pesanan';
    protected $primaryKey = 'id_pesanan';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;

    protected $fillable = [
        'id_pesanan', 'pelanggan_id', 'nama_pesanan', 'tanggal_pesanan',
        'deadline', 'status', 'catatan',
    ];

    public static function boot()
    {
        parent::boot();

            static::creating(function ($model) {
            $model->tanggal_pesanan = now();
        });
        static::creating(function ($model) {
            // Jika belum ada ID, buatkan otomatis
            if (empty($model->{$model->getKeyName()})) {
                $model->id_pesanan = self::generateKodePesanan();
            }
        });
    }

    protected static function generateKodePesanan(): string
    {
        $prefix = 'PSN';
        $latest = self::where('id_pesanan', 'like', "{$prefix}%")
            ->orderByDesc('id_pesanan')
            ->first();

        if (!$latest) {
            return $prefix . '001';
        }

        $lastNumber = (int) substr($latest->id_pesanan, strlen($prefix));
        return $prefix . str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
    }

    public function detailPesanan()
    {
        return $this->hasMany(DetailPesanan::class, 'pesanan_id', 'id_pesanan');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'pelanggan_id');
    }
    public function tracking()
{
    return $this->hasOne(Tracking::class, 'pesanan_id', 'id_pesanan');
}
}
