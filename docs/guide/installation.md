# Installation

## Requirements

Before installing Oxygen Ergani, ensure your environment meets these requirements:

- **PHP** ^8.2
- **Guzzle HTTP** ^7.9 (installed automatically)
- **ERGANI Credentials** - Username and password from the ERGANI system

## Composer Installation

Install the package using Composer:

```bash
composer require oxygensuite/oxygen-ergani
```

This will install the package and its dependencies, including Guzzle HTTP.

## Verify Installation

After installation, verify everything is working:

```php
<?php

require 'vendor/autoload.php';

use OxygenSuite\OxygenErgani\Enums\Environment;

// Verify the package is loaded
echo Environment::TEST->getApiUrl(); // Should output the test API URL
```

## Obtaining ERGANI Credentials

To use the ERGANI API, you need credentials issued by the Greek Ministry of Labor:

1. Register with the ERGANI system at [https://eservices.yeka.gr](https://eservices.yeka.gr)
2. Request API access for your organization
3. Store your credentials securely (never commit them to version control)

::: tip Environment Variables
Store credentials in environment variables:

```bash
ERGANI_USERNAME=your-username
ERGANI_PASSWORD=your-password
```

```php
Token::setCurrentTokenManager(
    new FileToken(
        getenv('ERGANI_USERNAME'),
        getenv('ERGANI_PASSWORD')
    ),
    Environment::PRODUCTION
);
```
:::

## Token Storage Directory

By default, `FileToken` stores tokens in a `.cache/` directory within the package root. For production, configure a secure directory outside the web root:

```php
use OxygenSuite\OxygenErgani\Storage\FileToken;

// Option 1: Instance-level configuration
$options = ['cache_dir' => '/var/app/storage/tokens'];
$manager = new FileToken($username, $password, $options);

// Option 2: Global configuration
FileToken::setDirectory('/var/app/storage/tokens');
$manager = new FileToken($username, $password);
```

::: warning Security
- The `.cache/` directory includes an `.htaccess` file for Apache protection
- For Nginx, add: `location ~ /\.cache { deny all; }`
- Best practice: Store tokens outside the web root entirely
:::

## Next Steps

- [Configuration](/guide/configuration) - Configure environments and options
- [Token Management](/guide/token-management) - Learn about automatic token handling
