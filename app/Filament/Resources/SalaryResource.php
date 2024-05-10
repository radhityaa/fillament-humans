<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\RelationManagers\SalariesRelationManager;
use App\Filament\Resources\SalaryResource\Pages;
use App\Filament\Resources\SalaryResource\RelationManagers;
use App\Models\Salary;
use App\Traits\DefaultCounterNavigationBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Support\RawJs;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class SalaryResource extends Resource
{
    use DefaultCounterNavigationBadge;

    protected static ?string $model = Salary::class;
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?string $navigationIcon = 'heroicon-o-banknotes';
    protected static ?int $navigationSort = 2;

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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ManageSalaries::route('/'),
        ];
    }

    public static function getFormFields(): array
    {
        return [
            Forms\Components\Select::make('employee_id')
                ->relationship('employee', 'name')
                ->searchable()
                ->preload()
                ->hiddenOn(SalariesRelationManager::class)
                ->required(),
            Forms\Components\TextInput::make('amount')
                ->prefix('Rp ')
                ->mask(RawJs::make('$money($input)'))
                ->required(),
            Forms\Components\DatePicker::make('effective_date')
                ->native(false)
                ->default(now()->addMonth(6)->startOfMonth())
                ->required(),
        ];
    }

    public static function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('employee.name')
                ->hiddenOn(SalariesRelationManager::class)
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('amount')
                ->prefix('Rp ')
                ->numeric()
                ->sortable(),
            Tables\Columns\TextColumn::make('effective_date')
                ->date()
                ->sortable(),
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
}
