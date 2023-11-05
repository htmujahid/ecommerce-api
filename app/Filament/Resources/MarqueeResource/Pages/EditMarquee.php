<?php

namespace App\Filament\Resources\MarqueeResource\Pages;

use App\Filament\Resources\MarqueeResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditMarquee extends EditRecord
{
    protected static string $resource = MarqueeResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
