# Configuration

## Environment Selection

The package supports two environments:

```php
use OxygenSuite\OxygenErgani\Enums\Environment;

// Test environment (recommended for development)
$env = Environment::TEST;     // trialv2eservices.yeka.gr

// Production environment
$env = Environment::PRODUCTION; // eservices.yeka.gr
```

Set the environment when configuring the token manager:

```php
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;

Token::setCurrentTokenManager(
    new FileToken($username, $password),
    Environment::TEST
);
```

## Token Managers

Three token managers are available:

### FileToken (Recommended)

Stores tokens in files. Survives script restarts and is suitable for production:

```php
use OxygenSuite\OxygenErgani\Storage\FileToken;

$manager = new FileToken($username, $password);

// With custom cache directory
$manager = new FileToken($username, $password, [
    'cache_dir' => '/var/app/storage/tokens'
]);
```

### InMemoryToken

Stores tokens in memory. Suitable for single script execution or testing:

```php
use OxygenSuite\OxygenErgani\Storage\InMemoryToken;

$manager = new InMemoryToken($username, $password);
```

### Custom Cache Directory

Configure the token storage location:

```php
// Option 1: Instance-level (highest priority)
$options = ['cache_dir' => '/var/app/storage/tokens'];
$manager = new FileToken($username, $password, $options);

// Option 2: Static global (middle priority)
FileToken::setDirectory('/var/app/storage/tokens');
$manager = new FileToken($username, $password);

// Option 3: Default .cache directory (lowest priority)
$manager = new FileToken($username, $password);
```

## Manual Token Handling

For advanced use cases, you can handle authentication manually:

```php
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogin;
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Enums\Environment;

// Authenticate manually
$auth = new AuthenticationLogin();
$token = $auth->handle('username', 'password');

// Use the token directly
$workCard = new WorkCard($token->accessToken, Environment::TEST);
$response = $workCard->handle($card);
```

The authentication response contains:

| Property | Description |
|----------|-------------|
| `accessToken` | JWT for API requests |
| `accessTokenExpirationSeconds` | Token validity duration |
| `refreshToken` | Token for refreshing access |
| `accessTokenExpiresAt` | Access token expiration timestamp |
| `refreshTokenExpiresAt` | Refresh token expiration timestamp |

## Refresh Token

When the access token expires, refresh it:

```php
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationRefresh;

$auth = new AuthenticationRefresh();
$newToken = $auth->handle($oldAccessToken, $oldRefreshToken);
```

## Logout

Invalidate tokens when done:

```php
use OxygenSuite\OxygenErgani\Http\Auth\AuthenticationLogout;

$auth = new AuthenticationLogout($accessToken);
$auth->handle($refreshToken);
```

::: tip
The `FileToken` and `InMemoryToken` managers handle all token lifecycle operations automatically. Manual token handling is only needed for custom implementations.
:::
