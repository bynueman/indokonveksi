<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan'; // Nama tabel di database
    protected $primaryKey = 'id_pelanggan'; // Primary key
    public $incrementing = false; // Jika ID tidak auto-increment
    protected $keyType = 'string'; // Tipe data primary key
    public $timestamps = false; // Jika tidak menggunakan timestamps

    protected $fillable = ['id_pelanggan', 'nama', 'no_hp', 'alamat', 'created_at']; // Field yang dapat diisi

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Cek ID terakhir dari database
            $lastId = self::orderBy('id_pelanggan', 'desc')->first();

            if (!$lastId) {
                $number = 1;
            } else {
                // Ambil angka dari belakang ID terakhir, misal: PLN007 => 7
                $number = (int) substr($lastId->id_pelanggan, 3) + 1;
            }

            // Format jadi PLN001, PLN002, dst.
            $model->id_pelanggan = 'PLN' . str_pad($number, 3, '0', STR_PAD_LEFT);
            $model->created_at = now();
        });
    }
}
