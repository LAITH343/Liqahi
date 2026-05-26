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

namespace Database\Factories;

use App\Models\MedicalCenter;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<MedicalCenter>
 */
class MedicalCenterFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name_ar' => 'مركز '.fake()->unique()->company(),
            'name_en' => 'Center '.fake()->unique()->company(),
            'address_ar' => fake()->address(),
            'address_en' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'latitude' => fake()->randomFloat(7, 33.2, 33.4),
            'longitude' => fake()->randomFloat(7, 44.3, 44.5),
            'is_active' => true,
        ];
    }
}
