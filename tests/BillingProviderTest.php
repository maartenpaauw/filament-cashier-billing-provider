<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\Stripe\BillingProvider;
use Workbench\App\enums\Plan;

it('should use the defined plan', function (): void {
    expect(new BillingProvider('basic'))
        ->getSubscribedMiddleware()
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic');
});

it('should use the default plan when no plan provided', function (): void {
    expect(new BillingProvider)
        ->getSubscribedMiddleware()
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:default');
});

it('should use the value from string backed enums as plan name', function (BackedEnum $plan, string $expectedMiddleware): void {
    expect(new BillingProvider($plan))
        ->getSubscribedMiddleware()
        ->toEqual($expectedMiddleware);
})->with([
    [Plan::Basic, 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic'],
    [Plan::Advanced, 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:advanced'],
    [Plan::Premium, 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:premium'],
]);
