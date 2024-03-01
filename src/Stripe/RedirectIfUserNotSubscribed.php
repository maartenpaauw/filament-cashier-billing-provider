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
            throw new LogicException('Customer model does not use Cashier Billable trait');
        }

        if ($tenant->subscribed($plan)) {
            return $next($request);
        }

        $priceId = $this->repository->get("cashier.plans.$plan.price_id");
        $trialDays = $this->repository->get("cashier.plans.$plan.trial_days", false);
        $collectTaxIds = $this->repository->get("cashier.plans.$plan.collect_tax_ids", false);

        return $tenant->newSubscription($plan, $priceId)
            ->allowPromotionCodes()
            ->when($trialDays, static fn (SubscriptionBuilder $subscription) => $subscription->trialDays($trialDays))
            ->when($collectTaxIds, static fn (SubscriptionBuilder $subscription) => $subscription->collectTaxIds())
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
