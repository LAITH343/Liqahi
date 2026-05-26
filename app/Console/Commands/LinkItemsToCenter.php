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

namespace App\Console\Commands;

use App\Models\Item;
use App\Models\MedicalCenter;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('liqahi:link-items {center_id? : The center to link items to; omit for all}')]
#[Description('Create missing medical_center_item pivot rows so a center starts with the full item catalog.')]
class LinkItemsToCenter extends Command
{
    public function handle(): int
    {
        $centerId = $this->argument('center_id');

        $centers = $centerId
            ? MedicalCenter::whereKey($centerId)->get()
            : MedicalCenter::all();

        if ($centers->isEmpty()) {
            $this->error('No matching centers found.');

            return self::FAILURE;
        }

        $itemIds = Item::where('is_active', true)->pluck('id');
        $created = 0;

        foreach ($centers as $center) {
            $existing = $center->items()->pluck('items.id')->all();
            $missing = $itemIds->diff($existing);

            foreach ($missing as $itemId) {
                $center->items()->attach($itemId, ['is_available' => false]);
                $created++;
            }
        }

        $this->info("Linked {$created} item(s) across {$centers->count()} center(s).");

        return self::SUCCESS;
    }
}
