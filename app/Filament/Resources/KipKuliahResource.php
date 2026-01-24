<?php

namespace App\Filament\Resources;

use App\Models\KipKuliah;
use App\Models\Siswa;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\KipKuliahResource\Pages;

class KipKuliahResource extends Resource
{
    protected static ?string $model = KipKuliah::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationGroup = 'KIP';
    protected static ?int $navigationSort = 2;
    protected static ?string $label = 'KIP Kuliah';

    /* ================= FORM ================= */
    public static function form(Form $form): Form
    {
        return $form->schema([

            Forms\Components\TextInput::make('nisn')
                ->label('NISN')
                ->required()
                ->reactive()
                ->exists('siswas', 'nisn')
                ->unique(ignoreRecord: true)
                ->afterStateUpdated(function ($state, Set $set) {
                    $siswa = Siswa::where('nisn', $state)->first();
                    $set('nama_mahasiswa', $siswa?->nama_siswa);
                }),

            Forms\Components\TextInput::make('nama_mahasiswa')
                ->label('Nama Mahasiswa')
                ->disabled()
                ->dehydrated()
                ->required(),

            Forms\Components\TextInput::make('nim')
                ->label('NIM'),

            Forms\Components\TextInput::make('perguruan_tinggi')
                ->label('Perguruan Tinggi')
                ->required(),

            Forms\Components\TextInput::make('program_studi')
                ->label('Program Studi')
                ->required(),

            Forms\Components\Select::make('jenjang')
                ->options([
                    'D3' => 'D3',
                    'S1' => 'S1',
                    'S2' => 'S2',
                ])
                ->required(),

            Forms\Components\TextInput::make('semester')
                ->numeric(),

            Forms\Components\TextInput::make('tahun_masuk')
                ->numeric(),

            Forms\Components\TextInput::make('nominal')
                ->label('Nominal Bantuan')
                ->numeric(),

            Forms\Components\Select::make('status_pengajuan')
                ->options([
                    'draft' => 'Draft',
                    'diajukan' => 'Diajukan',
                    'diterima' => 'Diterima',
                    'ditolak' => 'Ditolak',
                ])
                ->default('draft')
                ->required(),

            Forms\Components\Select::make('status')
                ->options([
                    'aktif' => 'Aktif',
                    'tidak aktif' => 'Tidak Aktif',
                ])
                ->default('aktif'),
        ])->columns(2);
    }

    /* ================= TABLE ================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nisn')->searchable(),
                Tables\Columns\TextColumn::make('nama_mahasiswa')->searchable(),
                Tables\Columns\TextColumn::make('perguruan_tinggi')->searchable(),
                Tables\Columns\TextColumn::make('program_studi')->searchable(),
                Tables\Columns\TextColumn::make('jenjang'),
                Tables\Columns\TextColumn::make('status_pengajuan')
                    ->badge()
                    ->colors([
                        'gray' => 'draft',
                        'warning' => 'diajukan',
                        'success' => 'diterima',
                        'danger' => 'ditolak',
                    ]),
                Tables\Columns\TextColumn::make('nominal')->money('IDR', true),
            ])
            ->filters([
                SelectFilter::make('jenjang')->options([
                    'D3' => 'D3',
                    'S1' => 'S1',
                    'S2' => 'S2',
                ]),
                SelectFilter::make('status_pengajuan')->options([
                    'draft' => 'Draft',
                    'diajukan' => 'Diajukan',
                    'diterima' => 'Diterima',
                    'ditolak' => 'Ditolak',
                ]),
            ]);
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
