<?php

declare(strict_types=1);

use Maartenpaauw\Filament\Cashier\ConfigPlanRepository;
use Maartenpaauw\Filament\Cashier\Plan;

beforeEach(closure: function (): void {
    $this->planRepository = app(abstract: ConfigPlanRepository::class);

    config()->set('cashier.plans', [
        'default' => [
            'product_id' => 'prod_0JkO18GJx0Vtux',
            'price_id' => 'price_Fxp5y8x0qjrm2jjk2nzuMpSF',
            'type' => 'primary',
            'has_generic_trial' => false,
            'trial_days' => 14,
            'allow_promotion_codes' => true,
            'collect_tax_ids' => true,
            'metered_price' => true,
        ],
        'basic' => [
            'product_id' => 'prod_jad5TBR8eIwywy',
            'price_id' => 'price_ruVAAh5dwyhwbtWIVQn0lUvc',
        ],
    ]);
});

it(description: 'resolves all values from configuration', closure: function (): void {
    expect(value: $this->planRepository->get(name: 'default'))
        ->type
        ->toEqual(expected: 'primary')
        ->productId
        ->toEqual(expected: 'prod_0JkO18GJx0Vtux')
        ->priceId
        ->toEqual(expected: 'price_Fxp5y8x0qjrm2jjk2nzuMpSF')
        ->trialDays
        ->toEqual(expected: 14)
        ->hasGenericTrial
        ->toBeFalse()
        ->allowPromotionCodes
        ->toBeTrue()
        ->collectTaxIds
        ->toBeTrue()
        ->isMeteredPrice
        ->toBeTrue();
});

it(description: 'resolves all required values from configuration', closure: function (): void {
    expect(value: $this->planRepository->get(name: 'basic'))
        ->type
        ->toEqual(expected: 'basic')
        ->productId
        ->toEqual(expected: 'prod_jad5TBR8eIwywy')
        ->priceId
        ->toEqual(expected: 'price_ruVAAh5dwyhwbtWIVQn0lUvc')
        ->trialDays
        ->toBeFalse()
        ->hasGenericTrial
        ->toBeFalse()
        ->allowPromotionCodes
        ->toBeFalse()
        ->collectTaxIds
        ->toBeFalse()
        ->isMeteredPrice
        ->toBeFalse();
});

it(description: 'resolves all configured plans', closure: function (): void {
    $plans = $this->planRepository->all();

    expect(value: $plans)
        ->toBeArray()
        ->toHaveCount(count: 2)
        ->toContainOnlyInstancesOf(class: Plan::class)
        ->toHaveKeys(keys: ['default', 'basic'])
        ->and(value: $plans['default'])
        ->type
        ->toEqual(expected: 'primary')
        ->productId
        ->toEqual(expected: 'prod_0JkO18GJx0Vtux')
        ->priceId
        ->toEqual(expected: 'price_Fxp5y8x0qjrm2jjk2nzuMpSF')
        ->trialDays
        ->toEqual(expected: 14)
        ->hasGenericTrial
        ->toBeFalse()
        ->allowPromotionCodes
        ->toBeTrue()
        ->collectTaxIds
        ->toBeTrue()
        ->isMeteredPrice
        ->toBeTrue()
        ->and(value: $plans['basic'])
        ->type
        ->toEqual(expected: 'basic')
        ->productId
        ->toEqual(expected: 'prod_jad5TBR8eIwywy')
        ->priceId
        ->toEqual(expected: 'price_ruVAAh5dwyhwbtWIVQn0lUvc')
        ->trialDays
        ->toBeFalse()
        ->hasGenericTrial
        ->toBeFalse()
        ->allowPromotionCodes
        ->toBeFalse()
        ->collectTaxIds
        ->toBeFalse()
        ->isMeteredPrice
        ->toBeFalse();
});
