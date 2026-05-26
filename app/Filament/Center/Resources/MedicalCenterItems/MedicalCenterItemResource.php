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

namespace App\Filament\Center\Resources\MedicalCenterItems;

use App\Filament\Center\Resources\MedicalCenterItems\Pages\ListMedicalCenterItems;
use App\Filament\Center\Resources\MedicalCenterItems\Tables\MedicalCenterItemsTable;
use App\Models\MedicalCenterItem;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MedicalCenterItemResource extends Resource
{
    protected static ?string $model = MedicalCenterItem::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    protected static ?string $tenantOwnershipRelationshipName = 'medicalCenter';

    public static function getModelLabel(): string
    {
        return __('messages.availability');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.item_availability');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.item_availability');
    }

    public static function table(Table $table): Table
    {
        return MedicalCenterItemsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListMedicalCenterItems::route('/'),
        ];
    }
}
