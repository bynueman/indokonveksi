<?php

namespace App\Filament\Resources;

use App\Filament\Resources\InvoiceResource\Pages;
use App\Models\Invoice;
use App\Models\Pesanan;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Columns\TextColumn;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Filament\Tables\Columns\SelectColumn;

class InvoiceResource extends Resource
{
    protected static ?string $model = Invoice::class;
    protected static ?string $slug = 'invoice';
    protected static ?string $navigationLabel = 'Invoice';
    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?int $navigationSort = 4; // Urutan di menu navigasi

    public static function getPluralModelLabel(): string
    {
        return 'Invoice'; // label plural yang muncul di beberapa tempat
    }

    public static function form(Form $form): Form
{
    return $form->schema([
        Components\Select::make('pesanan_id')
            ->label('Pesanan')
            ->options(function () {
                return Pesanan::where('status', 'diproses')->pluck('id_pesanan', 'id_pesanan');
            })
            ->afterStateUpdated(function (callable $set, $state) {
                $pesanan = Pesanan::with(['pelanggan', 'detailPesanan'])->find($state);

                if ($pesanan) {
                    $set('pelanggan_id', $pesanan->pelanggan_id);
                    $set('produk', $pesanan->nama_pesanan);

                    $produkId = $pesanan->detailPesanan->first()?->produk_id;
                    $jumlah = $pesanan->detailPesanan->first()?->jumlah_total ?? 0;
                    $harga = Produk::find($produkId)?->harga ?? 0;

                    $set('jumlah', $jumlah);
                    $set('harga_satuan', $harga);
                    $set('jumlah_bayar', ($jumlah * $harga));
                }
            }),

        Components\Select::make('pelanggan_id')
            ->label('Pelanggan')
            ->relationship('pelanggan', 'nama')
            ->disabled(),

        Components\TextInput::make('produk')
            ->label('Produk (Nama Pesanan)')
            ->disabled(),

        Components\TextInput::make('jumlah')
            ->label('Jumlah Order')
            ->numeric()
            ->disabled(),

        Components\TextInput::make('harga_satuan')
            ->label('Harga Satuan')
            ->numeric()
            ->disabled(),

        Components\TextInput::make('jumlah_bayar')
            ->label('Jumlah Bayar')
            ->numeric()
            ->disabled(),

        Components\DatePicker::make('tanggal_invoice')
            ->required()
            ->default(now())
            ->disabled(),

        Components\TextInput::make('npwp')->nullable(),
        
        Components\TextInput::make('biaya_tambahan')
            ->label('Biaya Tambahan')
            ->numeric()
            ->default(0)
            ->reactive()
            ->afterStateUpdated(function (callable $get, callable $set) {
                $jumlah = intval($get('jumlah') ?? 0);
                $harga = intval($get('harga_satuan') ?? 0);
                $biaya = intval($get('biaya_tambahan') ?? 0);
                $diskon = intval($get('diskon') ?? 0);

                $set('jumlah_bayar', ($jumlah * $harga) + $biaya - $diskon);
            }),

        Components\TextInput::make('diskon')
            ->label('Diskon')
            ->numeric()
            ->default(0)
            ->reactive()
            ->afterStateUpdated(function (callable $get, callable $set) {
                $jumlah = intval($get('jumlah') ?? 0);
                $harga = intval($get('harga_satuan') ?? 0);
                $biaya = intval($get('biaya_tambahan') ?? 0);
                $diskon = intval($get('diskon') ?? 0);

                $set('jumlah_bayar', ($jumlah * $harga) + $biaya - $diskon);
            }),

        Components\Select::make('status_pembayaran')
            ->label('Status Pembayaran')
            ->options([
                'dp' => 'DP',
                'lunas' => 'LUNAS',
            ])
            ->required()
            ->reactive()
            ->afterStateUpdated(function (callable $get, callable $set, $state) {
                if ($state === 'lunas') {
                    $jumlah = intval($get('jumlah') ?? 0);
                    $harga = intval($get('harga_satuan') ?? 0);
                    $biaya = intval($get('biaya_tambahan') ?? 0);
                    $diskon = intval($get('diskon') ?? 0);

                    $set('jumlah_dibayar', ($jumlah * $harga) + $biaya - $diskon);
                }
            }),

        Components\TextInput::make('jumlah_dibayar')
            ->label('DP / Jumlah Dibayar')
            ->numeric()
            ->required(fn (callable $get) => $get('status_pembayaran') === 'dp'),

        Components\TextInput::make('metode_pembayaran')->nullable(),
        Components\Textarea::make('keterangan')->nullable(),
    ]);
}



    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_invoice')->label('ID Invoice')->sortable()->searchable(),
                TextColumn::make('pelanggan.nama')->label('Pelanggan')->sortable(),
                TextColumn::make('pesanan.nama_pesanan')->label('Nama Pesanan')->sortable(),
                TextColumn::make('pesanan.deadline')->label('Deadline')->date()->sortable(),
                TextColumn::make('total_tagihan')->money('IDR')->label('Total Tagihan')->sortable(),
                TextColumn::make('kurang')->money('IDR')->label('Kurang')->sortable(),
                SelectColumn::make('status_pembayaran')
            ->label('Status Pembayaran')
            ->options([
                'dp' => 'DP',
                'lunas' => 'LUNAS',
            ])
            ->inline()
            ->disabled(fn ($record) => $record->status_pembayaran === 'lunas')
            ->sortable(),
        ])
        ->defaultSort('id_invoice', 'desc')
        ->actions([
            EditAction::make(),
        ])
            ->bulkActions([
                DeleteBulkAction::make()->color('primary'),

                BulkAction::make('cetak_invoice')
                    ->label('Cetak PDF - Invoice')
                    ->icon('heroicon-o-printer')
                    ->action(function (Collection $records) {
                        if ($records->count() === 1) {
                            $invoice = $records->first();
                            $invoice->load(['pelanggan', 'pesanan']);
                            $namaPesanan = $invoice->pesanan->nama_pesanan ?? 'Invoice';
                            $filename = 'INV ' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $namaPesanan) . '.pdf';

                            $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice]);

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                $filename
                            );
                        }

                        $zipFileName = 'Invoices_' . now()->format('Ymd_His') . '.zip';
                        $zipPath = storage_path("app/public/{$zipFileName}");
                        $zip = new \ZipArchive();
                        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                        foreach ($records as $invoice) {
                            $invoice->load(['pelanggan', 'pesanan']);
                            $namaPesanan = $invoice->pesanan->nama_pesanan ?? 'Invoice';
                            $fileName = 'INV ' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $namaPesanan) . '.pdf';

                            $pdf = Pdf::loadView('pdf.invoice', ['invoice' => $invoice]);
                            $zip->addFromString($fileName, $pdf->output());
                        }

                        $zip->close();

                        return response()->download($zipPath)->deleteFileAfterSend(true);
                    })
                    ->deselectRecordsAfterCompletion()
                    ->color('biru'),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
{
    return auth()->user()?->role === 'admin';
}

public static function canViewAny(): bool
{
    return auth()->user()?->role === 'admin';
}

public static function canCreate(): bool
{
    return auth()->user()?->role === 'admin';
}

public static function canEdit($record): bool
{
    return auth()->user()?->role === 'admin';
}

public static function canDelete($record): bool
{
    return auth()->user()?->role === 'admin';
}


    public static function getPages(): array
    {
        return [
            'index' => Pages\ListInvoices::route('/'),
            'create' => Pages\CreateInvoice::route('/create'),
            'edit' => Pages\EditInvoice::route('/{record}/edit'),
        ];
    }
}
