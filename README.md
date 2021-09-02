# Paybox bundle

## Configuration

To configure the bundle, you will have to enter the api key, secret and list of templates :

``` yaml

    # app/config/paybox.yml
    paybox:
    parameters:
        production: false # Switch between Paybox test and production servers
        site: 'XXXXX' # Site number
        rank: 'XXX' # Rank number
        login: 'XXXXXXX' # Customer's login
        hmac:
            key: 'XXXXXXX' # Key used to compute the hmac hash
```

## Setup

Add `Antilop\Bundle\PayboxBundle\PayboxBundle` to your `bundles.php`:

```php
$bundles = [
    // ...
    Antilop\Bundle\PayboxBundle\PayboxBundle::class => ['all' => true]
];
```

# Limitation

For the moment, the bundle was developed for experimental purposes. Changes and adjustements may
be added for a more complete use.