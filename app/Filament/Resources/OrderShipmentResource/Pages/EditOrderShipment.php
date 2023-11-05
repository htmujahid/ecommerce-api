<?php

namespace App\Filament\Resources\OrderShipmentResource\Pages;

use App\Filament\Resources\OrderShipmentResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditOrderShipment extends EditRecord
{
    protected static string $resource = OrderShipmentResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
