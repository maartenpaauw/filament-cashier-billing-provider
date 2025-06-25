<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\Stripe\BillingProvider;
use Workbench\App\enums\Plan;

it(description: 'should use the defined plan', closure: function (): void {
    expect(value: new BillingProvider(plans: 'basic'))
        ->getSubscribedMiddleware()
        ->toEqual(expected: 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic');
});

it(description: 'should use the default plan when no plan provided', closure: function (): void {
    expect(value: new BillingProvider)
        ->getSubscribedMiddleware()
        ->toEqual(expected: 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:default');
});

it(description: 'should use the value from string backed enums as plan name', closure: function (BackedEnum $plan, string $expectedMiddleware): void {
    expect(value: new BillingProvider(plans: $plan))
        ->getSubscribedMiddleware()
        ->toEqual(expected: $expectedMiddleware);
})->with([
    [Plan::Basic, 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic'],
    [Plan::Advanced, 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:advanced'],
    [Plan::Premium, 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:premium'],
]);

it(description: 'should use the defined string plans', closure: function (): void {
    expect(value: new BillingProvider(plans: 'basic,advanced,premium'))
        ->getSubscribedMiddleware()
        ->toEqual(expected: 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic,advanced,premium');
});

it(description: 'should use the defined enum plans', closure: function (): void {
    expect(value: new BillingProvider(plans: Plan::cases()))
        ->getSubscribedMiddleware()
        ->toEqual(expected: 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic,advanced,premium');
});
