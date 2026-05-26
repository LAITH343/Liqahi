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

namespace App\Filament\Admin\Resources\MedicalCenters\Schemas;

use App\Filament\Forms\Components\LocationPicker;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;

class MedicalCenterForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Tabs::make()
                    ->columnSpanFull()
                    ->tabs([
                        Tab::make(__('messages.arabic'))
                            ->schema([
                                TextInput::make('name_ar')
                                    ->label(__('messages.name'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('address_ar')
                                    ->label(__('messages.address'))
                                    ->required()
                                    ->rows(2),
                            ]),
                        Tab::make(__('messages.english'))
                            ->schema([
                                TextInput::make('name_en')
                                    ->label(__('messages.name'))
                                    ->required()
                                    ->maxLength(255),
                                Textarea::make('address_en')
                                    ->label(__('messages.address'))
                                    ->required()
                                    ->rows(2),
                            ]),
                    ]),
                Grid::make(2)
                    ->schema([
                        TextInput::make('phone')
                            ->label(__('messages.phone'))
                            ->tel()
                            ->maxLength(50),
                        Toggle::make('is_active')
                            ->label(__('messages.active'))
                            ->default(true)
                            ->inline(false),
                    ]),
                LocationPicker::make('location')
                    ->label(__('messages.pick_on_map'))
                    ->latStatePath('latitude')
                    ->lngStatePath('longitude')
                    ->columnSpanFull()
                    ->dehydrated(false),
                Grid::make(2)
                    ->schema([
                        TextInput::make('latitude')
                            ->label(__('messages.latitude'))
                            ->required()
                            ->numeric()
                            ->step(0.0000001)
                            ->minValue(-90)
                            ->maxValue(90),
                        TextInput::make('longitude')
                            ->label(__('messages.longitude'))
                            ->required()
                            ->numeric()
                            ->step(0.0000001)
                            ->minValue(-180)
                            ->maxValue(180),
                    ]),
            ]);
    }
}
