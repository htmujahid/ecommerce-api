<?php

namespace App\Filament\Resources\MarqueeResource\Pages;

use App\Filament\Resources\MarqueeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListMarquees extends ListRecords
{
    protected static string $resource = MarqueeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
