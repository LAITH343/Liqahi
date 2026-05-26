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

namespace App\Filament\Admin\Resources\MedicalCenters;

use App\Filament\Admin\Resources\MedicalCenters\Pages\CreateMedicalCenter;
use App\Filament\Admin\Resources\MedicalCenters\Pages\EditMedicalCenter;
use App\Filament\Admin\Resources\MedicalCenters\Pages\ListMedicalCenters;
use App\Filament\Admin\Resources\MedicalCenters\Schemas\MedicalCenterForm;
use App\Filament\Admin\Resources\MedicalCenters\Tables\MedicalCentersTable;
use App\Models\MedicalCenter;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class MedicalCenterResource extends Resource
{
    protected static ?string $model = MedicalCenter::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBuildingOffice2;

    public static function getModelLabel(): string
    {
        return __('messages.medical_center');
    }

    public static function getPluralModelLabel(): string
    {
        return __('messages.medical_centers');
    }

    public static function getNavigationLabel(): string
    {
        return __('messages.medical_centers');
    }

    public static function getRecordTitleAttribute(): ?string
    {
        return app()->getLocale() === 'ar' ? 'name_ar' : 'name_en';
    }

    public static function form(Schema $schema): Schema
    {
        return MedicalCenterForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return MedicalCentersTable::configure($table);
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
            'index' => ListMedicalCenters::route('/'),
            'create' => CreateMedicalCenter::route('/create'),
            'edit' => EditMedicalCenter::route('/{record}/edit'),
        ];
    }
}
