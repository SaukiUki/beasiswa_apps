<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PipPengajuanResource\Pages;
use App\Filament\Resources\PipPengajuanResource\RelationManagers;
use App\Models\PipUsulan;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PipPengajuanResource extends Resource
{
    protected static ?string $model = PipUsulan::class;
    protected static ?string $navigationIcon = 'heroicon-o-inbox-arrow-down';
    protected static ?string $navigationGroup = 'PIP';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Data Pengajuan PIP';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // /* ================= IDENTITAS SISWA ================= */
        Forms\Components\Section::make('Identitas Siswa')->schema([
            TextInput::make('pdid'),
            TextInput::make('nisn')->required(),
            TextInput::make('nik'),
            TextInput::make('nama_siswa')->required(),
            TextInput::make('nama_sekolah')->required(),
            TextInput::make('npsn'),
        ])->columns(3),

        /* ================= WILAYAH & SEKOLAH ================= */
        Forms\Components\Section::make('Wilayah & Sekolah')->schema([
            TextInput::make('provinsi'),
            TextInput::make('kabupaten'),
            TextInput::make('kecamatan'),
            TextInput::make('jenjang'),
            TextInput::make('bentuk'),
            TextInput::make('kelas'),
            TextInput::make('rombel'),
            TextInput::make('semester'),
        ])->columns(4),

        /* ================= DATA PRIBADI ================= */
        Forms\Components\Section::make('Data Pribadi')->schema([
            Select::make('jenis_kelamin')->options([
                'L' => 'Laki-laki',
                'P' => 'Perempuan',
            ]),
            TextInput::make('tempat_lahir'),
            DatePicker::make('tanggal_lahir'),
            TextInput::make('nama_ayah'),
            TextInput::make('nama_ibu'),
        ])->columns(3),

        /* ================= DATA BANTUAN ================= */
        Forms\Components\Section::make('Data Bantuan')->schema([
            TextInput::make('nominal')->numeric(),
            TextInput::make('tahap'),
            TextInput::make('tahap_nominasi'),
            DatePicker::make('tanggal_aktifasi'),
            DatePicker::make('tanggal_mulai_pencairan'),
            DatePicker::make('tanggal_cair'),
        ])->columns(3),

        /* ================= SK & ADMINISTRASI ================= */
        Forms\Components\Section::make('SK & Administrasi')->schema([
            TextInput::make('tipe_sk'),
            TextInput::make('nomor_sk'),
            TextInput::make('nomor_sk_nominasi'),
            DatePicker::make('tanggal_sk'),
            DatePicker::make('tanggal_sk_nominasi'),
        ])
        ->columns(3)
        ->visible(fn ($record) => $record !== null), // 🔥 hanya EDIT

        /* ================= REKENING ================= */
        Forms\Components\Section::make('Rekening')->schema([
            TextInput::make('bank'),
            TextInput::make('no_rekening'),
            TextInput::make('virtual_account'),
            TextInput::make('virtual_account_nominasi'),
            TextInput::make('fase'),
        ])->columns(3),

        /* ================= BANSOS ================= */
        Forms\Components\Section::make('Bantuan Sosial')->schema([
            TextInput::make('no_kip'),
            TextInput::make('no_kks'),
            TextInput::make('no_kps'),
            TextInput::make('no_pkh'),
            Select::make('layak_pip')->options([
                'ya' => 'Ya',
                'tidak' => 'Tidak',
            ]),
        ])->columns(3),

        /* ================= PENGUSUL ================= */
        Forms\Components\Section::make('Pengusul')->schema([
            TextInput::make('nama_pengusul'),
            TextInput::make('nama_pengusul_utama'),
        ])->columns(2),

        /* ================= KETERANGAN ================= */
        Forms\Components\Section::make('Keterangan')->schema([
            Textarea::make('keterangan_tahap')->columnSpanFull(),
            Textarea::make('keterangan_pencairan')->columnSpanFull(),
            Textarea::make('keterangan_tambahan')->columnSpanFull(),
        ]),

        /* ================= STATUS (KHUSUS EDIT) ================= */
        Forms\Components\Section::make('Status Usulan')
    ->schema([
        Select::make('status_usulan')
            ->label('Status Pengajuan')
            ->options([
                'draft' => 'Draft',
                'diajukan' => 'Diajukan',
            ])
            ->required()
            ->disabled(fn ($record) => $record?->status_usulan === 'diajukan'),
    ])
    ->visible(fn ($record) => $record !== null),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('pdid')->searchable()->toggleable(),
                TextColumn::make('nama_siswa')->toggleable(),
                TextColumn::make('nama_sekolah')->toggleable(),
                TextColumn::make('provinsi')->toggleable(),
                TextColumn::make('kabupaten')->toggleable(),
                TextColumn::make('kecamatan')->toggleable(),
                TextColumn::make('nik')->searchable()->toggleable(),
                TextColumn::make('nisn')->searchable()->toggleable(),
                TextColumn::make('npsn')->searchable()->toggleable(),
                TextColumn::make('kelas')->toggleable(),
                TextColumn::make('rombel')->toggleable(),
                TextColumn::make('semester')->toggleable(),
                TextColumn::make('jenjang')->toggleable(),
                TextColumn::make('bentuk')->toggleable(),
                TextColumn::make('jenis_kelamin')->toggleable(),
                TextColumn::make('tempat_lahir')->toggleable(),
                TextColumn::make('tanggal_lahir')->toggleable(),
                TextColumn::make('nama_ayah')->toggleable(),
                TextColumn::make('nama_ibu')->toggleable(),
                TextColumn::make('nominal')->money('IDR', true)->toggleable(),
                TextColumn::make('tipe_sk')->toggleable(),
                TextColumn::make('nomor_sk')->toggleable(),
                TextColumn::make('nomor_sk_nominasi')->toggleable(),
                TextColumn::make('tanggal_sk')->date()->toggleable(),
                TextColumn::make('tanggal_sk_nominasi')->date()->toggleable(),
                TextColumn::make('tahap')->toggleable(),
                TextColumn::make('tahap_nominasi')->toggleable(),
                TextColumn::make('virtual_account')->toggleable(),
                TextColumn::make('virtual_account_nominasi')->toggleable(),
                TextColumn::make('no_rekening')->toggleable(),
                TextColumn::make('bank')->toggleable(),
                TextColumn::make('tanggal_aktifasi')->date()->toggleable(),
                TextColumn::make('tanggal_mulai_pencairan')->date()->toggleable(),
                TextColumn::make('tanggal_cair')->date()->toggleable(),
                TextColumn::make('no_kip')->toggleable(),
                TextColumn::make('no_kks')->toggleable(),
                TextColumn::make('no_kps')->toggleable(),
                TextColumn::make('no_pkh')->toggleable(),
                TextColumn::make('layak_pip')->toggleable(),
                TextColumn::make('nama_pengusul')->toggleable(),
                TextColumn::make('nama_pengusul_utama')->toggleable(),
                TextColumn::make('keterangan_tahap')->toggleable(),
                TextColumn::make('keterangan_pencairan')->toggleable(),
                TextColumn::make('keterangan_tambahan')->toggleable(),
                TextColumn::make('status')->badge()->toggleable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

        public static function getEloquentQuery(): Builder
        {
            return PipUsulan::query()->diajukan();
        }


    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPipPengajuans::route('/'),
            'create' => Pages\CreatePipPengajuan::route('/create'),
            'edit' => Pages\EditPipPengajuan::route('/{record}/edit'),
        ];
    }
}
