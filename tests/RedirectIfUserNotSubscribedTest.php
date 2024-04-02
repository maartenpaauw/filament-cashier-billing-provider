<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed;

it('should add the plan as parameter', function () {
    expect(RedirectIfUserNotSubscribed::plan('basic'))
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic');
});

it('should use the default plan when no plan provided', function () {
    expect(RedirectIfUserNotSubscribed::plan())
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:default');
});
