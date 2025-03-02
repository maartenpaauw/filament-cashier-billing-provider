<?php

declare(strict_types=1);

use Illuminate\Config\Repository;
use Maartenpaauw\Filament\Cashier\Plan;

beforeEach(function (): void {
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

it('it resolves all values from configuration', function (): void {
    expect(new Plan(new Repository($this->config), 'default'))
        ->type()
        ->toEqual('primary')
        ->productId()
        ->toEqual('prod_0JkO18GJx0Vtux')
        ->priceId()
        ->toEqual('price_Fxp5y8x0qjrm2jjk2nzuMpSF')
        ->trialDays()
        ->toEqual(14)
        ->hasGenericTrial()
        ->toBeTrue()
        ->allowPromotionCodes()
        ->toBeTrue()
        ->collectTaxIds()
        ->toBeTrue()
        ->isMeteredPrice()
        ->toBeTrue();
});

it('it resolves all required values from configuration', function (): void {
    expect(new Plan(new Repository($this->config), 'basic'))
        ->type()
        ->toEqual('basic')
        ->productId()
        ->toEqual('prod_jad5TBR8eIwywy')
        ->priceId()
        ->toEqual('price_ruVAAh5dwyhwbtWIVQn0lUvc')
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

it('throws an exception when the plan price could not be parsed from configuration', function (): void {
    expect(new Plan(new Repository($this->config), 'premium'))->priceId();
})->throws(InvalidArgumentException::class, 'Invalid plan configuration');

it('throws an exception when the plan product could not be parsed from configuration', function (): void {
    expect(new Plan(new Repository($this->config), 'premium'))->productId();
})->throws(InvalidArgumentException::class, 'Invalid plan configuration');
