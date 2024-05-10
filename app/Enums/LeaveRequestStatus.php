<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum LeaveRequestStatus: string implements HasLabel
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case REJECTED = 'rejected';

    public function getLabel(): ?string
    {
        return str($this->value)->title();
    }

    public function getColor(): string
    {
        return match ($this) {
            self::APPROVED => 'success',
            self::PENDING => 'warning',
            self::REJECTED => 'danger',
        };
    }
}
