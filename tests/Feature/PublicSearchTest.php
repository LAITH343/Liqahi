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

use App\Livewire\Pages\Search;
use App\Models\Item;
use App\Models\MedicalCenter;
use Livewire\Livewire;

beforeEach(function () {
    $this->item = Item::factory()->create();

    $this->near = MedicalCenter::factory()->create([
        'latitude' => 33.3152,
        'longitude' => 44.3661,
    ]);
    $this->mid = MedicalCenter::factory()->create([
        'latitude' => 33.3500,
        'longitude' => 44.4000,
    ]);
    $this->far = MedicalCenter::factory()->create([
        'latitude' => 33.0773,
        'longitude' => 44.3344,
    ]);

    $this->near->items()->updateExistingPivot($this->item->id, ['is_available' => true]);
    $this->mid->items()->updateExistingPivot($this->item->id, ['is_available' => true]);
    $this->far->items()->updateExistingPivot($this->item->id, ['is_available' => false]);
});

it('orders centers by distance and excludes unavailable items', function () {
    $results = MedicalCenter::query()
        ->where('is_active', true)
        ->whereHas('items', fn ($q) => $q
            ->where('items.id', $this->item->id)
            ->where('medical_center_item.is_available', true))
        ->withinRadius(33.3152, 44.3661, 50)
        ->get();

    expect($results->pluck('id')->all())->toBe([$this->near->id, $this->mid->id])
        ->and($results->first()->distance_km)->toBeLessThan($results->last()->distance_km);
});

it('respects radius cutoff', function () {
    $results = MedicalCenter::query()
        ->withinRadius(33.3152, 44.3661, 5)
        ->get();

    expect($results->pluck('id')->all())->toContain($this->near->id)
        ->not->toContain($this->far->id);
});

it('renders the search page', function () {
    Livewire::test(Search::class)
        ->assertOk()
        ->assertSee(__('messages.find_vaccine'));
});

it('returns no results when item is not available anywhere', function () {
    $unavailableItem = Item::factory()->create();
    $this->near->items()->updateExistingPivot($unavailableItem->id, ['is_available' => false]);

    $results = MedicalCenter::query()
        ->whereHas('items', fn ($q) => $q
            ->where('items.id', $unavailableItem->id)
            ->where('medical_center_item.is_available', true))
        ->withinRadius(33.3152, 44.3661, 50)
        ->get();

    expect($results)->toBeEmpty();
});
