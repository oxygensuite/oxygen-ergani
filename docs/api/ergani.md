# Ergani Facade

The `Ergani` class provides a simplified interface for all ERGANI operations including document submissions, services, and queries.

## Class Definition

```php
namespace OxygenSuite\OxygenErgani;

class Ergani
{
    public function __construct(
        ?string $accessToken = null,
        ?Environment $environment = Environment::TEST,
        ?ClientConfig $config = null,
        ?CacheInterface $cache = null,
        string $cachePrefix = '',
    );
}
```

## Constructor Parameters

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$accessToken` | string\|null | `null` | Bearer token for authentication |
| `$environment` | Environment\|null | `Environment::TEST` | API environment |
| `$config` | ClientConfig\|null | `null` | Custom HTTP client configuration |
| `$cache` | CacheInterface\|null | `null` | PSR-16 cache for service responses |
| `$cachePrefix` | string\|null | `null` | Cache key prefix. Null = auto-derive from TokenManager credentials. |
| `$cacheTtl` | int | `2592000` | Cache TTL in seconds (default: 30 days) |

---

## Authentication & Services

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

### logout()

Invalidate the session by deleting the refresh token from the API server.

```php
public function logout(string $refreshToken): bool
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$refreshToken` | string | - | The refresh token to revoke |

**Returns:** `bool` - `true` if the refresh token was revoked

**Throws:** `ErganiException`

**Example:**

```php
$ergani = new Ergani($accessToken);

$ergani->logout($refreshToken);
```

::: tip
After logging out, the refresh token can no longer be used to obtain new access tokens. Call `authenticate()` again to start a new session.
:::

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

### getEmployerInfo()

Retrieve employer information. Results are cached if a cache is configured (30-day TTL).

```php
public function getEmployerInfo(): EmployerResponse
```

**Returns:** `EmployerResponse`

**Throws:** `ErganiException`

**Example:**

```php
$ergani = new Ergani($accessToken);
$employer = $ergani->getEmployerInfo();

echo $employer->name;          // "COMPANY A.E."
echo $employer->tin;           // "123456789"
echo $employer->isInCardSector; // true/false
```

---

### getBranches()

Retrieve all branches for the employer. Results are cached if a cache is configured (30-day TTL).

```php
public function getBranches(): BranchCollection
```

**Returns:** `BranchCollection`

**Throws:** `ErganiException`

**Example:**

```php
$ergani = new Ergani($accessToken);
$branches = $ergani->getBranches();

foreach ($branches as $aa => $branch) {
    echo "Branch {$aa}: {$branch->address}\n";
}

$dropdown = $branches->toDropdown();
```

---

### getMonthlyStatus()

Retrieve monthly employee status for reporting.

```php
public function getMonthlyStatus(int $year, int $month): array
```

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$year` | int | Report year (e.g., 2025) |
| `$month` | int | Report month (1-12) |

**Returns:** `array<int, EmployeeStatusResponse>` - List of employee status records

**Throws:** `ErganiException`

**Example:**

```php
$ergani = new Ergani($accessToken);
$employees = $ergani->getMonthlyStatus(2025, 1);

foreach ($employees as $employee) {
    echo "{$employee->lastName} {$employee->firstName}\n";
    echo "AFM: {$employee->afm}\n";
}
```

---

### getWorkforceStatus()

Retrieve current workforce status, optionally filtered by employee AFM. Not cached (dynamic data).

```php
public function getWorkforceStatus(?string $tin = null): array
```

**Parameters:**

| Parameter | Type | Default | Description |
|-----------|------|---------|-------------|
| `$tin` | string\|null | `null` | Employee tax ID to filter by |

**Returns:** `array<int, WorkforceStatusResponse>` - List of workforce status records

**Throws:** `ErganiException`

**Example:**

```php
$ergani = new Ergani($accessToken);

// All employees
$employees = $ergani->getWorkforceStatus();

// Specific employee
$employees = $ergani->getWorkforceStatus('123456789');

foreach ($employees as $employee) {
    echo "{$employee->lastName} {$employee->firstName}\n";
    echo "Step: {$employee->step}\n";
    echo "Salary: {$employee->grossSalary}\n";
}
```

---

### getAcceptanceStatus()

Retrieve the acceptance status of essential terms declarations from myErgani. Not cached (dynamic data).

