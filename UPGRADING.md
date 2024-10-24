# Upgrading

## From v1 to v2

Starting from version 2, the middleware now checks the product subscription using `->subscribedToProduct(...)` instead
of `->subscribed()`. Therefore, the `product_id` configuration is required. This change allows you to manage multiple
plans within your application, such as `basic`, `advanced`, and `premium`, and restrict tenant access to your Filament
panel based on their subscription to the `advanced` plan.

### Stripe Product Identifier

Add the `product_id` to each plan configured in the `cashier.php` configuration file.

```diff
'plans' => [
    'default' => [
+        'product_id' => ENV('CASHIER_STRIPE_SUBSCRIPTION_DEFAULT_PRODUCT_ID'),
        'price_id' => ENV('CASHIER_STRIPE_SUBSCRIPTION_DEFAULT_PRICE_ID'),
    ],
],
