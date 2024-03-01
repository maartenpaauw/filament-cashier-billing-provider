<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed;

it('should add the plan as parameter', function () {
    // Act
    $middleware = RedirectIfUserNotSubscribed::plan('basic');

    // Assert
    expect($middleware)
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:basic');
});

it('should use the default plan when no plan provided', function () {
    // Act
    $middleware = RedirectIfUserNotSubscribed::plan();

    // Assert
    expect($middleware)
        ->toEqual('Maartenpaauw\Filament\Cashier\Stripe\RedirectIfUserNotSubscribed:default');
});
