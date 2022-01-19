<p align="center"><a href="https://naykel.com.au" target="_blank"><img src="https://avatars0.githubusercontent.com/u/32632005?s=460&u=d1df6f6e0bf29668f8a4845271e9be8c9b96ed83&v=4" width="120"></a></p>

<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/dt/naykel/payit" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/v/naykel/payit" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/naykel/payit"><img src="https://img.shields.io/packagist/l/naykel/payit" alt="License"></a>

# NAYKEL Payment Management Package


## Thinks to know

**On inception of this package there is a bit of confusion surrounding the database column names and roles. This confusion is caused by the fact that there are different providers such as PayPal and Stipe, and each provider may have different payment methods such as express checkout for PayPal, or credit card and afterpay for Stripe.**

- `payit` only handles the payment processing, it does not care about products!
- Naming convention is important as the service class and provider keys are based on the `platform_name`. Find out more in the naming conventions section.



- the payment platform name stored in the payment configuration needs to be the same as the name stored in the database
- Platform alias is created when persists to database converting name to lowercase and replacing spaces with dashes
- save icon names the sames as the platform alias (lower case and with dashes)






## Installation

To get started, install Payit using the Composer package manager:

    composer require naykel/payit

## Finishing up and making it work

Run the migration to install and update database tables:

    php artisan migrate

Run payment platform seeder

    # add seeder to project
    $this->call(\Naykel\Payit\Database\Seeders\PaymentPlatformSeeder::class);
    # seed from command line
    php artisan db:seed --class=Naykel\\Payit\\Database\\Seeders\\PaymentPlatformSeeder


Add payment configuration/'s to `config/services.php`

    'paypal' => [
        'base_uri' => env('PAYPAL_BASE_URI'),
        'client_id' => env('PAYPAL_CLIENT_ID'),
        'client_secret' => env('PAYPAL_CLIENT_SECRET'),
        'class' => Naykel\Payit\Services\PayPalService::class,
        'plans' => [
            'monthly' => env('PAYPAL_MONTHLY_PLAN'),
            'yearly' => env('PAYPAL_YEARLY_PLAN'),
        ],
    ],

    'stripe' => [
        'base_uri' => env('STRIPE_BASE_URI'),
        'key' => env('STRIPE_KEY'),
        'secret' => env('STRIPE_SECRET'),
        'class' => Naykel\Payit\Services\StripeService::class,
        'plans' => [
            'monthly' => env('STRIPE_MONTHLY_PLAN'),
            'yearly' => env('STRIPE_YEARLY_PLAN'),
        ],
    ],

Add keys to `env` file

    PAYPAL_BASE_URI=https://api-m.sandbox.paypal.com
    PAYPAL_CLIENT_ID=-
    PAYPAL_CLIENT_SECRET=

    STRIPE_BASE_URI=https://api.stripe.com
    STRIPE_KEY=
    STRIPE_SECRET=

#### Naming Conventions

When adding a new payment platform the `platform_name` is used to to resolve the platform service, and fetch service credentials `services.php`.

The platform name stored in the database should be stored in 'CamelCase' or 'Title Case'. (PayPal or After Pay)

The platform service class should be named in 'CamelCase'. (PayPalService or AfterPayService)

The platform key in `config/services.php` should be lowercase with no spaces. (`paypal` => [] or `afterpay` => [])

The `alias` is used on to display the payment option on the front-end should be stored as 'Title Case'. (PayPal or After Pay)

The `collapse` component should be created with the same name as the alias in 'lower-case' and hyphenated.
