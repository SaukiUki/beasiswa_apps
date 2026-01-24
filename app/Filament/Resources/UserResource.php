<?php

namespace App\Filament\Resources;

use Filament\Forms;
use App\Models\User;
use Filament\Tables;
use Filament\Forms\Form;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\UserResource\Pages;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use App\Filament\Resources\UserResource\RelationManagers;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-circle';
    protected static ?string $navigationGroup = 'User Management';
        protected static ?int $navigationSort = 5;
    protected static ?string $label = 'Data User';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                ->label('Nama User')
                ->required(),
                Forms\Components\TextInput::make('email')
                ->label('Email')
                ->required(),
                Forms\Components\TextInput::make('password')
                ->label('Password')
                ->password()
                ->revealable()
                ->dehydrated(fn ($state) => filled($state))
                ->required(fn (string $context): bool => $context === 'create'),
                Forms\Components\Select::make('role')
                ->options([
                    'guru' => 'Guru',
                    'operator' => 'Operator',
                    'admin' => 'Admin'
                ]),
                Forms\Components\Select::make('kota_id')
                ->label('Nama Kota')
                ->relationship('kota', 'nama_kota')
                ->required(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Name User')->searchable(),
                TextColumn::make('email')->label('Email')->searchable(),
                TextColumn::make('role')->label('Role')->searchable(),
                TextColumn::make('kota.nama_kota')
            ->label('Nama Kota')
            ->searchable(),
                TextColumn::make('created_at')->label('Created At'),
            ])
            ->filters([
                //
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
