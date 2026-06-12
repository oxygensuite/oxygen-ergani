# Dismissal Without Notice (E6NXP)

The E6NXP form is used for employer-initiated immediate dismissals without a notice period. The employment ends on the date of notification, and full severance compensation is required.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `DismissalWithoutNotice` |
| Action Code | `WebE6NXP` |
| Declaration Model | `DismissalWithoutNoticeDeclaration` |
| Use Case | Immediate dismissal with severance |

## When to Use E6NXP

Use E6NXP when:
- Dismissing an employee immediately (same day)
- The employee does not work a notice period
- Full severance compensation is paid
- Individual or collective layoffs

::: tip E6NXP vs E6NMP
- **E6NXP**: Immediate dismissal, no notice period, full severance
- **E6NMP**: Dismissal with advance notice, reduced severance (50%)
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithoutNotice;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithoutNoticeDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = DismissalWithoutNoticeDeclaration::make()
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

    // Dates
    ->setHiringDate('01/01/2018')
    ->setDismissalDate('31/01/2025')
    ->setTerminationNotificationDate('31/01/2025')  // Same as dismissal

    // Financial
    ->setGrossSalary(2000.00)
    ->setCompensationAmount(14000.00)

    // Not collective dismissal
    ->setCollectiveDismissal(false)

    // Signed dismissal document
    ->setFormFile(base64_encode(file_get_contents('dismissal.pdf')));

$response = (new DismissalWithoutNotice())->handle($declaration);
```

## E6NXP-Specific Fields

In addition to [common fields](./index#common-fields), E6NXP includes:

### Employment Classification (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setEmploymentStatus()` | `f_kathestosapasxolisis` | EmploymentStatus | Yes | Full/Part-time |
| `setWorkerType()` | `f_xaraktirismos` | WorkerType | Yes | Worker/Employee |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty code |

### Employment Dates

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setHiringDate()` | `f_proslipsidate` | string | Yes | Original hiring date |
| `setDismissalDate()` | `f_apolysisdate` | string | Yes | Dismissal effective date |
| `setTerminationNotificationDate()` | `f_koinopoihshdate` | string | Yes | Date employee was notified |

### Financial (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary |
| `setCompensationAmount()` | `f_posoapozimiosis` | float | Yes | Full severance amount |

### Collective Dismissal

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCollectiveDismissal()` | `f_omadiki` | bool | Yes | Part of collective layoff |
| `setCollectiveDismissalNumber()` | `f_omadikiarithmos` | string | Cond. | Decision number |
| `setCollectiveDismissalDate()` | `f_omadikidate` | string | Cond. | Decision date |

### Form File (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Signed dismissal form (Base64 PDF) |

## Complete Examples

### Standard Individual Dismissal

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\DismissalWithoutNotice;
use OxygenSuite\OxygenErgani\Models\Dismissal\DismissalWithoutNoticeDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = DismissalWithoutNoticeDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΚΩΝΣΤΑΝΤΙΝΟΥ')
    ->setFirstName('ΔΗΜΗΤΡΙΟΣ')
    ->setFatherName('ΑΝΤΩΝΙΟΣ')
    ->setMotherName('ΣΟΦΙΑ')
    ->setBirthDate('20/07/1980')
    ->setSex(Sex::MALE)
    ->setMaritalStatus(MaritalStatus::MARRIED)
    ->setNumberOfChildren(2)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΘΙ345678')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')

    // Tax/Insurance
    ->setAfm('444555666')
    ->setTaxOffice('1234')
    ->setAmka('20078012345')
    ->setAmika('12345678')

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setSpecialtyCode('456789')

    // Dates
    ->setHiringDate('01/03/2015')  // 10 years service
    ->setDismissalDate('31/01/2025')
    ->setTerminationNotificationDate('31/01/2025')

    // Full severance for 10 years
    ->setGrossSalary(2500.00)
    ->setCompensationAmount(25000.00)

    // Individual dismissal
    ->setCollectiveDismissal(false)

    // Comments and file
    ->setComments('Κατάργηση θέσης εργασίας')
    ->setFormFile(base64_encode(file_get_contents('dismissal_letter.pdf')));

$response = (new DismissalWithoutNotice())->handle($declaration);
```

### Collective Dismissal (Part of Layoffs)

```php
$declaration = DismissalWithoutNoticeDeclaration::make()
    // ... branch and personal fields ...

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::WORKER)
    ->setSpecialtyCode('234567')

    // Dates
    ->setHiringDate('01/06/2016')
    ->setDismissalDate('15/02/2025')
    ->setTerminationNotificationDate('15/02/2025')

    // Financial
    ->setGrossSalary(1800.00)
    ->setCompensationAmount(18000.00)

    // COLLECTIVE DISMISSAL
    ->setCollectiveDismissal(true)
    ->setCollectiveDismissalNumber('ΑΠ/2025/001')
    ->setCollectiveDismissalDate('01/02/2025')

    ->setFormFile(base64_encode(file_get_contents('collective_dismissal.pdf')));
```

### Part-Time Employee Dismissal

```php
$declaration = DismissalWithoutNoticeDeclaration::make()
    // ... branch and personal fields ...

    // Part-time employment
    ->setEmploymentStatus(EmploymentStatus::PARTIAL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setSpecialtyCode('345678')

    // Dates
    ->setHiringDate('01/09/2020')
    ->setDismissalDate('31/01/2025')
    ->setTerminationNotificationDate('31/01/2025')

    // Part-time salary and proportional severance
    ->setGrossSalary(900.00)
    ->setCompensationAmount(4500.00)

    ->setCollectiveDismissal(false)
    ->setFormFile(base64_encode(file_get_contents('dismissal.pdf')));
```

## Response Handling

```php
$response = (new DismissalWithoutNotice())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε6ΝΧΠ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

After a successful submission, retrieve the official PDF document:

```php
$pdfBase64 = (new DismissalWithoutNotice())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

// Save to file
file_put_contents('dismissal.pdf', base64_decode($pdfBase64));
```

## Severance Calculation Guidelines

Full severance for immediate dismissal (indicative):

| Years of Service | Severance (months of salary) |
|------------------|------------------------------|
| 1-4 years | 1-4 months |
| 5-10 years | 5-10 months |
| 10-15 years | 10-12 months |
| 15-20 years | 12-16 months |
| 20+ years | Up to 24 months |

::: warning Legal Advice
Severance calculations are complex and depend on employment type (worker vs employee), years of service, and current legislation. Consult with a labor law specialist.
:::

## Important Notes

1. **Immediate Effect**: Dismissal takes effect on the notification date.

2. **Full Severance**: No notice period = full severance (not 50%).

3. **Notification Date**: Must match or precede the dismissal date.

4. **Collective Dismissals**: If part of mass layoffs, include the collective dismissal decision details.

5. **Documentation**: The signed dismissal letter is mandatory.

## See Also

- [Dismissal Overview](./index) - Common fields and enums
- [Dismissal With Notice (E6NMP)](./with-notice) - For dismissals with notice period
- [Termination (E5)](/guide/termination/) - Employee-initiated departures
