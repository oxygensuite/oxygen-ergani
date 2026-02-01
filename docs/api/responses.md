# Responses

Response classes wrap API responses and provide typed access to data.

## Base Class

### Response

Abstract base class for all response classes.

```php
namespace OxygenSuite\OxygenErgani\Responses;

abstract class Response
{
    use HasAttributes;

    public function __construct(mixed $attributes = []);
    abstract protected function processData(): void;
}
```

All response classes extend `Response` and implement `processData()` to map API data to properties.

---

## Authentication

### AuthenticationToken

Response from authentication requests.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class AuthenticationToken extends Response
```

**Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `accessToken` | string\|null | Bearer token for API requests |
| `refreshToken` | string\|null | Token for refreshing access |
| `accessTokenExpiresAt` | DateTimeImmutable\|null | Access token expiration time |
| `refreshTokenExpiresAt` | DateTimeImmutable\|null | Refresh token expiration time |

**Example:**

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani();
$token = $ergani->authenticate('username', 'password');

echo $token->accessToken;
echo $token->refreshToken;
echo $token->accessTokenExpiresAt->format('Y-m-d H:i:s');

// Check if token is still valid
if ($token->accessTokenExpiresAt > new DateTimeImmutable()) {
    echo "Token is valid";
}
```

---

## Submission Responses

### SubmissionResponse

Base response for document submissions.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class SubmissionResponse extends Response
```

**Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `id` | string\|null | Unique submission ID |
| `protocol` | string\|null | Protocol number (e.g., 'Ε3Ν123') |
| `submissionDate` | DateTimeInterface\|null | Date and time of submission |

**Example:**

```php
foreach ($response as $result) {
    echo "ID: {$result->id}\n";
    echo "Protocol: {$result->protocol}\n";
    echo "Date: {$result->submissionDate->format('d/m/Y H:i:s')}\n";
}
```

---

### WorkCardResponse

Response for work card submissions. Extends `SubmissionResponse`.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class WorkCardResponse extends SubmissionResponse
```

**Inherits all properties from `SubmissionResponse`.**

**Example:**

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;

$responses = (new WorkCard($token))->handle($cards);

foreach ($responses as $response) {
    echo $response->protocol;  // e.g., 'WKC123456'
}
```

---

### WorkTimeResponse

Response for work time declarations. Extends `SubmissionResponse`.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class WorkTimeResponse extends SubmissionResponse
```

**Inherits all properties from `SubmissionResponse`.**

---

### OvertimeResponse

Response for overtime declarations. Extends `SubmissionResponse`.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class OvertimeResponse extends SubmissionResponse
```

**Inherits all properties from `SubmissionResponse`.**

---

## Service Responses

### EmployerResponse

Response from `EmployerInfo` service.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class EmployerResponse extends Response
```

**Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `id` | string\|null | Employer ID in ERGANI |
| `tin` | string\|null | Tax identification number (AFM) |
| `name` | string\|null | Legal name (Επωνυμία) |
| `ame` | string\|null | AME registration number |
| `isInCardSector` | bool\|null | Whether employer must submit work cards |

**Example:**

```php
use OxygenSuite\OxygenErgani\Http\Services\EmployerInfo;

$employer = (new EmployerInfo($token))->handle();

echo $employer->name;
echo $employer->tin;

if ($employer->isInCardSector) {
    echo "Work cards required";
}
```

---

### BranchResponse

Response item from `BranchInfo` service.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class BranchResponse extends Response
```

**Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `aa` | string\|null | Branch sequence number (0 = HQ) |
| `address` | string\|null | Branch address |

---

### ParameterResponse

Response item from `ParameterLookup` service.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class ParameterResponse extends Response
```

**Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `code` | string\|null | Parameter code |
| `description` | string\|null | Human-readable description |
| `extra` | string\|null | Additional information |

**Example:**

```php
$param = $collection->find('001');
echo $param->code;        // "001"
echo $param->description; // "ΕΛΛΑΔΑ"
echo $param->extra;       // Additional data if available
```

---

### EmployeeStatusResponse

