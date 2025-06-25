<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed;
use Workbench\App\enums\Plan;

it(description: 'should add the plan as parameter', closure: function (): void {
    expect(value: RedirectIfUserNotSubscribed::plan(plans: 'basic'))
        ->toEqual(expected: 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic');
});

it(description: 'should use the default plan when no plan provided', closure: function (): void {
    expect(value: RedirectIfUserNotSubscribed::plan())
        ->toEqual(expected: 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:default');
});

it(description: 'should add the string plans as parameter', closure: function (): void {
    expect(value: RedirectIfUserNotSubscribed::plan(plans: 'basic,advanced,premium'))
        ->toEqual(expected: 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic,advanced,premium');
});

it(description: 'should add the enum plans as parameter', closure: function (): void {
    expect(value: RedirectIfUserNotSubscribed::plan(plans: Plan::cases()))
        ->toEqual(expected: 'Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic,advanced,premium');
});
