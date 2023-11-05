<?php

namespace App\Filament\Resources\ContactMessageResource\Pages;

use App\Filament\Resources\ContactMessageResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListContactMessage extends ListRecords
{
    protected static string $resource = ContactMessageResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
