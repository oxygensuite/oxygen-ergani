# Services & Queries

The ERGANI API provides several query services for retrieving employer information, branch details, employee status, and system parameters. These services use the `ExecuteService` endpoint.

## Available Services

| Service           | Class              | Code         | Description                                                |
|-------------------|--------------------|--------------|------------------------------------------------------------|
| Employer Info     | `EmployerInfo`     | `EX_BASE_01` | Employer details and card sector status                    |
| Branch Info       | `BranchInfo`       | `EX_BASE_02` | All employer branches with addresses                       |
| Parameter Lookup  | `ParameterLookup`  | `EX_BASE_03` | System parameter values (nationalities, specialties, etc.) |
| Monthly Status    | `MonthlyStatus`    | `EX_BASE_04` | Employee status for a specific month                       |
| Workforce Status  | `WorkforceStatus`  | `EX_BASE_05` | Current workforce status with employment details           |
| Acceptance Status | `AcceptanceStatus` | `EX_BASE_06` | Essential terms acceptance status from myErgani            |

---

## Employer Info (EX_BASE_01)

Retrieves basic information about the authenticated employer.

### Usage

```php
use OxygenSuite\OxygenErgani\Http\Services\EmployerInfo;

$service = new EmployerInfo($accessToken, $environment);
$employer = $service->handle();

echo $employer->id;            // Employer ID
echo $employer->tin;           // Tax ID (AFM)
echo $employer->name;          // Legal name
echo $employer->ame;           // AME number
echo $employer->isInCardSector; // true if work cards required
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);
$employer = $ergani->getEmployerInfo();
```

### Response: `EmployerResponse`

| Property         | Type         | Description                        |
|------------------|--------------|------------------------------------|
| `id`             | string\|null | Employer ID in ERGANI              |
| `tin`            | string\|null | Tax identification number (AFM)    |
| `name`           | string\|null | Legal name (Επωνυμία)              |
| `ame`            | string\|null | AME registration number            |
| `isInCardSector` | bool\|null   | Whether employer is in card sector |

::: info Card Sector
If `isInCardSector` is `true`, the employer must submit work cards (check-in/check-out) for employees.
:::

---

## Branch Info (EX_BASE_02)

Retrieves all registered branches for the employer.

### Usage

```php
use OxygenSuite\OxygenErgani\Http\Services\BranchInfo;

$service = new BranchInfo($accessToken, $environment);
$branches = $service->handle();

// Iterate all branches
foreach ($branches as $aa => $branch) {
    echo "Branch {$aa}: {$branch->address}";
}

// Find branch by sequence number
$headquarters = $branches->find('0');  // or $branches['0']

// Search by address
$athensOffices = $branches->search('ΑΘΗΝΑ');

// For HTML dropdowns
$dropdown = $branches->toDropdown();  // ['0' => 'Λ. Αλεξάνδρας 1', ...]

// Check if branch exists
if ($branches->has('1')) {
    // Branch 1 exists
}

// Get first/last
$first = $branches->first();
$last = $branches->last();
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);
$branches = $ergani->getBranches();
```

### Response: `BranchCollection` of `BranchResponse`

| Property  | Type         | Description                     |
|-----------|--------------|---------------------------------|
| `aa`      | string\|null | Branch sequence number (0 = HQ) |
| `address` | string\|null | Branch address                  |

### Collection Methods

| Method           | Return Type          | Description                          |
|------------------|----------------------|--------------------------------------|
| `find($aa)`      | BranchResponse\|null | Find by sequence number              |
| `has($aa)`       | bool                 | Check if branch exists               |
| `search($query)` | BranchCollection     | Search by address (case-insensitive) |
| `toDropdown()`   | array                | Get [aa => address] for dropdowns    |
| `first()`        | BranchResponse\|null | Get first branch                     |
| `last()`         | BranchResponse\|null | Get last branch                      |
| `count()`        | int                  | Total branch count                   |

---

## Parameter Lookup (EX_BASE_03)

Retrieves system parameter values used in form fields. Essential for populating dropdowns with valid codes.

### Usage

