<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Laporan;

class Laporan extends Model
{
    use HasFactory;

    protected $table = 'laporan';

    protected $primaryKey = 'id_laporan';     // Set primary key custom
    public $incrementing = false;             // Karena bukan auto-increment
    protected $keyType = 'string';            // Karena id_laporan seperti 'LPR001'

    protected $fillable = [
        'id_laporan',
        'jenis_laporan',
        'tanggal_mulai',
        'tanggal_selesai',
        'deskripsi',
        'total_pesanan',
    ];

    public $timestamps = true;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($laporan) {
            if (empty($laporan->id_laporan)) {
                $last = self::orderBy('id_laporan', 'desc')->first();
                $nextNumber = $last ? intval(substr($last->id_laporan, 3)) + 1 : 1;
                $laporan->id_laporan = 'LPR' . str_pad($nextNumber, 3, '0', STR_PAD_LEFT);
            }
        });
    }
}
