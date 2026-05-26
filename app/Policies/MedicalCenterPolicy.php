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

use App\Models\MedicalCenter;
use App\Models\User;

class MedicalCenterPolicy
{
    public function viewAny(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function view(User $user, MedicalCenter $medicalCenter): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->medical_center_id === $medicalCenter->id;
    }

    public function create(User $user): bool
    {
        return $user->hasRole('super_admin');
    }

    public function update(User $user, MedicalCenter $medicalCenter): bool
    {
        if ($user->hasRole('super_admin')) {
            return true;
        }

        return $user->hasRole('center_owner') && $user->medical_center_id === $medicalCenter->id;
    }

    public function delete(User $user, MedicalCenter $medicalCenter): bool
    {
        return $user->hasRole('super_admin');
    }

    public function restore(User $user, MedicalCenter $medicalCenter): bool
    {
        return $user->hasRole('super_admin');
    }

    public function forceDelete(User $user, MedicalCenter $medicalCenter): bool
    {
        return $user->hasRole('super_admin');
    }
}
