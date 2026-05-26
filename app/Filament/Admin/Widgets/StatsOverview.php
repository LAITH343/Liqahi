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

namespace App\Filament\Admin\Widgets;

use App\Models\Item;
use App\Models\MedicalCenter;
use App\Models\MedicalCenterItem;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class StatsOverview extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $total = MedicalCenterItem::query()->count();
        $available = MedicalCenterItem::query()->where('is_available', true)->count();
        $pct = $total > 0 ? round(($available / $total) * 100, 1) : 0;

        return [
            Stat::make(__('messages.totals_centers'), MedicalCenter::query()->count()),
            Stat::make(__('messages.totals_items'), Item::query()->count()),
            Stat::make(__('messages.totals_availability'), $pct.'%'),
        ];
    }
}
