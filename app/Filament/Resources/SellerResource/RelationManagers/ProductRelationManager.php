<?php

namespace App\Filament\Resources\SellerResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Notifications\Notification;
use Illuminate\Support\Str;

class ProductRelationManager extends RelationManager
{
    protected static string $relationship = 'products';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
        ->schema([
            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make()
                        ->schema([
                            Forms\Components\TextInput::make('name')
                                ->required()
                                ->live(onBlur: true)
                                ->afterStateUpdated(function (string $operation, $state, Forms\Set $set) {
                                    if ($operation !== 'create') {
                                        return;
                                    }

                                    $set('slug', Str::slug($state));
                                }),

                            Forms\Components\TextInput::make('slug')
                                ->disabled()
                                ->dehydrated()
                                ->required()
                                ->unique(Product::class, 'slug', ignoreRecord: true),

                            Forms\Components\MarkdownEditor::make('description')
                                ->columnSpan('full'),
                        ])
                        ->columns(2),

                    Forms\Components\Section::make('Images')
                        ->schema([
                            SpatieMediaLibraryFileUpload::make('media')
                                ->collection('product-images')
                                ->multiple()
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
                                ->rules(['regex:/^\d{1,6}(\.\d{0,2})?$/'])
                                ->required(),

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
                                ->unique(Product::class, 'sku', ignoreRecord: true),

                            Forms\Components\TextInput::make('barcode')
                                ->label('Barcode (ISBN, UPC, GTIN, etc.)')
                                ->unique(Product::class, 'barcode', ignoreRecord: true),

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

                    Forms\Components\Section::make('Shipping')
                        ->schema([
                            Forms\Components\Checkbox::make('backorder')
                                ->label('This product can be returned'),

                            Forms\Components\Checkbox::make('requires_shipping')
                                ->label('This product will be shipped'),
                        ])
                        ->columns(2),
                ])
                ->columnSpan(['lg' => 2]),

            Forms\Components\Group::make()
                ->schema([
                    Forms\Components\Section::make('Status')
                        ->schema([
                            Forms\Components\Toggle::make('is_visible')
                                ->label('Visible')
                                ->helperText('This product will be hidden from all sales channels.')
                                ->default(true),

                            Forms\Components\DatePicker::make('published_at')
                                ->label('Availability')
                                ->default(now())
                                ->required(),
                        ]),

                    Forms\Components\Section::make('Customization')
                        ->schema([
                            Forms\Components\Toggle::make('is_customizable')
                                ->label('Customizable')
                                ->helperText('This product can be customized.')
                                ->default(true),

                            Forms\Components\TextInput::make('customization_link')
                                ->label('Customization Link')
                                ->default(null)
                        ]),

                    Forms\Components\Section::make('Associations')
                        ->schema([
                            Forms\Components\Select::make('brand_id')
                                ->relationship('brand', 'name')
                                ->searchable()
                                ->hiddenOn(ProductsRelationManager::class),

                            Forms\Components\Select::make('categories')
                                ->relationship('categories', 'name')
                                ->multiple()
                                ->required(),
                        ]),
                ])
                ->columnSpan(['lg' => 1]),
        ])
        ->columns(3);
    }

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\SpatieMediaLibraryImageColumn::make('product-image')
                    ->label('Image')
                    ->collection('product-images'),

                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('brand.name')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\IconColumn::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('sku')
                    ->label('SKU')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('qty')
                    ->label('Quantity')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),

                Tables\Columns\TextColumn::make('security_stock')
                    ->searchable()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),

                Tables\Columns\TextColumn::make('published_at')
                    ->label('Publish Date')
                    ->date()
                    ->sortable()
                    ->toggleable()
                    ->toggledHiddenByDefault(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('brand')
                    ->relationship('brand', 'name')
                    ->preload()
                    ->multiple()
                    ->searchable(),

                Tables\Filters\TernaryFilter::make('is_visible')
                    ->label('Visibility')
                    ->boolean()
                    ->trueLabel('Only visible')
                    ->falseLabel('Only hidden')
                    ->native(false),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
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
}
