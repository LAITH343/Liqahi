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

use App\Services\Geocoder;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

it('caches results by lowercased query', function () {
    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([
            ['lat' => '33.3152', 'lon' => '44.3661', 'display_name' => 'Baghdad'],
        ]),
    ]);

    $geocoder = new Geocoder;
    $first = $geocoder->search('Baghdad');
    $second = $geocoder->search('BAGHDAD');

    expect($first)->toBe($second)
        ->and($first['lat'])->toBe(33.3152)
        ->and($first['lng'])->toBe(44.3661);

    Http::assertSentCount(1);
});

it('returns null on upstream 5xx without throwing', function () {
    Cache::flush();

    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response('boom', 502),
    ]);

    $result = (new Geocoder)->search('failure');

    expect($result)->toBeNull();
});

it('returns null when no match found', function () {
    Cache::flush();

    Http::fake([
        'nominatim.openstreetmap.org/*' => Http::response([]),
    ]);

    expect((new Geocoder)->search('nowhereville'))->toBeNull();
});

it('returns null on empty query', function () {
    expect((new Geocoder)->search('   '))->toBeNull();
});
