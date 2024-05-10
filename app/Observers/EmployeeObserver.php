<?php

namespace App\Observers;

use App\Models\Employee;
use App\Models\User;
use Filament\Notifications\Notification;

class EmployeeObserver
{
    public function created(Employee $employee)
    {
        Notification::make()
            ->success()
            ->title('Employee Created')
            ->body("{$employee->name} has been created.")
            ->sendToDatabase(User::where('email', 'ayasya@gmail.com')->first());
    }
}
