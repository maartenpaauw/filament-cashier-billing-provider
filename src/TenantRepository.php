<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier;

use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Cashier;
use LogicException;

final class TenantRepository
{
    public static function make(): self
    {
        return new self();
    }

    /**
     * @return Model & Billable
     */
    public function current(): Model
    {
        $tenant = Filament::getTenant();

        if ($tenant::class !== Cashier::$customerModel) {
            throw new LogicException('Filament tenant does not match the Cashier customer model');
        }

        if (! in_array(Billable::class, class_uses_recursive($tenant), true)) {
            throw new LogicException('Tenant model does not use Cashier Billable trait');
        }

        return $tenant;
    }
}
