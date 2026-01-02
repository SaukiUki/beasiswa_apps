<?php

namespace App\Filament\Resources;

use App\Models\Pip;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Filters\SelectFilter;
use App\Filament\Resources\PipResource\Pages;
use Illuminate\Database\Eloquent\Builder;
use App\Models\PipUsulan;
use Filament\Tables\Actions\Action;

class PipResource extends Resource
{
    protected static ?string $model = Pip::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Data PIP';

    /* =========================================================
     * FORM
     * ========================================================= */
    public static function form(Form $form): Form
{
    return $form
        ->schema([
            Forms\Components\Section::make('Identitas Siswa')
                ->schema([
                    Forms\Components\TextInput::make('pdid')->label('PDID')->nullable(),
                    Forms\Components\TextInput::make('nisn')->label('NISN')->nullable(),
                    Forms\Components\TextInput::make('nik')->label('NIK')->nullable(),
                    Forms\Components\TextInput::make('nama_siswa')->label('Nama Siswa')->nullable(),
                    Forms\Components\TextInput::make('nama_sekolah')->label('Nama Sekolah')->nullable(),
                    Forms\Components\TextInput::make('npsn')->label('NPSN')->nullable(),
                ])->columns(3),

            Forms\Components\Section::make('Wilayah & Sekolah')
                ->schema([
                    Forms\Components\TextInput::make('provinsi')->nullable(),
                    Forms\Components\TextInput::make('kabupaten')->label('Kabupaten / Kota')->nullable(),
                    Forms\Components\TextInput::make('kecamatan')->nullable(),
                    Forms\Components\TextInput::make('jenjang')->nullable(),
                    Forms\Components\TextInput::make('bentuk')->nullable(),
                    Forms\Components\TextInput::make('kelas')->nullable(),
                    Forms\Components\TextInput::make('rombel')->nullable(),
                    Forms\Components\TextInput::make('semester')->label('Semeter')->nullable(),
                ])->columns(4),

            Forms\Components\Section::make('Data Pribadi')
                ->schema([
                    Forms\Components\Select::make('jenis_kelamin')
                        ->label('JK')
                        ->options([
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan',
                        ])
                        ->nullable(),
                    Forms\Components\TextInput::make('tempat_lahir')->nullable(),
                    Forms\Components\DatePicker::make('tanggal_lahir')->nullable(),
                    Forms\Components\TextInput::make('nama_ayah')->nullable(),
                    Forms\Components\TextInput::make('nama_ibu')->nullable(),
                ])->columns(3),

            Forms\Components\Section::make('Data Bantuan')
                ->schema([
                    Forms\Components\TextInput::make('nominal')->numeric()->nullable(),
                    Forms\Components\TextInput::make('tahap')->nullable(),
                    Forms\Components\TextInput::make('tahap_nominasi')->nullable(),
                    Forms\Components\DatePicker::make('tanggal_aktifasi')->nullable(),
                    Forms\Components\DatePicker::make('tanggal_mulai_pencairan')->nullable(),
                    Forms\Components\DatePicker::make('tanggal_cair')->nullable(),
                ])->columns(3),

            Forms\Components\Section::make('Rekening & Status')
                ->schema([
                    Forms\Components\TextInput::make('bank')->nullable(),
                    Forms\Components\TextInput::make('no_rekening')->nullable(),
                    Forms\Components\TextInput::make('virtual_account')->nullable(),
                    Forms\Components\TextInput::make('virtual_account_nominasi')->nullable(),
                    Forms\Components\Select::make('status')
                        ->options([
                            'aktif' => 'Aktif',
                            'tidak aktif' => 'Tidak Aktif',
                        ])
                        ->nullable(),
                    Forms\Components\TextInput::make('fase')->nullable(),
                ])->columns(3),

            Forms\Components\Section::make('Keterangan')
                ->schema([
                    Forms\Components\Textarea::make('keterangan_tahap')->columnSpanFull(),
                    Forms\Components\Textarea::make('keterangan_pencairan')->columnSpanFull(),
                    Forms\Components\Textarea::make('keterangan_tambahan')->columnSpanFull(),
                ]),
        ]);
}



public static function getEloquentQuery(): Builder
{
    return parent::getEloquentQuery()
        ->select([
            'id',
            'nisn',
            'nama_siswa',
            'nama_sekolah',
            'kabupaten',
            'nominal',
            'tanggal_cair',
            'status',
        ]);
}

    /* =========================================================
     * TABLE
     * ========================================================= */
    public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('nisn')->label('NISN')->searchable()->sortable(),
            Tables\Columns\TextColumn::make('nama_siswa'),
            Tables\Columns\TextColumn::make('nama_sekolah'),
            Tables\Columns\TextColumn::make('kabupaten')->label('Kabupaten'),
            Tables\Columns\TextColumn::make('nominal')->money('IDR', true)->sortable(),
            Tables\Columns\TextColumn::make('tanggal_cair')->date('d M Y')->sortable(),
            Tables\Columns\BadgeColumn::make('status')
                ->colors([
                    'success' => 'aktif',
                    'danger' => 'tidak aktif',
                ]),
        ])
        ->filters([
            SelectFilter::make('status')
                ->options([
                    'aktif' => 'Aktif',
                    'tidak aktif' => 'Tidak Aktif',
                ]),

            Tables\Filters\Filter::make('nisn_prefix')
                ->form([
                    Forms\Components\TextInput::make('nisn')
                        ->label('NISN diawali'),
                ])
                ->query(fn ($query, $data) =>
                    filled($data['nisn'])
                        ? $query->where('nisn', 'like', $data['nisn'].'%')
                        : $query
                ),
        ])

        ->defaultPaginationPageOption(25)
        ->paginationPageOptions([25, 50])
        ->actions([
    Tables\Actions\ViewAction::make(),
    Tables\Actions\EditAction::make(),

    Action::make('ajukan')
        ->label('Ajukan Perubahan')
        ->icon('heroicon-o-paper-airplane')
        ->color('warning')

        // hanya muncul jika belum ada usulan aktif
        ->visible(fn ($record) =>
            ! PipUsulan::where('nisn', $record->nisn)
                ->whereIn('status_usulan', ['draft', 'diajukan'])
                ->exists()
        )

        ->form([
            Forms\Components\TextInput::make('nominal')
                ->label('Nominal Usulan')
                ->numeric()
                ->required(),

            Forms\Components\Textarea::make('catatan_admin')
                ->label('Catatan Usulan')
                ->required(),
        ])

            ->action(function ($record, array $data) {
                PipUsulan::create([
                    'nisn' => $record->nisn,
                    'nama_siswa' => $record->nama_siswa,
                    'nama_sekolah' => $record->nama_sekolah,
                    'nominal' => $data['nominal'],
                    'status' => $record->status,

                    'status_usulan' => 'diajukan',
                    'catatan_admin' => $data['catatan_admin'],
                ]);
            })

            ->successNotificationTitle('Usulan berhasil diajukan'),
    ]);
}

    /* =========================================================
     * PAGES
     * ========================================================= */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPips::route('/'),
            'create' => Pages\CreatePip::route('/create'),
            'edit' => Pages\EditPip::route('/{record}/edit'),
        ];
    }
}
