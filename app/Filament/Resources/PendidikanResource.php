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
    protected static ?string $recordTitleAttribute = 'nama_instansi';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('nama_pimpinan')
                    ->label('Nama Pimpinan'),
                TextInput::make('no_hp_pimpinan')
                    ->label('No. HP Pimpinan')
                    ->tel(),
                TextInput::make('email_instansi')
                    ->label('Email Instansi')
                    ->email(),
                TextInput::make('nama_instansi')->required(),
                Select::make('jenjang_pendidikan')->options([
                    'SD' => 'SEKOLAH DASAR',
                    'SMP' => 'SEKOLAH MENENGAH PERTAMA',
                    'SMA' => 'SEKOLAH MENENGAH AKHIR',
                    'PERGURUAN TINGGI' => 'PERGURUAN TINGGI'
                ])->required(),

                Select::make('kota_id')
                    ->label('Nama Kota')
                    ->relationship('kota', 'nama_kota')
                    ->required(),
                Select::make('kecamatan_id')
                    ->label('Nama Kecamatan')
                    ->relationship('kecamatan', 'nama_kecamatan')
                    ->required(),
                Select::make('kelurahan_id')
                    ->label('Nama Kelurahan')
                    ->relationship('kelurahan', 'nama_kelurahan')
                    ->required(),
                Textarea::make('alamat')->columnSpanFull(),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_pimpinan')
                    ->label('Pimpinan')
                    ->toggleable(),

                TextColumn::make('no_hp_pimpinan')
                    ->label('No HP')
                    ->toggleable(),
                TextColumn::make('email_instansi')
                    ->label('Email')
                    ->toggleable(),
                TextColumn::make('nama_instansi')->searchable(),
                TextColumn::make('jenjang_pendidikan'),
                TextColumn::make('kota.nama_kota')
                    ->label('Nama Kota')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('kecamatan.nama_kecamatan')
                    ->label('Nama Kecamatan')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('kelurahan.nama_kelurahan')
                    ->label('Nama Kelurahan')
                    ->toggleable()
                    ->searchable(),
                TextColumn::make('alamat'),
            ])
            ->filters([
                SelectFilter::make('kota')
                    ->label('Nama Kota')
                    ->relationship('kota', 'nama_kota'),
                SelectFilter::make('kecamatan')
                    ->label('Nama Kecamatan')
                    ->relationship('kecamatan', 'nama_kecamatan'),
                SelectFilter::make('kelurahan')
                    ->label('Nama Kelurahan')
                    ->relationship('kelurahan', 'nama_kelurahan')

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
