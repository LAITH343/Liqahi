<?php

/*
 * Liqahi - Vaccine availability locator for Iraqi medical centers
 * Copyright (C) 2026  Laith
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace App\Filament\Center\Resources\MedicalCenterItems\Tables;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class MedicalCenterItemsTable
{
    public static function configure(Table $table): Table
    {
        $nameColumn = app()->getLocale() === 'ar' ? 'item.name_ar' : 'item.name_en';

        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['item', 'lastUpdatedBy']))
            ->columns([
                TextColumn::make($nameColumn)
                    ->label(__('messages.item'))
                    ->searchable(['name_ar', 'name_en'])
                    ->sortable(),
                ToggleColumn::make('is_available')
                    ->label(__('messages.available'))
                    ->beforeStateUpdated(function (Model $record): void {
                        $record->last_updated_by = auth()->id();
                    })
                    ->disabled(fn () => ! auth()->user()?->can('toggle_availability')),
                TextColumn::make('lastUpdatedBy.name')
                    ->label(__('messages.last_updated_by'))
                    ->placeholder('—'),
                TextColumn::make('updated_at')
                    ->label(__('messages.last_updated_at'))
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                TernaryFilter::make('is_available')
                    ->label(__('messages.available')),
            ])
            ->recordActions([])
            ->toolbarActions([]);
    }
}
