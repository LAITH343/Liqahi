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

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class Geocoder
{
    public function search(string $query): ?array
    {
        $query = trim($query);

        if ($query === '') {
            return null;
        }

        return Cache::remember(
            'geocode:'.md5(mb_strtolower($query)),
            now()->addHours(24),
            function () use ($query) {
                try {
                    $response = Http::withHeaders([
                        'User-Agent' => 'Liqahi/1.0 (https://liqahi.test)',
                        'Accept-Language' => app()->getLocale().',en;q=0.8',
                    ])
                        ->timeout(8)
                        ->retry(2, 200)
                        ->get('https://nominatim.openstreetmap.org/search', [
                            'q' => $query,
                            'format' => 'jsonv2',
                            'limit' => 1,
                            'countrycodes' => 'iq',
                        ]);

                    if (! $response->successful()) {
                        return null;
                    }

                    $first = $response->json()[0] ?? null;

                    if (! $first) {
                        return null;
                    }

                    return [
                        'lat' => (float) $first['lat'],
                        'lng' => (float) $first['lon'],
                        'label' => $first['display_name'] ?? $query,
                    ];
                } catch (\Throwable) {
                    return null;
                }
            }
        );
    }
}