```php
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;

$service = new ParameterLookup($accessToken, $environment);

// Get work time types
$workTimeTypes = $service->handle(ParameterLookup::WORK_TIME_TYPE);

// Find specific code
$workType = $workTimeTypes->find('ΕΡΓ');  // or $workTimeTypes['ΕΡΓ']
echo $workType->description;  // "ΕΡΓΑΣΙΑ"

// Search by description
$results = $workTimeTypes->search('ΕΡΓΑΣΙΑ');

// Check if code exists
if ($workTimeTypes->has('ΕΡΓ')) {
    // Code exists
}

// For HTML dropdowns
$dropdown = $workTimeTypes->toDropdown();  // ['ΕΡΓ' => 'ΕΡΓΑΣΙΑ', ...]

// Get all codes
$codes = $workTimeTypes->codes();  // ['ΕΡΓ', 'ΥΠΕ', ...]

// Filter with callback
$filtered = $workTimeTypes->filter(
    fn($param) => $param->extra === 'ΕΡΓΑΣΙΑ-ΕΡΓΑΣΙΑ'
);
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;

$ergani = new Ergani($accessToken);
$params = $ergani->getParameters(ParameterLookup::WORK_TIME_TYPE);
```

### Available Parameter Types

| Constant                    | Parameter                    | Description                    |
|-----------------------------|------------------------------|--------------------------------|
| `SEPE`                      | Sepe                         | Labor Inspection Service codes |
| `OAED`                      | Oaed                         | DYPA/OAED service codes        |
| `STAKOD`                    | Stakod                       | Activity codes (KAD)           |
| `KALLIKRATIS_COMMUNITY`     | KallikratisKoinothta         | Community codes                |
| `KALLIKRATIS_MUNICIPALITY`  | KallikratisDhmos             | Municipality codes             |
| `KALLIKRATIS_REGIONAL_UNIT` | KallikratisPerifereiaEnothta | Regional unit codes            |
| `KALLIKRATIS_REGION`        | KallikratisPerifereia        | Region codes                   |
| `NATIONALITY`               | Nationality                  | Nationality codes              |
| `ID_TYPE`                   | TyposTaytotitas              | ID document types              |
| `RESIDENCE_PERMIT`          | ResidencePermit              | Residence permit types         |
| `DOY`                       | Doy                          | Tax office codes               |
| `EDUCATION_LEVEL`           | EpipedoMorfosis              | Education level codes          |
| `SUBJECT_AREA`              | SubjectArea                  | Education subject areas        |
| `SUBJECT_GROUP`             | SubjectGroup                 | Education subject groups       |
| `EDUCATION_AGENCY`          | EducationAgency              | Education agency codes         |
| `LANGUAGE`                  | Language                     | Language codes                 |
| `SPECIALTY`                 | Step92                       | Employee specialty codes       |
| `OAED_PROGRAM`              | ProgramaOaed                 | DYPA program codes             |
| `TRAFFIC_SPECIALTIES`       | TraficEmploymentSpecialties  | Traffic employment specialties |
| `OVERTIME_REASON`           | OvertimeAitiologia           | Overtime reason codes          |
| `TERMINATION_REASON`        | LogosApolyshs                | Termination reason codes       |
| `BANK`                      | Bank                         | Bank codes                     |
| `RAPID_EXCEPTION_REASON`    | RapidExceptionReason         | Rapid card exception reasons   |
| `SINGLE_PARENT_CASE`        | OneParentCase                | Single parent case codes       |
| `WORK_CARD_DELAY_REASON`    | WorkCardDelayReason          | Work card delay reasons        |
| `WORK_TIME_TYPE`            | WorkTimeType                 | Work time type codes           |
| `SIXTH_DAY_KAD`             | SixthDayKAD                  | Sixth day work activity codes  |
| `CHANGE_TYPE`               | TypeMetabolon                | Employment change type codes   |
| `PRIMARY_INSURANCE`         | ForeisKyriasAsfalisis        | Primary insurance codes        |
| `SUPPLEMENTARY_INSURANCE`   | ForeisEpikourikisAsfalisis   | Supplementary insurance codes  |

### Response: `ParameterCollection` of `ParameterResponse`

