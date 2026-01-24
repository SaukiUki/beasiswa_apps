<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use App\Models\Kecamatan;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KecamatanResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KecamatanResource\RelationManagers;

class KecamatanResource extends Resource
{
    protected static ?string $model = Kecamatan::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Daftar Kecamatan';
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
            Forms\Components\TextInput::make('nama_kecamatan')
            ->label('Nama Kecamatan')
            ->required(),
        ]);
}

public static function table(Table $table): Table
{
    return $table
        ->columns([
            TextColumn::make('kota.nama_kota')
            ->label('Nama Kota')
            ->searchable(),
            TextColumn::make('nama_kecamatan')
            ->label('Nama Kecamatan')
            ->searchable(),
            ])
            ->filters([
                SelectFilter::make('kota')
                ->label('Nama Kota')
                ->relationship('kota', 'nama_kota'),
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
            'index' => Pages\ListKecamatans::route('/'),
            'create' => Pages\CreateKecamatan::route('/create'),
            'edit' => Pages\EditKecamatan::route('/{record}/edit'),
        ];
    }
}