```php
public function getAcceptanceStatus(
    string $tin,
    string $protocol,
    DateTime|string $date
): ?AcceptanceStatusResponse
```

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$tin` | string | Employee tax identification number |
| `$protocol` | string | Declaration protocol number |
| `$date` | DateTime\|string | Declaration submission date (DD/MM/YYYY) |

**Returns:** `AcceptanceStatusResponse|null` - Status response, or null if no matching declaration

**Throws:** `ErganiException`

**Example:**

```php
$ergani = new Ergani($accessToken);

$status = $ergani->getAcceptanceStatus('123456789', '67890', '15/01/2025');

if ($status?->isAccepted()) {
    echo "Terms accepted on {$status->answerDate->format('d/m/Y')}\n";
} elseif ($status?->isAnswerPending()) {
    echo "Awaiting employee response\n";
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

## Work Cards

### workCardSchema()

Get the schema for work card submissions.

```php
public function workCardSchema(): array
```

**Returns:** `array<string, mixed>` - Work card schema definition

**Throws:** `ErganiException`

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
    ->setEmployerTin('123456789')
    ->setBranchCode(0)
    ->addDetails(
        CardDetail::make()
            ->setTin('987654321')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T09:00:00.000+02:00')
    );

$responses = $ergani->sendWorkCards($card);

foreach ($responses as $response) {
    echo $response->protocol;
}
```

---

## Hiring Documents (E3)

### sendHiringNew()

Submit new employee hiring declaration (E3N).

```php
public function sendHiringNew(NewDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

**Example:**

```php
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;

$declaration = NewDeclaration::make()
    ->setTin('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    // ... other fields
    ;

$responses = $ergani->sendHiringNew($declaration);
```

---

### sendHiringModification()

Submit employee transfer declaration (E3M). Used when an employee transfers from another company.

```php
public function sendHiringModification(ModificationDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendHiringDeletion()

Submit employee lending FROM direct employer declaration (E3D). Used when lending an employee to another company.

```php
public function sendHiringDeletion(DeletionDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendHiringWithLending()

Submit employee hiring TO indirect employer declaration (E3PD). Used when hiring an employee from a lending arrangement.

```php
public function sendHiringWithLending(LendingDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

## Termination Documents (E5)

### sendVoluntaryResignation()

Submit voluntary resignation declaration (E5N).

```php
public function sendVoluntaryResignation(VoluntaryResignationDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendResignationNotification()

Submit resignation notification declaration (E5O). Used to notify about possible voluntary resignation when an employee is absent.

```php
public function sendResignationNotification(NotificationDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendResignationAfterNotification()

Submit resignation after notification declaration (E5AO). Used when confirming a resignation that follows a previous E5O notification.

```php
public function sendResignationAfterNotification(ResignationAfterNotificationDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendTerminationByDeath()

Submit termination by death declaration (E5D).

```php
public function sendTerminationByDeath(DeathTerminationDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendVoluntaryExitCompensation()

Submit voluntary exit with compensation declaration (E5E). Used for voluntary separation programs with severance pay.

```php
public function sendVoluntaryExitCompensation(CompensatedExitDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendRetirementVoluntary()

Submit voluntary retirement declaration (E5S).

```php
public function sendRetirementVoluntary(VoluntaryRetirementDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendRetirementMandatory()

Submit mandatory retirement declaration (E5DS). Used when employee retirement is mandatory (15 years service or age limit).

```php
public function sendRetirementMandatory(MandatoryRetirementDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

## Fixed-Term Termination (E7)

### sendFixedTermTermination()

Submit fixed-term contract termination declaration (E7N).

```php
public function sendFixedTermTermination(FixedTermTerminationDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

## Dismissal Documents (E6)

### sendDismissalWithoutNotice()

Submit dismissal without notice declaration (E6NXP). Used for employer-initiated immediate terminations.

```php
public function sendDismissalWithoutNotice(DismissalWithoutNoticeDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendDismissalWithNotice()

Submit dismissal with notice declaration (E6NMP). Used for employer-initiated terminations with advance notice period.

```php
public function sendDismissalWithNotice(DismissalWithNoticeDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendRetirementDismissal()

Submit retirement dismissal declaration (E6SXP). Used when employer terminates contract for employee retirement.

```php
public function sendRetirementDismissal(RetirementDismissalDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendEndOfLoan()

Submit end of employee loan declaration (E6LD). Used when a loaned employee returns to their original employer.

```php
public function sendEndOfLoan(EndOfLoanDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendTrialPeriodTermination()

Submit trial period termination declaration (E6LT). Used when employment automatically terminates at end of trial period.

```php
public function sendTrialPeriodTermination(TrialPeriodTerminationDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendTransfer()

Submit employee transfer declaration (E6M). Used when an employee is transferred to another company.

```php
public function sendTransfer(TransferDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

## Work Time Documents

### sendDailyWorkTime()

Submit daily work time organization declaration.

```php
public function sendDailyWorkTime(WorkTime|array $workTime): array
```

**Returns:** `array<int, WorkTimeResponse>`

---

### sendWeeklyWorkTime()

Submit weekly work time organization declaration.

```php
public function sendWeeklyWorkTime(WorkTime|array $workTime): array
```

**Returns:** `array<int, WorkTimeResponse>`

---

### sendDailyWorkTimeDrivers()

Submit daily work time organization for drivers declaration.

```php
public function sendDailyWorkTimeDrivers(WorkTime|array $workTime): array
```

**Returns:** `array<int, WorkTimeResponse>`

---

### sendDailyWorkTimeRetrospective()

Submit daily work time organization retrospective declaration.

```php
public function sendDailyWorkTimeRetrospective(WorkTime|array $workTime): array
```

**Returns:** `array<int, WorkTimeResponse>`

---

### sendWorkTimeLeave()

Submit work time leave declaration.

```php
public function sendWorkTimeLeave(WorkTime|array $workTime): array
```

**Returns:** `array<int, WorkTimeResponse>`

---

### sendWorkTimeLeaveCorrection()

Submit work time leave correction declaration.

```php
public function sendWorkTimeLeaveCorrection(WorkTime|array $workTime): array
```

**Returns:** `array<int, WorkTimeResponse>`

---

## Overtime Documents

### sendOvertime()

Submit overtime declaration.

```php
public function sendOvertime(Overtime|array $overtime): array
```

**Returns:** `array<int, OvertimeResponse>`

---

### sendOvertimeDrivers()

Submit overtime declaration for drivers.

```php
public function sendOvertimeDrivers(Overtime|array $overtime): array
```

**Returns:** `array<int, OvertimeResponse>`

---

### sendOvertimeRetrospective()

Submit overtime declaration retrospective.

```php
public function sendOvertimeRetrospective(Overtime|array $overtime): array
```

**Returns:** `array<int, OvertimeResponse>`

---

## Employment Modification Documents (MA)

### sendEmploymentModification()

Submit employment modification declaration (WebMA). Reports modifications to employment terms for regular employees.

```php
public function sendEmploymentModification(ModificationDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendBorrowedEmploymentModification()

Submit borrowed employment modification declaration (WebMAD). Reports modifications to employment terms for loaned/borrowed employees.

```php
public function sendBorrowedEmploymentModification(BorrowedModificationDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

## Sixth Day Documents

### sendSixthDayDeclaration()

Submit sixth day / extra shift declaration (SixthDay).

```php
public function sendSixthDayDeclaration(SixthDayDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

## Pre-Announcement Documents

### sendPreAnnouncementExemption()

Submit pre-announcement exemption declaration (ExProan).

```php
public function sendPreAnnouncementExemption(ExemptionDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

## Construction Documents (E12)

### sendConstructionWork()

Submit construction work personnel declaration (E12).

```php
public function sendConstructionWork(ConstructionWork|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

### sendConstructionWorkCensus()

Submit construction work census declaration (E12Apogr).

```php
public function sendConstructionWorkCensus(ConstructionCensus|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

## Internship Documents (E3.5)

### sendInternshipDeclaration()

Submit internship declaration (E3.5 / action code 57).

```php
public function sendInternshipDeclaration(InternshipDeclaration|array $declarations): array
```

**Returns:** `array<int, SubmissionResponse>`

---

## Document Management

### cancelDocument()

Cancel a previously submitted document.

Currently supported document types:
- Work Time Organization - Leave (WTOLeave)
- Work Time Organization - Leave Correction (WTOLeaveC)

```php
public function cancelDocument(
    string $documentType,
    string $protocol,
    DateTime|int|string $submissionDate
): bool
```

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$documentType` | string | The document type code (e.g., 'WTOLeave') |
| `$protocol` | string | Protocol number from submission response |
| `$submissionDate` | DateTime\|int\|string | Submission date (DateTime, Ymd integer, or Ymd string) |

**Returns:** `bool` - True if cancellation was successful

**Example:**

```php
$ergani->cancelDocument('WTOLeave', 'WTO12345', 20250115);
// or
$ergani->cancelDocument('WTOLeave', 'WTO12345', new DateTime('2025-01-15'));
```

---

### getSubmissions()

Retrieve all available submissions/document types.

```php
public function getSubmissions(): array
```

**Returns:** `array<string, mixed>` - List of available document types

---

### getSchema()

Get schema for a specific document type.

```php
public function getSchema(string $documentClass): array
```

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$documentClass` | class-string | Fully qualified class name of the document |

**Returns:** `array<string, mixed>` - Schema definition

**Example:**

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;

$schema = $ergani->getSchema(HiringNew::class);
```

---

### getDocumentPdf()

Retrieve PDF of a submitted document.

```php
public function getDocumentPdf(
    string $documentClass,
    string $protocol,
    DateTime|int|string $submittedDate
): string
```

**Parameters:**

| Parameter | Type | Description |
|-----------|------|-------------|
| `$documentClass` | class-string | Fully qualified class name of the document |
| `$protocol` | string | Protocol number from submission response |
| `$submittedDate` | DateTime\|int\|string | Submission date (DateTime, Ymd integer, or Ymd string) |

**Returns:** `string` - Base64-encoded PDF content

**Example:**

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;

$pdfBase64 = $ergani->getDocumentPdf(
    HiringNew::class,
    $response->protocol,
    $response->submissionDate
);

// Decode and save
file_put_contents('submission.pdf', base64_decode($pdfBase64));
```

---

## Caching

The `Ergani` facade supports opt-in PSR-16 caching for `getEmployerInfo()`, `getBranches()`, and `getParameters()`. Pass any `Psr\SimpleCache\CacheInterface` implementation to the constructor.

### Setup

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Cache\InMemoryCache;
use OxygenSuite\OxygenErgani\Cache\FileCache;

// In-memory cache (single request)
$ergani = new Ergani($accessToken, cache: new InMemoryCache());

// File-based cache
$ergani = new Ergani($accessToken, cache: new FileCache());

// Prefix is auto-derived from TokenManager credentials
// Or set a custom prefix:
$ergani = new Ergani(
    $accessToken,
    cache: new FileCache(['cache_dir' => '/var/app/cache']),
    cachePrefix: 'my-custom-prefix',
);

// Laravel integration
$ergani = new Ergani($accessToken, cache: Cache::store('file'));
```

### Bundled Cache Implementations

| Class | Description |
|-------|-------------|
| `FileCache` | File-based cache with TTL, configurable directory |
| `InMemoryCache` | Array-based cache, lives for single request |
| `NullCache` | No-op, explicitly disables caching |

### Default TTLs

| Method | TTL |
|--------|-----|
| `getEmployerInfo()` | 30 days |
| `getBranches()` | 30 days |
| `getParameters()` | 30 days |

### clearCache()

Clear all cached data for the current credentials (current prefix).

```php
public function clearCache(): bool
```

### flushCache()

Flush the entire cache store (all prefixes, all credentials). Use this to clean up remnants when credentials change.

This is a **static method** - no Ergani instance or credentials required.

```php
public static function flushCache(CacheInterface $cache): bool
```

**Example:**

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Cache\FileCache;

$cache = new FileCache();
Ergani::flushCache($cache);
```

### clearEmployerCache()

Clear cached employer info.

```php
public function clearEmployerCache(): bool
```

### clearBranchCache()

Clear cached branch data.

```php
public function clearBranchCache(): bool
```

### clearParameterCache()

Clear cached parameters. Optionally specify a parameter type.

```php
public function clearParameterCache(?string $parameter = null): bool
```

**Example:**

```php
// Clear specific parameter type
$ergani->clearParameterCache(ParameterLookup::WORK_TIME_TYPE);

// Clear all cached parameters
$ergani->clearParameterCache();

// Clear everything
$ergani->clearCache();
```

---

## Complete Example

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;

try {
    // Authenticate
    $ergani = new Ergani(null, Environment::PRODUCTION);
    $token = $ergani->authenticate('username', 'password');

    // Create new instance with token
    $ergani = new Ergani($token->accessToken, Environment::PRODUCTION);

    // Get employer info
    $employer = $ergani->getEmployerInfo();
    echo "Employer: {$employer->name}\n";

    // Get parameter values
    $specialties = $ergani->getParameters(ParameterLookup::SPECIALTY);

    // Submit hiring declaration
    $declaration = NewDeclaration::factory()->make([
        'f_afm_ergodoti' => $employer->tin,
    ]);

    $responses = $ergani->sendHiringNew($declaration);

    foreach ($responses as $response) {
        echo "Submitted: {$response->protocol}\n";

        // Retrieve PDF
        $pdf = $ergani->getDocumentPdf(
            \OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew::class,
            $response->protocol,
            $response->submissionDate
        );
        file_put_contents("hiring_{$response->protocol}.pdf", base64_decode($pdf));
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
$responses = $ergani->sendHiringNew($declarations);
```

---

## Method Summary

| Category | Method | Form Code | Description |
|----------|--------|-----------|-------------|
| **Services** | `getServices()` | - | List available services |
| | `getEmployerInfo()` | EX_BASE_01 | Employer details |
| | `getBranches()` | EX_BASE_02 | Branch list |
| | `getMonthlyStatus()` | EX_BASE_04 | Monthly employee status |
| | `getWorkforceStatus()` | EX_BASE_05 | Current workforce status |
| | `getAcceptanceStatus()` | EX_BASE_06 | Essential terms acceptance status |
| | `getParameters()` | EX_BASE_03 | System parameters |
| **Work Cards** | `sendWorkCards()` | WRKCardSE | Check-in/check-out |
| **Hiring** | `sendHiringNew()` | E3N | New employee |
| | `sendHiringModification()` | E3M | Transfer from company |
| | `sendHiringDeletion()` | E3D | Loan to company |
| | `sendHiringWithLending()` | E3PD | Hire from loan |
| **Termination** | `sendVoluntaryResignation()` | E5N | Voluntary resignation |
| | `sendResignationNotification()` | E5O | Resignation notification |
| | `sendResignationAfterNotification()` | E5AO | Resignation after E5O |
| | `sendTerminationByDeath()` | E5D | Death termination |
| | `sendVoluntaryExitCompensation()` | E5E | Voluntary exit with pay |
| | `sendRetirementVoluntary()` | E5S | Voluntary retirement |
| | `sendRetirementMandatory()` | E5DS | Mandatory retirement |
| **Fixed-Term** | `sendFixedTermTermination()` | E7N | Fixed-term termination |
| **Dismissal** | `sendDismissalWithoutNotice()` | E6NXP | Immediate dismissal |
| | `sendDismissalWithNotice()` | E6NMP | Dismissal with notice |
| | `sendRetirementDismissal()` | E6SXP | Retirement dismissal |
| | `sendEndOfLoan()` | E6LD | End of loan |
| | `sendTrialPeriodTermination()` | E6LT | Trial period end |
| | `sendTransfer()` | E6M | Transfer to company |
| **Work Time** | `sendDailyWorkTime()` | WTODaily | Daily schedule |
| | `sendWeeklyWorkTime()` | WTOWeek | Weekly schedule |
| | `sendDailyWorkTimeDrivers()` | WTODailyD | Daily (drivers) |
| | `sendDailyWorkTimeRetrospective()` | WTODailyA | Daily retrospective |
| | `sendWorkTimeLeave()` | WTOLeave | Leave |
| | `sendWorkTimeLeaveCorrection()` | WTOLeaveC | Leave correction |
| **Overtime** | `sendOvertime()` | WTOOv | Overtime |
| | `sendOvertimeDrivers()` | WTOOvD | Overtime (drivers) |
| | `sendOvertimeRetrospective()` | WTOOvA | Overtime retrospective |
| **Modification** | `sendEmploymentModification()` | WebMA | Employment changes |
| | `sendBorrowedEmploymentModification()` | WebMAD | Borrowed employee changes |
| **Sixth Day** | `sendSixthDayDeclaration()` | SixthDay | Extra shift / 6th day |
| **Pre-Announce** | `sendPreAnnouncementExemption()` | ExProan | Pre-announcement exemption |
| **Construction** | `sendConstructionWork()` | E12 | Construction personnel |
| | `sendConstructionWorkCensus()` | E12Apogr | Construction census |
| **Internship** | `sendInternshipDeclaration()` | 57 | Internship (E3.5) |
| **Management** | `cancelDocument()` | - | Cancel submission |
| | `getSubmissions()` | - | List document types |
| | `getSchema()` | - | Get document schema |
| | `getDocumentPdf()` | - | Retrieve submitted PDF |

---

## See Also

- [Configuration](/guide/configuration) - Environment and token setup
- [Token Management](/guide/token-management) - Automatic token handling
- [Work Cards](/guide/work-cards) - Work card submissions
- [Services & Queries](/guide/services) - Service classes
- [Hiring](/guide/hiring/) - E3 hiring declarations
- [Termination](/guide/termination/) - E5 termination declarations
- [Dismissal](/guide/dismissal/) - E6 dismissal declarations
- [Construction](/guide/construction) - E12 construction declarations
- [Sixth Day](/guide/sixth-day) - Extra shift declarations
- [Pre-Announcement](/guide/pre-announcement) - Pre-announcement exemptions
- [Internship](/guide/internship) - E3.5 internship declarations