| Property | Type | Description |
|----------|------|-------------|
| `code` | string\|null | Parameter code |
| `description` | string\|null | Human-readable description |
| `extra` | string\|null | Additional information |

### Collection Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `find($code)` | ParameterResponse\|null | Find by code |
| `has($code)` | bool | Check if code exists |
| `search($query)` | ParameterCollection | Search descriptions (case-insensitive) |
| `toDropdown()` | array | Get [code => description] for dropdowns |
| `codes()` | array | Get all codes |
| `filter($callback)` | ParameterCollection | Filter with callback |
| `first()` | ParameterResponse\|null | Get first parameter |
| `last()` | ParameterResponse\|null | Get last parameter |
| `count()` | int | Total parameter count |

### Examples

#### Populate Nationality Dropdown

```php
$nationalities = $service->handle(ParameterLookup::NATIONALITY);

// For HTML select
echo '<select name="nationality">';
foreach ($nationalities->toDropdown() as $code => $description) {
    echo "<option value=\"{$code}\">{$description}</option>";
}
echo '</select>';
```

#### Find Specialty Code

```php
$specialties = $service->handle(ParameterLookup::SPECIALTY);

// Search for programmers
$programmers = $specialties->search('ΠΡΟΓΡΑΜΜΑΤΙΣΤ');

foreach ($programmers as $specialty) {
    echo "{$specialty->code}: {$specialty->description}\n";
}
```

#### Get Tax Office for AFM

```php
$taxOffices = $service->handle(ParameterLookup::DOY);
$office = $taxOffices->find('1234');

if ($office) {
    echo "Tax Office: {$office->description}";
}
```

---

## Monthly Status (EX_BASE_04)

Retrieves comprehensive monthly employment status for all employees, including work days, leave balances, overtime, and insurance data.

### Usage

```php
use OxygenSuite\OxygenErgani\Http\Services\MonthlyStatus;

$service = new MonthlyStatus($accessToken, $environment);
$employees = $service->handle(2025, 1);  // January 2025

foreach ($employees as $employee) {
    echo "{$employee->lastName} {$employee->firstName}\n";
    echo "AFM: {$employee->afm}, AMKA: {$employee->amka}\n";
    echo "Specialty: {$employee->specialty}\n";
    echo "Salary: {$employee->salary}\n";
    echo "Work days: {$employee->workDays}, Remote: {$employee->remoteWorkDays}\n";
    echo "Annual leave taken: {$employee->annualLeaveDays} days\n";
    echo "---\n";
}
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);
$employees = $ergani->getMonthlyStatus(2025, 1);  // January 2025
```

### Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `$year` | int | Report year (e.g., 2025) |
| `$month` | int | Report month (1-12) |

### Response: Array of `EmployeeStatusResponse`

The response includes comprehensive employee data organized into categories:

#### Identification

| Property | Type | Description |
|----------|------|-------------|
| `employerId` | string\|null | Employer ID |
| `branchAa` | string\|null | Branch sequence number |
| `year` | int\|null | Report year |
| `month` | int\|null | Report month |
| `employeeType` | string\|null | Employee type (e.g., "Εξαρτημένη") |
| `afm` | string\|null | Tax ID |
| `amka` | string\|null | Social security number |
| `ama` | string\|null | IKA insurance number |
| `lastName` | string\|null | Last name |
| `firstName` | string\|null | First name |
| `fatherName` | string\|null | Father's name |
| `motherName` | string\|null | Mother's name |
| `birthDate` | DateTimeInterface\|null | Birth date |
| `sex` | string\|null | Sex |
| `nationality` | string\|null | Nationality code with description |
| `maritalStatus` | string\|null | Marital status |
| `childrenCount` | int\|null | Number of children |
| `educationLevel` | string\|null | Education level code with description |

#### Employment Details

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

#### Work Days

| Property | Type | Description |
|----------|------|-------------|
| `workDays` | int\|null | Days worked on-site |
| `remoteWorkDays` | int\|null | Days worked remotely |
| `restDays` | int\|null | Rest/repo days |
| `nonWorkingDays` | int\|null | Non-working days |

#### Leave Days

