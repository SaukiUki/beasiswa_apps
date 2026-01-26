<?php

namespace App\Filament\Resources;

use App\Models\Siswa;
use App\Models\Kota;
use App\Models\Kecamatan;
use App\Models\Kelurahan;
use App\Models\Pendidikan;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\SiswaResource\Pages;

class SiswaResource extends Resource
{
    protected static ?string $model = Siswa::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $label = 'Data Siswa / Mahasiswa';
    protected static ?string $recordTitleAttribute = 'nama_siswa';

    /* =========================================================
     * FORM
     * ========================================================= */
    public static function form(Form $form): Form
    {
        return $form
            ->schema([

                /* =======================
                 * IDENTITAS UTAMA
                 * ======================= */
                Forms\Components\TextInput::make('nisn')
                    ->label('NISN')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->helperText('NISN wajib diisi dan harus unik'),

                Forms\Components\TextInput::make('nama_siswa')
                    ->label('Nama Siswa')
                    ->required(),

                Forms\Components\Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),

                /* =======================
                 * KONTAK
                 * ======================= */
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->nullable(),

                Forms\Components\TextInput::make('no_hp')
                    ->label('Nomor HP')
                    ->required(),

                /* =======================
                 * WILAYAH (BERTINGKAT)
                 * ======================= */
                Select::make('kota_id')
                    ->label('Kota')
                    ->options(
                        Kota::query()
                            ->orderBy('nama_kota')
                            ->pluck('nama_kota', 'id')
                    )
                    ->searchable()
                    ->required()
                    ->reactive(),

                Select::make('kecamatan_id')
                    ->label('Kecamatan')
                    ->options(
                        fn(callable $get) =>
                        $get('kota_id')
                            ? Kecamatan::where('kota_id', $get('kota_id'))
                            ->orderBy('nama_kecamatan')
                            ->pluck('nama_kecamatan', 'id')
                            : []
                    )
                    ->searchable()
                    ->required()
                    ->reactive(),

                Select::make('kelurahan_id')
                    ->label('Kelurahan')
                    ->options(
                        fn(callable $get) =>
                        $get('kecamatan_id')
                            ? Kelurahan::where('kecamatan_id', $get('kecamatan_id'))
                            ->orderBy('nama_kelurahan')
                            ->pluck('nama_kelurahan', 'id')
                            : []
                    )
                    ->searchable()
                    ->required()
                    ->reactive(),

                /* =======================
                 * PENDIDIKAN
                 * ======================= */
                Select::make('pendidikan_id')
                    ->label('Institusi Pendidikan')
                    ->options(
                        fn(callable $get) =>
                        $get('kecamatan_id')
                            ? Pendidikan::where('kecamatan_id', $get('kecamatan_id'))
                            ->orderBy('nama_sekolah')
                            ->pluck('nama_sekolah', 'id')
                            : []
                    )
                    ->searchable()
                    ->required(),

                /* =======================
                 * STATUS
                 * ======================= */
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak aktif' => 'Tidak Aktif',
                    ])
                    ->default('aktif'),

                Forms\Components\Textarea::make('alamat')
                    ->label('Alamat')
                    ->columnSpanFull(),

            ])
            ->columns(2);
    }

    /* =========================================================
     * TABLE
     * ========================================================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([

                TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('nama_siswa')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('jenis_kelamin')
                    ->label('Jenis Kelamin'),

                TextColumn::make('no_hp')
                    ->label('No. HP'),

                TextColumn::make('email')
                    ->label('Email')
                    ->toggleable(),

                TextColumn::make('kota.nama_kota')
                    ->label('Kota')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Kecamatan')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('kelurahan.nama_kelurahan')
                    ->label('Kelurahan')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('pendidikan.nama_sekolah')
                    ->label('Institusi Pendidikan')
                    ->searchable()
                    ->toggleable(),

                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->toggleable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'success' => 'aktif',
                        'danger' => 'tidak aktif',
                    ]),
            ])
            ->filters([

                SelectFilter::make('kota')
                    ->label('Kota')
                    ->relationship('kota', 'nama_kota'),

                SelectFilter::make('kecamatan')
                    ->label('Kecamatan')
                    ->relationship('kecamatan', 'nama_kecamatan'),

                SelectFilter::make('kelurahan')
                    ->label('Kelurahan')
                    ->relationship('kelurahan', 'nama_kelurahan'),

                SelectFilter::make('pendidikan')
                    ->label('Institusi Pendidikan')
                    ->relationship('pendidikan', 'nama_sekolah'),

                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'aktif' => 'Aktif',
                        'tidak aktif' => 'Tidak Aktif',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    /* =========================================================
     * PAGES
     * ========================================================= */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSiswas::route('/'),
            'create' => Pages\CreateSiswa::route('/create'),
            'edit' => Pages\EditSiswa::route('/{record}/edit'),
        ];
    }
}

