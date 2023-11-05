<?php

namespace App\Filament\Resources;

use App\Filament\Resources\SellerResource\Pages;
use App\Filament\Resources\SellerResource\RelationManagers;
use App\Models\Seller;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Squire\Models\Country;

class SellerResource extends Resource
{
    protected static ?string $model = Seller::class;

    protected static ?string $slug = 'sellers';

    protected static ?string $recordTitleAttribute = 'name';

    protected static ?string $navigationGroup = 'Shop';

    protected static ?string $navigationIcon = 'heroicon-o-user-group';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->maxValue(50)
                            ->required(),

                        Forms\Components\TextInput::make('email')
                            ->label('Email address')
                            ->required()
                            ->email()
                            ->unique(ignoreRecord: true),

                        Forms\Components\TextInput::make('phone')
                            ->maxValue(50),

                        Forms\Components\DatePicker::make('birthday')
                            ->maxDate('today'),

                        Forms\Components\Toggle::make('featured')
                            ->label('Featured')
                            ->helperText('This is a featured product')
                            ->default(false),
                    ])
                    ->columns(2)
                    ->columnSpan(['lg' => fn (?Seller $record) => $record === null ? 3 : 2]),
                
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Seller $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Seller $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Seller $record) => $record === null),
                    
                Forms\Components\Section::make('Display picture')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->collection('seller-images')
                            ->disableLabel(),
                    ])
                    ->collapsible(),  

                Forms\Components\Section::make('Cover picture')
                    ->schema([
                        SpatieMediaLibraryFileUpload::make('media')
                            ->collection('seller-images')
                            ->disableLabel(),
                    ])
                    ->collapsible(),  

            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('seller-image')
                    ->label('Image')
                    ->collection('seller-images'),

                Tables\Columns\TextColumn::make('name')
                    ->sortable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email address')
                    ->sortable(),
                Tables\Columns\IconColumn::make('featured')
                    ->label('Featured')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('phone')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\TrashedFilter::make(),
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

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('addresses')->withoutGlobalScope(SoftDeletingScope::class);
    }

    public static function getRelations(): array
    {
        return [
            RelationManagers\ProductRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSellers::route('/'),
            'create' => Pages\CreateSeller::route('/create'),
            'edit' => Pages\EditSeller::route('/{record}/edit'),
        ];
    }

    public static function getGloballySearchableAttributes(): array
    {
        return ['name', 'email'];
    }
}
