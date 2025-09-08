<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Produk extends Model
{
    protected $table = 'produk';
    protected $primaryKey = 'id_produk';
    public $incrementing = false;
    protected $keyType = 'string';
    public $timestamps = false;
    

    protected $fillable = 
    ['id_produk', 
    'nama_produk', 
    'harga', 
    'tgl_buat',
    'biaya_produksi',
    'harga_bahan_baku',];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Ambil semua ID produk
            $existingIds = self::pluck('id_produk')->map(function ($id) {
                return (int) substr($id, 3); // Ambil angka dari PRDxxx
            })->sort()->values();

            // Cari nomor terkecil yang belum dipakai
            $nextNumber = 1;
            foreach ($existingIds as $idNum) {
                if ($idNum != $nextNumber) break;
                $nextNumber++;
            }

            // Format ID: PRD001, PRD002, ...
            $model->id_produk = 'PRD' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
        });
    }
}
