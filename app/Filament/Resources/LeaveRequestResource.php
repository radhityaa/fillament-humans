<?php

namespace App\Filament\Resources;

use App\Enums\LeaveRequestStatus;
use App\Enums\LeaveRequestType;
use App\Filament\Resources\EmployeeResource\RelationManagers\LeaveRequestsRelationManager;
use App\Filament\Resources\LeaveRequestResource\Pages;
use App\Filament\Resources\LeaveRequestResource\RelationManagers;
use App\Filament\Resources\LeaveRequestResource\Widgets\LeaveRequestStats;
use App\Models\LeaveRequest;
use App\Traits\DefaultCounterNavigationBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Actions\ActionGroup;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Collection;

class LeaveRequestResource extends Resource
{
    use DefaultCounterNavigationBadge;

    protected static ?string $model = LeaveRequest::class;
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?string $navigationIcon = 'heroicon-o-calendar';
    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormFields());
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns(self::getTableColumns())
            ->filters([
                //
            ])
            ->actions([
                ActionGroup::make([
                    Tables\Actions\EditAction::make()
                        ->slideOver()
                        ->modalWidth('2xl'),
                    Tables\Actions\Action::make('approve')
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-m-check-circle')
                        ->visible(fn (LeaveRequest $record) => $record->status === LeaveRequestStatus::PENDING)
                        ->action(fn (LeaveRequest $record) => $record->approve())
                        ->after(function () {
                            Notification::make()
                                ->success()
                                ->title('Approved!')
                                ->body('The leave request has been Approved.')
                                ->send();
                        }),
                    Tables\Actions\Action::make('reject')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-m-minus-circle')
                        ->visible(fn (LeaveRequest $record) => $record->status === LeaveRequestStatus::PENDING)
                        ->action(fn (LeaveRequest $record) => $record->reject())
                        ->after(function () {
                            Notification::make()
                                ->danger()
                                ->title('Rejected!')
                                ->body('The leave request has been Rejected.')
                                ->send();
                        }),
                    Tables\Actions\Action::make('divider')
                        ->label('')
                        ->disabled(),
                    Tables\Actions\DeleteAction::make()
                ])
                    ->color('gray'),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\BulkAction::make('approve')
                        ->requiresConfirmation()
                        ->color('success')
                        ->icon('heroicon-m-check-circle')
                        ->action(fn (Collection $records) => $records->each->approve())
                        ->after(function () {
                            Notification::make()
                                ->success()
                                ->title('Approved!')
                                ->body('The leave request has been Approved.')
                                ->send();
                        }),
                    Tables\Actions\BulkAction::make('reject')
                        ->requiresConfirmation()
                        ->color('danger')
                        ->icon('heroicon-m-minus-circle')
                        ->action(fn (Collection $records) => $records->each->reject())
                        ->after(function () {
                            Notification::make()
                                ->danger()
                                ->title('Rejectd!')
                                ->body('The leave request has been Rejectd.')
                                ->send();
                        }),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListLeaveRequests::route('/'),
            // 'create' => Pages\CreateLeaveRequest::route('/create'),
            // 'edit' => Pages\EditLeaveRequest::route('/{record}/edit'),
        ];
    }

    public static function getFormFields(): array
    {
        return [
            Forms\Components\Select::make('employee_id')
                ->searchable()
                ->preload()
                ->relationship('employee', 'name')
                ->prefixIcon('heroicon-o-user')
                ->hiddenOn(LeaveRequestsRelationManager::class)
                ->required(),
            Forms\Components\Fieldset::make('Start Ending')
                ->columns(2)
                ->schema([
                    Forms\Components\DatePicker::make('start_date')
                        ->native(false)
                        ->default(now()->addMonth(6)->startOfMonth())
                        ->required(),
                    Forms\Components\DatePicker::make('end_date')
                        ->native(false)
                        ->default(now()->addMonth(6)->startOfMonth())
                        ->required(),
                ]),
            Forms\Components\Select::make('type')
                ->enum(LeaveRequestType::class)
                ->options(LeaveRequestType::class)
                ->required(),
            Forms\Components\Select::make('status')
                ->enum(LeaveRequestStatus::class)
                ->options(LeaveRequestStatus::class)
                ->required(),
            Forms\Components\RichEditor::make('reason')
                ->columnSpanFull(),
        ];
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('employee.name')
                ->hiddenOn(LeaveRequestsRelationManager::class)
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('start_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('end_date')
                ->date()
                ->sortable(),
            Tables\Columns\TextColumn::make('type')
                ->searchable(),
            Tables\Columns\TextColumn::make('status')
                ->badge()
                ->color(fn ($state) => $state->getColor())
                ->searchable(),
            Tables\Columns\TextColumn::make('created_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            Tables\Columns\TextColumn::make('updated_at')
                ->dateTime()
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
        ];
    }

    public static function getWidgets(): array
    {
        return [
            LeaveRequestStats::class,
        ];
    }
}
