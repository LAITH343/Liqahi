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

use App\Models\Item;
use App\Models\MedicalCenter;
use App\Models\MedicalCenterItem;
use App\Models\User;

it('stamps last_updated_by and bumps updated_at when toggled', function () {
    $item = Item::factory()->create();
    $center = MedicalCenter::factory()->create();
    $user = User::factory()->create([
        'role' => 'staff',
        'medical_center_id' => $center->id,
    ]);

    $pivot = MedicalCenterItem::query()
        ->where('medical_center_id', $center->id)
        ->where('item_id', $item->id)
        ->firstOrFail();

    $originalUpdatedAt = $pivot->updated_at;
    sleep(1);

    $pivot->is_available = true;
    $pivot->last_updated_by = $user->id;
    $pivot->save();

    $pivot->refresh();

    expect($pivot->is_available)->toBeTrue()
        ->and($pivot->last_updated_by)->toBe($user->id)
        ->and($pivot->updated_at->greaterThan($originalUpdatedAt))->toBeTrue();
});

it('allows staff to toggle their own center but not others', function () {
    Item::factory()->create();
    $own = MedicalCenter::factory()->create();
    $other = MedicalCenter::factory()->create();
    $staff = User::factory()->create([
        'role' => 'staff',
        'medical_center_id' => $own->id,
    ]);

    $ownPivot = MedicalCenterItem::query()
        ->where('medical_center_id', $own->id)->first();
    $otherPivot = MedicalCenterItem::query()
        ->where('medical_center_id', $other->id)->first();

    expect($staff->can('update', $ownPivot))->toBeTrue();
    expect($staff->can('update', $otherPivot))->toBeFalse();
});
