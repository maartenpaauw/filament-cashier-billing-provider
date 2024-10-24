<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier\Stripe;

use BackedEnum;
use Closure;
use Exception;
use Filament\Pages\Dashboard;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Laravel\Cashier\SubscriptionBuilder;
use Maartenpaauw\Filament\Cashier\Plan;
use Maartenpaauw\Filament\Cashier\TenantRepository;
use Symfony\Component\HttpFoundation\Response;

final class RedirectIfUserNotSubscribed
{
    public function __construct(
        private readonly Repository $repository,
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     *
     * @throws Exception
     */
    public function handle(Request $request, Closure $next, string $plan = 'default'): Response
    {
        $tenant = TenantRepository::make()->current();
        $plan = new Plan($this->repository, $plan);

        if ($plan->hasGenericTrial() && $tenant->onGenericTrial()) {
            return $next($request);
        }

        if ($tenant->subscribedToProduct($plan->productId(), $plan->type())) {
            return $next($request);
        }

        return $tenant
            ->newSubscription($plan->type(), $plan->isMeteredPrice() ? [] : $plan->priceId())
            ->when(
                $plan->isMeteredPrice(),
                static fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->meteredPrice($plan->priceId()),
            )
            ->when(
                ! $plan->hasGenericTrial() && $plan->trialDays() !== false,
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

    public static function plan(string|BackedEnum $plan = 'default'): string
    {
        return sprintf('%s:%s', self::class, match (true) {
            $plan instanceof BackedEnum => strval($plan->value),
            default => $plan,
        });
    }
}
