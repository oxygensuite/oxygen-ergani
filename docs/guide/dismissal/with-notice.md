# Dismissal With Notice (E6NMP)

The E6NMP form is used for employer-initiated dismissals with an advance notice period. The employee continues working during the notice period, and reduced severance (50%) is paid at the end.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `DismissalWithNotice` |
| Action Code | `WebE6NMP` |
| Declaration Model | `DismissalWithNoticeDeclaration` |
| Use Case | Dismissal with advance notice |

## When to Use E6NMP

Use E6NMP when:
- Giving the employee advance notice of termination
- The employee will work during the notice period (1-4 months)
- Paying reduced severance (50% of full amount)
- Individual or collective layoffs with notice

::: tip E6NMP vs E6NXP
- **E6NMP**: With notice period, 50% severance
- **E6NXP**: Immediate dismissal, 100% severance
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithNotice;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithNoticeDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\NoticePeriodMonths;

$declaration = DismissalWithNoticeDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1985')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15038512345')

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setSpecialtyCode('123456')

    // Notice Period
    ->setNoticeDate('01/01/2025')         // When notice was given
    ->setNoticePeriodMonths(2)            // 2 months notice

    // Dates
    ->setHiringDate('01/01/2018')
    ->setDismissalDate('28/02/2025')      // End of notice period

    // Financial (50% severance)
    ->setGrossSalary(2000.00)
    ->setCompensationAmount(7000.00)      // Half of full amount

    // Not collective dismissal
    ->setCollectiveDismissal(false)

    // Signed notice document
    ->setFormFile(base64_encode(file_get_contents('notice.pdf')));

$response = (new DismissalWithNotice())->handle($declaration);
```

## E6NMP-Specific Fields

In addition to [common fields](./index#common-fields), E6NMP includes:

### Employment Classification (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setEmploymentStatus()` | `f_kathestosapasxolisis` | EmploymentStatus | Yes | Full/Part-time |
| `setWorkerType()` | `f_xaraktirismos` | WorkerType | Yes | Worker/Employee |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty code |

### Notice Period (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setNoticeDate()` | `f_proidopoihshdate` | string | Yes | Date notice was given |
| `setNoticePeriodMonths()` | `f_minesproidopoihsh` | int | Yes | Notice period (1-4 months) |

### Employment Dates

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setHiringDate()` | `f_proslipsidate` | string | Yes | Original hiring date |
| `setDismissalDate()` | `f_apolysisdate` | string | Yes | End of notice period |

### Financial (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary |
| `setCompensationAmount()` | `f_posoapozimiosis` | float | Yes | Reduced severance (50%) |

### Collective Dismissal

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCollectiveDismissal()` | `f_omadiki` | bool | Yes | Part of collective layoff |
| `setCollectiveDismissalNumber()` | `f_omadikiarithmos` | string | Cond. | Decision number |
| `setCollectiveDismissalDate()` | `f_omadikidate` | string | Cond. | Decision date |

### Form File (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Signed notice (Base64 PDF) |

## Notice Period Requirements

The notice period depends on years of service:

| Years of Service | Required Notice Period |
|------------------|----------------------|
| 0-2 years | 1 month |
| 2-5 years | 2 months |
| 5-10 years | 3 months |
| 10+ years | 4 months |

### NoticePeriodMonths Enum

| Value | Description |
|-------|-------------|
| 1 | 1 month notice |
| 2 | 2 months notice |
| 3 | 3 months notice |
| 4 | 4 months notice |

## Complete Examples

