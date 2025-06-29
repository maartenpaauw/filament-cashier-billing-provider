<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier;

use Illuminate\Support\ServiceProvider;
use Override;

final class FilamentCashierServiceProvider extends ServiceProvider
{
    #[Override]
    public function register(): void
    {
        $this->app->bind(abstract: PlanRepository::class, concrete: ConfigPlanRepository::class);
    }
}
