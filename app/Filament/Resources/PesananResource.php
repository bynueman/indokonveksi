<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PesananResource\Pages;
use App\Models\Pesanan;
use App\Models\Produk;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Fieldset;
use Filament\Forms\Components\FileUpload;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ViewColumn;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkAction;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Collection;
use Filament\Tables\Columns\SelectColumn;

class PesananResource extends Resource
{
    protected static ?string $model = Pesanan::class;
    protected static ?string $slug = 'pesanan';
    protected static ?string $navigationLabel = 'Pesanan';
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document-check';
    protected static ?int $navigationSort = 3;

    public static function getPluralModelLabel(): string
    {
        return 'Pesanan';
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('id_pesanan')
                ->label('ID Pesanan')
                ->disabled()
                ->dehydrated(false)
                ->default(fn ($record) => $record?->id_pesanan ?? 'Auto-generated'),

            Select::make('pelanggan_id')
                ->label('Nama Pelanggan')
                ->relationship('pelanggan', 'nama')
                ->required(),

            TextInput::make('nama_pesanan')->required(),

            Select::make('status')
                ->options([
                    'diproses' => 'Diproses',
                    'selesai' => 'Selesai',
                ])
                ->required()
                ->reactive()
                ->default('diproses'),

            DatePicker::make('tanggal_pesanan')
                ->required()
                ->default(now())
                ->disabled(),

            DatePicker::make('deadline')
                ->required()
                ->minDate(now()),

            Repeater::make('detailPesanan')
                ->relationship()
                ->schema([
                    Select::make('produk_id')
                        ->label('Produk')
                        ->options(Produk::all()->pluck('nama_produk', 'id_produk'))
                        ->searchable(),

                    TextInput::make('katalog'),
                    TextInput::make('material'),
                    TextInput::make('warna'),

                    Select::make('tipe_lengan')
                        ->label('Tipe Lengan')
                        ->options([
                            'pendek' => 'Pendek',
                            'panjang' => 'Panjang',
                        ])
                        ->required(),

                    Fieldset::make('Jumlah Ukuran')
                        ->schema([
                            TextInput::make('jumlah_ukuran.xs')->label('XS')->numeric()->minValue(0)->nullable(),
                            TextInput::make('jumlah_ukuran.s')->label('S')->numeric()->minValue(0)->nullable(),
                            TextInput::make('jumlah_ukuran.m')->label('M')->numeric()->minValue(0)->nullable(),
                            TextInput::make('jumlah_ukuran.l')->label('L')->numeric()->minValue(0)->nullable(),
                            TextInput::make('jumlah_ukuran.xl')->label('XL')->numeric()->minValue(0)->nullable(),
                            TextInput::make('jumlah_ukuran.xxl')->label('XXL')->numeric()->minValue(0)->nullable(),
                            TextInput::make('jumlah_ukuran.xxxl')->label('XXXL')->numeric()->minValue(0)->nullable(),
                            TextInput::make('jumlah_ukuran.xxxxl')->label('XXXXL')->numeric()->minValue(0)->nullable(),
                            TextInput::make('jumlah_ukuran.xxxxxl')->label('XXXXXL')->numeric()->minValue(0)->nullable(),
                        ])
                        ->columns(3),

                    FileUpload::make('file_desain')
                        ->label('File Desain')
                        ->disk('public')
                        ->directory('desain')
                        ->preserveFilenames()
                        ->visibility('public')
                        ->image()
                        ->openable()
                        ->downloadable()
                        ->deletable()
                ])
                ->columns(2)
                ->createItemButtonLabel('Tambah Detail Produk'),

            Textarea::make('catatan'),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id_pesanan')->label('ID Pesanan')->sortable()->searchable(),
                TextColumn::make('nama_pesanan')->label('Nama Pesanan')->sortable()->searchable(),
                TextColumn::make('pelanggan.nama')->label('Nama Pelanggan')->sortable()->searchable(),
                TextColumn::make('tanggal_pesanan')->label('Tanggal Pesanan')->date()->sortable()->searchable(),
                TextColumn::make('deadline')->label('Deadline')->date()->sortable()->searchable(),
                SelectColumn::make('status')
                    ->label('Status')
                    ->options([
                        'diproses' => 'Diproses',
                        'selesai' => 'Selesai',
                    ])
                    ->sortable()
                    ->searchable()
                    ->disabled(fn ($record) => $record->status === 'selesai')
                    ->inline(),

                ViewColumn::make('detailPesanan')
                    ->label('Desain')
                    ->view('tables.columns.detail-desain'),

                TextColumn::make('total_produk')
                    ->label('Jumlah')
                    ->alignCenter()
                    ->getStateUsing(function ($record) {
                        return $record->detailPesanan->sum(function ($detail) {
                            return collect($detail->jumlah_ukuran)->sum();
                        });
                    }),
            ])
            ->defaultSort('id_pesanan', 'desc')
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->color('primary'),
                // PDF Export actions bisa disisipkan di sini
