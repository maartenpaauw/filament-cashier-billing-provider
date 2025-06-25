<?php

declare(strict_types=1);

use Illuminate\Config\Repository;
use Maartenpaauw\Filament\Cashier\Plan;

beforeEach(closure: function (): void {
    $this->config = [
        'cashier' => [
            'plans' => [
                'default' => [
                    'product_id' => 'prod_0JkO18GJx0Vtux',
                    'price_id' => 'price_Fxp5y8x0qjrm2jjk2nzuMpSF',
                    'type' => 'primary',
                    'has_generic_trial' => true,
                    'trial_days' => 14,
                    'allow_promotion_codes' => true,
                    'collect_tax_ids' => true,
                    'metered_price' => true,
                ],
                'basic' => [
                    'product_id' => 'prod_jad5TBR8eIwywy',
                    'price_id' => 'price_ruVAAh5dwyhwbtWIVQn0lUvc',
                ],
            ],
        ],
    ];
});

it(description: 'it resolves all values from configuration', closure: function (): void {
    expect(value: new Plan(repository: new Repository($this->config), plan: 'default'))
        ->type()
        ->toEqual(expected: 'primary')
        ->productId()
        ->toEqual(expected: 'prod_0JkO18GJx0Vtux')
        ->priceId()
        ->toEqual(expected: 'price_Fxp5y8x0qjrm2jjk2nzuMpSF')
        ->trialDays()
        ->toEqual(expected: 14)
        ->hasGenericTrial()
        ->toBeTrue()
        ->allowPromotionCodes()
        ->toBeTrue()
        ->collectTaxIds()
        ->toBeTrue()
        ->isMeteredPrice()
        ->toBeTrue();
});

it(description: 'it resolves all required values from configuration', closure: function (): void {
    expect(value: new Plan(repository: new Repository($this->config), plan: 'basic'))
        ->type()
        ->toEqual(expected: 'basic')
        ->productId()
        ->toEqual(expected: 'prod_jad5TBR8eIwywy')
        ->priceId()
        ->toEqual(expected: 'price_ruVAAh5dwyhwbtWIVQn0lUvc')
        ->trialDays()
        ->toBeFalse()
        ->hasGenericTrial()
        ->toBeFalse()
        ->allowPromotionCodes()
        ->toBeFalse()
        ->collectTaxIds()
        ->toBeFalse()
        ->isMeteredPrice()
        ->toBeFalse();
});

it(description: 'throws an exception when the plan price could not be parsed from configuration', closure: function (): void {
    expect(value: new Plan(repository: new Repository($this->config), plan: 'premium'))->priceId();
})->throws(exception: InvalidArgumentException::class, exceptionMessage: 'Invalid plan configuration');

it(description: 'throws an exception when the plan product could not be parsed from configuration', closure: function (): void {
    expect(value: new Plan(repository: new Repository($this->config), plan: 'premium'))->productId();
})->throws(exception: InvalidArgumentException::class, exceptionMessage: 'Invalid plan configuration');