Response item from `MonthlyStatus` service. Contains comprehensive monthly employment data.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class EmployeeStatusResponse extends Response
```

**Identification Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `employerId` | string\|null | Employer ID |
| `branchAa` | string\|null | Branch sequence number |
| `year` | int\|null | Report year |
| `month` | int\|null | Report month |
| `employeeType` | string\|null | Employee type (e.g., "Εξαρτημένη") |
| `afm` | string\|null | Tax ID |
| `amka` | string\|null | Social security number (AMKA) |
| `ama` | string\|null | IKA insurance number |
| `lastName` | string\|null | Last name (Επώνυμο) |
| `firstName` | string\|null | First name (Όνομα) |
| `fatherName` | string\|null | Father's name |
| `motherName` | string\|null | Mother's name |
| `birthDate` | DateTimeInterface\|null | Birth date |
| `sex` | string\|null | Sex |
| `nationality` | string\|null | Nationality code with description |
| `maritalStatus` | string\|null | Marital status |
| `childrenCount` | int\|null | Number of children |
| `educationLevel` | string\|null | Education level |

**Employment Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `characterization` | string\|null | Employee type (Υπάλληλος/Εργάτης) |
| `employmentRelation` | string\|null | Employment relation (Αορίστου/Ορισμένου) |
| `employmentStatus` | string\|null | Full/Part time (Πλήρης/Μερική) |
| `specialty` | string\|null | Specialty code with description |
| `salary` | string\|null | Gross salary (Greek format) |
| `weeklyHours` | string\|null | Weekly work hours |
| `hourlyWage` | string\|null | Hourly wage |
| `program` | string\|null | Employment program |
| `responsible` | string\|null | Responsible person |
| `hiringDate` | DateTimeInterface\|null | Hiring date |

**Work Day Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `workDays` | int\|null | Days worked on-site |
| `remoteWorkDays` | int\|null | Days worked remotely |
| `restDays` | int\|null | Rest/repo days |
| `nonWorkingDays` | int\|null | Non-working days |

**Leave Day Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `annualLeaveDays` | int\|null | Annual leave days |
| `bloodDonationLeaveDays` | int\|null | Blood donation leave |
| `examLeaveDays` | int\|null | Exam leave |
| `unpaidLeaveDays` | int\|null | Unpaid leave |
| `maternityLeaveDays` | int\|null | Maternity leave |
| `maternityProtectionDays` | int\|null | Maternity protection days |
| `paternityLeaveDays` | int\|null | Paternity leave |
| `childCareLeaveDays` | int\|null | Child care leave days |
| `parentalLeaveDays` | int\|null | Parental leave days |
| `caregiverLeaveDays` | int\|null | Caregiver leave |
| `forceMajeureDays` | int\|null | Force majeure days |
| `assistedReproductionLeaveDays` | int\|null | Assisted reproduction leave |
| `prenatalExamLeaveDays` | int\|null | Prenatal exam leave |
| `marriageLeaveDays` | int\|null | Marriage leave |
| `seriousChildIllnessLeaveDays` | int\|null | Serious child illness leave |
| `childHospitalizationLeaveDays` | int\|null | Child hospitalization leave |
| `singleParentLeaveDays` | int\|null | Single parent leave |
| `schoolPerformanceLeaveDays` | int\|null | School performance leave |
| `childIllnessLeaveDays` | int\|null | Child illness leave |
| `violenceAbsenceDays` | int\|null | Violence/harassment absence |
| `sicknessDays` | int\|null | Sick days |
| `disabilityLeaveDays` | int\|null | Disability leave |
| `bereavementLeaveDays` | int\|null | Bereavement leave |
| `minorStudentLeaveDays` | int\|null | Minor student leave |
| `bloodTransfusionDays` | int\|null | Blood transfusion/dialysis days |
| `kanepGseeLeaveDays` | int\|null | KANEP-GSEE training leave |
| `aidsLeaveDays` | int\|null | AIDS-related leave |
| `flexibleWorkDays` | int\|null | Flexible work arrangement days |
| `otherLeaveDays` | int\|null | Other leave types |

**Minute-Based Leave Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `childCareMinutes` | int\|null | Child care leave (minutes) |
| `parentalLeaveMinutes` | int\|null | Parental leave (minutes) |
| `forceMajeureMinutes` | int\|null | Force majeure (minutes) |
| `flexibleWorkMinutes` | int\|null | Flexible work (minutes) |
| `prenatalExamMinutes` | int\|null | Prenatal exam (minutes) |
| `schoolPerformanceMinutes` | int\|null | School performance (minutes) |
| `otherLeaveMinutes` | int\|null | Other leave (minutes) |
| `totalMinutes` | int\|null | Total leave minutes |

**Overtime & Work Card Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `overtimeMinutes` | int\|null | Total overtime minutes |
| `overtimeDays` | int\|null | Days with overtime |
| `workCardDays` | int\|null | Days with work card entries |
| `sundayHolidayDays` | int\|null | Sunday/holiday work days |
| `sundayHolidayCardDays` | int\|null | Sunday/holiday card days |

**Insurance Properties:**

| Property | Type | Description |
|----------|------|-------------|
| `totalInsuredLeaveDays` | int\|null | Total insured leave days |
| `totalInsuredSicknessDays` | int\|null | Total insured sickness days |

**Example:**

```php
use OxygenSuite\OxygenErgani\Http\Services\MonthlyStatus;

