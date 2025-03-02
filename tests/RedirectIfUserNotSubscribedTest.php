<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed;
use Workbench\App\enums\Plan;

it('should add the plan as parameter', function (): void {
    expect(RedirectIfUserNotSubscribed::plan('basic'))
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic');
});

it('should use the default plan when no plan provided', function (): void {
    expect(RedirectIfUserNotSubscribed::plan())
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:default');
});

it('should add the string plans as parameter', function (): void {
    expect(RedirectIfUserNotSubscribed::plan('basic,advanced,premium'))
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic,advanced,premium');
});

it('should add the enum plans as parameter', function (): void {
    expect(RedirectIfUserNotSubscribed::plan(Plan::cases()))
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic,advanced,premium');
});
