<?php

namespace App\Filament\Resources;

use App\Enums\EmployeeStatus;
use App\Filament\Resources\EmployeeResource\Pages;
use App\Filament\Resources\EmployeeResource\RelationManagers;
use App\Filament\Resources\EmployeeResource\RelationManagers\LeaveRequestsRelationManager;
use App\Filament\Resources\EmployeeResource\RelationManagers\SalariesRelationManager;
use App\Models\Departement;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Infolists;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class EmployeeResource extends Resource
{
    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $model = Employee::class;
    protected static ?string $navigationGroup = 'Employee Management';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('email')
                    ->prefixIcon('heroicon-o-envelope')
                    ->email()
                    ->required()
                    ->maxLength(255),
                Forms\Components\Fieldset::make('Attribute')
                    ->columns(3)
                    ->schema([
                        Forms\Components\Select::make('departement_id')
                            ->relationship('departement', 'name')
                            ->options(Departement::query()->whereActive(true)->get()->pluck('name', 'id'))
                            ->searchable()
                            ->preload()
                            ->label('Departement Name')
                            ->editOptionForm(fn () => DepartementResource::getFormFields())
                            ->required(),
                        Forms\Components\Select::make('position_id')
                            ->relationship('position', 'name')
                            ->searchable()
                            ->preload()
                            ->label('Employee Position')
                            ->createOptionForm(fn () => PositionResource::getFormFields())
                            ->required(),
                        Forms\Components\Select::make('status')
                            ->enum(EmployeeStatus::class)
                            ->options(EmployeeStatus::class)
                            ->required(),
                    ]),
                Forms\Components\DatePicker::make('joined')
                    ->native(false)
                    ->required(),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with('position:id,name');
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->description(fn (Employee $record) => $record->email)
                    ->searchable(),
                Tables\Columns\TextColumn::make('departement.name')
                    ->description(fn (Employee $record) => 'Position: ' . $record->position->name)
                    ->sortable(),
                Tables\Columns\TextColumn::make('joined')
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->date()
                    ->formatStateUsing(fn ($state) => $state->format('d F Y'))
                    ->sortable(),
                // Tables\Columns\SelectColumn::make('status')
                //     ->options(EmployeeStatus::class),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    // ->iconPosition('after')
                    ->icon(fn ($state) => $state->getIcon())
                    ->color(fn ($state) => $state->getColor()),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(EmployeeStatus::class)
            ])
            ->actions([
                Tables\Actions\ActionGroup::make([
                    Tables\Actions\ViewAction::make(),
                    Tables\Actions\EditAction::make(),
                    Tables\Actions\DeleteAction::make(),
                ])
                    ->color('gray')
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            SalariesRelationManager::class,
            LeaveRequestsRelationManager::class
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
            'view' => Pages\ViewEmployee::route('/{record}'),
        ];
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist->schema([
            Infolists\Components\Fieldset::make('Attributes')
                ->columns(1)
                ->schema([
                    Infolists\Components\Group::make()
                        ->columns(2)
                        ->schema([
                            Infolists\Components\TextEntry::make('name'),
                            Infolists\Components\TextEntry::make('email'),
                        ]),
                    Infolists\Components\TextEntry::make('departement.name'),
                    Infolists\Components\TextEntry::make('position.name'),
                    Infolists\Components\TextEntry::make('joined')
                        ->date()
                        ->formatStateUsing(fn ($state) => $state->format('d F Y')),
                    Infolists\Components\TextEntry::make('status')
                        ->badge()
                        ->color(fn ($state) => $state->getColor()),
                ]),
        ]);
    }

    public static function getNavigationBadge(): ?string
    {
        return static::$model::where('status', EmployeeStatus::ACTIVE)->count();
    }
}
