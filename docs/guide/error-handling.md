# Error Handling

The package uses a hierarchy of exceptions to handle different error scenarios. All exceptions extend the base `ErganiException` class.

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

## Exception Types

### ErganiException

The base exception for all ERGANI-related errors. Catches any API error not covered by more specific exceptions.

```php
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;

try {
    $response = (new WorkCard($token))->handle($cards);
} catch (ErganiException $e) {
    echo "Error: " . $e->getMessage();
    echo "Code: " . $e->getCode();
}
```

**Common causes:**
- Invalid data in request (HTTP 400)
- Server errors (HTTP 5xx)
- Malformed responses
- Unknown API errors

---

### AuthenticationException

Thrown when authentication fails due to invalid credentials.

```php
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;

try {
    $token = $ergani->authenticate('username', 'wrong_password');
} catch (AuthenticationException $e) {
    echo "Login failed: " . $e->getMessage();
    // Prompt user to check credentials
}
```

**Common causes:**
- Wrong username or password
- Account locked or disabled
- Missing access token when required
- HTTP 401 with invalid credentials

---

### TokenExpiredException

Thrown when the access token has expired and needs refreshing.

```php
use OxygenSuite\OxygenErgani\Exceptions\TokenExpiredException;

try {
    $response = (new WorkCard($token))->handle($cards);
} catch (TokenExpiredException $e) {
    // Token has expired, need to refresh or re-authenticate
    $newToken = $ergani->authenticate($username, $password);
    $response = (new WorkCard($newToken->accessToken))->handle($cards);
}
```

::: tip Automatic Token Refresh
When using a token manager, token refresh is handled automatically. The `TokenExpiredException` is caught internally, the token is refreshed, and the request is retried.
:::

---

### RefreshTokenExpiredException

Thrown when the refresh token has also expired, requiring full re-authentication.

```php
use OxygenSuite\OxygenErgani\Exceptions\RefreshTokenExpiredException;

try {
    // Automatic token refresh attempted but refresh token expired
    $response = (new WorkCard())->handle($cards);
} catch (RefreshTokenExpiredException $e) {
    // Must re-authenticate with username/password
    Token::currentTokenManager()->failedAuthentication();
    // Re-login flow required
}
```

---

### ConnectionException

Thrown when the client cannot connect to the ERGANI server.

```php
use OxygenSuite\OxygenErgani\Exceptions\ConnectionException;

try {
    $response = (new WorkCard($token))->handle($cards);
} catch (ConnectionException $e) {
    echo "Cannot connect to ERGANI: " . $e->getMessage();
    // Retry later or check network
}
```

**Common causes:**
- Network unavailable
- ERGANI server down
- DNS resolution failure
- Firewall blocking connection

---

### TimeoutException

Thrown when a request times out.

```php
use OxygenSuite\OxygenErgani\Exceptions\TimeoutException;

try {
    $response = (new WorkCard($token))->handle($cards);
} catch (TimeoutException $e) {
    echo "Request timed out: " . $e->getMessage();
    // Retry with backoff or notify user
}
```

**Common causes:**
- Slow network connection
- ERGANI server overloaded
- Request taking too long

---

## Comprehensive Error Handling

### Recommended Pattern

```php
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;
use OxygenSuite\OxygenErgani\Exceptions\ConnectionException;
use OxygenSuite\OxygenErgani\Exceptions\TimeoutException;
use OxygenSuite\OxygenErgani\Exceptions\TokenExpiredException;
use OxygenSuite\OxygenErgani\Exceptions\RefreshTokenExpiredException;

try {
    $response = (new WorkCard($token))->handle($cards);

    foreach ($response as $result) {
        echo "Submitted: {$result->protocol}";
    }

} catch (ConnectionException $e) {
    // Network/server issues - retry later
    logError('connection', $e);
    showUserMessage('Cannot connect to ERGANI. Please try again later.');

} catch (TimeoutException $e) {
    // Request timed out - may need retry
    logError('timeout', $e);
    showUserMessage('Request timed out. Please try again.');

} catch (AuthenticationException $e) {
    // Credentials invalid
    logError('auth', $e);
    showUserMessage('Authentication failed. Please check your credentials.');

} catch (TokenExpiredException | RefreshTokenExpiredException $e) {
    // Token issues (when not using token manager)
    logError('token', $e);
    showUserMessage('Session expired. Please log in again.');

} catch (ErganiException $e) {
    // All other ERGANI errors
    logError('ergani', $e);
    showUserMessage('Submission failed: ' . $e->getMessage());
}
```

