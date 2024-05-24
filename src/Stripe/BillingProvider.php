<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier\Stripe;

use Closure;
use Filament\Billing\Providers\Contracts\Provider;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;
use Illuminate\Http\RedirectResponse;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Cashier;
use LogicException;

final class BillingProvider implements Provider
{
    public function __construct(
        private readonly string $plan = 'default',
    ) {
    }

    public function getRouteAction(): string|Closure|array
    {
        return static function (): RedirectResponse {
            /** @var Billable $tenant */
            $tenant = Filament::getTenant();

            if ($tenant::class !== Cashier::$customerModel) {
                throw new LogicException('Filament tenant does not match the Cashier customer model');
            }

            if (! in_array(Billable::class, class_uses_recursive($tenant), true)) {
                throw new LogicException('Tenant model does not use Cashier Billable trait');
            }

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
