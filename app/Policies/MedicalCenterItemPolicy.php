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

namespace App\Policies;

use App\Models\MedicalCenterItem;
use App\Models\User;

class MedicalCenterItemPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasAnyRole(['super_admin', 'center_owner', 'staff']);
    }

    public function view(User $user, MedicalCenterItem $medicalCenterItem): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->medical_center_id === $medicalCenterItem->medical_center_id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, MedicalCenterItem $medicalCenterItem): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->can('toggle_availability')
            && $user->medical_center_id === $medicalCenterItem->medical_center_id;
    }

    public function delete(User $user, MedicalCenterItem $medicalCenterItem): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restore(User $user, MedicalCenterItem $medicalCenterItem): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, MedicalCenterItem $medicalCenterItem): bool
    {
        return $user->hasRole('super_admin');
    }
}
