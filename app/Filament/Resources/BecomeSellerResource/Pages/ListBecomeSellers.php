<?php

namespace App\Filament\Resources\BecomeSellerResource\Pages;

use App\Filament\Resources\BecomeSellerResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBecomeSellers extends ListRecords
{
    protected static string $resource = BecomeSellerResource::class;

    protected function getActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
