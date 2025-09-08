<?php

use App\Models\Tracking;

Route::get('/tracking', function () {
    $search = request('search');

    $trackings = Tracking::with('pesanan')
        ->when($search, fn($q) => $q->where('id_tracking', $search))
        ->latest()
        ->get();

    return view('landing.tracking', compact('trackings', 'search'));
});
