# Retirement Dismissal (E6SXP)

The E6SXP form is used when the employer initiates retirement dismissal. This is similar to E5DS (mandatory retirement) but classified as a dismissal (E6) rather than termination (E5).

## Overview

| Property | Value |
|----------|-------|
| Document Class | `RetirementDismissal` |
| Action Code | `WebE6SXP` |
| Declaration Model | `RetirementDismissalDeclaration` |
| Use Case | Employer-initiated retirement |

## When to Use E6SXP

Use E6SXP when:
- The employer decides to retire an employee
- Employee has reached retirement age or pension eligibility
- 15-year rule with pension rights applies
- Compensation is provided

::: tip E6SXP vs E5DS
Both are employer-initiated retirement, but E6SXP is classified as a dismissal while E5DS is classified as a termination. Use E6SXP for consistency with other E6 dismissal forms.
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\RetirementDismissal;
use OxygenSuite\OxygenErgani\Models\Dismissal\RetirementDismissalDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = RetirementDismissalDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1958')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15035812345')

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setSpecialtyCode('123456')

    // Dates
    ->setHiringDate('01/01/1990')
    ->setDismissalDate('31/01/2025')
    ->setTerminationNotificationDate('15/01/2025')

    // Financial
    ->setGrossSalary(3000.00)
    ->setCompensationAmount(45000.00)

    // Retirement documentation
    ->setFormFile(base64_encode(file_get_contents('retirement_dismissal.pdf')));

$response = (new RetirementDismissal())->handle($declaration);
```

## E6SXP-Specific Fields

In addition to [common fields](./index#common-fields), E6SXP includes:

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
| `setDismissalDate()` | `f_apolysisdate` | string | Yes | Retirement effective date |
| `setTerminationNotificationDate()` | `f_koinopoihshdate` | string | Yes | Date employee was notified |

### Financial (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary |
| `setCompensationAmount()` | `f_posoapozimiosis` | float | Yes | Retirement severance |

### Form File (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Retirement notification (Base64 PDF) |

::: info No Collective Dismissal
E6SXP does NOT include collective dismissal fields. Retirement dismissals are always individual.
:::

## Complete Example

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\RetirementDismissal;
use OxygenSuite\OxygenErgani\Models\Dismissal\RetirementDismissalDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = RetirementDismissalDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal - Long-tenured employee
    ->setLastName('ΑΝΤΩΝΙΟΥ')
    ->setFirstName('ΓΕΩΡΓΙΟΣ')
    ->setFatherName('ΔΗΜΗΤΡΙΟΣ')
    ->setMotherName('ΑΙΚΑΤΕΡΙΝΗ')
    ->setBirthDate('05/11/1957')  // 67+ years old
    ->setSex(Sex::MALE)
    ->setMaritalStatus(MaritalStatus::MARRIED)
    ->setNumberOfChildren(2)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΠΕΙΡΑΙΑ')
    ->setIdIssueDate('15/03/2000')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('05115712345')
    ->setAmika('12345678')

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setSpecialtyCode('345678')

    // Dates - 35 years of service
    ->setHiringDate('01/03/1990')
    ->setDismissalDate('28/02/2025')
    ->setTerminationNotificationDate('01/02/2025')

    // Full retirement compensation
    ->setGrossSalary(3500.00)
    ->setCompensationAmount(60000.00)

    // Comments
    ->setComments('Λήξη σύμβασης λόγω συνταξιοδότησης')

    // Documentation
    ->setFormFile(base64_encode(file_get_contents('retirement_notice.pdf')));

$response = (new RetirementDismissal())->handle($declaration);
```

## Response Handling

```php
$response = (new RetirementDismissal())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε6ΣΧΠ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Important Notes

1. **Individual Only**: No collective dismissal option for retirement.

2. **Notification Required**: Must include the date the employee was notified of retirement.

3. **Pension Coordination**: Ensure alignment with EFKA pension processes.

4. **Compensation**: Full severance is typically required.

## See Also

- [Dismissal Overview](./index) - Common fields and enums
- [Mandatory Retirement (E5DS)](/guide/termination/mandatory-retirement) - Alternative retirement form
- [Voluntary Retirement (E5S)](/guide/termination/voluntary-retirement) - Employee-initiated retirement
