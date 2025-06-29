<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier;

use InvalidArgumentException;

final readonly class Plan
{
    public function __construct(
        public string $type,
        public string $productId,
        public string $priceId,
        public int | false $trialDays,
        public bool $hasGenericTrial,
        public bool $allowPromotionCodes,
        public bool $collectTaxIds,
        public bool $isMeteredPrice,
    ) {
        if ($this->type === '') {
            throw new InvalidArgumentException(message: 'Type cannot be empty.');
        }

        if ($this->productId === '') {
            throw new InvalidArgumentException(message: 'Product ID cannot be empty.');
        }

        if ($this->priceId === '') {
            throw new InvalidArgumentException(message: 'Price ID cannot be empty.');
        }

        if ($this->trialDays !== false && $this->hasGenericTrial === true) {
            throw new InvalidArgumentException(message: 'Only "trial days" or "has generic trial" can be used.');
        }

        if ($this->trialDays !== false && $this->trialDays < 0) {
            throw new InvalidArgumentException(message: 'Trial days must be greater than 0.');
        }
    }
}