$employees = (new MonthlyStatus($token))->handle(2025, 1);

foreach ($employees as $emp) {
    echo "{$emp->lastName} {$emp->firstName}\n";
    echo "AFM: {$emp->afm}, AMKA: {$emp->amka}\n";
    echo "Hired: {$emp->hiringDate->format('d/m/Y')}\n";
    echo "Work days: {$emp->workDays}, Remote: {$emp->remoteWorkDays}\n";
    echo "Salary: {$emp->salary}, Weekly hours: {$emp->weeklyHours}\n";
}
```

---

## Collections

### Collection

Abstract base class for typed collections.

```php
namespace OxygenSuite\OxygenErgani\Responses;

abstract class Collection implements ArrayAccess, Countable, IteratorAggregate
```

**Methods:**

| Method | Return Type | Description |
|--------|-------------|-------------|
| `find($key)` | object\|null | Find item by key |
| `has($key)` | bool | Check if key exists |
| `filter(callable $callback)` | static | Filter items with callback |
| `keys()` | array | Get all keys |
| `values()` | array | Get all values |
| `count()` | int | Get item count |
| `first()` | object\|null | Get first item |
| `last()` | object\|null | Get last item |
| `isEmpty()` | bool | Check if empty |

---

### BranchCollection

Collection of `BranchResponse` objects.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class BranchCollection extends Collection
```

**Additional Methods:**

| Method | Return Type | Description |
|--------|-------------|-------------|
| `search(string $query)` | BranchCollection | Search by address (case-insensitive) |
| `toDropdown()` | array | Get [aa => address] for dropdowns |

**Example:**

```php
use OxygenSuite\OxygenErgani\Http\Services\BranchInfo;

$branches = (new BranchInfo($token))->handle();

// Find HQ
$hq = $branches->find('0');

// Search by address
$athensOffices = $branches->search('ΑΘΗΝΑ');

// For HTML select
$options = $branches->toDropdown();

// Iterate
foreach ($branches as $aa => $branch) {
    echo "Branch {$aa}: {$branch->address}\n";
}
```

---

### ParameterCollection

Collection of `ParameterResponse` objects.

```php
namespace OxygenSuite\OxygenErgani\Responses;

class ParameterCollection extends Collection
```

**Additional Methods:**

| Method | Return Type | Description |
|--------|-------------|-------------|
| `search(string $query)` | ParameterCollection | Search by description (case-insensitive) |
| `toDropdown()` | array | Get [code => description] for dropdowns |
| `codes()` | array | Get all codes |

**Example:**

```php
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;

$nationalities = (new ParameterLookup($token))
    ->handle(ParameterLookup::NATIONALITY);

// O(1) lookup
$greek = $nationalities->find('001');
// or
$greek = $nationalities['001'];

// Check existence
if ($nationalities->has('001')) {
    echo "Found Greek nationality";
}

// Search descriptions
$results = $nationalities->search('ΕΛΛΑ');

// Filter with callback
$euCountries = $nationalities->filter(
    fn($p) => in_array($p->code, ['001', '002', '003'])
);

// For dropdown
$dropdown = $nationalities->toDropdown();
// ['001' => 'ΕΛΛΑΔΑ', '002' => 'ΑΛΒΑΝΙΑ', ...]

// Get all codes
$codes = $nationalities->codes();
```

---

## Using Array Access

Collections support array-style access:

```php
// Access by key
$branch = $branches['0'];
$nationality = $nationalities['001'];

// Check existence
if (isset($branches['1'])) {
    // Branch 1 exists
}

// Count
echo count($branches);

// Iteration
foreach ($branches as $key => $branch) {
    echo "{$key}: {$branch->address}\n";
}
```

---

## See Also

- [Services & Queries](/guide/services) - Service usage guide
- [Ergani Facade](/api/ergani) - Facade methods
- [Error Handling](/guide/error-handling) - Exception handling
