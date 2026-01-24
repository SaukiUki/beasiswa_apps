<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Mitra;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\MitraResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\MitraResource\RelationManagers;

class MitraResource extends Resource
{
    protected static ?string $model = Mitra::class;

    protected static ?string $navigationIcon = 'heroicon-o-shield-exclamation';
    protected static ?string $label = 'MITRA';
    protected static ?string $navigationGroup = 'DATA MITRA';
        protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make()
                ->schema([
                    Select::make('kategori_id')
                ->label('Kategori')
                ->relationship('kategori', 'nama_kategori')
                ->required(),
                Forms\Components\TextInput::make('nama_mitra')
                    ->required()
                    ->label('Nama'),
                    Forms\Components\TextInput::make('instansi')
                    ->required()
                    ->label('Instansi/Lembaga'),
                    Forms\Components\TextInput::make('jabatan')
                    ->required()
                    ->label('Posisi Jabatan'),
                    Forms\Components\Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),
                    Forms\Components\TextInput::make('no_hp')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->label('Nomor HP'),
                    Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ])->columns(4),

                    Forms\Components\TextArea::make('alamat')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('kategori.nama_kategori')
                ->label('Kategori')
                ->searchable(),
                TextColumn::make('nama_mitra')
                ->label('Nama')
                ->searchable(),
                TextColumn::make('instansi')
                ->label('Instansi/Lembaga')
                ->searchable(),
                TextColumn::make('jabatan')
                ->label('Posisi Jabatan'),
                TextColumn::make('jenis_kelamin')
                ->label('Jenis Kelamin'),
                TextColumn::make('no_hp')
                ->label('Kontak'),
                TextColumn::make('email')
                ->label('Email'),
                TextColumn::make('alamat')
                ->label('Alamat'),
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

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMitras::route('/'),
            'create' => Pages\CreateMitra::route('/create'),
            'edit' => Pages\EditMitra::route('/{record}/edit'),
        ];
    }
}
