<?php

namespace App\Filament\Resources\ProductResource\RelationManagers;

use App\Models\ProductVariant;
use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;

class ProductVariantsRelationManager extends RelationManager
{
    protected static string $relationship = 'productVariants';

    protected static ?string $recordTitleAttribute = 'title';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Group::make()
                    ->schema([
                        Forms\Components\Section::make()
                            ->schema([
                                // title select like size, color, default
                                Forms\Components\Select::make('title')
                                    ->options(['default' => 'Default', 'color' => 'Color'])
                                    ->required(),

                                Forms\Components\TextInput::make('type')
                                    ->label('Type (e.g. "Large", "Red", "Default")')
                                    ->required(),
                                
                                Forms\Components\Toggle::make('is_visible')
                                    ->label('Visible')
                                    ->helperText('This product will be hidden from all sales channels.')
                                    ->default(true),
                            ])
                            ->columns(2),
                        
                        Forms\Components\Section::make('Images')
                            ->schema([
                                SpatieMediaLibraryFileUpload::make('media')
                                    ->collection('product-variant-images')
                                    ->multiple()
                                    ->required()
                                    ->maxFiles(5)
                                    ->disableLabel(),
                            ])
                            ->collapsible(),
                        
                        
                        Forms\Components\Section::make('Pricing')
                            ->schema([
                                Forms\Components\TextInput::make('price')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->required(),

                                Forms\Components\TextInput::make('old_price')
                                    ->label('Compare at price')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/']),

                                Forms\Components\TextInput::make('cost')
                                    ->label('Cost per item')
                                    ->helperText('Customers won\'t see this price.')
                                    ->numeric()
                                    ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                    ->required(),
                            ])
                            ->columns(2),
                        
                        
                        Forms\Components\Section::make('Inventory')
                            ->schema([
                                Forms\Components\TextInput::make('sku')
                                    ->label('SKU (Stock Keeping Unit)')
                                    ->unique(ProductVariant::class, 'sku', ignoreRecord: true),

                                Forms\Components\TextInput::make('barcode')
                                    ->label('Barcode (ISBN, UPC, GTIN, etc.)')
                                    ->unique(ProductVariant::class, 'barcode', ignoreRecord: true),

                                Forms\Components\TextInput::make('qty')
                                    ->label('Quantity')
                                    ->numeric()
                                    ->rules(['integer', 'min:0'])
                                    ->required(),

                                Forms\Components\TextInput::make('security_stock')
                                    ->helperText('The safety stock is the limit stock for your products which alerts you if the product stock will soon be out of stock.')
                                    ->numeric()
                                    ->rules(['integer', 'min:0'])
                                    ->required(),
                            ])
                            ->columns(2),
                    ]),
                ])
            ->columns(1);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('product-variant-image')
                    ->label('Image')
                    ->collection('product-variant-images'),
                
                Tables\Columns\TextColumn::make('title')
                    ->label('Title')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('type')
                    ->label('Type')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->sortable(),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->searchable()
                    ->sortable(),
            ])
            ->filters([
                //
            ])            
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->after(function ($record) {
                        Notification::make()
                            ->title('New Variant')
                            ->icon('heroicon-o-chat-bubble-bottom-center-text')
                            // ->body("**{$record->customer->name} commented on product ({$record->commentable->name}).**")
                            ->sendToDatabase(auth()->user());
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->groupedBulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
