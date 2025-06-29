<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier;

interface PlanRepository
{
    /**
     * @return array<int, Plan>
     */
    public function all(): array;

    public function get(string $name): Plan;
}
