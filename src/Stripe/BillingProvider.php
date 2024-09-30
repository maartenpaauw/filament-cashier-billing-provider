<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier\Stripe;

use BackedEnum;
use Closure;
use Filament\Billing\Providers\Contracts\Provider;
use Filament\Pages\Dashboard;
use Illuminate\Http\RedirectResponse;
use Maartenpaauw\Filament\Cashier\TenantRepository;

final class BillingProvider implements Provider
{
    public function __construct(
        private readonly string|BackedEnum $plan = 'default',
    ) {}

    public function getRouteAction(): string|Closure|array
    {
        return static function (): RedirectResponse {
            $tenant = TenantRepository::make()->current();

            if (! $tenant->hasStripeId()) {
                $tenant->createAsStripeCustomer();
            }

            return $tenant->redirectToBillingPortal(Dashboard::getUrl());
        };
    }

    public function getSubscribedMiddleware(): string
    {
        return RedirectIfUserNotSubscribed::plan($this->plan);
    }
}
