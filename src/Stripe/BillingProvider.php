<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier\Stripe;

use BackedEnum;
use Closure;
use Filament\Billing\Providers\Contracts\BillingProvider as BillingProviderContract;
use Filament\Pages\Dashboard;
use Illuminate\Http\RedirectResponse;
use Maartenpaauw\Filament\Cashier\TenantRepository;
use Override;

final readonly class BillingProvider implements BillingProviderContract
{
    /**
     * @param  string|BackedEnum|array<array-key, string|BackedEnum>  $plans
     */
    public function __construct(
        private string|BackedEnum|array $plans = 'default',
    ) {}

    #[Override]
    public function getRouteAction(): string|Closure|array
    {
        return static function (): RedirectResponse {
            $tenant = TenantRepository::make()->current();

            if ($tenant->hasStripeId() === false) {
                $tenant->createAsStripeCustomer();
            }

            return $tenant->redirectToBillingPortal(returnUrl: Dashboard::getUrl());
        };
    }

    #[Override]
    public function getSubscribedMiddleware(): string
    {
        return RedirectIfUserNotSubscribed::plan(plans: $this->plans);
    }
}
