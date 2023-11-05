<?php

namespace App\Filament\Resources;

use App\Filament\Resources\MarqueeResource\Pages;
use App\Models\Marquee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class MarqueeResource extends Resource
{
    protected static ?string $model = Marquee::class;

    protected static ?string $slug = 'marquees';

    protected static ?string $navigationGroup = 'UI Customization';

    protected static ?string $recordTitleAttribute = 'message';

    protected static ?string $navigationIcon = 'heroicon-o-megaphone';

    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\MarkdownEditor::make('message')
                            ->required()
                            ->label('Message'),

                        Forms\Components\ColorPicker::make('background_color')
                            ->required()
                            ->label('Background Color')
                            ->default('#ffffff'),

                        Forms\Components\ColorPicker::make('text_color')
                            ->required()
                            ->label('Text Color')
                            ->default('#000000'),
                            
                        Forms\Components\Toggle::make('is_visible')
                            ->label('Visible to customers.')
                            ->default(true),

                    ])
                    ->columnSpan(['lg' => fn (?Marquee $record) => $record === null ? 3 : 2]),
                Forms\Components\Section::make()
                    ->schema([
                        Forms\Components\Placeholder::make('created_at')
                            ->label('Created at')
                            ->content(fn (Marquee $record): ?string => $record->created_at?->diffForHumans()),

                        Forms\Components\Placeholder::make('updated_at')
                            ->label('Last modified at')
                            ->content(fn (Marquee $record): ?string => $record->updated_at?->diffForHumans()),
                    ])
                    ->columnSpan(['lg' => 1])
                    ->hidden(fn (?Marquee $record) => $record === null),
            ])
            ->columns(3);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('message')
                    ->label('Message')
                    ->searchable(),

                Tables\Columns\TextColumn::make('background_color')
                    ->label('Background Color'),

                Tables\Columns\TextColumn::make('text_color')
                    ->label('Text Color'),
                    
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
            ])
            ->defaultSort('created_at', 'desc');
        }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListMarquees::route('/'),
            'create' => Pages\CreateMarquee::route('/create'),
            'edit' => Pages\EditMarquee::route('/{record}/edit'),
        ];
    }
}
