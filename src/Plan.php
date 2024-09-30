<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier;

use Illuminate\Config\Repository;
use InvalidArgumentException;

final class Plan
{
    public function __construct(
        private readonly Repository $repository,
        private readonly string $plan = 'default',
    ) {
    }

    public function type(): string
    {
        return $this->repository->get("cashier.plans.$this->plan.type", $this->plan);
    }

    public function priceId(): string
    {
        return $this->repository->get(
            "cashier.plans.$this->plan.price_id",
            static fn () => throw new InvalidArgumentException('Invalid plan configuration'),
        );
    }

    public function trialDays(): int|false
    {
        return $this->repository->get("cashier.plans.$this->plan.trial_days", false);
    }

    public function allowPromotionCodes(): bool
    {
        return $this->repository->get("cashier.plans.$this->plan.allow_promotion_codes", false);
    }

    public function collectTaxIds(): bool
    {
        return $this->repository->get("cashier.plans.$this->plan.collect_tax_ids", false);
    }

    public function isMeteredPrice(): bool
    {
        return $this->repository->get("cashier.plans.$this->plan.metered_price", false);
    }
}
