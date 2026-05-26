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

namespace App\Livewire\Pages;

use App\Models\Item;
use App\Models\MedicalCenter;
use App\Services\Geocoder;
use Illuminate\Support\Facades\RateLimiter;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

#[Layout('layouts.public')]
#[Title('Liqahi')]
class Search extends Component
{
    #[Url(as: 'lat', except: null)]
    public ?float $latitude = null;

    #[Url(as: 'lng', except: null)]
    public ?float $longitude = null;

    #[Url(as: 'q', except: '')]
    public string $address = '';

    #[Url(as: 'item', except: null)]
    public ?int $itemId = null;

    public string $itemSearch = '';

    public ?string $error = null;

    public ?int $selectedCenterId = null;

    public function updatedItemSearch(): void
    {
        // Keep dropdown responsive
    }

    public function selectItem(int $id, string $label): void
    {
        $this->itemId = $id;
        $this->itemSearch = $label;
    }

    public function clearItem(): void
    {
        $this->itemId = null;
        $this->itemSearch = '';
    }

    public function setCoords(float $lat, float $lng): void
    {
        $this->latitude = $lat;
        $this->longitude = $lng;
        $this->error = null;
    }

    public function geocode(Geocoder $geocoder): void
    {
        $this->error = null;

        if (trim($this->address) === '') {
            return;
        }

        $key = 'geocode:'.request()->ip();

        if (RateLimiter::tooManyAttempts($key, 10)) {
            $this->error = __('messages.rate_limited');

            return;
        }

        RateLimiter::hit($key, 60);

        $result = $geocoder->search($this->address);

        if ($result === null) {
            $this->error = __('messages.address_not_found');

            return;
        }

        $this->latitude = $result['lat'];
        $this->longitude = $result['lng'];
    }

    #[Computed]
    public function itemSuggestions()
    {
        $term = trim($this->itemSearch);

        if ($term === '' || $this->itemId !== null) {
            return collect();
        }

        return Item::query()
            ->where('is_active', true)
            ->where(function ($q) use ($term) {
                $q->where('name_ar', 'ILIKE', '%'.$term.'%')
                    ->orWhere('name_en', 'ILIKE', '%'.$term.'%');
            })
            ->limit(8)
            ->get(['id', 'name_ar', 'name_en']);
    }

    public function selectCenter(int $id): void
    {
        $this->selectedCenterId = $id;
    }

    public function clearCenter(): void
    {
        $this->selectedCenterId = null;
    }

    #[Computed]
    public function allCenters()
    {
        return MedicalCenter::query()
            ->where('is_active', true)
            ->get(['id', 'name_ar', 'name_en', 'latitude', 'longitude']);
    }

    #[Computed]
    public function selectedCenter()
    {
        if ($this->selectedCenterId === null) {
            return null;
        }

        return MedicalCenter::query()
            ->with(['items' => fn ($q) => $q->where('is_active', true)->orderBy('name_'.app()->getLocale())])
            ->find($this->selectedCenterId);
    }

    #[Computed]
    public function results()
    {
        if ($this->latitude === null || $this->longitude === null || $this->itemId === null) {
            return collect();
        }

        return MedicalCenter::query()
            ->where('is_active', true)
            ->whereHas('items', function ($q) {
                $q->where('items.id', $this->itemId)
                    ->where('medical_center_item.is_available', true);
            })
            ->withinRadius($this->latitude, $this->longitude, 50)
            ->limit(20)
            ->get();
    }

    public function render()
    {
        return view('livewire.pages.search');
    }
}
