<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier\Stripe;

use Closure;
use Exception;
use Filament\Facades\Filament;
use Filament\Pages\Dashboard;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Laravel\Cashier\Billable;
use Laravel\Cashier\Cashier;
use Laravel\Cashier\SubscriptionBuilder;
use LogicException;
use Maartenpaauw\Filament\Cashier\Plan;
use Symfony\Component\HttpFoundation\Response;

final class RedirectIfUserNotSubscribed
{
    public function __construct(
        private readonly Repository $repository,
    ) {
    }

    /**
     * @param  Closure(Request): (Response)  $next
     *
     * @throws Exception
     */
    public function handle(Request $request, Closure $next, string $plan = 'default'): Response
    {
        /** @var Billable $tenant */
        $tenant = Filament::getTenant();

        if ($tenant::class !== Cashier::$customerModel) {
            throw new LogicException('Filament tenant does not match the Cashier customer model');
        }

        if (! in_array(Billable::class, class_uses_recursive($tenant), true)) {
            throw new LogicException('Tenant model does not use Cashier Billable trait');
        }

        $plan = new Plan($this->repository, $plan);

        if ($tenant->subscribed($plan->type())) {
            return $next($request);
        }

        return $tenant
            ->newSubscription($plan->type(), $plan->isMeteredPrice() ? [] : $plan->priceId())
            ->when(
                $plan->isMeteredPrice(),
                static fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->meteredPrice($plan->priceId()),
            )
            ->when(
                $plan->trialDays() !== false,
                static fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->trialDays($plan->trialDays()),
            )
            ->when(
                $plan->allowPromotionCodes(),
                static fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->allowPromotionCodes(),
            )
            ->when(
                $plan->collectTaxIds(),
                static fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->collectTaxIds(),
            )
            ->checkout([
                'success_url' => Dashboard::getUrl(),
                'cancel_url' => Dashboard::getUrl(),
            ])
            ->redirect();
    }

    public static function plan(string $plan = 'default'): string
    {
        return sprintf('%s:%s', self::class, $plan);
    }
}
