<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracking extends Model
{
    protected $table = 'tracking';

    protected $primaryKey = 'id_tracking';

    public $incrementing = false; // Karena primary key string

    protected $keyType = 'string';

    public $timestamps = false; // Matikan timestamps karena kolom tidak ada di tabel

    protected $fillable = [
        'id_tracking',
        'pesanan_id',
        'tanggal',
        'status',
        'deskripsi',
        'deadline',
        'catatan',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public static function boot()
{
    parent::boot();

                static::creating(function ($model) {
            $model->tanggal = now();
        });

static::creating(function ($model) {
    if (empty($model->id_tracking)) {
        do {
            // Buat angka acak 6 digit
            $randomNumber = mt_rand(100000, 999999);
            $newId = $randomNumber . '-IK';
        } while (static::where('id_tracking', $newId)->exists()); // pastikan unik

        $model->id_tracking = $newId;
    }

    // Set tanggal jika belum ada
    if (empty($model->tanggal)) {
        $model->tanggal = now();
    }
});

}

    

    public function pesanan()
    {
        return $this->belongsTo(Pesanan::class, 'pesanan_id', 'id_pesanan');
    }
}
