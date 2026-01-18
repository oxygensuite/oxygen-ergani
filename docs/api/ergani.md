# Ergani Facade

The `Ergani` class provides a simplified interface for common ERGANI operations.

## Class Definition

```php
namespace OxygenSuite\OxygenErgani;

class Ergani
{
    public function __construct(
        ?string $accessToken = null,
        ?Environment $environment = Environment::TEST,
        ?ClientConfig $config = null
    );
}
```

## Constructor Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$accessToken` | string\|null | `null` | Bearer token for authentication |
| `$environment` | Environment\|null | `Environment::TEST` | API environment |
| `$config` | ClientConfig\|null | `null` | Custom HTTP client configuration |

## Methods

### authenticate()

Authenticate with username and password to obtain tokens.

```php
public function authenticate(
    string $username,
    string $password,
    UserType $userType = UserType::EXTERNAL
): AuthenticationToken
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$username` | string | - | ERGANI username |
| `$password` | string | - | ERGANI password |
| `$userType` | UserType | `UserType::EXTERNAL` | Type of user |

**Returns:** `AuthenticationToken`

**Throws:** `ErganiException`, `AuthenticationException`

**Example:**

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Enums\Environment;

$ergani = new Ergani(null, Environment::PRODUCTION);

$token = $ergani->authenticate('myusername', 'mypassword');

echo $token->accessToken;
echo $token->refreshToken;
echo $token->accessTokenExpiresAt->format('Y-m-d H:i:s');
```

---

### getServices()

Get list of available services for the authenticated user.

```php
public function getServices(): array
```

**Returns:** `array<string, mixed>` - List of available services

**Throws:** `ErganiException`

**Example:**

```php
$ergani = new Ergani($accessToken);
$services = $ergani->getServices();

foreach ($services as $service) {
    print_r($service);
}
```

---

### workCardSchema()

Get the schema for work card submissions.

```php
public function workCardSchema(): array
```

**Returns:** `array<string, mixed>` - Work card schema definition

**Throws:** `ErganiException`

**Example:**

```php
$ergani = new Ergani($accessToken);
$schema = $ergani->workCardSchema();

print_r($schema);
```

---

### sendWorkCards()

Submit work cards (check-in/check-out events).

```php
public function sendWorkCards(Card|array $cards): array
```

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$cards` | Card\|Card[] | Single card or array of cards |

**Returns:** `array<int, WorkCardResponse>` - Submission responses

**Throws:** `ErganiException`

**Example:**

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

$ergani = new Ergani($accessToken);

$card = Card::make()
    ->setEmployerAfm('123456789')
    ->setBranchCode(0)
    ->setDate('15/01/2025')
    ->setAfm('987654321')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->addCardDetail(
        CardDetail::make()
            ->setType(CardDetailType::CHECK_IN)
            ->setTime('09:00')
    );

$responses = $ergani->sendWorkCards($card);

foreach ($responses as $response) {
    echo $response->protocol;
}
```

---

### getParameters()

Look up parameter values by type.

```php
public function getParameters(string $parameter): ParameterCollection
```

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$parameter` | string | Parameter type (use `ParameterLookup` constants) |

**Returns:** `ParameterCollection` - Collection of parameter values

**Throws:** `ErganiException`

**Example:**

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;

$ergani = new Ergani($accessToken);

// Get nationality codes
$nationalities = $ergani->getParameters(ParameterLookup::NATIONALITY);

// Find Greek nationality
$greek = $nationalities->find('001');
echo $greek->description;  // "ΕΛΛΑΔΑ"

// Get work time types
$workTypes = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);

// For HTML dropdown
$dropdown = $workTypes->toDropdown();
```

---

## Complete Example

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;

try {
    // Authenticate
    $ergani = new Ergani(null, Environment::PRODUCTION);
    $token = $ergani->authenticate('username', 'password');

    // Create new instance with token
    $ergani = new Ergani($token->accessToken, Environment::PRODUCTION);

    // Check available services
    $services = $ergani->getServices();

    // Get parameter values
    $specialties = $ergani->getParameters(ParameterLookup::SPECIALTY);

    // Submit work card
    $card = Card::make()
        ->setEmployerAfm('123456789')
        ->setBranchCode(0)
        ->setDate('15/01/2025')
        ->setAfm('987654321')
        ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
        ->setFirstName('ΙΩΑΝΝΗΣ')
        ->addCardDetail(
            CardDetail::make()
                ->setType(CardDetailType::CHECK_IN)
                ->setTime('09:00')
        );

    $responses = $ergani->sendWorkCards($card);

    foreach ($responses as $response) {
        echo "Submitted: {$response->protocol}\n";
    }

} catch (AuthenticationException $e) {
    echo "Authentication failed: " . $e->getMessage();
} catch (ErganiException $e) {
    echo "Error: " . $e->getMessage();
}
```

---

## Using with Token Manager

When using a token manager, you don't need to manage tokens manually:

```php
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Ergani;

// Set up token manager (once, at application startup)
Token::setCurrentTokenManager(
    new FileToken('username', 'password'),
    Environment::PRODUCTION
);

// Create Ergani without explicit token - it uses the token manager
$ergani = new Ergani();

// All methods work automatically
$services = $ergani->getServices();
$responses = $ergani->sendWorkCards($cards);
```

---

## See Also

- [Configuration](/guide/configuration) - Environment and token setup
- [Token Management](/guide/token-management) - Automatic token handling
- [Work Cards](/guide/work-cards) - Work card submissions
- [Services & Queries](/guide/services) - Service classes
