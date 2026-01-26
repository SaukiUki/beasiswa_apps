<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\Guru;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\GuruResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\GuruResource\RelationManagers;

class GuruResource extends Resource
{
    protected static ?string $model = Guru::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-plus';
    protected static ?string $label = 'DAFTAR GURU/DOSEN';
    protected static ?string $recordTitleAttribute = 'nama_guru';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('nama_guru')
                ->label('Nama Lengkap')
                ->required(),
                Forms\Components\TextInput::make('nip')
                ->label('NIP')
                ->nullable(),
                Forms\Components\Select::make('pendidikan_id')
                ->label('Pendidikan')
                ->relationship('pendidikan', 'nama_sekolah') // Relasi ke model Pendidikan
                ->required(),
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
                Forms\Components\Select::make('jenis_kelamin')
                    ->label('Jenis Kelamin')
                    ->options([
                        'L' => 'Laki-laki',
                        'P' => 'Perempuan',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->nullable(),
                Forms\Components\TextInput::make('no_hp')
                    ->label('Nomor HP')
                    ->unique(ignoreRecord: true)
                    ->required(),
                    Forms\Components\TextArea::make('alamat')->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_guru')
                ->label('Nama Guru')
                ->searchable(),
            Tables\Columns\TextColumn::make('nip')
                ->label('NIP'),
            Tables\Columns\TextColumn::make('jenis_kelamin')
                ->label('Jenis Kelamin'),
            Tables\Columns\TextColumn::make('pendidikan.nama_sekolah')
                ->label('Pendidikan')
                ->sortable()
                ->searchable(),
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
            Tables\Columns\TextColumn::make('email')
                ->label('Email'),
            Tables\Columns\TextColumn::make('no_hp')
                ->label('Nomor HP'),
            Tables\Columns\TextColumn::make('alamat')
                ->label('Alamat'),
            ])
            ->filters([
            SelectFilter::make('pendidikan')
                ->label('Nama Instansi')
                ->relationship('pendidikan', 'nama_sekolah'),
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

            public static function shouldRegisterNavigation(): bool
    {
        return false;
    }
    
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGurus::route('/'),
            'create' => Pages\CreateGuru::route('/create'),
            'edit' => Pages\EditGuru::route('/{record}/edit'),
        ];
    }
}