| Property | Type | Description |
|----------|------|-------------|
| `annualLeaveDays` | int\|null | Annual leave days |
| `bloodDonationLeaveDays` | int\|null | Blood donation leave |
| `examLeaveDays` | int\|null | Exam leave |
| `unpaidLeaveDays` | int\|null | Unpaid leave |
| `maternityLeaveDays` | int\|null | Maternity leave |
| `maternityProtectionDays` | int\|null | Maternity protection days |
| `paternityLeaveDays` | int\|null | Paternity leave |
| `childCareLeaveDays` | int\|null | Child care leave |
| `parentalLeaveDays` | int\|null | Parental leave |
| `sicknessDays` | int\|null | Sick days |
| `otherLeaveDays` | int\|null | Other leave types |

See [EmployeeStatusResponse](/api/responses#employeestatusresponse) for the complete list of 30+ leave type fields.

#### Overtime & Work Card

| Property | Type | Description |
|----------|------|-------------|
| `overtimeMinutes` | int\|null | Total overtime minutes |
| `overtimeDays` | int\|null | Days with overtime |
| `workCardDays` | int\|null | Days with work card entries |
| `sundayHolidayDays` | int\|null | Sunday/holiday work days |
| `sundayHolidayCardDays` | int\|null | Sunday/holiday card days |

#### Insurance Totals

| Property | Type | Description |
|----------|------|-------------|
| `totalInsuredLeaveDays` | int\|null | Total insured leave days |
| `totalInsuredSicknessDays` | int\|null | Total insured sickness days |

### Example: Generate Monthly Report

```php
$service = new MonthlyStatus($accessToken, $environment);

// Get current month's employees
$now = new DateTime();
$employees = $service->handle(
    (int) $now->format('Y'),
    (int) $now->format('n')
);

// Calculate totals
$totalWorkDays = array_sum(array_map(fn($e) => $e->workDays ?? 0, $employees));
$totalRemoteDays = array_sum(array_map(fn($e) => $e->remoteWorkDays ?? 0, $employees));
$totalLeaveDays = array_sum(array_map(fn($e) => $e->annualLeaveDays ?? 0, $employees));

echo "Total employees: " . count($employees) . "\n";
echo "Total work days: {$totalWorkDays}\n";
echo "Total remote days: {$totalRemoteDays}\n";
echo "Total leave days taken: {$totalLeaveDays}\n";
```

### Example: Find Employees on Leave

```php
$employees = $service->handle(2025, 1);

$onLeave = array_filter($employees, fn($e) =>
    ($e->annualLeaveDays ?? 0) > 0 ||
    ($e->sicknessDays ?? 0) > 0 ||
    ($e->maternityLeaveDays ?? 0) > 0
);

foreach ($onLeave as $emp) {
    echo "{$emp->lastName}: ";
    if ($emp->annualLeaveDays > 0) echo "Annual: {$emp->annualLeaveDays}d ";
    if ($emp->sicknessDays > 0) echo "Sick: {$emp->sicknessDays}d ";
    echo "\n";
}
```

---

## Workforce Status (EX_BASE_05)

Retrieves current workforce status for all employees or a specific employee. Returns detailed employment information including identity, contract terms, insurance, and work organization settings.

### Usage

```php
use OxygenSuite\OxygenErgani\Http\Services\WorkforceStatus;

$service = new WorkforceStatus($accessToken, $environment);

// Get all employees
$employees = $service->handle();

// Get specific employee by AFM
$employees = $service->handle('123456789');

foreach ($employees as $employee) {
    echo "{$employee->lastName} {$employee->firstName}\n";
    echo "AFM: {$employee->afm}, AMKA: {$employee->amka}\n";
    echo "Specialty: {$employee->step}\n";
    echo "Salary: {$employee->grossSalary}\n";
    echo "Weekly hours: {$employee->weeklyHours}\n";
    echo "Hired: {$employee->hiringDate->format('d/m/Y')}\n";
}
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);

// All employees
$employees = $ergani->getWorkforceStatus();

// Specific employee
$employees = $ergani->getWorkforceStatus('123456789');
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `$tin` | string\|null | No | Employee tax ID to filter by |

### Response: Array of `WorkforceStatusResponse`

#### Employee Identification

| Property | Type | Description |
|----------|------|-------------|
| `afm` | string\|null | Tax identification number |
| `lastName` | string\|null | Last name |
| `firstName` | string\|null | First name |
| `fatherName` | string\|null | Father's name |
| `motherName` | string\|null | Mother's name |
| `birthDate` | DateTimeInterface\|null | Birth date |
| `sex` | string\|null | Sex with code |
| `nationality` | string\|null | Nationality code with description |
| `maritalStatus` | string\|null | Marital status with code |
| `childrenCount` | int\|null | Number of children |
| `doy` | string\|null | Tax office code with description |
| `amka` | string\|null | Social security number |
| `amIka` | string\|null | IKA insurance number |

#### Identity Document

| Property | Type | Description |
|----------|------|-------------|
| `idType` | string\|null | ID document type |
| `idNumber` | string\|null | ID document number |
| `idIssuingAuthority` | string\|null | Issuing authority |
| `idIssueDate` | DateTimeInterface\|null | Issue date |
| `idExpiryDate` | DateTimeInterface\|null | Expiry date |

#### Employment Details

| Property | Type | Description |
|----------|------|-------------|
| `branchAa` | int\|null | Branch sequence number |
| `effectiveDate` | DateTimeInterface\|null | Effective date of current status |
| `hiringDate` | DateTimeInterface\|null | Hiring date |
| `step` | string\|null | Specialty code with description |
| `characterization` | string\|null | Employee type (Υπάλληλος/Εργάτης) |
| `employmentRelation` | string\|null | Employment relation |
| `employmentStatus` | string\|null | Full/part-time status |
| `weeklyHours` | string\|null | Weekly work hours |
| `grossSalary` | string\|null | Gross salary |
| `hourlyWage` | string\|null | Hourly wage |
| `experienceYears` | int\|null | Years of experience |
| `trialPeriod` | string\|null | Trial period status |
| `educationLevel` | string\|null | Education level |
| `primaryInsurance` | string\|null | Primary insurance provider |
| `supplementaryInsurance` | string\|null | Supplementary insurance provider |

#### Digital Work Time Organization

| Property | Type | Description |
|----------|------|-------------|
| `digitalWorkTimeOrganization` | string\|null | Digital work time organization status |
| `fullEmploymentHours` | string\|null | Full employment hours |
| `breakMinutes` | int\|null | Break duration in minutes |
| `breakWithinSchedule` | string\|null | Whether break is within schedule |
| `workingCard` | string\|null | Work card requirement status |
| `flexibleArrivalMinutes` | int\|null | Flexible arrival minutes |
| `lastModifiedDate` | DateTimeInterface\|null | Last modification date |

---

## Acceptance Status (EX_BASE_06)

Checks the acceptance status of essential terms declarations submitted through myErgani. Use this to verify whether an employee has accepted or rejected the terms of a submitted declaration.

### Usage

```php
use OxygenSuite\OxygenErgani\Http\Services\AcceptanceStatus;

$service = new AcceptanceStatus($accessToken, $environment);

$status = $service->handle(
    afm: '123456789',
    protocol: '67890',
    date: '15/01/2025',
);

if ($status === null) {
    echo "No matching declaration found.\n";
} else {
    echo "Declaration status: {$status->mainStatus}\n";     // 1=Submitted, 2=Revoked
    echo "Answer status: {$status->answerStatus}\n";         // 0=Pending, 1=Submitted
    echo "Acceptance: {$status->answerAccept}\n";            // 0=Rejected, 1=Accepted, 2=Auto-accepted
    echo "Answer protocol: {$status->answerProtocol}\n";
    echo "Answer date: {$status->answerDate?->format('d/m/Y')}\n";
}
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);

