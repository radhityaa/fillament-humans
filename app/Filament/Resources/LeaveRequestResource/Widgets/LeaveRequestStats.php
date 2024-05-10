<?php

namespace App\Filament\Resources\LeaveRequestResource\Widgets;

use App\Enums\LeaveRequestStatus;
use App\Models\LeaveRequest;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LeaveRequestStats extends BaseWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Pending leave request', LeaveRequest::where('status', LeaveRequestStatus::PENDING)->count())
                ->description('The number of pending leave request')
                ->icon('heroicon-o-exclamation-circle')
                ->color('warning'),
            Stat::make('Approved leave request', LeaveRequest::where('status', LeaveRequestStatus::APPROVED)->count())
                ->description('The number of approved leave request')
                ->icon('heroicon-o-check-circle')
                ->color('success'),
            Stat::make('Rejected leave request', LeaveRequest::where('status', LeaveRequestStatus::REJECTED)->count())
                ->description('The number of rejected leave request')
                ->icon('heroicon-o-minus-circle')
                ->color('danger'),
        ];
    }
}
