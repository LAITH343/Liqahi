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

use App\Models\MedicalCenter;
use App\Models\User;

it('blocks center_owner from viewing another center', function () {
    $own = MedicalCenter::factory()->create();
    $other = MedicalCenter::factory()->create();
    $owner = User::factory()->create([
        'role' => 'center_owner',
        'medical_center_id' => $own->id,
    ]);

    expect($owner->can('view', $own))->toBeTrue();
    expect($owner->can('view', $other))->toBeFalse();
    expect($owner->can('update', $other))->toBeFalse();
});

it('lets center_owner update only own-center staff', function () {
    $own = MedicalCenter::factory()->create();
    $other = MedicalCenter::factory()->create();
    $owner = User::factory()->create([
        'role' => 'center_owner',
        'medical_center_id' => $own->id,
    ]);
    $myStaff = User::factory()->create([
        'role' => 'staff',
        'medical_center_id' => $own->id,
    ]);
    $foreignStaff = User::factory()->create([
        'role' => 'staff',
        'medical_center_id' => $other->id,
    ]);

    expect($owner->can('update', $myStaff))->toBeTrue();
    expect($owner->can('update', $foreignStaff))->toBeFalse();
});
