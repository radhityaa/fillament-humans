<?php

namespace App\Filament\Resources\LeaveRequestResource\Pages;

use App\Filament\Resources\LeaveRequestResource;
use App\Filament\Resources\LeaveRequestResource\Widgets\LeaveRequestStats;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListLeaveRequests extends ListRecords
{
    protected static string $resource = LeaveRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->slideOver()
                ->modalWidth('2xl'),
        ];
    }

    protected function getHeaderWidgets(): array
    {
        return [
            LeaveRequestStats::class,
        ];
    }
}
