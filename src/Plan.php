<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier;

use Illuminate\Config\Repository;
use InvalidArgumentException;

final class Plan
{
    public function __construct(
        private readonly Repository $repository,
        private readonly string $type = 'default',
    ) {
    }

    public function type(): string
    {
        return $this->type;
    }

    public function priceId(): string
    {
        return $this->repository->get(
            "cashier.plans.$this->type.price_id",
            static fn () => throw new InvalidArgumentException('Invalid plan configuration'),
        );
    }

    public function trialDays(): int|false
    {
        return $this->repository->get("cashier.plans.$this->type.trial_days", false);
    }

    public function allowPromotionCodes(): bool
    {
        return $this->repository->get("cashier.plans.$this->type.allow_promotion_codes", false);
    }

    public function collectTaxIds(): bool
    {
        return $this->repository->get("cashier.plans.$this->type.collect_tax_ids", false);
    }

    public function isMeteredPrice(): bool
    {
        return $this->repository->get("cashier.plans.$this->type.metered_price", false);
    }
}
