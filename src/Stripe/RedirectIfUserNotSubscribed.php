<?php

declare(strict_types=1);

namespace Maartenpaauw\Filament\Cashier\Stripe;

use BackedEnum;
use Closure;
use Exception;
use Filament\Pages\Dashboard;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Laravel\Cashier\SubscriptionBuilder;
use Maartenpaauw\Filament\Cashier\Plan;
use Maartenpaauw\Filament\Cashier\TenantRepository;
use Symfony\Component\HttpFoundation\Response;

final readonly class RedirectIfUserNotSubscribed
{
    public function __construct(
        private Repository $repository,
    ) {}

    /**
     * @param  Closure(Request): (Response)  $next
     *
     * @throws Exception
     */
    public function handle(Request $request, Closure $next, string ...$plans): Response
    {
        $tenant = TenantRepository::make()->current();

        /** @var array<array-key, Plan> $instances */
        $instances = Arr::map(
            array: $plans,
            callback: fn (string $plan): Plan => new Plan(repository: $this->repository, plan: $plan),
        );

        foreach ($instances as $plan) {
            if ($plan->hasGenericTrial() === true && $tenant->onGenericTrial() === true) {
                return $next($request);
            }

            if ($tenant->subscribedToProduct(products: $plan->productId(), type: $plan->type()) === true) {
                return $next($request);
            }
        }

        /** @var Plan $plan */
        $plan = Arr::first(array: $instances);

        return $tenant
            ->newSubscription(type: $plan->type(), prices: $plan->isMeteredPrice() ? [] : $plan->priceId())
            ->when(
                value: $plan->isMeteredPrice() === true,
                callback: static fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->meteredPrice(price: $plan->priceId()),
            )
            ->when(
                value: $plan->hasGenericTrial() === false && $plan->trialDays() !== false,
                callback: static fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->trialDays(trialDays: $plan->trialDays()),
            )
            ->when(
                value: $plan->allowPromotionCodes() === true,
                callback: static fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->allowPromotionCodes(),
            )
            ->when(
                value: $plan->collectTaxIds() === true,
                callback: static fn (SubscriptionBuilder $subscription): SubscriptionBuilder => $subscription->collectTaxIds(),
            )
            ->checkout(sessionOptions: [
                'success_url' => Dashboard::getUrl(),
                'cancel_url' => Dashboard::getUrl(),
            ])
            ->redirect();
    }

    /**
     * @param  string|BackedEnum|array<array-key, string|BackedEnum>  $plans
     */
    public static function plan(string|BackedEnum|array $plans = 'default'): string
    {
        return sprintf(
            '%s:%s',
            self::class,
            Collection::wrap(value: $plans)
                ->map(callback: static fn (string|BackedEnum $plan): string => match (true) {
                    $plan instanceof BackedEnum => strval(value: $plan->value),
                    default => $plan,
                })
                ->join(glue: ','),
        );
    }
}
