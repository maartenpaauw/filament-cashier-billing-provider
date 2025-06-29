<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\Plan;

it(description: 'throws an exception when type is empty', closure: function (): void {
    $plan = new Plan(
        type: '',
        productId: 'prod_0JkO18GJx0Vtux',
        priceId: 'price_Fxp5y8x0qjrm2jjk2nzuMpSF',
        trialDays: false,
        hasGenericTrial: false,
        allowPromotionCodes: false,
        collectTaxIds: false,
        isMeteredPrice: false,
    );

    expect(value: $plan)->type;
})->throws(exception: InvalidArgumentException::class, exceptionMessage: 'Type cannot be empty.');

it(description: 'throws an exception when product id is empty', closure: function (): void {
    $plan = new Plan(
        type: 'default',
        productId: '',
        priceId: 'price_Fxp5y8x0qjrm2jjk2nzuMpSF',
        trialDays: false,
        hasGenericTrial: false,
        allowPromotionCodes: false,
        collectTaxIds: false,
        isMeteredPrice: false,
    );

    expect(value: $plan)->type;
})->throws(exception: InvalidArgumentException::class, exceptionMessage: 'Product ID cannot be empty.');

it(description: 'throws an exception when price id is empty', closure: function (): void {
    $plan = new Plan(
        type: 'default',
        productId: 'prod_0JkO18GJx0Vtux',
        priceId: '',
        trialDays: false,
        hasGenericTrial: false,
        allowPromotionCodes: false,
        collectTaxIds: false,
        isMeteredPrice: false,
    );

    expect(value: $plan)->type;
})->throws(exception: InvalidArgumentException::class, exceptionMessage: 'Price ID cannot be empty.');

it(description: 'throws an exception when trial days and has generic trial are used together', closure: function (): void {
    $plan = new Plan(
        type: 'default',
        productId: 'prod_0JkO18GJx0Vtux',
        priceId: 'price_Fxp5y8x0qjrm2jjk2nzuMpSF',
        trialDays: 14,
        hasGenericTrial: true,
        allowPromotionCodes: false,
        collectTaxIds: false,
        isMeteredPrice: false,
    );

    expect(value: $plan)->type;
})->throws(exception: InvalidArgumentException::class, exceptionMessage: 'Only "trial days" or "has generic trial" can be used.');

it(description: 'throws an exception when trial days is a negative integer', closure: function (): void {
    $plan = new Plan(
        type: 'default',
        productId: 'prod_0JkO18GJx0Vtux',
        priceId: 'price_Fxp5y8x0qjrm2jjk2nzuMpSF',
        trialDays: -1,
        hasGenericTrial: false,
        allowPromotionCodes: false,
        collectTaxIds: false,
        isMeteredPrice: false,
    );

    expect(value: $plan)->type;
})->throws(exception: InvalidArgumentException::class, exceptionMessage: 'Trial days must be greater than 0.');
