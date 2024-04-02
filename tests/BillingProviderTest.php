<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\Stripe\BillingProvider;

it('should use the defined plan', function () {
    expect(new BillingProvider('basic'))
        ->getSubscribedMiddleware()
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic');
});

it('should use the default plan when no plan provided', function () {
    expect(new BillingProvider())
        ->getSubscribedMiddleware()
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:default');
});
