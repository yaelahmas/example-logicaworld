<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ServiceResource\Pages;
use App\Filament\Resources\ServiceResource\RelationManagers;
use App\Models\Service;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class ServiceResource extends Resource
{
    protected static ?string $model = Service::class;
    protected static ?string $modelLabel = 'Service';
    // protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    protected static ?string $navigationGroup = 'Company';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'default' => 2,
                    ])
                    ->schema([
                        FileUpload::make('icon')
                            ->image()
                            ->directory('services/icon')
                            ->visibility('public')
                            // ->minSize(512)
                            ->maxSize(1024)
                            // ->imagePreviewHeight('250') // Preview lebih besar
                            ->openable() // Bisa klik untuk buka gambar
                            ->downloadable() // Bisa unduh gambar yang sudah diupload
                            ->previewable()
                            ->required() // Wajib diisi (hapus jika ingin opsional)
                            ->columnSpan(1),
                        FileUpload::make('image')
                            ->image()
                            ->directory('services/image')
                            ->visibility('public')
                            // ->minSize(512)
                            ->maxSize(1024)
                            // ->imagePreviewHeight('250') // Preview lebih besar
                            ->openable() // Bisa klik untuk buka gambar
                            ->downloadable() // Bisa unduh gambar yang sudah diupload
                            ->previewable()
                            ->required() // Wajib diisi (hapus jika ingin opsional)
                            ->columnSpan(1),
                        TextInput::make('title')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->live(true)
                            ->afterStateUpdated(
                                fn(Set $set, ?string $state) =>
                                $set('slug', Str::slug($state))
                            )
                            ->columnSpan(1),
                        TextInput::make('slug')
                            ->readOnly()
                            ->columnSpan(1),
                        RichEditor::make('description')
                            ->required()
                            ->minLength(3)
                            ->columnSpanFull(),
                    ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                ImageColumn::make('icon')
                    ->label('Icon')
                    ->disk('public')
                    ->circular(),
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->searchable(),
                TextColumn::make('slug')
                    ->searchable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Service deleted')
                        ->body('The service has been deleted successfully.')
                ),
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
            'index' => Pages\ListServices::route('/'),
            'create' => Pages\CreateService::route('/create'),
            'edit' => Pages\EditService::route('/{record}/edit'),
        ];
    }
}
