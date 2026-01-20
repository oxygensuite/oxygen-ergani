# Models

Models are data classes that represent ERGANI form data. They use a fluent interface for building and support automatic type conversion.

## Base Concepts

### HasAttributes Trait

All models use the `HasAttributes` trait providing:

- Fluent setters that return `$this`
- Automatic type casting via `$casts` property
- Ordered array output via `$expectedOrder` property
- Greek float formatting for decimal values

### DateTime Support

All date setter methods accept both `DateTime` objects and strings:

```php
use DateTime;

// Both are equivalent:
$declaration->setBirthDate('15/01/1990');
$declaration->setBirthDate(new DateTime('1990-01-15'));

// Works with DateTimeImmutable too:
$declaration->setHiringDate(new DateTimeImmutable('2025-01-20'));
```

When a `DateTime` is passed, it's automatically formatted to the expected format (usually `DD/MM/YYYY`).

### Factory Pattern

Models support a factory pattern for testing:

```php
$declaration = NewDeclaration::factory()->make();

// With overrides
$declaration = NewDeclaration::factory()->make([
    'f_afm' => '123456789',
]);

// Multiple instances
$declarations = NewDeclaration::factory(3)->make();
```

---

## Work Card Models

### Card

Main work card container.

```php
namespace OxygenSuite\OxygenErgani\Models\WorkCard;

class Card
```

**Key Methods:**

| Method | Type | Description |
|--------|------|-------------|
| `setEmployerAfm($afm)` | string | Employer's tax ID |
| `setBranchCode($code)` | int | Branch sequence number |
| `setDate($date)` | string | Card date (DD/MM/YYYY) |
| `setAfm($afm)` | string | Employee's tax ID |
| `setLastName($name)` | string | Employee's last name |
| `setFirstName($name)` | string | Employee's first name |
| `addCardDetail($detail)` | CardDetail | Add check-in/check-out |

**Example:**

```php
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

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
    )
    ->addCardDetail(
        CardDetail::make()
            ->setType(CardDetailType::CHECK_OUT)
            ->setTime('17:00')
    );
```

### CardDetail

Individual check-in or check-out entry.

```php
namespace OxygenSuite\OxygenErgani\Models\WorkCard;

class CardDetail
```

**Key Methods:**

| Method | Type | Description |
|--------|------|-------------|
| `setType($type)` | CardDetailType | Check-in (0) or check-out (1) |
| `setTime($time)` | string | Time in HH:MM format |
| `setDelayReason($reason)` | string | Delay reason code (optional) |

---

## Work Time Models

### WorkTime

Work time declaration container.

```php
namespace OxygenSuite\OxygenErgani\Models\WorkTime;

class WorkTime
```

**Key Methods:**

| Method | Type | Description |
|--------|------|-------------|
| `setEmployerAfm($afm)` | string | Employer's tax ID |
| `setBranchCode($code)` | int | Branch sequence number |
| `setComments($comments)` | string | Declaration comments |
| `addEmployee($employee)` | WorkTimeEmployee | Add employee |

### WorkTimeEmployee

Employee entry in work time declaration.

```php
namespace OxygenSuite\OxygenErgani\Models\WorkTime;

class WorkTimeEmployee
```

**Key Methods:**

| Method | Type | Description |
|--------|------|-------------|
| `setAfm($afm)` | string | Employee's tax ID |
| `setLastName($name)` | string | Last name |
| `setFirstName($name)` | string | First name |
| `setDate($date)` | string | Date (DD/MM/YYYY) for daily |
| `setDayOfWeek($day)` | DayOfWeek | Day for weekly |
| `addEntry($entry)` | WorkTimeEntry | Add time entry |

### WorkTimeEntry

Individual time entry (work, leave, break, etc.).

```php
namespace OxygenSuite\OxygenErgani\Models\WorkTime;

class WorkTimeEntry
```

**Key Methods:**

| Method | Type | Description |
|--------|------|-------------|
| `setType($type)` | WorkTimeType | Entry type (ΕΡΓ, ΑΔ.ΚΑΝ, etc.) |
| `setFrom($time)` | string | Start time (HH:MM) |
| `setTo($time)` | string | End time (HH:MM) |

---

## Hiring Models (E3)

All hiring models extend the base `Hiring\Declaration` class.

### NewDeclaration (E3N)

New employee hiring.

```php
namespace OxygenSuite\OxygenErgani\Models\Hiring;

class NewDeclaration extends Declaration
```

**Traits used:**
- `HasExtendedEmploymentDetails` - Hiring date, salary, employment type
- `HasDypaPrograms` - DYPA program tracking
- `HasTrialPeriod` - Trial period fields
- `HasInsurance` - Insurance codes
- `HasWagePayment` - Payment timing
- `HasAcceptanceFiles` - Essential terms acceptance

