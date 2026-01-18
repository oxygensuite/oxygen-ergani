# Voluntary Retirement (E5S)

The E5S form is used when an employee voluntarily retires and receives compensation. This form applies to employees who choose to retire before reaching mandatory retirement conditions.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `RetirementVoluntary` |
| Action Code | `WebE5S` |
| Declaration Model | `VoluntaryRetirementDeclaration` |
| Use Case | Employee chooses to retire |

## When to Use E5S

Use E5S when:
- An employee requests to retire
- The employee meets pension eligibility requirements
- Retirement is the employee's choice (not employer-initiated)
- Compensation/severance is provided

::: tip E5S vs E5DS
- **E5S**: Employee requests retirement (this form)
- **E5DS**: Employer requires retirement (15+ years or age limit)
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementVoluntary;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryRetirementDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = VoluntaryRetirementDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1960')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15036012345')

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('123456')
    ->setHiringDate('01/01/1995')
    ->setDepartureDate('31/01/2025')
    ->setGrossSalary(2500.00)

    // Retirement compensation
    ->setCompensationAmount(30000.00)

    // Retirement application/agreement
    ->setFormFile(base64_encode(file_get_contents('retirement_application.pdf')));

$response = (new RetirementVoluntary())->handle($declaration);
```

## E5S-Specific Fields

In addition to [common fields](./index#common-fields), E5S includes:

### Salary (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary at retirement |

### Compensation (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCompensationAmount()` | `f_posoapozimiosis` | float | Yes | Retirement compensation |

### Form File (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Retirement application (Base64 PDF) |

## Complete Example

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\RetirementVoluntary;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryRetirementDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = VoluntaryRetirementDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal - Long-tenured employee nearing retirement
    ->setLastName('ΑΝΤΩΝΙΟΥ')
    ->setFirstName('ΓΕΩΡΓΙΟΣ')
    ->setFatherName('ΔΗΜΗΤΡΙΟΣ')
    ->setMotherName('ΑΙΚΑΤΕΡΙΝΗ')
    ->setBirthDate('05/11/1958')  // 66 years old
    ->setSex(Sex::MALE)
    ->setMaritalStatus(MaritalStatus::MARRIED)
    ->setNumberOfChildren(3)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΘΕΣΣΑΛΟΝΙΚΗΣ')
    ->setIdIssueDate('15/03/2005')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('05115812345')
    ->setAmika('12345678')

    // Education
    ->setEducationLevel('5')

    // Employment - 30 years of service
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('345678')
    ->setHiringDate('01/03/1995')  // 30 years
    ->setDepartureDate('28/02/2025')
    ->setGrossSalary(3200.00)

    // Full retirement compensation
    ->setCompensationAmount(45000.00)

    // Comments
    ->setComments('Εθελούσια συνταξιοδότηση - 30 έτη προϋπηρεσίας')

    // Retirement application form
    ->setFormFile(base64_encode(file_get_contents('retirement_request.pdf')));

$response = (new RetirementVoluntary())->handle($declaration);
```

## Response Handling

```php
$response = (new RetirementVoluntary())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε5Σ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Important Notes

1. **Employee-Initiated**: E5S is for cases where the employee requests retirement.

2. **Compensation Required**: Both salary and compensation amounts must be provided.

3. **Documentation**: Attach the employee's retirement request/application.

4. **Pension Coordination**: Ensure alignment with EFKA pension application.

5. **For Employer-Initiated**: If the employer is requiring retirement, use E5DS instead.

## See Also

- [Termination Overview](./index) - Common fields and enums
- [Mandatory Retirement (E5DS)](./mandatory-retirement) - Employer-initiated retirement
- [Compensated Exit (E5E)](./compensated-exit) - Voluntary exit (non-retirement)
