<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SettingResource\Pages;
use App\Filament\Resources\SettingResource\RelationManagers;
use App\Models\Setting;
use Filament\Forms;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SettingResource extends Resource
{
    protected static ?string $model = Setting::class;
    protected static ?string $modelLabel = 'Setting';
    // protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';
    protected static ?string $navigationGroup = 'Management';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                //
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('index')
                    ->label('No')
                    ->rowIndex(),
                TextColumn::make('key')
                    ->searchable(),
                TextColumn::make('value')
                    ->copyable()
                    ->limit(100)
                    ->searchable()
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make()->form(function (Setting $setting) {
                    switch ($setting->type) {
                        case 'image':
                            return [Hidden::make('key'), FileUpload::make('value')
                                ->label($setting->key)
                                ->image()
                                ->directory('settings/' . $setting->key)
                                ->visibility('public')
                                ->maxSize(1024)
                                ->nullable()];
                            break;
                        case 'textarea':
                            return [Hidden::make('key'), Textarea::make('value')
                                ->label($setting->key)
                                ->autosize()];
                            break;
                        default:
                            return [Hidden::make('key'), TextInput::make('value')
                                ->label($setting->key)];
                            break;
                    }
                })->successNotification(
                    Notification::make()
                        ->success()
                        ->title('Setting updated')
                        ->body('The setting has been updated successfully.')
                ),
                // Tables\Actions\DeleteAction::make(),
            ]);
        // ->bulkActions([
        //     Tables\Actions\BulkActionGroup::make([
        //         Tables\Actions\DeleteBulkAction::make(),
        //     ]),
        // ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSettings::route('/'),
        ];
    }
}
