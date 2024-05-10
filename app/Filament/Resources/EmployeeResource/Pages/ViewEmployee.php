<?php

namespace App\Filament\Resources\EmployeeResource\Pages;

use App\Filament\Resources\EmployeeResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewEmployee extends ViewRecord
{
    protected static string $resource = EmployeeResource::class;

    public function getHeaderActions(): array
    {
        return [
            Actions\ActionGroup::make([
                Actions\DeleteAction::make('Delete'),
                Actions\EditAction::make('Edit'),
            ])
                ->color('gray')
        ];
    }

    public function getTitle(): string
    {
        return $this->record->name;
    }
}
