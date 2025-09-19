<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TestimonialResource\Pages;
use App\Filament\Resources\TestimonialResource\RelationManagers;
use App\Models\Testimonial;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\KeyValue;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TestimonialResource extends Resource
{
    protected static ?string $model = Testimonial::class;
    protected static ?string $modelLabel = 'Testimonial';
    // protected static ?string $navigationIcon = 'heroicon-o-chat-bubble-left-ellipsis';
    protected static ?string $navigationGroup = 'Company';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make()
                    ->columns([
                        'default' => 2,
                    ])
                    ->schema([
                        FileUpload::make('photo')
                            ->image()
                            ->directory('testimonials')
                            ->visibility('public')
                            // ->minSize(512)
                            ->maxSize(1024)
                            // ->imagePreviewHeight('250') // Preview lebih besar
                            ->openable() // Bisa klik untuk buka gambar
                            ->downloadable() // Bisa unduh gambar yang sudah diupload
                            ->previewable()
                            ->required() // Wajib diisi (hapus jika ingin opsional)
                            ->columnSpanFull(),
                        TextInput::make('client_name')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('client_position')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('company')
                            ->required()
                            ->minLength(3)
                            ->maxLength(255)
                            ->columnSpan(1),
                        TextInput::make('rating')
                            ->columnSpan(1),
                        RichEditor::make('message')
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
                ImageColumn::make('photo')
                    ->label('Photo')
                    ->disk('public')
                    ->square(),
                TextColumn::make('client_name')
                    ->label('Client Name')
                    ->searchable(),
                TextColumn::make('client_position')
                    ->label('Client Position')
                    ->searchable(),
                TextColumn::make('company')
                    ->searchable(),
                TextColumn::make('rating')
                    ->searchable(),
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
            'index' => Pages\ListTestimonials::route('/'),
            'create' => Pages\CreateTestimonial::route('/create'),
            'edit' => Pages\EditTestimonial::route('/{record}/edit'),
        ];
    }
}
