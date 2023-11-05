<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SocialPostResource\Pages;
use App\Models\SocialPost;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SocialPostResource extends Resource
{
    protected static ?string $model = SocialPost::class;

    protected static ?string $slug = 'social-posts';

    protected static ?string $navigationGroup = 'UI Customization';

    protected static ?string $navigationIcon = 'heroicon-o-link';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('link')
                            ->autofocus()
                            ->required()
                            ->placeholder('Enter link')
                            ->label('Link')
                            ->url(),
                            
                        Forms\Components\Toggle::make('is_visible')
                            ->label('Visible to customers.')
                            ->default(true),
                    ])
                    ->columnSpan(['lg' => fn (?SocialPost $record) => $record === null ? 3 : 2]),

                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (SocialPost $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (SocialPost $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?SocialPost $record) => $record === null),

                Forms\Components\Section::make('Images')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->required()
                            ->collection('social-post-images')
                            ->multiple()
                            ->maxFiles(5)
                            ->disableLabel(),
                    ])
                    ->columnSpan(['lg' => fn (?SocialPost $record) => $record === null ? 3 : 2]),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('social-post-image')
                ->label('Image')
                ->collection('social-post-images'),
                
                Tables\Columns\TextColumn::make('link')
                    ->label('Link')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created Date')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make()
                    ->action(function () {
                        Notification::make()
                            ->title('Now, now, don\'t be cheeky, leave some records for others to play with!')
                            ->warning()
                            ->send();
                    }),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSocialPosts::route('/'),
            'create' => Pages\CreateSocialPost::route('/create'),
            'edit' => Pages\EditSocialPost::route('/{record}/edit'),
        ];
    }
}