**Key Fields:**

| Field | API Field | Description |
|-------|-----------|-------------|
| `setHiringDate($date)` | `f_proslipsidate` | Hiring date |
| `setGrossSalary($amount)` | `f_apodoxes` | Gross salary |
| `setEmploymentStatus($status)` | `f_kathestosapasxolisis` | Full/Part-time |
| `setWorkerType($type)` | `f_xaraktirismos` | Worker/Employee |
| `setSpecialtyCode($code)` | `f_eidikothta` | Specialty code |

### ModificationDeclaration (E3M)

Employee transfer from another company.

```php
namespace OxygenSuite\OxygenErgani\Models\Hiring;

class ModificationDeclaration extends Declaration
```

**Additional Fields:**

| Field | API Field | Description |
|-------|-----------|-------------|
| `setTransferDate($date)` | `f_date_metabibashs` | Transfer date |
| `setTransferCompanyAfm($afm)` | `f_transfer_company_afm` | Previous employer's AFM |
| `setTransferCompanyName($name)` | `f_transfer_company_eponimia` | Previous employer's name |

### LendingDeclaration (E3D)

Employee lending FROM direct employer.

```php
namespace OxygenSuite\OxygenErgani\Models\Hiring;

class LendingDeclaration extends Declaration
```

**Additional Fields:**

| Field | API Field | Description |
|-------|-----------|-------------|
| `setLoanType($type)` | `f_borrow_type` | Genuine or EPA |
| `setLoanStartDate($date)` | `f_borrow_date_from` | Loan start date |
| `setLoanEndDate($date)` | `f_borrow_date_to` | Loan end date |
| `setBorrowingCompanyAfm($afm)` | `f_borrow_company_afm` | Borrower's AFM |

### DeletionDeclaration (E3PD)

Hiring TO indirect employer (borrowed employee).

```php
namespace OxygenSuite\OxygenErgani\Models\Hiring;

class DeletionDeclaration extends Declaration
```

---

## Termination Models (E5)

All termination models extend the base `Termination\Declaration` class.

### Common Traits

- `HasSalary` - Gross salary at departure
- `HasCompensation` - Severance amount
- `HasFormFile` - Signed PDF form
- `HasNotificationReference` - Link to prior E5O

### Model Classes

| Class | Form | Description |
|-------|------|-------------|
| `VoluntaryResignationDeclaration` | E5N | Standard resignation |
| `NotificationDeclaration` | E5O | Resignation notification |
| `ResignationAfterNotificationDeclaration` | E5AO | After E5O confirmation |
| `DeathTerminationDeclaration` | E5D | Termination by death |
| `CompensatedExitDeclaration` | E5E | Voluntary exit with compensation |
| `VoluntaryRetirementDeclaration` | E5S | Voluntary retirement |
| `MandatoryRetirementDeclaration` | E5DS | Mandatory retirement |
| `FixedTermTerminationDeclaration` | E7N | Fixed-term contract termination |

**Key E5 Fields:**

| Field | API Field | Description |
|-------|-----------|-------------|
| `setTerminationDate($date)` | `f_apoxwrisidate` | Termination date |
| `setGrossSalary($amount)` | `f_apodoxes` | Salary at departure |
| `setCompensationAmount($amount)` | `f_posoapozimiosis` | Severance amount |
| `setFormFile($base64)` | `f_file` | Signed form (Base64 PDF) |

---

## Dismissal Models (E6)

All dismissal models extend the base `Dismissal\Declaration` class.

### Common Traits

- `HasEmploymentClassification` - Employment status, worker type
- `HasCollectiveDismissal` - Collective layoff tracking
- `HasTerminationNotification` - Notification date
- `HasNoticePeriod` - Notice period details (E6NMP)
- `HasLoanDetails` - Loan details (E6LD)
- `HasSalary`, `HasCompensation`, `HasFormFile` (reused from E5)

### Model Classes

| Class | Form | Description |
|-------|------|-------------|
| `DismissalWithoutNoticeDeclaration` | E6NXP | Immediate dismissal |
| `DismissalWithNoticeDeclaration` | E6NMP | Dismissal with notice |
| `RetirementDismissalDeclaration` | E6SXP | Retirement dismissal |
| `EndOfLoanDeclaration` | E6LD | End of loan arrangement |
| `TrialPeriodTerminationDeclaration` | E6LT | Trial period termination |
| `TransferDeclaration` | E6M | Employee transfer |

**Key E6 Fields:**

| Field | API Field | Description |
|-------|-----------|-------------|
| `setDismissalDate($date)` | `f_apolysisdate` | Dismissal date |
| `setTerminationNotificationDate($date)` | `f_koinopoihshdate` | Notification date |
| `setNoticePeriodMonths($months)` | `f_minesproidopoihsh` | Notice period (E6NMP) |
| `setIsCollectiveDismissal($bool)` | `f_omadikhap` | Collective dismissal flag |

