<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TrackingResource\Pages;
use App\Models\Tracking;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class TrackingResource extends Resource
{
    protected static ?string $model = Tracking::class;
    protected static ?string $modelLabel = 'Tracking';
    protected static ?string $navigationLabel = 'Tracking';
    protected static ?string $navigationIcon = 'heroicon-o-document-magnifying-glass';
    protected static ?string $slug = 'tracking';
    protected static ?int $navigationSort = 6;

    public static function shouldRegisterNavigation(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'produksi']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function canEdit($record): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Tracking';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\TextInput::make('id_tracking')
                ->label('Tracking ID')
                ->disabled()
                ->default(fn ($record) => $record?->id_tracking ?? 'Auto-generated'),

            Forms\Components\Select::make('pesanan_id')
                ->label('Pesanan')
                ->relationship(
                    name: 'pesanan',
                    titleAttribute: 'id_pesanan',
                    modifyQueryUsing: fn ($query) => $query->where('status', 'diproses')
                )
                ->required()
                ->visible(fn () => Auth::user()?->role === 'admin'),

            Forms\Components\DatePicker::make('tanggal')
                ->label('Tanggal Dibuat')
                ->required()
                ->default(now())
                ->disabled(),

            Forms\Components\Select::make('status')
                ->label('Status')
                ->options([
                    'beli bahan' => 'Beli Bahan',
                    'dijahit' => 'Dijahit',
                    'disablon/bordir' => 'Disablon/Bordir',
                    'QC' => 'Quality Control',
                    'packing' => 'Packing',
                    'selesai' => 'Selesai',
                ])
                ->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            Tables\Columns\TextColumn::make('id_tracking')->label('Tracking ID')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('pesanan.id_pesanan')->label('ID Pesanan')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('pesanan.nama_pesanan')->label('Pesanan')->sortable()->searchable(),
            Tables\Columns\TextColumn::make('tanggal')->label('Tanggal')->date()->sortable()->searchable(),
            Tables\Columns\TextColumn::make('pesanan.deadline')->label('Deadline')->date()->sortable(),
            Tables\Columns\SelectColumn::make('status')
                ->label('Status')
                ->options([
                    'beli bahan' => 'Beli Bahan',
                    'dijahit' => 'Dijahit',
                    'disablon/bordir' => 'Disablon/Bordir',
                    'QC' => 'Quality Control',
                    'packing' => 'Packing',
                    'selesai' => 'Selesai',
                ])
                ->disabled(fn () => Auth::user()?->role !== 'produksi')
                ->sortable(),

            Tables\Columns\TextColumn::make('pesanan.catatan')->label('Catatan')->sortable()->wrap(),
        ])
        ->actions([
            EditAction::make()
                ->visible(fn () => Auth::user()?->role === 'admin'),
        ])
        ->bulkActions([
            DeleteBulkAction::make()->color('primary'),

            BulkAction::make('cetak_produksi')
                ->label('Cetak PDF Produksi')
                ->icon('heroicon-o-printer')
                ->action(function (Collection $records) {
                    if ($records->count() === 1) {
                        $tracking = $records->first();
                        $pesanan = $tracking->pesanan()->with(['pelanggan', 'detailPesanan.produk'])->first();
                        $pesanan->adjusted_deadline = \Carbon\Carbon::parse($pesanan->deadline)->subDays(5);

                        $pdf = Pdf::loadView('pdf.pesanan_produksi', ['pesanans' => collect([$pesanan])]);
                        $filename = 'SPK ' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $pesanan->nama_pesanan) . '.pdf';

                        return response()->streamDownload(
                            fn () => print($pdf->output()),
                            $filename
                        );
                    }

                    $zipFileName = 'SPK_Tracking_' . now()->format('Ymd_His') . '.zip';
                    $zipPath = storage_path("app/public/{$zipFileName}");
                    $zip = new \ZipArchive();
                    $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                    foreach ($records as $tracking) {
                        $pesanan = $tracking->pesanan()->with(['pelanggan', 'detailPesanan.produk'])->first();
                        if (!$pesanan) continue;

                        $pesanan->adjusted_deadline = \Carbon\Carbon::parse($pesanan->deadline)->subDays(5);
                        $pdf = Pdf::loadView('pdf.pesanan_produksi', ['pesanans' => collect([$pesanan])]);
                        $fileName = 'SPK_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $pesanan->nama_pesanan) . '.pdf';
                        $zip->addFromString($fileName, $pdf->output());
                    }

                    $zip->close();
                    return response()->download($zipPath)->deleteFileAfterSend(true);
                })
                ->deselectRecordsAfterCompletion()
                ->color('biru'),
        ]);
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTrackings::route('/'),
            'create' => Pages\CreateTracking::route('/create'),
            'edit' => Pages\EditTracking::route('/{record}/edit'),
        ];
    }
}
