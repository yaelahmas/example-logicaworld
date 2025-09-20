<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PostResource\Pages;
use App\Filament\Resources\PostResource\RelationManagers;
use App\Models\Post;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Forms\Set;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class PostResource extends Resource
{
    protected static ?string $model = Post::class;
    protected static ?string $modelLabel = 'Post';
    // protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationGroup = 'Content';
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
                        FileUpload::make('thumbnail')
                            ->image()
                            ->directory('posts')
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
                            ->live(true)
                            ->afterStateUpdated(
                                fn(Set $set, ?string $state) =>
                                $set('slug', Str::slug($state))
                            )
                            ->columnSpan(1),
                        TextInput::make('slug')
                            ->readOnly()
                            ->columnSpan(1),
                        RichEditor::make('content')
                            ->required()
                            ->minLength(3)
                            ->columnSpanFull(),
                        Select::make('category_id')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->columnSpan(1),
                        Select::make('status')
                            ->label('Status')
                            ->options([
                                'draft' => 'Draft',
                                'published' => 'Published',
                            ])
                            ->required()
                            ->columnSpan(1),
                        Select::make('tags')
                            ->relationship('tags', 'name')
                            ->searchable()
                            ->required()
                            ->preload()
                            ->multiple()
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
                ImageColumn::make('thumbnail')
                    ->label('Thumbnail')
                    ->disk('public')
                    ->square(),
                TextColumn::make('title')
                    ->label('Title'),
                TextColumn::make('category.name')
                    ->label('Category'),
                // TextColumn::make('tags.name')
                //     ->label('Tags'),
                TextColumn::make('user.name')
                    ->label('Author'),
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'draft' => 'gray',
                        'published' => 'success',
                    }),
                TextColumn::make('published_at')
                    ->label('Published At')
                    ->since(),
                TextColumn::make('updated_at')
                    ->since(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make()->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Post deleted')
                        ->body('The post has been deleted successfully.')
                ),
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
            'index' => Pages\ListPosts::route('/'),
            'create' => Pages\CreatePost::route('/create'),
            'edit' => Pages\EditPost::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getNavigationBadgeTooltip(): ?string
    {
        return 'The number of posts';
    }
}
