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

function makeUser(string $role, ?MedicalCenter $center = null): User
{
    return User::factory()->create([
        'role' => $role,
        'medical_center_id' => $center?->id,
    ]);
}

it('lets super_admin reach /admin and rejects /center', function () {
    $admin = makeUser('super_admin');

    $this->actingAs($admin)->get('/admin')->assertSuccessful();
    $this->actingAs($admin)->get('/center')->assertForbidden();
});

it('lets center_owner reach /center but not /admin', function () {
    $center = MedicalCenter::factory()->create();
    $owner = makeUser('center_owner', $center);

    $this->actingAs($owner)->get('/admin')->assertForbidden();
    $this->actingAs($owner)->get('/center')->assertRedirect(); // tenant redirect
});

it('lets staff reach /center but not /admin', function () {
    $center = MedicalCenter::factory()->create();
    $staff = makeUser('staff', $center);

    $this->actingAs($staff)->get('/admin')->assertForbidden();
    $this->actingAs($staff)->get('/center')->assertRedirect();
});

it('rejects center user with no center from /center', function () {
    $orphan = User::factory()->create([
        'role' => 'staff',
        'medical_center_id' => null,
    ]);

    $this->actingAs($orphan)->get('/center')->assertForbidden();
});
