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

namespace App\Filament\Forms\Components;

use Filament\Forms\Components\Field;

class LocationPicker extends Field
{
    protected string $view = 'filament.forms.components.location-picker';

    protected string $latStatePath = 'latitude';

    protected string $lngStatePath = 'longitude';

    public function latStatePath(string $path): static
    {
        $this->latStatePath = $path;

        return $this;
    }

    public function lngStatePath(string $path): static
    {
        $this->lngStatePath = $path;

        return $this;
    }

    public function getLatStatePath(): string
    {
        return $this->resolveStatePath($this->latStatePath);
    }

    public function getLngStatePath(): string
    {
        return $this->resolveStatePath($this->lngStatePath);
    }

    public function getLat(): mixed
    {
        return $this->evaluate(fn ($get) => $get($this->latStatePath));
    }

    public function getLng(): mixed
    {
        return $this->evaluate(fn ($get) => $get($this->lngStatePath));
    }

    private function resolveStatePath(string $path): string
    {
        $container = $this->getContainer();
        $statePath = $container->getStatePath();

        return $statePath === '' ? $path : $statePath.'.'.$path;
    }
}
