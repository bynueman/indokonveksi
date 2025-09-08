<?php

namespace App\Filament\Resources;

use App\Filament\Resources\LaporanResource\Pages;
use App\Models\Laporan;
use Filament\Forms;
use Filament\Tables;
use Filament\Resources\Resource;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Collection;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Textarea;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Model;

class LaporanResource extends Resource
{
    protected static ?string $model = Laporan::class;
    protected static ?string $navigationIcon = 'heroicon-o-clipboard-document';
    protected static ?string $slug = 'laporan';
    protected static ?string $navigationLabel = 'Laporan';
    protected static ?string $modelLabel = 'Laporan';
    protected static ?int $navigationSort = 7;

    public static function getPluralModelLabel(): string
    {
        return 'Laporan';
    }

    public static function shouldRegisterNavigation(): bool
    {
        return in_array(Auth::user()->role, ['admin', 'owner']);
    }

    public static function canCreate(): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function canEdit(Model $record): bool
    {
        return Auth::user()->role === 'admin';
    }

    public static function form(Forms\Form $form): Forms\Form
    {
        return $form->schema([
            TextInput::make('id_laporan')
                ->label('ID Laporan')
                ->default('Auto-generated')
                ->disabled()
                ->required(),

            DatePicker::make('tanggal_mulai')
                ->label('Tanggal Mulai')
                ->required(),

            DatePicker::make('tanggal_selesai')
                ->label('Tanggal Selesai')
                ->required(),

            Textarea::make('deskripsi')
                ->label('Keterangan')
                ->nullable(),
        ]);
    }

    public static function table(Tables\Table $table): Tables\Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id_laporan')->searchable(),
                Tables\Columns\TextColumn::make('tanggal_mulai')->date(),
                Tables\Columns\TextColumn::make('tanggal_selesai')->date(),
            ])
            ->actions([
                Tables\Actions\EditAction::make()
                    ->visible(fn () => Auth::user()->role === 'admin'),
            ])
            ->bulkActions([
                DeleteBulkAction::make()->color('primary'),

                BulkAction::make('cetak_omzet')
    ->label('Cetak PDF Omzet')
    ->action(function (Collection $records) {
        $record = $records->first();

        $pesananList = DB::table('pesanan')
            ->join('invoice', 'pesanan.id_pesanan', '=', 'invoice.pesanan_id')
            ->join('pelanggan', 'pesanan.pelanggan_id', '=', 'pelanggan.id_pelanggan')
            ->leftJoin('detail_pesanan', 'pesanan.id_pesanan', '=', 'detail_pesanan.pesanan_id')
            ->whereBetween('pesanan.tanggal_pesanan', [$record->tanggal_mulai, $record->tanggal_selesai])
            ->select(
                'pesanan.id_pesanan',
                'pesanan.nama_pesanan',
                'pelanggan.nama as nama_pelanggan',
                'invoice.total_tagihan',
                'invoice.jumlah_dibayar',
                DB::raw('SUM(detail_pesanan.jumlah_total) as jumlah_order'),
                'pesanan.tanggal_pesanan',
                'pesanan.deadline',
                'invoice.status_pembayaran'
            )
            ->groupBy(
                'pesanan.id_pesanan',
                'pesanan.nama_pesanan',
                'pelanggan.nama',
                'invoice.total_tagihan',
                'invoice.jumlah_dibayar',
                'pesanan.tanggal_pesanan',
                'pesanan.deadline',
                'invoice.status_pembayaran'
            )
            ->orderBy('pesanan.tanggal_pesanan', 'asc') // urut dari atas ke bawah
            ->get();

        $omzet = $pesananList->sum('jumlah_dibayar');
        $totalKurang = $pesananList->sum(fn ($item) => $item->total_tagihan - $item->jumlah_dibayar);

        $pdf = Pdf::loadView('pdf.omzet', [
            'pesananList' => $pesananList,
            'omzet' => $omzet,
            'totalKurang' => $totalKurang,
            'tanggalMulai' => $record->tanggal_mulai,
            'tanggalSelesai' => $record->tanggal_selesai,
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'Laporan_Omzet_' . now()->format('Ymd_His') . '.pdf'
        );
    })
    ->requiresConfirmation()
    ->deselectRecordsAfterCompletion()
    ->color('biru'),

                        BulkAction::make('cetak_laba')
    ->label('Cetak PDF Laba')
    ->action(function (Collection $records) {
        $record = $records->first();

        $pesananList = DB::table('pesanan')
            ->join('invoice', 'pesanan.id_pesanan', '=', 'invoice.pesanan_id')
            ->join('pelanggan', 'pesanan.pelanggan_id', '=', 'pelanggan.id_pelanggan')
            ->join('detail_pesanan', 'pesanan.id_pesanan', '=', 'detail_pesanan.pesanan_id')
            ->join('produk', 'detail_pesanan.produk_id', '=', 'produk.id_produk')
            ->whereBetween('pesanan.tanggal_pesanan', [$record->tanggal_mulai, $record->tanggal_selesai])
            ->select(
                'pesanan.nama_pesanan',
                'produk.nama_produk',
                DB::raw('SUM(detail_pesanan.jumlah_total) as jumlah_total'),
                'invoice.total_tagihan',
                'invoice.jumlah_dibayar',
                'invoice.diskon',
                'invoice.biaya_tambahan',
                'produk.harga as harga_jual',
                'produk.biaya_produksi',
                'produk.harga_bahan_baku'
            )
            ->groupBy(
                'pesanan.nama_pesanan',
                'produk.nama_produk',
                'invoice.total_tagihan',
                'invoice.jumlah_dibayar',
                'invoice.diskon',
                'invoice.biaya_tambahan',
                'produk.harga',
                'produk.biaya_produksi',
                'produk.harga_bahan_baku'
            )
            ->orderBy('pesanan.nama_pesanan', 'asc')
            ->get();

        $pdf = Pdf::loadView('pdf.laba', [
            'pesananList' => $pesananList,
            'tanggalMulai' => $record->tanggal_mulai,
            'tanggalSelesai' => $record->tanggal_selesai,
        ]);

        return response()->streamDownload(
            fn () => print($pdf->output()),
            'Laporan_Laba_' . now()->format('Ymd_His') . '.pdf'
        );
    })
    ->requiresConfirmation()
    ->deselectRecordsAfterCompletion()
    ->color('biru'),

            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLaporans::route('/'),
            'create' => Pages\CreateLaporan::route('/create'),
            'edit' => Pages\EditLaporan::route('/{record}/edit'),
        ];
    }
}
