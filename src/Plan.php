<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier;

use Illuminate\Config\Repository;
use InvalidArgumentException;

final readonly class Plan
{
    public function __construct(
        private Repository $repository,
        private string $plan = 'default',
    ) {}

    public function type(): string
    {
        return $this->repository->get(key: "cashier.plans.$this->plan.type", default: $this->plan);
    }

    public function productId(): string
    {
        return $this->repository->get(
            key: "cashier.plans.$this->plan.product_id",
            default: static fn () => throw new InvalidArgumentException(message: 'Invalid plan configuration'),
        );
    }

    public function priceId(): string
    {
        return $this->repository->get(
            key: "cashier.plans.$this->plan.price_id",
            default: static fn () => throw new InvalidArgumentException(message: 'Invalid plan configuration'),
        );
    }

    public function trialDays(): int|false
    {
        return $this->repository->get(key: "cashier.plans.$this->plan.trial_days", default: false);
    }

    public function hasGenericTrial(): bool
    {
        return $this->repository->get(key: "cashier.plans.$this->plan.has_generic_trial", default: false);
    }

    public function allowPromotionCodes(): bool
    {
        return $this->repository->get(key: "cashier.plans.$this->plan.allow_promotion_codes", default: false);
    }

    public function collectTaxIds(): bool
    {
        return $this->repository->get(key: "cashier.plans.$this->plan.collect_tax_ids", default: false);
    }

    public function isMeteredPrice(): bool
    {
        return $this->repository->get(key: "cashier.plans.$this->plan.metered_price", default: false);
    }
}
