<?php

namespace App\Filament\Resources;

use App\Models\KipKuliah;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Forms\Components\Section;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\Filter;
use App\Filament\Resources\KipKuliahResource\Pages;
use Illuminate\Database\Eloquent\Builder;

class KipKuliahResource extends Resource
{
    protected static ?string $model = KipKuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $label = 'Data Penerima KIP Kuliah';
    protected static ?string $navigationGroup = 'KIP';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Data Penerima KIP';

   
    /* ================= FORM ================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Section::make('Identitas Mahasiswa')->schema([
                TextInput::make('no_pendaftaran'),
                TextInput::make('nama_siswa')->required(),
                TextInput::make('nik'),
                TextInput::make('nisn'),
                TextInput::make('no_kartu_keluarga'),
                TextInput::make('nik_kepala_keluarga'),
            ])->columns(3),

            Section::make('Status Bantuan')->schema([

            Select::make('status_dtks')
                ->label('Status DTKS')
                ->options([
                    'terdata' => 'Terdata',
                    'belum_terdata' => 'Belum Terdata',
                ])
                ->required()
                ->native(false),

        Select::make('status_p3ke')
            ->label('Status P3KE')
            ->options([
                'belum_terdata' => 'Belum Terdata',
                'desil_1' => 'Terdata : Desil 1',
                'desil_2' => 'Terdata : Desil 2',
                'desil_3' => 'Terdata : Desil 3',
                'desil_4' => 'Terdata : Desil 4',
                'desil_5' => 'Terdata : Desil 5',
                'desil_6' => 'Terdata : Desil 6',
                'desil_7' => 'Terdata : Desil 7',
            ])
            ->required()
            ->searchable()
            ->native(false),
        ])->columns(2),

        Section::make('Data Identitas Tambahan')->schema([
            TextInput::make('no_kip'),
            TextInput::make('no_kks'),
        ])->columns(4),

            Section::make('Sekolah')->schema([
                TextInput::make('asal_sekolah'),
                TextInput::make('kab_kota_sekolah'),
                TextInput::make('provinsi_sekolah'),
            ])->columns(3),

            Section::make('Data Pribadi')->schema([
                Select::make('jenis_kelamin')->options([
                    'L' => 'Laki-laki',
                    'P' => 'Perempuan',
                ]),
                TextInput::make('tempat_lahir'),
                DatePicker::make('tanggal_lahir'),
            ])->columns(3),

            Section::make('Kontak')->schema([
                Textarea::make('alamat_tinggal')->columnSpanFull(),
                TextInput::make('no_handphone'),
                TextInput::make('email'),
            ])->columns(2),

            Section::make('Data Orang Tua')->schema([
                TextInput::make('nama_ayah'),
                TextInput::make('pekerjaan_ayah'),
                TextInput::make('penghasilan_ayah')->numeric(),
                TextInput::make('status_ayah'),

                TextInput::make('nama_ibu'),
                TextInput::make('pekerjaan_ibu'),
                TextInput::make('penghasilan_ibu')->numeric(),
                TextInput::make('status_ibu'),
            ])->columns(4),

            Section::make('Kondisi Ekonomi')->schema([
                TextInput::make('jumlah_tanggungan')->numeric(),
                TextInput::make('kepemilikan_rumah'),
                TextInput::make('tahun_perolehan'),
                TextInput::make('sumber_listrik'),
                TextInput::make('luas_tanah')->numeric(),
                TextInput::make('luas_bangunan')->numeric(),
                TextInput::make('sumber_air'),
                TextInput::make('mck'),
                TextInput::make('jarak_pusat_kota_km')->numeric(),
            ])->columns(4),

            Section::make('Pengajuan')->schema([
                TextInput::make('diusulkan_oleh'),
                TextInput::make('pt_tujuan'),
                TextInput::make('prodi_rekomendasi'),
                TextInput::make('status_pengajuan'),
                TextInput::make('tahun'),
                Textarea::make('rekomendasi')->columnSpanFull(),
            ])->columns(3),
        ]);
    }

    /* ================= TABLE ================= */
public static function table(Table $table): Table
{
    return $table
        ->columns([

            // ===== IDENTITAS =====
            TextColumn::make('no_pendaftaran')
                ->label('No. Pendaftaran')
                ->searchable()
                ->sortable(),

            TextColumn::make('nama_siswa')
                ->searchable()
                ->sortable(),

            TextColumn::make('nik')->searchable()->toggleable(),
            TextColumn::make('nisn')->searchable()->toggleable(),
            TextColumn::make('no_kartu_keluarga')->toggleable(),
            TextColumn::make('nik_kepala_keluarga')->toggleable(),

            // ===== STATUS BANTUAN =====
            TextColumn::make('status_dtks')
                ->badge()
                ->formatStateUsing(fn ($state) => match ($state) {
                    'terdata' => 'Terdata',
                    'belum_terdata' => 'Belum Terdata',
                    default => '-',
                })
                ->toggleable(),

            TextColumn::make('status_p3ke')
                ->badge()
                ->formatStateUsing(fn ($state) => match ($state) {
                    'belum_terdata' => 'Belum Terdata',
                    'desil_1' => 'Desil 1',
                    'desil_2' => 'Desil 2',
                    'desil_3' => 'Desil 3',
                    'desil_4' => 'Desil 4',
                    'desil_5' => 'Desil 5',
                    'desil_6' => 'Desil 6',
                    'desil_7' => 'Desil 7',
                    default => '-',
                })
                ->toggleable(),

            TextColumn::make('no_kip')->toggleable(),
            TextColumn::make('no_kks')->toggleable(),

            // ===== SEKOLAH =====
            TextColumn::make('asal_sekolah')->toggleable(),
            TextColumn::make('kab_kota_sekolah')->toggleable(),
            TextColumn::make('provinsi_sekolah')->toggleable(),

            // ===== DATA PRIBADI =====
            TextColumn::make('tempat_lahir')->toggleable(),
            TextColumn::make('tanggal_lahir')->date()->toggleable(),
            TextColumn::make('jenis_kelamin')->toggleable(),

            // ===== KONTAK =====
            TextColumn::make('alamat_tinggal')->limit(30)->toggleable(),
            TextColumn::make('no_handphone')->toggleable(),
            TextColumn::make('email')->toggleable(),

            // ===== ORANG TUA =====
            TextColumn::make('nama_ayah')->toggleable(),
            TextColumn::make('pekerjaan_ayah')->toggleable(),
            TextColumn::make('penghasilan_ayah')->money('IDR', true)->toggleable(),
            TextColumn::make('status_ayah')->toggleable(),

            TextColumn::make('nama_ibu')->toggleable(),
            TextColumn::make('pekerjaan_ibu')->toggleable(),
            TextColumn::make('penghasilan_ibu')->money('IDR', true)->toggleable(),
            TextColumn::make('status_ibu')->toggleable(),

            // ===== EKONOMI =====
            TextColumn::make('jumlah_tanggungan')->toggleable(),
            TextColumn::make('kepemilikan_rumah')->toggleable(),
            TextColumn::make('tahun_perolehan')->toggleable(),
            TextColumn::make('sumber_listrik')->toggleable(),
            TextColumn::make('luas_tanah')->toggleable(),
            TextColumn::make('luas_bangunan')->toggleable(),
            TextColumn::make('sumber_air')->toggleable(),
            TextColumn::make('mck')->toggleable(),
            TextColumn::make('jarak_pusat_kota_km')->toggleable(),

            // ===== PENGAJUAN =====
            TextColumn::make('diusulkan_oleh')->toggleable(),
            TextColumn::make('pt_tujuan')->toggleable(),
            TextColumn::make('prodi_rekomendasi')->toggleable(),
            TextColumn::make('status_pengajuan')->badge()->toggleable(),
            TextColumn::make('tahun')->sortable()->toggleable(),
            TextColumn::make('rekomendasi')->limit(30)->toggleable(),

            // ===== META =====
            TextColumn::make('created_at')
                ->since()
                ->label('Dibuat')
                ->toggleable(isToggledHiddenByDefault: true),
        ])
        ->filters([
            Tables\Filters\SelectFilter::make('status_pengajuan'),
            Tables\Filters\SelectFilter::make('tahun'),

            Tables\Filters\SelectFilter::make('status_dtks')->options([
                'terdata' => 'Terdata',
                'belum_terdata' => 'Belum Terdata',
            ]),

            Tables\Filters\SelectFilter::make('status_p3ke')->options([
                'belum_terdata' => 'Belum Terdata',
                'desil_1' => 'Desil 1',
                'desil_2' => 'Desil 2',
                'desil_3' => 'Desil 3',
                'desil_4' => 'Desil 4',
                'desil_5' => 'Desil 5',
                'desil_6' => 'Desil 6',
                'desil_7' => 'Desil 7',
            ]),
        ])
        ->actions([
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ])
        ->defaultSort('created_at', 'desc')
        ->paginated([25, 50, 100]);
}

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKipKuliahs::route('/'),
            'create' => Pages\CreateKipKuliah::route('/create'),
            'edit' => Pages\EditKipKuliah::route('/{record}/edit'),
        ];
    }
}
