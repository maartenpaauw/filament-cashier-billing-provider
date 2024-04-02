<?php

declare(strict_types=1);

use Illuminate\Config\Repository;
use Maartenpaauw\Filament\Cashier\Plan;

beforeEach(function () {
    $this->config = [
        'cashier' => [
            'plans' => [
                'default' => [
                    'price_id' => 'price_Fxp5y8x0qjrm2jjk2nzuMpSF',
                    'trial_days' => 14,
                    'allow_promotion_codes' => true,
                    'collect_tax_ids' => true,
                ],
                'basic' => [
                    'price_id' => 'price_ruVAAh5dwyhwbtWIVQn0lUvc',
                ],
            ],
        ],
    ];
});

it('it resolves all values from configuration', function () {
    expect(new Plan(new Repository($this->config), 'default'))
        ->type()
        ->toEqual('default')
        ->priceId()
        ->toEqual('price_Fxp5y8x0qjrm2jjk2nzuMpSF')
        ->trialDays()
        ->toEqual(14)
        ->allowPromotionCodes()
        ->toBeTrue()
        ->collectTaxIds()
        ->toBeTrue();
});

it('it resolves all required values from configuration', function () {
    expect(new Plan(new Repository($this->config), 'basic'))
        ->type()
        ->toEqual('basic')
        ->priceId()
        ->toEqual('price_ruVAAh5dwyhwbtWIVQn0lUvc')
        ->trialDays()
        ->toBeFalse()
        ->allowPromotionCodes()
        ->toBeFalse()
        ->collectTaxIds()
        ->toBeFalse();
});

it('throws an exception when the plan could not be parsed from configuration', function () {
    expect(new Plan(new Repository($this->config), 'premium'))->priceId();
})->throws(InvalidArgumentException::class, 'Invalid plan configuration');
