# Filament billing provider for Laravel Cashier

[![Latest Version on Packagist](https://img.shields.io/packagist/v/maartenpaauw/filament-cashier-billing-provider.svg?style=flat-square)](https://packagist.org/packages/maartenpaauw/filament-cashier-billing-provider)
[![Tests](https://img.shields.io/github/actions/workflow/status/maartenpaauw/filament-cashier-billing-provider/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/maartenpaauw/filament-cashier-billing-provider/actions/workflows/run-tests.yml)
[![Total Downloads](https://img.shields.io/packagist/dt/maartenpaauw/filament-cashier-billing-provider.svg?style=flat-square)](https://packagist.org/packages/maartenpaauw/filament-cashier-billing-provider)

Integrate Laravel Cashier Stripe support into Filament's multi-tenant panels.

## Support me

<p class="filament-hidden">
    <a href="https://filamentphp.com/plugins/maartenpaauw-model-states">
        <img src="https://raw.githubusercontent.com/maartenpaauw/model-states-for-filament-docs/main/assets/images/model-states-for-filament-banner.jpg"
            alt="Model States for Filament"
            width="700px" />
    </a>
</p>

You can support me by [buying Model States for Filament](https://filamentphp.com/plugins/maartenpaauw-model-states).

## Installation

1. Install the package via composer:

    ```bash
    composer require maartenpaauw/filament-cashier-billing-provider
    ```

2. Make sure your `Billable` model is the same as your Filament tenant model.

> [!TIP]
> For more information configuring a `Billable` model, refer to the official
> Laravel [documentation](https://laravel.com/docs/11.x/billing#billable-model).

## Usage

Add plans to your `cashier.php` config file:

```php
'plans' => [
    'default' => [
        'price_id' => ENV('CASHIER_STRIPE_SUBSCRIPTION_DEFAULT_PRICE_ID'),
        'type' => 'default', // Optional, by default it uses the array key as type.
        'trial_days' => 14, // Optional
        'has_generic_trial' => true, // Optional, only `trial_days` OR `has_generic_trial` can be used.
        'allow_promotion_codes' => true, // Optional
        'collect_tax_ids' => true, // Optional
        'metered_price' => true, // Optional
    ],
],
```

> [!CAUTION]
> The current implementation only supports recurring subscriptions.

Add the following code to your `AdminPanelProvider` (or other panel providers):

```php
use Maartenpaauw\Filament\Cashier\Stripe\BillingProvider;

// ...

public function panel(Panel $panel): Panel
{
    return $panel
        // ...
        ->tenantBillingProvider(new BillingProvider('default'))
        ->requiresTenantSubscription()
        // ...
}
```

> [!NOTE]
> Requiring tenant subscription is optional. You can remove `->requiresTenantSubscription()` if you wish.

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Maarten Paauw](https://github.com/maartenpaauw)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
