<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Pendidikan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\PendidikanResource\Pages;
use App\Filament\Resources\PendidikanResource\RelationManagers;

class PendidikanResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $label = 'Daftar Sekolah/Kampus';
    protected static ?string $recordTitleAttribute = 'nama_sekolah';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_sekolah')
                    ->label('Nama Sekolah')
                    ->required(),
                
                Forms\Components\Select::make('jenjang_instansi')
                    ->label('Jenjang Instansi')
                    ->options([
                        'SD' => 'SEKOLAH DASAR',
                        'SMP' => 'SEKOLAH MENENGAH PERTAMA',
                        'SMA' => 'SEKOLAH MENENGAH AKHIR',
                        'PERGURUAN TINGGI' => 'PERGURUAN TINGGI',
                        'SLB' => 'SEKOLAH LUAR BIASA',
                        'SMK' => 'SEKOLAH MENENGAH KEJURUAN',
                    ])
                    ->nullable(),
                
                Select::make('kota_id')
                    ->label('Nama Kota/Kabupaten')
                    ->relationship('kota', 'nama_kota')
                    ->required(),
                
                Select::make('kecamatan_id')
                    ->label('Nama Kecamatan')
                    ->relationship('kecamatan', 'nama_kecamatan')
                    ->required(),
                
                Textarea::make('alamat')
                    ->label('Alamat')
                    ->columnSpanFull(),
                
                TextInput::make('nama_kepsek')
                    ->label('Nama Kepsek'),
                
                TextInput::make('no_hp_kepsek')
                    ->label('No. HP Kepsek')
                    ->tel(),
                
                TextInput::make('nama_operator')
                    ->label('Nama Operator'),
                
                TextInput::make('no_hp_operator')
                    ->label('No. HP Operator')
                    ->tel(),
                
                TextInput::make('jumlah_siswa')
                    ->label('Jumlah Siswa')
                    ->numeric()
                    ->default(0),
                
                TextInput::make('jumlah_pip_aspirasi')
                    ->label('Jumlah Penerima PIP Aspirasi')
                    ->numeric()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_sekolah')
                    ->label('Nama Sekolah')
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('jenjang_instansi')
                    ->label('Jenjang')
                    ->toggleable()
                    ->sortable(),
                
                TextColumn::make('kota.nama_kota')
                    ->label('Kota/Kabupaten')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Kecamatan')
                    ->toggleable()
                    ->searchable()
                    ->sortable(),
                
                TextColumn::make('alamat')
                    ->label('Alamat')
                    ->toggleable()
                    ->limit(50),
                
                TextColumn::make('nama_kepsek')
                    ->label('Nama Kepsek')
                    ->toggleable()
                    ->searchable(),
                
                TextColumn::make('no_hp_kepsek')
                    ->label('No HP Kepsek')
                    ->toggleable(),
                
                TextColumn::make('nama_operator')
                    ->label('Nama Operator')
                    ->toggleable()
                    ->searchable(),
                
                TextColumn::make('no_hp_operator')
                    ->label('No HP Operator')
                    ->toggleable(),
                
                TextColumn::make('jumlah_siswa')
                    ->label('Jumlah Siswa')
                    ->toggleable()
                    ->sortable(),
                
                TextColumn::make('jumlah_pip_aspirasi')
                    ->label('PIP Aspirasi')
                    ->toggleable()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('kota')
                    ->label('Kota/Kabupaten')
                    ->relationship('kota', 'nama_kota'),
                SelectFilter::make('kecamatan')
                    ->label('Kecamatan')
                    ->relationship('kecamatan', 'nama_kecamatan'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListPendidikans::route('/'),
            'create' => Pages\CreatePendidikan::route('/create'),
            'edit' => Pages\EditPendidikan::route('/{record}/edit'),
        ];
    }
}