### Standard Notice Dismissal

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithNotice;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithNoticeDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = DismissalWithNoticeDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΓΕΩΡΓΙΟΥ')
    ->setFirstName('ΜΑΡΙΑ')
    ->setFatherName('ΔΗΜΗΤΡΙΟΣ')
    ->setMotherName('ΑΙΚΑΤΕΡΙΝΗ')
    ->setBirthDate('22/08/1978')
    ->setSex(Sex::FEMALE)
    ->setMaritalStatus(MaritalStatus::MARRIED)
    ->setNumberOfChildren(1)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΘΕΣΣΑΛΟΝΙΚΗΣ')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('22087812345')
    ->setAmika('87654321')

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setSpecialtyCode('345678')

    // Notice Period - 8 years service = 3 months notice
    ->setNoticeDate('01/12/2024')
    ->setNoticePeriodMonths(3)

    // Dates
    ->setHiringDate('01/03/2017')  // ~8 years
    ->setDismissalDate('28/02/2025')  // End of 3-month notice

    // 50% severance for with-notice dismissal
    ->setGrossSalary(2200.00)
    ->setCompensationAmount(8800.00)

    // Individual dismissal
    ->setCollectiveDismissal(false)

    // Comments and file
    ->setComments('Αναδιάρθρωση τμήματος')
    ->setFormFile(base64_encode(file_get_contents('notice_letter.pdf')));

$response = (new DismissalWithNotice())->handle($declaration);
```

### Long-Service Employee (4 Months Notice)

```php
$declaration = DismissalWithNoticeDeclaration::make()
    // ... branch and personal fields ...

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setSpecialtyCode('456789')

    // 4 months notice for 15+ years service
    ->setNoticeDate('01/11/2024')
    ->setNoticePeriodMonths(4)

    // Dates
    ->setHiringDate('01/06/2008')  // 16+ years
    ->setDismissalDate('28/02/2025')  // End of 4-month notice

    // 50% of full severance
    ->setGrossSalary(3000.00)
    ->setCompensationAmount(24000.00)  // Half of 48 months equivalent

    ->setCollectiveDismissal(false)
    ->setFormFile(base64_encode(file_get_contents('notice.pdf')));
```

### Collective Layoff with Notice

```php
$declaration = DismissalWithNoticeDeclaration::make()
    // ... branch and personal fields ...

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::WORKER)
    ->setSpecialtyCode('234567')

    // 2 months notice
    ->setNoticeDate('01/01/2025')
    ->setNoticePeriodMonths(2)

    // Dates
    ->setHiringDate('01/09/2020')
    ->setDismissalDate('28/02/2025')

    // Financial
    ->setGrossSalary(1600.00)
    ->setCompensationAmount(4000.00)

    // COLLECTIVE DISMISSAL
    ->setCollectiveDismissal(true)
    ->setCollectiveDismissalNumber('ΑΠ/2025/002')
    ->setCollectiveDismissalDate('15/12/2024')

    ->setFormFile(base64_encode(file_get_contents('collective_notice.pdf')));
```

## Response Handling

```php
$response = (new DismissalWithNotice())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε6ΝΜΠ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Severance Calculation

For dismissal with notice, severance is **50%** of full amount:

```php
// Example calculation
$yearsOfService = 10;
$monthlySalary = 2000.00;
$fullSeveranceMonths = 10;  // Based on service

$fullSeverance = $monthlySalary * $fullSeveranceMonths;  // €20,000
$reducedSeverance = $fullSeverance * 0.5;                // €10,000

$declaration->setCompensationAmount($reducedSeverance);
```

## Important Notes

1. **Notice Date vs Dismissal Date**: The notice date is when you inform the employee. The dismissal date is when employment actually ends (after the notice period).

2. **50% Severance**: With notice = half the severance of immediate dismissal.

3. **Working During Notice**: The employee is expected to continue working during the notice period.

4. **Cannot Shorten Notice**: Once notice is given with a specific period, you cannot unilaterally shorten it without the employee's agreement.

5. **Documentation**: Submit the form when notice is given, not at the end of the notice period.

## See Also

- [Dismissal Overview](./index) - Common fields and enums
- [Dismissal Without Notice (E6NXP)](./without-notice) - Immediate dismissal
- [Termination (E5)](/guide/termination/) - Employee-initiated departures
