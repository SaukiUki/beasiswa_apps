<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Kota;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\KotaResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\KotaResource\RelationManagers;

class KotaResource extends Resource
{
    protected static ?string $model = Kota::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $label = 'Daftar Kota/Kabupaten';
    protected static ?string $navigationGroup = 'DATA WILAYAH';
        protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_kota')
                ->label('Nama Kabupaten/Kota')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('nama_kota')
                ->label('KOTA/KABUPATEN')
                ->searchable(),
            ])
            ->filters([
                SelectFilter::make('nama_kota')
                ->label('Nama Kota')
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
            'index' => Pages\ListKotas::route('/'),
            'create' => Pages\CreateKota::route('/create'),
            'edit' => Pages\EditKota::route('/{record}/edit'),
        ];
    }
}
