# Exceptions

The package uses a hierarchy of exceptions for different error scenarios. All exceptions extend PHP's base `Exception` class.

## Exception Hierarchy

```
Exception
└── ErganiException
    ├── AuthenticationException
    ├── ConnectionException
    ├── TimeoutException
    ├── TokenExpiredException
    └── RefreshTokenExpiredException
```

---

## ErganiException

Base exception for all ERGANI-related errors.

```php
namespace OxygenSuite\OxygenErgani\Exceptions;

class ErganiException extends Exception
```

**When thrown:**
- API returns an error response
- Invalid data in request (HTTP 400)
- Server errors (HTTP 5xx)
- General API errors not covered by specific exceptions

**Example:**

```php
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;

try {
    $response = (new WorkCard($token))->handle($cards);
} catch (ErganiException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
}
```

---

## AuthenticationException

Thrown when authentication fails due to invalid credentials.

```php
namespace OxygenSuite\OxygenErgani\Exceptions;

class AuthenticationException extends ErganiException
```

**When thrown:**
- Wrong username or password
- Account locked or disabled
- Missing access token when required
- HTTP 401 with invalid credentials

**Example:**

```php
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;

try {
    $token = $ergani->authenticate('user', 'wrong_password');
} catch (AuthenticationException $e) {
    // Prompt user to check credentials
    echo "Login failed: " . $e->getMessage();
}
```

---

## TokenExpiredException

Thrown when the access token has expired.

```php
namespace OxygenSuite\OxygenErgani\Exceptions;

class TokenExpiredException extends ErganiException
```

**When thrown:**
- Access token has expired (detected via `api-token-expired` header)
- HTTP 401 with expired token

**Automatic handling:**
When using a token manager, `TokenExpiredException` is caught internally, the token is refreshed, and the request is retried automatically.

**Example:**

```php
use OxygenSuite\OxygenErgani\Exceptions\TokenExpiredException;

try {
    $response = (new WorkCard($token))->handle($cards);
} catch (TokenExpiredException $e) {
    // Token expired, need to refresh
    $newToken = refreshToken();
    $response = (new WorkCard($newToken))->handle($cards);
}
```

---

## RefreshTokenExpiredException

Thrown when the refresh token has also expired.

```php
namespace OxygenSuite\OxygenErgani\Exceptions;

class RefreshTokenExpiredException extends ErganiException
```

**When thrown:**
- Refresh token has expired
- Full re-authentication required

**Example:**

```php
use OxygenSuite\OxygenErgani\Exceptions\RefreshTokenExpiredException;

try {
    $response = (new WorkCard())->handle($cards);
} catch (RefreshTokenExpiredException $e) {
    // Must re-authenticate with username/password
    Token::currentTokenManager()->failedAuthentication();
    // Trigger re-login flow
}
```

---

## ConnectionException

Thrown when the client cannot connect to the ERGANI server.

```php
namespace OxygenSuite\OxygenErgani\Exceptions;

class ConnectionException extends ErganiException
```

**When thrown:**
- Network unavailable
- ERGANI server down
- DNS resolution failure
- Firewall blocking connection
- HTTP status code 0

**Example:**

```php
use OxygenSuite\OxygenErgani\Exceptions\ConnectionException;

try {
    $response = (new WorkCard($token))->handle($cards);
} catch (ConnectionException $e) {
    // Retry later or check network
    echo "Cannot connect: " . $e->getMessage();
}
```

---

## TimeoutException

Thrown when a request times out.

```php
namespace OxygenSuite\OxygenErgani\Exceptions;

class TimeoutException extends ErganiException
```

**When thrown:**
- Request exceeds timeout limit
- Server taking too long to respond
- Network latency issues

**Example:**

```php
use OxygenSuite\OxygenErgani\Exceptions\TimeoutException;

try {
    $response = (new WorkCard($token))->handle($cards);
} catch (TimeoutException $e) {
    // Retry with backoff
    echo "Request timed out: " . $e->getMessage();
}
```

---

## HTTP Status Code Mapping

| HTTP Code | Exception | Description |
|-----------|-----------|-------------|
| 0 | `ConnectionException` | Connection failed |
| 400 | `ErganiException` | Bad request (validation error) |
| 401 | `TokenExpiredException` | Token expired (if `api-token-expired` header) |
| 401 | `AuthenticationException` | Invalid credentials |
| 5xx | `ErganiException` | Server error |

---

## Comprehensive Error Handling

```php
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;
use OxygenSuite\OxygenErgani\Exceptions\ConnectionException;
use OxygenSuite\OxygenErgani\Exceptions\TimeoutException;
use OxygenSuite\OxygenErgani\Exceptions\TokenExpiredException;
use OxygenSuite\OxygenErgani\Exceptions\RefreshTokenExpiredException;

try {
    $response = (new WorkCard($token))->handle($cards);

} catch (ConnectionException $e) {
    // Network issues - retry later
    logError('connection', $e);
    showUserMessage('Cannot connect to ERGANI.');

} catch (TimeoutException $e) {
    // Timeout - may need retry
    logError('timeout', $e);
    showUserMessage('Request timed out.');

} catch (AuthenticationException $e) {
    // Invalid credentials
    logError('auth', $e);
    showUserMessage('Authentication failed.');

} catch (TokenExpiredException | RefreshTokenExpiredException $e) {
    // Session expired
    logError('token', $e);
    showUserMessage('Session expired. Please log in again.');

} catch (ErganiException $e) {
    // All other errors
    logError('ergani', $e);
    showUserMessage('Error: ' . $e->getMessage());
}
```

---

## Exception Properties

All exceptions inherit standard `Exception` properties:

| Property/Method | Type | Description |
|-----------------|------|-------------|
| `getMessage()` | string | Error message from API |
| `getCode()` | int | HTTP status code or error code |
| `getPrevious()` | Throwable\|null | Previous exception (if wrapped) |
| `getFile()` | string | File where exception was thrown |
| `getLine()` | int | Line where exception was thrown |
| `getTrace()` | array | Stack trace |

**Example:**

```php
try {
    // ...
} catch (ErganiException $e) {
    echo "Message: " . $e->getMessage();
    echo "Code: " . $e->getCode();
    echo "File: " . $e->getFile();
    echo "Line: " . $e->getLine();

    // Log full trace
    error_log($e->getTraceAsString());
}
```

---

## Retry Pattern

```php
function submitWithRetry(array $cards, int $maxRetries = 3): array
{
    $lastException = null;

    for ($i = 0; $i < $maxRetries; $i++) {
        try {
            return (new WorkCard())->handle($cards);
        } catch (ConnectionException | TimeoutException $e) {
            $lastException = $e;
            // Exponential backoff
            sleep(pow(2, $i));
        }
    }

    throw $lastException;
}
```

---

## See Also

- [Error Handling](/guide/error-handling) - Complete error handling guide
- [Token Management](/guide/token-management) - Automatic token refresh
- [Configuration](/guide/configuration) - Client configuration
