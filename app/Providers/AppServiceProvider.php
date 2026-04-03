<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Filament\Forms\Components\FileUpload;
use Illuminate\Support\Facades\Storage;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
            FileUpload::macro('getUploadedFileUrlUsing', function () {
        return fn ($filePath) => Storage::disk('public')->url($filePath);
    });
    }
}
