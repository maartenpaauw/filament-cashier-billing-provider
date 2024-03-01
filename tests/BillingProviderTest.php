<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\Stripe\BillingProvider;

it('should use the defined plan', function () {
    // Arrange
    $billingProvider = new BillingProvider('basic');

    // Act
    $middleware = $billingProvider->getSubscribedMiddleware();

    // Assert
    expect($middleware)
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic');
});

it('should use the default plan when no plan provided', function () {
    // Arrange
    $billingProvider = new BillingProvider();

    // Act
    $middleware = $billingProvider->getSubscribedMiddleware();

    // Assert
    expect($middleware)
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:default');
});
