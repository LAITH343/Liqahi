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

use App\Filament\Center\Resources\Staff\Pages\CreateStaff;
use App\Filament\Center\Resources\Staff\Pages\EditStaff;
use App\Models\MedicalCenter;
use App\Models\User;
use Filament\Facades\Filament;
use Livewire\Livewire;

beforeEach(function () {
    Filament::setCurrentPanel(Filament::getPanel('center'));
});

it('forces a created staff member to role staff in the owner tenant despite tampered input', function () {
    $center = MedicalCenter::factory()->create();
    $otherCenter = MedicalCenter::factory()->create();
    $owner = User::factory()->create([
        'role' => 'center_owner',
        'medical_center_id' => $center->id,
    ]);

    $this->actingAs($owner);
    Filament::setTenant($center);

    Livewire::test(CreateStaff::class)
        ->fillForm([
            'name' => 'New Staff',
            'email' => 'new-staff@example.com',
            'password' => 'a-strong-password',
            'role' => 'super_admin',
            'medical_center_id' => $otherCenter->id,
        ])
        ->call('create')
        ->assertHasNoFormErrors();

    $created = User::where('email', 'new-staff@example.com')->firstOrFail();

    expect($created->role)->toBe('staff')
        ->and($created->medical_center_id)->toBe($center->id)
        ->and($created->hasRole('super_admin'))->toBeFalse()
        ->and($created->hasRole('staff'))->toBeTrue();
});

it('prevents a center owner from promoting existing staff via tampered edit input', function () {
    $center = MedicalCenter::factory()->create();
    $otherCenter = MedicalCenter::factory()->create();
    $owner = User::factory()->create([
        'role' => 'center_owner',
        'medical_center_id' => $center->id,
    ]);
    $staff = User::factory()->create([
        'role' => 'staff',
        'medical_center_id' => $center->id,
    ]);

    $this->actingAs($owner);
    Filament::setTenant($center);

    Livewire::test(EditStaff::class, ['record' => $staff->getRouteKey()])
        ->fillForm([
            'role' => 'super_admin',
            'medical_center_id' => $otherCenter->id,
        ])
        ->call('save')
        ->assertHasNoFormErrors();

    $staff->refresh();

    expect($staff->role)->toBe('staff')
        ->and($staff->medical_center_id)->toBe($center->id)
        ->and($staff->hasRole('super_admin'))->toBeFalse();
});
