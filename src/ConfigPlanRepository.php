<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier;

use Illuminate\Config\Repository;
use Illuminate\Support\Arr;
use Override;

final readonly class ConfigPlanRepository implements PlanRepository
{
    public function __construct(
        private Repository $config,
    ) {}

    #[Override]
    public function all(): array
    {
        return Arr::map(
            array: $this->config->array(key: 'cashier.plans'),
            callback: fn (array $plan, string $name) => $this->createPlanFromArray(plan: $plan, name: $name),
        );
    }

    #[Override]
    public function get(string $name): Plan
    {
        return $this->createPlanFromArray(
            plan: $this->config->array(key: "cashier.plans.$name"),
            name: $name,
        );
    }

    private function createPlanFromArray(array $plan, string $name): Plan
    {
        return new Plan(
            type: Arr::get($plan, key: 'type', default: $name),
            productId: Arr::get($plan, key: 'product_id', default: ''),
            priceId: Arr::get($plan, key: 'price_id', default: ''),
            trialDays: Arr::get($plan, key: 'trial_days', default: false),
            hasGenericTrial: Arr::get($plan, key: 'has_generic_trial', default: false),
            allowPromotionCodes: Arr::get($plan, key: 'allow_promotion_codes', default: false),
            collectTaxIds: Arr::get($plan, key: 'collect_tax_ids', default: false),
            isMeteredPrice: Arr::get($plan, key: 'metered_price', default: false),
        );
    }
}