BulkAction::make('cetak_pelanggan')
    ->label('Cetak PDF - Pelanggan (FO)')
    ->icon('heroicon-o-printer')
    ->action(function (Collection $records) {
        if ($records->count() === 1) {
            $pesanan = $records->first()
                   ->load(['pelanggan', 'detailPesanan.produk', 'tracking']);   // 👈 tambahkan 'tracking'
            $pdf = Pdf::loadView('pdf.pesanan_pelanggan', [
                     'pesanans' => collect([$pesanan])
                   ]);
            $filename = 'FO ' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $pesanan->nama_pesanan) . '.pdf';
            return response()->streamDownload(fn () => print($pdf->output()), $filename);
        }

        // ZIP – pastikan juga eager-load tracking
        $zipFileName = 'FO_Pesanan_' . now()->format('Ymd_His') . '.zip';
        $zipPath     = storage_path("app/public/{$zipFileName}");
        $zip         = new \ZipArchive();
        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        foreach ($records as $pesanan) {
            $pesanan->load(['pelanggan', 'detailPesanan.produk', 'tracking']);
            $pdf = Pdf::loadView('pdf.pesanan_pelanggan', [
                     'pesanans' => collect([$pesanan])
                   ]);
            $fileName = 'FO ' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $pesanan->nama_pesanan) . '.pdf';
            $zip->addFromString($fileName, $pdf->output());
        }

        $zip->close();
        return response()->download($zipPath)->deleteFileAfterSend(true);
    })
    ->deselectRecordsAfterCompletion()
    ->color('biru'),

                BulkAction::make('cetak_produksi')
                    ->label('Cetak PDF - Produksi (SPK)')
                    ->icon('heroicon-o-printer')
                    ->action(function (Collection $records) {
                        if ($records->count() === 1) {
                            $pesanan = $records->first()->load(['pelanggan', 'detailPesanan.produk']);
                            $pesanan->adjusted_deadline = \Carbon\Carbon::parse($pesanan->deadline)->subDays(5);

                            $pdf = Pdf::loadView('pdf.pesanan_produksi', ['pesanans' => collect([$pesanan])]);
                            $filename = 'SPK ' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $pesanan->nama_pesanan) . '.pdf';

                            return response()->streamDownload(
                                fn () => print($pdf->output()),
                                $filename
                            );
                        }

                        $zipFileName = 'SPK_Pesanan_' . now()->format('Ymd_His') . '.zip';
                        $zipPath = storage_path("app/public/{$zipFileName}");
                        $zip = new \ZipArchive();
                        $zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

                        foreach ($records as $pesanan) {
                            $pesanan->load(['pelanggan', 'detailPesanan.produk']);
                            $pesanan->adjusted_deadline = \Carbon\Carbon::parse($pesanan->deadline)->subDays(5);

                            $pdf = Pdf::loadView('pdf.pesanan_produksi', ['pesanans' => collect([$pesanan])]);
                            $fileName = 'SPK ' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $pesanan->nama_pesanan) . '.pdf';
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
            'index' => Pages\ListPesanans::route('/'),
            'create' => Pages\CreatePesanan::route('/create'),
            'edit' => Pages\EditPesanan::route('/{record}/edit'),
        ];
    }
}
