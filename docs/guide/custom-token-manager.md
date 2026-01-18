# Custom Token Manager

For advanced use cases (database storage, Redis, etc.), you can create a custom token manager by extending the `Token` abstract class.

## Implementation

```php
namespace App\Services\Ergani\TokenManagers;

use DateTime;
use OxygenSuite\OxygenErgani\Http\Client;
use OxygenSuite\OxygenErgani\Responses\AuthenticationToken;
use OxygenSuite\OxygenErgani\Storage\Token;

class DatabaseToken extends Token
{
    private ?AuthenticationToken $token = null;

    private function loadTokenFromDatabase(): void
    {
        // Create unique key from username, password, and environment
        $key = hash('sha256', $this->username . '-' . $this->password . '-' . Client::getDefaultEnvironment()->name);

        // Retrieve from your database
        $dbToken = DB::table('ergani_tokens')
            ->where('token_key', $key)
            ->first();

        if (!$dbToken) {
            return;
        }

        $this->token = new AuthenticationToken();
        $this->token->accessToken = $dbToken->access_token;
        $this->token->accessTokenExpiresAt = new DateTime($dbToken->access_expires_at);
        $this->token->refreshToken = $dbToken->refresh_token;
        $this->token->refreshTokenExpiresAt = new DateTime($dbToken->refresh_expires_at);
    }

    public function getAccessToken(): ?string
    {
        if (empty($this->token)) {
            $this->loadTokenFromDatabase();
        }

        return $this->token?->accessToken;
    }

    public function getRefreshToken(): string
    {
        return $this->token?->refreshToken ?? '';
    }

    public function isAccessTokenExpired(): bool
    {
        return $this->token?->accessTokenExpiresAt < new DateTime();
    }

    public function isRefreshTokenExpired(): bool
    {
        return $this->token?->refreshTokenExpiresAt < new DateTime();
    }

    public function setAuthToken(AuthenticationToken $token): static
    {
        $this->token = $token;

        $key = hash('sha256', $this->username . '-' . $this->password . '-' . Client::getDefaultEnvironment()->name);

        DB::table('ergani_tokens')->updateOrInsert(
            ['token_key' => $key],
            [
                'access_token' => $token->accessToken,
                'access_expires_at' => $token->accessTokenExpiresAt->format('Y-m-d H:i:s'),
                'refresh_token' => $token->refreshToken,
                'refresh_expires_at' => $token->refreshTokenExpiresAt->format('Y-m-d H:i:s'),
                'updated_at' => now(),
            ]
        );

        return $this;
    }

    public function failedAuthentication(): static
    {
        $this->token = null;

        $key = hash('sha256', $this->username . '-' . $this->password . '-' . Client::getDefaultEnvironment()->name);

        DB::table('ergani_tokens')
            ->where('token_key', $key)
            ->delete();

        return $this;
    }

    public function authToken(string $token): ?AuthenticationToken
    {
        return $this->token;
    }
}
```

## Required Methods

Your custom manager must implement these methods:

| Method | Purpose |
|--------|---------|
| `getAccessToken()` | Return current access token or `null` |
| `getRefreshToken()` | Return current refresh token |
| `isAccessTokenExpired()` | Check if access token has expired |
| `isRefreshTokenExpired()` | Check if refresh token has expired |
| `setAuthToken(AuthenticationToken)` | Store new tokens after auth/refresh |
| `failedAuthentication()` | Handle authentication failures |
| `authToken(string)` | Return the AuthenticationToken object |

## Database Migration

Example migration for token storage:

```php
Schema::create('ergani_tokens', function (Blueprint $table) {
    $table->id();
    $table->string('token_key')->unique();
    $table->text('access_token');
    $table->timestamp('access_expires_at');
    $table->text('refresh_token');
    $table->timestamp('refresh_expires_at');
    $table->timestamps();
});
```

## Usage

```php
use App\Services\Ergani\TokenManagers\DatabaseToken;
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Enums\Environment;

Token::setCurrentTokenManager(
    new DatabaseToken($username, $password),
    Environment::PRODUCTION
);
```

::: warning Multi-User Environments
Usernames alone are not unique identifiers. The built-in `FileToken` uses a SHA-256 hash of `username + password + environment` to create unique storage keys. Implement a similar approach in your custom manager to avoid token conflicts.
:::
