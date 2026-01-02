<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PipUsulanResource\Pages;
use App\Models\PipUsulan;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class PipUsulanResource extends Resource
{
    protected static ?string $model = PipUsulan::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $label = 'Usulan PIP';
    protected static ?string $navigationGroup = 'Data Usulan';

    /* =========================================================
     * QUERY
     * ========================================================= */
    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->select([
                'id',
                'nisn',
                'nama_siswa',
                'nama_sekolah',
                'nominal',
                'status_usulan',
                'catatan_admin',
                'approved_at',
                'approved_by',
                'created_at',
            ]);
    }

    /* =========================================================
     * FORM (READ ONLY)
     * ========================================================= */
    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Siswa')
                ->schema([
                    Forms\Components\TextInput::make('nisn')
                        ->disabled(),

                    Forms\Components\TextInput::make('nama_siswa')
                        ->disabled(),

                    Forms\Components\TextInput::make('nama_sekolah')
                        ->disabled(),
                ])->columns(3),

            Forms\Components\Section::make('Usulan')
                ->schema([
                    Forms\Components\TextInput::make('nominal')
                        ->label('Nominal Usulan')
                        ->disabled(),

                    Forms\Components\Select::make('status_usulan')
                        ->options([
                            'draft' => 'Draft',
                            'diajukan' => 'Diajukan',
                            'disetujui' => 'Disetujui',
                            'ditolak' => 'Ditolak',
                        ])
                        ->disabled(),

                    Forms\Components\Textarea::make('catatan_admin')
                        ->label('Catatan Admin')
                        ->disabled()
                        ->columnSpanFull(),
                ])->columns(2),
        ]);
    }

    /* =========================================================
     * TABLE
     * ========================================================= */
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nisn')
                    ->label('NISN')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_siswa')
                    ->label('Nama Siswa')
                    ->limit(30),

                Tables\Columns\TextColumn::make('nama_sekolah')
                    ->label('Sekolah')
                    ->limit(30),

                Tables\Columns\TextColumn::make('nominal')
                    ->money('IDR', true)
                    ->sortable(),

                Tables\Columns\BadgeColumn::make('status_usulan')
                    ->label('Status')
                    ->colors([
                        'warning' => 'diajukan',
                        'success' => 'disetujui',
                        'danger'  => 'ditolak',
                    ]),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Tanggal Usulan')
                    ->dateTime('d M Y'),
            ])

            /* ================= FILTER ================= */
            ->filters([
                Tables\Filters\SelectFilter::make('status_usulan')
                    ->label('Status Usulan')
                    ->options([
                        'draft' => 'Draft',
                        'diajukan' => 'Diajukan',
                        'disetujui' => 'Disetujui',
                        'ditolak' => 'Ditolak',
                    ])
                    ->default('diajukan'),
            ])

            /* ================= ACTION ================= */
            ->actions([
                Tables\Actions\ViewAction::make(),

                Tables\Actions\Action::make('approve')
                    ->label('Setujui')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn ($record) => $record->status_usulan === 'diajukan')
                    ->requiresConfirmation()
                    ->action(function ($record) {
                        app(\App\Services\PipApprovalService::class)
                            ->approve($record);
                    }),

                Tables\Actions\Action::make('reject')
                    ->label('Tolak')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn ($record) => $record->status_usulan === 'diajukan')
                    ->form([
                        Forms\Components\Textarea::make('catatan_admin')
                            ->label('Catatan Penolakan')
                            ->required(),
                    ])
                    ->action(function ($record, array $data) {
                        $record->update([
                            'status_usulan' => 'ditolak',
                            'catatan_admin' => $data['catatan_admin'],
                        ]);
                    }),
            ])

            /* ================= PAGINATION ================= */
            ->defaultPaginationPageOption(25)
            ->paginationPageOptions([25, 50]);
    }

    /* =========================================================
     * PAGES
     * ========================================================= */
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPipUsulans::route('/'),
            'view'  => Pages\ViewPipUsulan::route('/{record}'),
        ];
    }
}
