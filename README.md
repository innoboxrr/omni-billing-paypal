# OmniBilling — PayPal Driver

**PayPal, behind a gateway-agnostic interface.**

This is the PayPal adapter for [OmniBilling](https://github.com/innoboxrr/omni-billing). Your application keeps talking to one billing API; this package translates it to PayPal.

```php
// Application code. Identical whichever driver is active.
Billing::customer($user)->subscribe('pro-monthly')->create();
```

Useful precisely where Stripe is not an option — markets, industries or customer preferences that require PayPal. Switching does not touch your business logic.

## What it covers

| | |
|---|---|
| **Payments** | Orders, captures, refunds |
| **Subscriptions** | Plans, billing agreements, upgrades, cancellation |
| **Customers** | Payer records and stored funding sources |

## Install

```bash
composer require innoboxrr/omni-billing innoboxrr/omni-billing-paypal
php artisan vendor:publish --tag=omni-billing-config
```

```dotenv
OMNI_BILLING_DRIVER=paypal
PAYPAL_CLIENT_ID=...
PAYPAL_CLIENT_SECRET=...
PAYPAL_MODE=sandbox
```

---

Part of [Innobox R&R](https://github.com/innoboxrr) — 52 open-source packages extracted from production work. **[innobox.systems](https://innobox.systems)**