### Order Matters

Catch more specific exceptions before general ones:

```php
// Correct order
try {
    // ...
} catch (AuthenticationException $e) {
    // Specific handling
} catch (ErganiException $e) {
    // General handling
}

// Wrong order - AuthenticationException never caught
try {
    // ...
} catch (ErganiException $e) {
    // Catches everything including AuthenticationException
} catch (AuthenticationException $e) {
    // Never reached
}
```

---

## HTTP Status Code Mapping

| HTTP Code | Exception | Description |
|-----------|-----------|-------------|
| 0 | `ConnectionException` | Connection failed |
| 400 | `ErganiException` | Bad request (validation error) |
| 401 | `AuthenticationException` or `TokenExpiredException` | Unauthorized |
| 5xx | `ErganiException` | Server error |

The 401 response is further analyzed:
- If `api-token-expired` header is `true` → `TokenExpiredException`
- Otherwise → `AuthenticationException`

---

## Error Messages

Exception messages contain details from the ERGANI API:

```php
try {
    $response = (new WorkCard($token))->handle($cards);
} catch (ErganiException $e) {
    // Message from API
    echo $e->getMessage();  // e.g., "Invalid AFM format"

    // HTTP status code
    echo $e->getCode();     // e.g., 400
}
```

---

## Retry Strategies

### Simple Retry

```php
function submitWithRetry(array $cards, int $maxRetries = 3): array
{
    $lastException = null;

    for ($i = 0; $i < $maxRetries; $i++) {
        try {
            return (new WorkCard())->handle($cards);
        } catch (ConnectionException | TimeoutException $e) {
            $lastException = $e;
            sleep(pow(2, $i));  // Exponential backoff
        }
    }

    throw $lastException;
}
```

### With Token Refresh

```php
function submitWithTokenRefresh(array $cards): array
{
    try {
        return (new WorkCard())->handle($cards);
    } catch (TokenExpiredException $e) {
        // Manually refresh if not using token manager
        $newToken = refreshToken();
        return (new WorkCard($newToken))->handle($cards);
    }
}
```

---

## Logging Best Practices

```php
use Psr\Log\LoggerInterface;

class ErganiService
{
    public function __construct(
        private LoggerInterface $logger,
        private string $accessToken
    ) {}

    public function submitWorkCards(array $cards): array
    {
        try {
            $this->logger->info('Submitting work cards', [
                'count' => count($cards),
            ]);

            $response = (new WorkCard($this->accessToken))->handle($cards);

            $this->logger->info('Work cards submitted', [
                'protocols' => array_map(fn($r) => $r->protocol, $response),
            ]);

            return $response;

        } catch (ErganiException $e) {
            $this->logger->error('Work card submission failed', [
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
                'exception' => $e,
            ]);

            throw $e;
        }
    }
}
```

---

## Validation Errors

HTTP 400 errors usually indicate validation failures. The message contains details:

```php
try {
    $response = (new WorkCard($token))->handle($cards);
} catch (ErganiException $e) {
    if ($e->getCode() === 400) {
        // Validation error - check the message
        echo "Validation failed: " . $e->getMessage();
        // e.g., "Field f_afm is required"
        // e.g., "Invalid date format for f_birthdate"
    }
}
```

---

## Testing Error Scenarios

When writing tests, use the mock handler to simulate errors:

```php
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;

// Simulate authentication error
$mock = new MockHandler([
    new Response(401, [], json_encode(['message' => 'Invalid credentials'])),
]);

// Simulate timeout
$mock = new MockHandler([
    new RequestException('Timeout', new Request('POST', 'uri')),
]);

// Simulate validation error
$mock = new MockHandler([
    new Response(400, [], json_encode(['message' => 'Invalid AFM format'])),
]);
```

---

## Best Practices

1. **Always Catch Exceptions**: Never let exceptions bubble up without handling.

2. **Log Everything**: Log all exceptions for debugging and audit trails.

3. **User-Friendly Messages**: Don't expose technical details to end users.

4. **Use Token Manager**: Automatic token refresh prevents most token errors.

5. **Implement Retries**: Network errors are often transient; retry with backoff.

6. **Validate Before Submission**: Catch validation errors early with client-side validation.

7. **Monitor Patterns**: Track error rates to detect systemic issues.

---

## See Also

- [Configuration](/guide/configuration) - Token manager setup
- [Token Management](/guide/token-management) - Automatic token handling
- [Services & Queries](/guide/services) - Query services with error handling
