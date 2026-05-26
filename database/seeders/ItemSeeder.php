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

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['name_ar' => 'لقاح B.C.G', 'name_en' => 'BCG Vaccine'],
            ['name_ar' => 'لقاح التهاب الكبد الفيروسي B للصغار', 'name_en' => 'Hepatitis B (Pediatric)'],
            ['name_ar' => 'لقاح شلل الأطفال الفموي', 'name_en' => 'Oral Polio (OPV)'],
            ['name_ar' => 'لقاح الروتا فايروس', 'name_en' => 'Rotavirus'],
            ['name_ar' => 'اللقاح الخماسي الخلوي', 'name_en' => 'Pentavalent'],
            ['name_ar' => 'لقاح المكورات الرئوية', 'name_en' => 'Pneumococcal'],
            ['name_ar' => 'لقاح شلل الأطفال الزرقي', 'name_en' => 'Inactivated Polio (IPV)'],
            ['name_ar' => 'اللقاح الثلاثي', 'name_en' => 'MMR'],
            ['name_ar' => 'لقاح الحصبة المنفردة', 'name_en' => 'Measles (single)'],
            ['name_ar' => 'فيتامين A 100,000', 'name_en' => 'Vitamin A 100,000 IU'],
            ['name_ar' => 'فيتامين A 200,000', 'name_en' => 'Vitamin A 200,000 IU'],
            ['name_ar' => 'لقاح الحصبة المختلطة (جرعة واحدة)', 'name_en' => 'MR (1-dose vial)'],
            ['name_ar' => 'لقاح الحصبة المختلطة (10 جرعات)', 'name_en' => 'MR (10-dose vial)'],
            ['name_ar' => 'لقاح توكسيد الكزاز', 'name_en' => 'Tetanus Toxoid'],
            ['name_ar' => 'لقاح التهاب الكبد الفايروسي B للكبار', 'name_en' => 'Hepatitis B (Adult)'],
            ['name_ar' => 'لقاح ثنائي للصغار', 'name_en' => 'DT (Pediatric)'],
            ['name_ar' => 'لقاح ثنائي للكبار', 'name_en' => 'Td (Adult)'],
            ['name_ar' => 'لقاح التيفوئيد (جرعة واحدة)', 'name_en' => 'Typhoid (1-dose vial)'],
            ['name_ar' => 'لقاح التيفوئيد (10 جرعات)', 'name_en' => 'Typhoid (10-dose vial)'],
            ['name_ar' => 'لقاح الانفلونزا الموسمي', 'name_en' => 'Seasonal Influenza'],
            ['name_ar' => 'لقاح التهاب الكبد الفايروسي A للكبار', 'name_en' => 'Hepatitis A (Adult)'],
            ['name_ar' => 'لقاح التهاب الكبد الفايروسي A للصغار', 'name_en' => 'Hepatitis A (Pediatric)'],
            ['name_ar' => 'لقاح السحايا الرباعي (جرعة واحدة)', 'name_en' => 'Meningococcal Quadrivalent (1-dose)'],
            ['name_ar' => 'لقاح السحايا الرباعي (10 جرعات)', 'name_en' => 'Meningococcal Quadrivalent (10-dose)'],
            ['name_ar' => 'لقاح داء الكلب', 'name_en' => 'Rabies'],
            ['name_ar' => 'لقاح الحمى الصفراء', 'name_en' => 'Yellow Fever'],
            ['name_ar' => 'سرنجات BCG', 'name_en' => 'BCG Syringes'],
            ['name_ar' => 'سرنجات كوفيد 1 مل', 'name_en' => 'COVID Syringes 1 mL'],
            ['name_ar' => 'سرنجات ذاتية التعطيل 0.5 سي سي', 'name_en' => 'Auto-disable Syringes 0.5 cc'],
            ['name_ar' => 'صناديق الامان', 'name_en' => 'Safety Boxes'],
            ['name_ar' => 'لقاح استرازنيكا', 'name_en' => 'AstraZeneca'],
            ['name_ar' => 'لقاح ساينوفارم', 'name_en' => 'Sinopharm'],
        ];

        foreach ($items as $i) {
            Item::updateOrCreate(['name_ar' => $i['name_ar']], $i + ['is_active' => true]);
        }
    }
}
