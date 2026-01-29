# Oxygen Ergani

A comprehensive PHP package for interacting with Greece's ERGANI II system, enabling automated submissions for employee data including work cards, hiring declarations, terminations, and more.

[![PHP Version](https://img.shields.io/badge/php-%5E8.2-blue)](https://www.php.net/)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)
[![PHPStan](https://img.shields.io/badge/PHPStan-level%207-brightgreen)](https://phpstan.org/)

> **Warning**
> This package is currently in **alpha** (v2.0.0-alpha). The API is not yet stable and **breaking changes may occur** between releases. Please pin to a specific version in production environments and review the [CHANGELOG.md](CHANGELOG.md) before upgrading.

## Features

- **Work Cards** - Employee check-in/check-out submissions
- **Work Time Declarations** - Daily and weekly work schedules
- **Hiring Forms (E3)** - New hires, modifications, deletions, and lending arrangements
- **Termination Forms (E5)** - Voluntary resignations, retirements, and death terminations
- **Dismissal Forms (E6)** - Employer-initiated terminations, transfers, and loan endings
- **Fixed-Term Terminations (E7)** - Contract expirations and early terminations
- **Employment Modifications (MA/MAD)** - Changes to employment terms
- **Overtime Declarations** - Regular and retrospective overtime submissions
- **Query Services** - Employer info, branch details, parameter lookups, and employee status
- **PSR-16 Caching** - Opt-in caching for service responses with bundled file-based and in-memory implementations

## Requirements

- PHP ^8.2
- Guzzle HTTP ^7.9
- ERGANI credentials (username and password)

## Installation

```bash
composer require oxygensuite/oxygen-ergani
```

## Quick Start

### 1. Set Up Token Management

The package handles JWT authentication automatically. Configure it once at application boot:

```php
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Enums\Environment;

Token::setCurrentTokenManager(
    new FileToken('your-username', 'your-password'),
    Environment::TEST // or Environment::PRODUCTION
);
```

### 2. Submit a Work Card

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

$card = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0)
    ->setComments('')
    ->addDetails(
        CardDetail::make()
            ->setTin('888888888')
            ->setLastName('DOE')
            ->setFirstName('JOHN')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate(date('Y-m-d'))
            ->setDate(date('Y-m-d\TH:i:s.uP'))
    );

$response = (new WorkCard())->handle($card);

echo $response[0]->protocol; // e.g., 'ΕΥΣ92'
```

### 3. Submit a Hiring Declaration (E3)

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;

$declaration = NewDeclaration::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0)
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setTin('888888888')
    ->setAmka('01018012345')
    ->setHiringDate('15/01/2025')
    // ... additional fields
    ->withDefaults(); // Fill remaining fields with empty strings

$response = (new HiringNew())->handle($declaration);
```

## Documentation

Full documentation is available at **[oxygensuite.github.io/oxygen-ergani](https://oxygensuite.github.io/oxygen-ergani)**.

### Guides

- [Getting Started](https://oxygensuite.github.io/oxygen-ergani/guide/getting-started)
- [Token Management](https://oxygensuite.github.io/oxygen-ergani/guide/token-management)
- [Work Cards](https://oxygensuite.github.io/oxygen-ergani/guide/work-cards)
- [Work Time Declarations](https://oxygensuite.github.io/oxygen-ergani/guide/work-time)
- [Hiring Forms (E3)](https://oxygensuite.github.io/oxygen-ergani/guide/hiring/)
- [Termination Forms (E5)](https://oxygensuite.github.io/oxygen-ergani/guide/termination/)
- [Dismissal Forms (E6)](https://oxygensuite.github.io/oxygen-ergani/guide/dismissal/)
- [Fixed-Term Terminations (E7)](https://oxygensuite.github.io/oxygen-ergani/guide/fixed-term)
- [Employment Modifications (MA/MAD)](https://oxygensuite.github.io/oxygen-ergani/guide/modifications)
- [Query Services](https://oxygensuite.github.io/oxygen-ergani/guide/services)
- [Error Handling](https://oxygensuite.github.io/oxygen-ergani/guide/error-handling)

### API Reference

- [Ergani Facade](https://oxygensuite.github.io/oxygen-ergani/api/ergani)
- [Models](https://oxygensuite.github.io/oxygen-ergani/api/models)
- [Enums](https://oxygensuite.github.io/oxygen-ergani/api/enums)
- [Responses](https://oxygensuite.github.io/oxygen-ergani/api/responses)
- [Exceptions](https://oxygensuite.github.io/oxygen-ergani/api/exceptions)

## Upgrading

If you're upgrading from v1.x, please see the [UPGRADING.md](UPGRADING.md) guide for breaking changes and migration instructions.

## Development

```bash
# Install dependencies
composer install

# Run tests
composer test

# Static analysis (PHPStan level 7)
composer analyse

# Code style (PER)
composer lint

# Mutation testing
composer infect

# Run all checks
composer check
```

## Security

If you discover a security vulnerability, please send an email to [security@oxygen.gr](mailto:security@oxygen.gr). All security vulnerabilities will be promptly addressed. Please see [SECURITY.md](SECURITY.md) for more details.

## Contributing

Contributions are welcome! Please see [CONTRIBUTING.md](CONTRIBUTING.md) for guidelines.

## Changelog

Please see [CHANGELOG.md](CHANGELOG.md) for information about recent changes.

## License

This package is open-source software licensed under the [MIT License](LICENSE).

Copyright 2025 [Oxygen Suite](https://github.com/oxygensuite).
