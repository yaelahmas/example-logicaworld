<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BannerResource\Pages;
use App\Filament\Resources\BannerResource\RelationManagers;
use App\Models\Banner;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BannerResource extends Resource
{
    protected static ?string $model = Banner::class;
    protected static ?string $modelLabel = 'Banner';
    // protected static ?string $navigationIcon = 'heroicon-o-photo';
    protected static ?string $navigationGroup = 'Company';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'default' => 3,
                    ])
                    ->schema([
                        FileUpload::make('image')
                            ->image()
                            ->directory('banners')
                            ->visibility('public')
                            // ->minSize(512)
                            ->maxSize(1024)
                            // ->imagePreviewHeight('250') // Preview lebih besar
                            ->openable() // Bisa klik untuk buka gambar
                            ->downloadable() // Bisa unduh gambar yang sudah diupload
                            ->previewable()
                            ->required() // Wajib diisi (hapus jika ingin opsional)
                            ->columnSpanFull(),
                        TextInput::make('title')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->columnSpan(2),
                        TextInput::make('subtitle')
                            ->label('Sub Title')
                            ->nullable()
                            ->minLength(3)
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('link_url')
                            ->label('Link Url')
                            ->url()
                            ->nullable()
                            ->columnSpan(2),
                        TextInput::make('order_no')
                            ->label('Order')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->columnSpan(1),
                        Toggle::make('is_active')
                            ->label('Active')
                            ->default(true)
                            ->columnSpan(1)
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
                ImageColumn::make('image')
                    ->label('Image')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Title')
                    ->searchable(),
                TextColumn::make('subtitle')
                    ->label('Sub Title')
                    ->searchable(),
                TextColumn::make('link_url')
                    ->label('Link Url')
                    ->copyable()
                    ->searchable(),
                TextColumn::make('order_no')
                    ->label('Order'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('created_at')
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                // Tables\Actions\DeleteAction::make(),
            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ]);
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
            'index' => Pages\ListBanners::route('/'),
            'create' => Pages\CreateBanner::route('/create'),
            'edit' => Pages\EditBanner::route('/{record}/edit'),
        ];
    }
}
