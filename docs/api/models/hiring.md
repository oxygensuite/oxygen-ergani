# Hiring Models (E3)

Models for employee hiring declarations.

All hiring models extend the base `Hiring\Declaration` class.

## NewDeclaration (E3N)

New employee hiring.

```php
namespace OxygenSuite\OxygenErgani\Models\Hiring;

class NewDeclaration extends Declaration
```

### Traits Used

- `HasExtendedEmploymentDetails` - Hiring date, salary, employment type
- `HasDypaPrograms` - DYPA program tracking
- `HasTrialPeriod` - Trial period fields
- `HasInsurance` - Insurance codes
- `HasWagePayment` - Payment timing
- `HasAcceptanceFiles` - Essential terms acceptance

### Key Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setHiringDate($date)` | `f_proslipsidate` | Hiring date |
| `setGrossSalary($amount)` | `f_apodoxes` | Gross salary |
| `setEmploymentStatus($status)` | `f_kathestosapasxolisis` | Full/Part-time |
| `setWorkerType($type)` | `f_xaraktirismos` | Worker/Employee |
| `setSpecialtyCode($code)` | `f_eidikothta` | Specialty code |
| `setEmploymentType($type)` | `f_sxeshapasxolisis` | Contract type |
| `setWeeklyHours($hours)` | `f_week_hours` | Weekly hours |

### Example

```php
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Enums\{Sex, EmploymentStatus, WorkerType, EmploymentType};

$declaration = NewDeclaration::make()
    ->setBranchCode(0)
    ->setAfm('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setSex(Sex::MALE)
    ->setBirthDate('15/01/1990')
    ->setHiringDate('20/01/2025')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setGrossSalary(1500.00)
    ->setWeeklyHours(40.0);
```

---

## ModificationDeclaration (E3M)

Employee transfer from another company.

```php
namespace OxygenSuite\OxygenErgani\Models\Hiring;

class ModificationDeclaration extends Declaration
```

### Additional Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setTransferDate($date)` | `f_date_metabibashs` | Transfer date |
| `setTransferCompanyAfm($afm)` | `f_transfer_company_afm` | Previous employer's AFM |
| `setTransferCompanyName($name)` | `f_transfer_company_eponimia` | Previous employer's name |

---

## LendingDeclaration (E3D)

Employee lending FROM direct employer.

```php
namespace OxygenSuite\OxygenErgani\Models\Hiring;

class LendingDeclaration extends Declaration
```

### Additional Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setLoanType($type)` | `f_borrow_type` | Genuine or EPA |
| `setLoanStartDate($date)` | `f_borrow_date_from` | Loan start date |
| `setLoanEndDate($date)` | `f_borrow_date_to` | Loan end date |
| `setBorrowingCompanyAfm($afm)` | `f_borrow_company_afm` | Borrower's AFM |
| `setBorrowingCompanyName($name)` | `f_borrow_company_eponimia` | Borrower's name |

---

## DeletionDeclaration (E3PD)

Hiring TO indirect employer (borrowed employee).

```php
namespace OxygenSuite\OxygenErgani\Models\Hiring;

class DeletionDeclaration extends Declaration
```

This model is used by the borrowing company to record a borrowed employee.

---

## Factory State Methods

```php
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;

$declaration = NewDeclaration::factory()
    ->fixedTerm('01/01/2025', '30/06/2025')  // Fixed-term contract
    ->partTime(25.0)                          // Part-time (25 hours/week)
    ->withTrialPeriod('30/06/2025')          // With trial period
    ->female()                                // Set sex
    ->asWorker()                              // Worker type
    ->withDypaPlacement('PROG001')           // DYPA program
    ->make();
```

**Available States:**
- `fixedTerm($from, $to)` - Fixed-term contract
- `partTime($hours)` - Part-time employment
- `withTrialPeriod($endDate)` - Trial period
- `male()` / `female()` - Set sex
- `foreignNationalDirectAccess($nationality)` - Foreign with direct access
- `foreignNationalApproval($nationality)` - Foreign requiring approval
- `asWorker()` / `asManager()` - Worker type
- `withDypaPlacement($programCode)` - DYPA program

## See Also

- [Hiring Guide](/guide/hiring/) - Complete E3 usage guide
- [Common Fields](./common-fields) - Shared declaration fields
- [Employment Enums](/api/enums/employment) - Employment-related enums
