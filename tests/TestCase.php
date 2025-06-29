<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier\Tests;

use Maartenpaauw\Filament\Cashier\FilamentCashierServiceProvider;
use Orchestra\Testbench\TestCase as Orchestra;
use Override;

abstract class TestCase extends Orchestra
{
    #[Override]
    protected function getPackageProviders(mixed $app): array
    {
        return [
            FilamentCashierServiceProvider::class,
        ];
    }
}