// With string date
$status = $ergani->getAcceptanceStatus('123456789', '67890', '15/01/2025');

// With DateTime object
$status = $ergani->getAcceptanceStatus('123456789', '67890', new DateTime('2025-01-15'));
```

### Parameters

| Parameter | Type | Required | Description |
|-----------|------|----------|-------------|
| `$tin` | string | Yes | Employee tax identification number |
| `$protocol` | string | Yes | Declaration protocol number |
| `$date` | DateTime\|string | Yes | Declaration submission date (DD/MM/YYYY) |

### Response: `AcceptanceStatusResponse` or `null`

Returns `null` when no matching declaration is found.

| Property | Type | Description |
|----------|------|-------------|
| `mainStatus` | int\|null | Declaration status: 1=Submitted, 2=Revoked |
| `answerStatus` | int\|null | Answer submission status: 0=Pending, 1=Submitted |
| `answerAccept` | int\|null | Acceptance result: 0=Rejected, 1=Accepted, 2=Auto-accepted, 3=Rejected by deadline |
| `answerProtocol` | string\|null | Acceptance protocol number |
| `answerDate` | DateTimeInterface\|null | Acceptance submission date |

### Helper Methods

| Method | Return Type | Description |
|--------|-------------|-------------|
| `isSubmitted()` | bool | Declaration is submitted (mainStatus = 1) |
| `isRevoked()` | bool | Declaration is revoked (mainStatus = 2) |
| `isAnswerPending()` | bool | Answer is pending (answerStatus = 0) |
| `isAnswerSubmitted()` | bool | Answer has been submitted (answerStatus = 1) |
| `isAccepted()` | bool | Terms were accepted (answerAccept = 1) |
| `isRejected()` | bool | Terms were rejected (answerAccept = 0) |
| `isAutoAccepted()` | bool | Terms were auto-accepted by deadline (answerAccept = 2) |

### Example: Check Pending Acceptances

```php
$ergani = new Ergani($accessToken);

