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

namespace App\Filament\Admin\Resources\Users\Schemas;

use App\Models\MedicalCenter;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Hash;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label(__('messages.name'))
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->label(__('messages.email'))
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label(__('messages.password'))
                    ->password()
                    ->revealable()
                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                    ->dehydrated(fn ($state) => filled($state))
                    ->required(fn (string $operation) => $operation === 'create')
                    ->maxLength(255),
                Select::make('role')
                    ->label(__('messages.role'))
                    ->options([
                        'super_admin' => __('messages.role_super_admin'),
                        'center_owner' => __('messages.role_center_owner'),
                        'staff' => __('messages.role_staff'),
                    ])
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state === 'super_admin') {
                            $set('medical_center_id', null);
                        }
                    }),
                Select::make('medical_center_id')
                    ->label(__('messages.medical_center'))
                    ->options(fn () => MedicalCenter::query()->pluck('name_ar', 'id'))
                    ->searchable()
                    ->visible(fn (callable $get) => $get('role') !== 'super_admin')
                    ->required(fn (callable $get) => in_array($get('role'), ['center_owner', 'staff'], true))
                    ->rules([
                        fn (callable $get) => function (string $attribute, $value, \Closure $fail) use ($get) {
                            $role = $get('role');
                            if ($role === 'super_admin' && $value !== null) {
                                $fail(__('messages.super_admin_no_center'));
                            }
                            if (in_array($role, ['center_owner', 'staff'], true) && $value === null) {
                                $fail(__('messages.center_required'));
                            }
                        },
                    ]),
            ]);
    }
}
