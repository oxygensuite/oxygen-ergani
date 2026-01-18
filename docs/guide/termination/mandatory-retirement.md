# Mandatory Retirement (E5DS)

The E5DS form is used when the employer requires an employee to retire due to reaching the age limit or completing 15+ years of service with pension eligibility. Unlike E5S (voluntary), this is an employer-initiated retirement.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `RetirementMandatory` |
| Action Code | `WebE5DS` |
| Declaration Model | `MandatoryRetirementDeclaration` |
| Use Case | Employer-required retirement |

## When to Use E5DS

Use E5DS when:
- Employee has reached the mandatory retirement age
- Employee has completed 15+ years of service AND is pension-eligible
- The employer is requiring (not requesting) retirement
- Retirement compensation is provided

::: tip E5DS vs E5S
- **E5S**: Employee voluntarily requests retirement
- **E5DS**: Employer requires retirement (age or tenure based)
:::

::: info Why 15 Years?
Greek labor law allows employers to terminate employees who have completed 15 years of service with full pension rights, provided the employer pays the legal severance.
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementMandatory;
use OxygenSuite\OxygenErgani\Models\Termination\MandatoryRetirementDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = MandatoryRetirementDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1957')  // 68 years old
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15035712345')

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('123456')
    ->setHiringDate('01/01/1990')
    ->setDepartureDate('31/01/2025')
    ->setGrossSalary(2800.00)

    // Legal severance for retirement
    ->setCompensationAmount(40000.00)

    // Documentation
    ->setFormFile(base64_encode(file_get_contents('retirement_notice.pdf')));

$response = (new RetirementMandatory())->handle($declaration);
```

## E5DS-Specific Fields

In addition to [common fields](./index#common-fields), E5DS includes:

### Salary (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary at retirement |

### Compensation (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCompensationAmount()` | `f_posoapozimiosis` | float | Yes | Legal severance for retirement |

### Form File (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Retirement notification (Base64 PDF) |

## Complete Example

### Age-Based Mandatory Retirement

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementMandatory;
use OxygenSuite\OxygenErgani\Models\Termination\MandatoryRetirementDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = MandatoryRetirementDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal - Reached retirement age
    ->setLastName('ΔΗΜΗΤΡΙΟΥ')
    ->setFirstName('ΚΩΝΣΤΑΝΤΙΝΟΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΣΟΦΙΑ')
    ->setBirthDate('10/06/1957')  // 67+ years old
    ->setSex(Sex::MALE)
    ->setMaritalStatus(MaritalStatus::MARRIED)
    ->setNumberOfChildren(2)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΘΙ345678')
    ->setIdIssuingAuthority('Α.Τ. ΠΕΙΡΑΙΑ')
    ->setIdIssueDate('20/05/2000')

    // Tax/Insurance
    ->setAfm('444555666')
    ->setTaxOffice('1234')
    ->setAmka('10065712345')
    ->setAmika('87654321')

    // Education
    ->setEducationLevel('6')

    // Employment - Long service
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('456789')
    ->setHiringDate('01/09/1985')  // 40 years
    ->setDepartureDate('31/01/2025')
    ->setGrossSalary(3500.00)

    // Full legal severance
    ->setCompensationAmount(60000.00)

    // Comments
    ->setComments('Υποχρεωτική συνταξιοδότηση λόγω ορίου ηλικίας')

    // Retirement notification
    ->setFormFile(base64_encode(file_get_contents('mandatory_retirement_notice.pdf')));

$response = (new RetirementMandatory())->handle($declaration);
```

### 15-Year Rule Retirement

```php
$declaration = MandatoryRetirementDeclaration::make()
    // ... branch fields ...

    // Employee with 15+ years and pension eligibility
    ->setLastName('ΓΕΩΡΓΙΟΥ')
    ->setFirstName('ΑΛΕΞΑΝΔΡΟΣ')
    ->setBirthDate('22/08/1962')  // 62 years old, pension-eligible
    ->setSex(Sex::MALE)

    // ... identity and tax fields ...

    // Employment - Exactly 15 years
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('234567')
    ->setHiringDate('01/02/2010')  // 15 years
    ->setDepartureDate('31/01/2025')
    ->setGrossSalary(2800.00)

    // Legal severance (40% of normal for 15-year rule)
    ->setCompensationAmount(20000.00)

    ->setComments('Λήξη σύμβασης βάσει άρθρου 74 ΕΚ - 15ετία με πλήρη συνταξιοδοτικά δικαιώματα')
    ->setFormFile(base64_encode(file_get_contents('retirement_notice.pdf')));
```

## Response Handling

```php
$response = (new RetirementMandatory())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε5ΔΣ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Compensation Guidelines

For mandatory retirement (Art. 74 Labor Code), severance is typically:

| Scenario | Severance Amount |
|----------|-----------------|
| Age limit reached | Full legal severance |
| 15+ years with pension rights | 40% of full severance |

::: warning Legal Advice
Severance calculations for mandatory retirement are complex. Consult with a labor law specialist for accurate amounts.
:::

## Important Notes

1. **Employer-Initiated**: E5DS is when the employer decides to retire the employee, not when the employee requests it.

2. **Pension Eligibility**: For the 15-year rule, the employee must have full pension rights.

3. **Compensation Required**: Legal severance must be provided and documented.

4. **Proper Notice**: Ensure proper written notification has been given to the employee.

5. **Coordination with EFKA**: The retirement should be coordinated with pension application processes.

## See Also

- [Termination Overview](./index) - Common fields and enums
- [Voluntary Retirement (E5S)](./voluntary-retirement) - Employee-initiated retirement
- [Retirement Dismissal (E6SXP)](/guide/dismissal/retirement) - E6 version of retirement dismissal
