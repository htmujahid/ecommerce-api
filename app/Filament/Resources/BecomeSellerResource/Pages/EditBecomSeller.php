<?php

namespace App\Filament\Resources\BecomeSellerResource\Pages;

use App\Filament\Resources\BecomeSellerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditBecomeSeller extends EditRecord
{
    protected static string $resource = BecomeSellerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
