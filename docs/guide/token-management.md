# Token Management

The ERGANI API uses JWT (JSON Web Tokens) for authentication. Managing token lifecycles (authentication, refresh, and expiration) can be complex. This package provides automatic token management to simplify this process.

## How It Works

1. **Initial Authentication** - When you first make an API call, the token manager authenticates with your credentials
2. **Token Storage** - The access and refresh tokens are stored (file or memory)
3. **Automatic Refresh** - Before the access token expires, it's automatically refreshed
4. **Re-authentication** - If the refresh token expires, the manager re-authenticates

## FileToken (Recommended)

The `FileToken` manager stores tokens in files, making them persist across script executions:

```php
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Enums\Environment;

Token::setCurrentTokenManager(
    new FileToken('username', 'password'),
    Environment::PRODUCTION
);
```

### Unique Token Files

Tokens are stored in files named using a SHA-256 hash of the username, password, and environment. This ensures:

- Multiple users can have separate token files
- Test and production environments use different tokens
- Token files don't conflict between environments

### Custom Storage Directory

```php
// Option 1: Per-instance configuration
$manager = new FileToken('username', 'password', [
    'cache_dir' => '/var/app/storage/ergani-tokens'
]);

// Option 2: Global configuration
FileToken::setDirectory('/var/app/storage/ergani-tokens');
$manager = new FileToken('username', 'password');
```

### Security Considerations

**File System Protection:**
- Cache directory is created with `0700` permissions (owner read/write/execute only)
- Token files are stored with `0600` permissions (owner read/write only)
- Default `.cache/` directory includes `.htaccess` for Apache
- For Nginx: `location ~ /\.cache { deny all; }`
- Recommended: Store tokens outside the web root

**Hashing:**
- Filenames use SHA-256 hash of `username + password + environment`
- This prevents exposing credentials in filenames while ensuring uniqueness

**Storage Format:**
- Tokens are stored as JSON with the following structure:
  - `accessToken` - The JWT access token
  - `accessTokenExpiresAt` - Unix timestamp of expiration
  - `refreshToken` - The refresh token
  - `refreshTokenExpiresAt` - Unix timestamp of expiration

## InMemoryToken

For single-script execution or testing:

```php
use OxygenSuite\OxygenErgani\Storage\InMemoryToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Enums\Environment;

Token::setCurrentTokenManager(
    new InMemoryToken('username', 'password'),
    Environment::TEST
);
```

::: warning
Tokens are lost when the script ends. Each execution requires fresh authentication.
:::

## Test Doubles

For testing, use the fake implementations:

```php
use OxygenSuite\OxygenErgani\Storage\FakeFileToken;
use OxygenSuite\OxygenErgani\Storage\FakeInMemoryToken;

// In your test setup
Token::setCurrentTokenManager(
    new FakeFileToken('test-user', 'test-pass'),
    Environment::TEST
);
```

## Using Without Token Manager

You can bypass the token manager and use tokens directly:

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Enums\Environment;

// Provide token and environment explicitly
$workCard = new WorkCard('your-access-token', Environment::TEST);
$response = $workCard->handle($card);
```

This approach requires you to handle token refresh and expiration manually.

## Laravel Integration

Set up the token manager in a service provider:

```php
// app/Providers/ErganiServiceProvider.php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Enums\Environment;

class ErganiServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        $env = config('ergani.environment') === 'production'
            ? Environment::PRODUCTION
            : Environment::TEST;

        Token::setCurrentTokenManager(
            new FileToken(
                config('ergani.username'),
                config('ergani.password'),
                ['cache_dir' => storage_path('ergani')]
            ),
            $env
        );
    }
}
```

Then add to `config/ergani.php`:

```php
return [
    'username' => env('ERGANI_USERNAME'),
    'password' => env('ERGANI_PASSWORD'),
    'environment' => env('ERGANI_ENVIRONMENT', 'test'),
];
```
