<?php

namespace App\Filament\Resources\OrderShipmentResource\Pages;

use App\Filament\Resources\OrderShipmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListOrderShipment extends ListRecords
{
    protected static string $resource = OrderShipmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
