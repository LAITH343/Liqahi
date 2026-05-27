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
use App\Models\MedicalCenter;
use Illuminate\Database\Seeder;

class MedicalCenterSeeder extends Seeder
{
    public function run(): void
    {
        $centers = [
            [
                'name_ar' => 'مدينة الطب',
                'name_en' => 'Baghdad Medical City',
                'address_ar' => 'باب المعظم، بغداد',
                'address_en' => 'Bab Al-Moatham, Baghdad',
                'phone' => '+964 1 416 8888',
                'latitude' => 33.3451,
                'longitude' => 44.3781,
            ],
            [
                'name_ar' => 'مستشفى بغداد التعليمي',
                'name_en' => 'Baghdad Teaching Hospital',
                'address_ar' => 'مدينة الطب، باب المعظم، بغداد',
                'address_en' => 'Medical City, Bab Al-Moatham, Baghdad',
                'phone' => '+964 1 416 1212',
                'latitude' => 33.3461,
                'longitude' => 44.3795,
            ],
            [
                'name_ar' => 'مستشفى الكرامة التعليمي',
                'name_en' => 'Al-Karama Teaching Hospital',
                'address_ar' => 'الكرخ، بغداد',
                'address_en' => 'Karkh, Baghdad',
                'phone' => '+964 1 537 0011',
                'latitude' => 33.3082,
                'longitude' => 44.3672,
            ],
            [
                'name_ar' => 'مستشفى اليرموك التعليمي',
                'name_en' => 'Al-Yarmouk Teaching Hospital',
                'address_ar' => 'اليرموك، بغداد',
                'address_en' => 'Al-Yarmouk, Baghdad',
                'phone' => '+964 1 543 1010',
                'latitude' => 33.2898,
                'longitude' => 44.3357,
            ],
            [
                'name_ar' => 'مستشفى الكندي التعليمي',
                'name_en' => 'Al-Kindi Teaching Hospital',
                'address_ar' => 'الرصافة، بغداد',
                'address_en' => 'Rusafa, Baghdad',
                'phone' => '+964 1 422 5050',
                'latitude' => 33.3441,
                'longitude' => 44.4163,
            ],
            [
                'name_ar' => 'مدينة الإمامين الكاظمين الطبية',
                'name_en' => 'Al-Imamain Al-Kadhimain Medical City',
                'address_ar' => 'الكاظمية، بغداد',
                'address_en' => 'Kadhimiya, Baghdad',
                'phone' => '+964 1 522 8000',
                'latitude' => 33.3789,
                'longitude' => 44.3370,
            ],
            [
                'name_ar' => 'مستشفى ابن البيطار للقلب',
                'name_en' => 'Ibn Al-Bitar Cardiac Hospital',
                'address_ar' => 'الأندلس، بغداد',
                'address_en' => 'Andalus, Baghdad',
                'phone' => '+964 1 718 1718',
                'latitude' => 33.3122,
                'longitude' => 44.3878,
            ],
            [
                'name_ar' => 'مستشفى ابن سينا',
                'name_en' => 'Ibn Sina Hospital',
                'address_ar' => 'المنطقة الخضراء، بغداد',
                'address_en' => 'Green Zone, Baghdad',
                'phone' => '+964 1 538 4000',
                'latitude' => 33.3061,
                'longitude' => 44.3856,
            ],
            [
                'name_ar' => 'مستشفى النعمان العام',
                'name_en' => 'Al-Nu’man General Hospital',
                'address_ar' => 'الأعظمية، بغداد',
                'address_en' => 'Adhamiya, Baghdad',
                'phone' => '+964 1 422 4477',
                'latitude' => 33.3603,
                'longitude' => 44.4036,
            ],
            [
                'name_ar' => 'مستشفى الشهيد الصدر',
                'name_en' => 'Martyr Al-Sadr Hospital',
                'address_ar' => 'مدينة الصدر، بغداد',
                'address_en' => 'Sadr City, Baghdad',
                'phone' => '+964 1 418 9000',
                'latitude' => 33.3833,
                'longitude' => 44.4596,
            ],
            [
                'name_ar' => 'مستشفى بغداد الدولي',
                'name_en' => 'Baghdad International Hospital',
                'address_ar' => 'الزعفرانية، بغداد',
                'address_en' => 'Zaafaraniyah, Baghdad',
                'phone' => '+964 1 776 8888',
                'latitude' => 33.2528,
                'longitude' => 44.4886,
            ],
            [
                'name_ar' => 'مستشفى الكرخ العام',
                'name_en' => 'Al-Karkh General Hospital',
                'address_ar' => 'الكرخ، بغداد',
                'address_en' => 'Karkh, Baghdad',
                'phone' => '+964 1 537 8181',
                'latitude' => 33.3215,
                'longitude' => 44.3580,
            ],
            [
                'name_ar' => 'مستشفى الزعفرانية العام',
                'name_en' => 'Al-Zaafaraniyah General Hospital',
                'address_ar' => 'الزعفرانية، بغداد',
                'address_en' => 'Zaafaraniyah, Baghdad',
                'phone' => '+964 1 555 2030',
                'latitude' => 33.2462,
                'longitude' => 44.4811,
            ],
            [
                'name_ar' => 'مستشفى المحمودية العام',
                'name_en' => 'Al-Mahmodia General Hospital',
                'address_ar' => 'المحمودية، بغداد',
                'address_en' => 'Al-Mahmodia, Baghdad',
                'phone' => '+964 1 502 7070',
                'latitude' => 33.0773,
                'longitude' => 44.3344,
            ],
            [
                'name_ar' => 'مستشفى أبو غريب العام',
                'name_en' => 'Abu Ghraib General Hospital',
                'address_ar' => 'أبو غريب، بغداد',
                'address_en' => 'Abu Ghraib, Baghdad',
                'phone' => '+964 1 555 1818',
                'latitude' => 33.2962,
                'longitude' => 44.1192,
            ],
        ];

        $itemIds = Item::where('is_active', true)->pluck('id');

        foreach ($centers as $data) {
            $existed = MedicalCenter::where('name_ar', $data['name_ar'])->exists();

            $center = MedicalCenter::updateOrCreate(
                ['name_ar' => $data['name_ar']],
                $data + ['is_active' => true]
            );

            if ($existed) {
                continue;
            }

            foreach ($itemIds as $itemId) {
                $center->items()->updateExistingPivot($itemId, [
                    'is_available' => random_int(1, 100) <= 70,
                ]);
            }
        }
    }
}