$status = $ergani->getAcceptanceStatus('123456789', '67890', '15/01/2025');

if ($status === null) {
    echo "Declaration not found.\n";
} elseif ($status->isAnswerPending()) {
    echo "Employee has not yet responded.\n";
} elseif ($status->isAccepted()) {
    echo "Terms accepted on {$status->answerDate->format('d/m/Y')}.\n";
} elseif ($status->isAutoAccepted()) {
    echo "Terms auto-accepted (deadline passed).\n";
} elseif ($status->isRejected()) {
    echo "Terms rejected by employee.\n";
}
```

---

## Using Services with Token Manager

When using a token manager, you don't need to pass the access token manually:

```php
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Http\Services\EmployerInfo;
use OxygenSuite\OxygenErgani\Http\Services\BranchInfo;
use OxygenSuite\OxygenErgani\Http\Services\ParameterLookup;

// Set up token manager once
Token::setCurrentTokenManager(
    new FileToken('username', 'password'),
    Environment::PRODUCTION
);

// Services will automatically use the token manager
$employer = (new EmployerInfo())->handle();
$branches = (new BranchInfo())->handle();
$params = (new ParameterLookup())->handle(ParameterLookup::NATIONALITY);
```

---

## Error Handling

All services throw `ErganiException` or its subclasses on failure:

```php
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;
use OxygenSuite\OxygenErgani\Http\Services\EmployerInfo;

try {
    $employer = (new EmployerInfo($accessToken))->handle();
} catch (AuthenticationException $e) {
    // Invalid or expired token
    echo "Authentication failed: " . $e->getMessage();
} catch (ErganiException $e) {
    // Other API errors
    echo "Error: " . $e->getMessage();
}
```

---

## Best Practices

1. **Cache Parameters**: Parameter lookup results don't change frequently. Use the built-in PSR-16 caching on the `Ergani` facade to reduce API calls (see [Ergani Facade - Caching](/api/ergani#caching)).

2. **Validate Codes**: Always validate user-provided codes against parameter lookups before submission.

3. **Use Collections**: Leverage collection methods (`search`, `filter`, `toDropdown`) for efficient data handling.

4. **Check Card Sector**: Use `EmployerInfo` to determine if work card submissions are required.

5. **Monthly Reports**: Use `MonthlyStatus` for reconciliation and employee roster management.

---

## See Also

- [Configuration](/guide/configuration) - Setting up token managers
- [Error Handling](/guide/error-handling) - Exception handling patterns
- [Work Cards](/guide/work-cards) - Submitting work cards