---

## Modification Models (MA)

### ModificationDeclaration (MA)

Regular employee modifications.

```php
namespace OxygenSuite\OxygenErgani\Models\Modification;

class ModificationDeclaration extends Declaration
```

**Key Fields:**

| Field | API Field | Description |
|-------|-----------|-------------|
| `setModificationDate($date)` | `f_date_metabolhs` | Effective date |
| `setSettlementType($type)` | `f_eidos_dieuthethshs` | Settlement type |
| `addModificationTypeSelection($sel)` | `ModificationTypeSelections` | What changed |

### BorrowedModificationDeclaration (MAD)

Borrowed employee modifications.

```php
namespace OxygenSuite\OxygenErgani\Models\Modification;

class BorrowedModificationDeclaration extends Declaration
```

**Key Fields:**

| Field | API Field | Description |
|-------|-----------|-------------|
| `setModificationDate($date)` | `f_date_metabolhs` | Effective date |
| `setSalaryPaymentSource($source)` | `f_kataboli_apodoxon` | Who pays salary |
| `setLoanType($type)` | `f_borrow_type` | Genuine or EPA |

---

## Common Fields

All declaration models share common personal information fields:

### Branch/Location

| Method | API Field | Description |
|--------|-----------|-------------|
| `setBranchCode($code)` | `f_aa_pararthmatos` | Branch number (0 = HQ) |
| `setLaborInspectionServiceCode($code)` | `f_ypiresia_sepe` | SEPE code |
| `setDypaServiceCode($code)` | `f_ypiresia_oaed` | DYPA/OAED code |
| `setBranchActivityCode($code)` | `f_kad_pararthmatos` | Activity code (KAD) |

### Personal Information

| Method | API Field | Description |
|--------|-----------|-------------|
| `setLastName($name)` | `f_eponymo` | Last name (uppercase Greek) |
| `setFirstName($name)` | `f_onoma` | First name |
| `setFatherName($name)` | `f_onoma_patros` | Father's name |
| `setMotherName($name)` | `f_onoma_mitros` | Mother's name |
| `setBirthDate($date)` | `f_birthdate` | Birth date (DD/MM/YYYY) |
| `setSex($sex)` | `f_sex` | Sex (enum) |
| `setMaritalStatus($status)` | `f_marital_status` | Marital status |
| `setNumberOfChildren($count)` | `f_arithmos_teknon` | Number of children |

### Identity

| Method | API Field | Description |
|--------|-----------|-------------|
| `setNationality($code)` | `f_yphkoothta` | Nationality code |
| `setIdType($type)` | `f_typos_taytothtas` | ID document type |
| `setIdNumber($number)` | `f_ar_taytothtas` | ID number |
| `setIdIssuingAuthority($auth)` | `f_ekdousa_arxh` | Issuing authority |
| `setIdIssueDate($date)` | `f_date_ekdosis` | Issue date |
| `setIdExpiryDate($date)` | `f_date_ekdosis_lixi` | Expiry date |

### Tax/Insurance

| Method | API Field | Description |
|--------|-----------|-------------|
| `setAfm($afm)` | `f_afm` | Tax ID (9 digits) |
| `setTaxOffice($code)` | `f_doy` | Tax office code |
| `setAmka($amka)` | `f_amka` | Social security number |
| `setAmika($amika)` | `f_amika` | IKA number |

---

## Greek Float Casting

Decimal fields use Greek format (`1.234,56`) via the `$casts` property:

```php
protected array $casts = [
    'f_apodoxes' => 'greek_float',      // 2 decimals
    'f_week_hours' => 'greek_float:1',  // 1 decimal
];
```

**Input:** `1500.00` → **Output:** `"1.500,00"`

---

## Factory State Methods

Factories provide fluent state methods:

```php
$declaration = NewDeclaration::factory()
    ->fixedTerm('01/01/2025', '30/06/2025')
    ->partTime(25.0)
    ->withTrialPeriod('30/06/2025')
    ->female()
    ->make();
```

**Available States:**
- `fixedTerm($from, $to)` - Fixed-term contract
- `partTime($hours)` - Part-time employment
- `withTrialPeriod($endDate)` - Trial period
- `male()` / `female()` - Set sex
- `foreignNationalDirectAccess($nationality)` - Foreign with direct access
- `asWorker()` / `asManager()` - Worker type
- `withDypaPlacement($programCode)` - DYPA program

---

## See Also

- [Hiring Documentation](/guide/hiring/) - E3 forms guide
- [Termination Documentation](/guide/termination/) - E5 forms guide
- [Dismissal Documentation](/guide/dismissal/) - E6 forms guide
- [Enums](/api/enums) - Enum values
