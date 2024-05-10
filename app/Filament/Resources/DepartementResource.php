<?php

namespace App\Filament\Resources;

use App\Filament\Resources\DepartementResource\Pages;
use App\Filament\Resources\DepartementResource\RelationManagers;
use App\Models\Departement;
use App\Traits\DefaultCounterNavigationBadge;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class DepartementResource extends Resource
{
    use DefaultCounterNavigationBadge;

    protected static ?string $recordTitleAttribute = 'name';
    protected static ?string $model = Departement::class;
    protected static ?string $navigationGroup = 'Resources';
    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema(self::getFormFields());
    }

    public static function getFormFields(): array
    {
        return [
            Forms\Components\TextInput::make('name')
                ->required()
                ->maxLength(255),
            Forms\Components\Textarea::make('description'),
            Forms\Components\Toggle::make('active'),

        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('active'),
                // Tables\Columns\TextColumn::make('active')
                //     ->formatStateUsing(fn ($state) => $state === 1 ? 'Active' : 'Inactive')
                //     ->badge()
                //     ->color(fn ($state) => $state === 1 ? 'success' : 'warning'),
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
                Tables\Filters\TernaryFilter::make('active')
                    ->boolean()
                // Tables\Filters\Filter::make('active')
                //     ->toggle()
                //     ->modifyQueryUsing(fn (Builder $query) => $query->where('active', true)),
                // Tables\Filters\Filter::make('inactive')
                //     ->toggle()
                //     ->modifyQueryUsing(fn (Builder $query) => $query->where('active', false)),
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
            'index' => Pages\ManageDepartements::route('/'),
        ];
    }
}
