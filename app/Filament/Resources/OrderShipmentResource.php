<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderShipmentResource\Pages;
use App\Models\OrderShipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class OrderShipmentResource extends Resource
{
    protected static ?string $model = OrderShipment::class;

    protected static ?string $slug = 'order-shipments';

    protected static ?string $navigationGroup = 'Settings';

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\TextInput::make('price')
                            ->required()
                            ->numeric()
                            ->label('Price'),

                        Forms\Components\Toggle::make('is_threshold')
                            ->label('Is Threshold')
                            ->default(false),

                        Forms\Components\TextInput::make('threshold_price')
                            ->numeric()
                            ->label('Threshold Price'),
                    ])
                    ->columnSpan(['lg' => fn (?OrderShipment $record) => $record === null ? 3 : 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (OrderShipment $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (OrderShipment $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?OrderShipment $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('price')
                    ->label('Price')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_threshold')
                    ->label('Threshold')
                    ->boolean()
                    ->sortable(),
                Tables\Columns\TextColumn::make('threshold_price')
                    ->label('Threshold Price')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('updated_at')
                    ->label('Updated Date')
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
            ])
            ->defaultSort('created_at')
            ->reorderable('created_at');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrderShipment::route('/'),
            'create' => Pages\CreateOrderShipment::route('/create'),
            'edit' => Pages\EditOrderShipment::route('/{record}/edit'),
        ];
    }
}
