# Getting Started

Oxygen Ergani is a PHP package for interacting with Greece's ERGANI system, enabling automated submissions for employee data such as check-ins, check-outs, work time declarations, hiring, and terminations.

## Installation

Install the package via Composer:

```bash
composer require oxygensuite/oxygen-ergani
```

## Requirements

- PHP ^8.2
- Guzzle HTTP ^7.9
- ERGANI credentials (username and password)

## Basic Setup

### 1. Configure Token Management

The recommended approach is to set up the `FileToken` manager once when your application boots. This handles authentication, token refresh, and persistence automatically.

```php
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Enums\Environment;

Token::setCurrentTokenManager(
    new FileToken('your-username', 'your-password'),
    Environment::TEST // or Environment::PRODUCTION
);
```

::: tip Laravel Integration
In Laravel, set the token manager in a service provider or middleware to ensure it's configured for every request.
:::

### 2. Make API Calls

Once the token manager is configured, you can make API calls without specifying credentials:

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

$card = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0)
    ->addDetails(
        CardDetail::make()
            ->setTin('888888888')
            ->setFirstName('John')
            ->setLastName('Doe')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate(date('Y-m-d'))
            ->setDate(date('Y-m-d\TH:i:s.uP'))
    );

$response = (new WorkCard())->handle($card);
```

## Environments

The package supports two environments:

| Environment | Domain | Use Case |
|-------------|--------|----------|
| `Environment::TEST` | `trialv2eservices.yeka.gr` | Development and testing |
| `Environment::PRODUCTION` | `eservices.yeka.gr` | Live production data |

::: warning
Always use `Environment::TEST` during development. Production submissions are real and cannot be easily reversed.
:::

## Next Steps

- [Token Management](/guide/token-management) - Learn about automatic token handling
- [Work Cards](/guide/work-cards) - Submit employee check-ins and check-outs
- [Hiring (E3)](/guide/hiring/) - Handle employee hiring declarations
