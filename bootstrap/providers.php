<?php

use App\Providers\AppServiceProvider;
use App\Providers\Filament\AdminPanelProvider;
use App\Providers\Filament\CenterPanelProvider;

return [
    AppServiceProvider::class,
    AdminPanelProvider::class,
    CenterPanelProvider::class,
];
