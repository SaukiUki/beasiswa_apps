<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Kelurahan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KelurahanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KelurahanResource\RelationManagers;

class KelurahanResource extends Resource
{
    protected static ?string $model = Kelurahan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Daftar Kelurahan';
    protected static ?string $navigationGroup = 'DATA WILAYAH';
        protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Select::make('kota_id')
            ->label('Nama Kota')
            ->relationship('kota', 'nama_kota')
            ->required(),
            Forms\Components\Select::make('kecamatan_id')
            ->label('Nama Kecamatan')
            ->relationship('kecamatan', 'nama_kecamatan')
            ->required(),
            Forms\Components\TextInput::make('nama_kelurahan')
            ->label('Nama Kelurahan')
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('kota.nama_kota')
            ->label('Nama Kota')
            ->searchable(),
            TextColumn::make('kecamatan.nama_kecamatan')
            ->label('Nama Kecamatan')
            ->searchable(),
            TextColumn::make('nama_kelurahan')
            ->label('Nama kelurahan')
            ->searchable(),
            ])
            ->filters([
                SelectFilter::make('kota')
                ->label('Nama Kota')
                ->relationship('kota', 'nama_kota'),
                SelectFilter::make('kecamatan')
                ->label('Nama Kecamatan')
                ->relationship('kecamatan', 'nama_kecamatan'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListKelurahans::route('/'),
            'create' => Pages\CreateKelurahan::route('/create'),
            'edit' => Pages\EditKelurahan::route('/{record}/edit'),
        ];
    }
}
