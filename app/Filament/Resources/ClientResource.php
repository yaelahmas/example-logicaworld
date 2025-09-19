<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ClientResource\Pages;
use App\Filament\Resources\ClientResource\RelationManagers;
use App\Models\Client;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\EditAction as ActionsEditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class ClientResource extends Resource
{
    protected static ?string $model = Client::class;
    protected static ?string $modelLabel = 'Client';
    // protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationGroup = 'Company';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                FileUpload::make('logo')
                    ->image()
                    ->directory('clients')
                    ->visibility('public')
                    // ->minSize(512)
                    ->maxSize(1024)
                    // ->imagePreviewHeight('250') // Preview lebih besar
                    ->openable() // Bisa klik untuk buka gambar
                    ->downloadable() // Bisa unduh gambar yang sudah diupload
                    ->previewable()
                    ->required() // Wajib diisi (hapus jika ingin opsional)
                    ->columnSpanFull(),
                TextInput::make('name')
                    ->required()
                    ->minLength(3)
                    ->maxLength(255),
                TextInput::make('url')
                    ->url(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                ImageColumn::make('logo')
                    ->label('Logo')
                    ->disk('public')
                    ->square(),
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('url')
                    ->copyable()
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Client updated')
                        ->body('The client has been updated successfully.')
                ),
                // Tables\Actions\DeleteAction::make()->successNotification(
                //     Notification::make()
                //         ->success()
                //         ->title('Client deleted')
                //         ->body('The client has been deleted successfully.')
                // ),
            ]);
        // ->bulkActions([
        //     BulkActionGroup::make([
        //         DeleteBulkAction::make(),
        //     ]),
        // ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageClients::route('/'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of clients';
    }
}
