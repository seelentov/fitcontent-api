<?php

namespace App\Filament\Resources\FolderResource\Pages;

use App\Filament\Resources\FolderResource;
use App\Models\Folder;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Storage;

class EditFolder extends EditRecord
{
    protected static string $resource = FolderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make()
                ->after(function (Folder $record) {
                    if ($record->icon_url) {
                        Storage::disk('public')->delete($record->icon_url);
                    }
                    if ($record->path) {
                        Storage::disk('public')->delete($record->path);
                    }
                }),

        ];
    }
}
