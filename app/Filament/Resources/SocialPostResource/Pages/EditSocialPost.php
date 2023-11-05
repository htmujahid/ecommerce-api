<?php

namespace App\Filament\Resources\SocialPostResource\Pages;

use App\Filament\Resources\SocialPostResource;
use Filament\Pages\Actions;
use Filament\Resources\Pages\EditRecord;

class EditSocialPost extends EditRecord
{
    protected static string $resource = SocialPostResource::class;

    protected function getActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
