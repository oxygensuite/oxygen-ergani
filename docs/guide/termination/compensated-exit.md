# Voluntary Exit with Compensation (E5E)

The E5E form is used when an employee voluntarily exits with severance compensation. This typically occurs in voluntary separation programs where the employer offers compensation incentives for employees to resign.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `VoluntaryExitCompensation` |
| Action Code | `WebE5E` |
| Declaration Model | `CompensatedExitDeclaration` |
| Use Case | Voluntary exit with severance pay |

## When to Use E5E

Use E5E when:
- The company offers a voluntary separation program with compensation
- An employee agrees to resign in exchange for severance pay
- There's a mutual agreement for compensated departure
- Early retirement incentives are offered (not age-based retirement)

::: tip E5E vs Other Forms
- **E5N**: Simple resignation, no compensation
- **E5E**: Voluntary exit WITH compensation (this form)
- **E5S/E5DS**: Retirement with compensation (age-related)
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryExitCompensation;
use OxygenSuite\OxygenErgani\Models\Termination\CompensatedExitDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = CompensatedExitDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1975')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15037512345')

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('123456')
    ->setHiringDate('01/01/2010')
    ->setDepartureDate('31/01/2025')
    ->setGrossSalary(2500.00)

    // Compensation (the key difference from E5N)
    ->setCompensationAmount(25000.00)

    // Signed agreement
    ->setFormFile(base64_encode(file_get_contents('separation_agreement.pdf')));

$response = (new VoluntaryExitCompensation())->handle($declaration);
```

## E5E-Specific Fields

In addition to [common fields](./index#common-fields), E5E includes:

### Salary (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary at departure |

### Compensation (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCompensationAmount()` | `f_posoapozimiosis` | float | Yes | Severance amount |

### Form File (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Signed agreement (Base64 PDF) |

## Complete Example

### Voluntary Separation Program

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryExitCompensation;
use OxygenSuite\OxygenErgani\Models\Termination\CompensatedExitDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = CompensatedExitDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΚΩΝΣΤΑΝΤΙΝΟΥ')
    ->setFirstName('ΜΑΡΙΑ')
    ->setFatherName('ΑΝΤΩΝΙΟΣ')
    ->setMotherName('ΕΛΕΝΗ')
    ->setBirthDate('22/08/1970')
    ->setSex(Sex::FEMALE)
    ->setMaritalStatus(MaritalStatus::MARRIED)
    ->setNumberOfChildren(2)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')
    ->setIdIssueDate('10/05/2010')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('22087012345')
    ->setAmika('12345678')

    // Education
    ->setEducationLevel('6')

    // Employment - Long tenure employee
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('345678')
    ->setHiringDate('01/03/2005')  // 20 years of service
    ->setDepartureDate('28/02/2025')
    ->setGrossSalary(3000.00)

    // Generous severance for long-term employee
    ->setCompensationAmount(50000.00)

    // Comments
    ->setComments('Πρόγραμμα εθελούσιας εξόδου 2025')

    // Signed separation agreement
    ->setFormFile(base64_encode(file_get_contents('voluntary_separation_agreement.pdf')));

$response = (new VoluntaryExitCompensation())->handle($declaration);
```

### Early Retirement Incentive

```php
$declaration = CompensatedExitDeclaration::make()
    // ... branch and personal fields ...

    // Senior employee opting for early exit
    ->setLastName('ΓΕΩΡΓΙΟΥ')
    ->setFirstName('ΠΕΤΡΟΣ')
    ->setBirthDate('10/06/1963')  // 62 years old
    ->setSex(Sex::MALE)

    // ... identity and tax fields ...

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('456789')
    ->setHiringDate('01/01/2000')  // 25 years
    ->setDepartureDate('31/01/2025')
    ->setGrossSalary(3500.00)

    // Enhanced compensation for early retirement
    ->setCompensationAmount(75000.00)

    ->setComments('Πρόγραμμα πρόωρης εθελούσιας εξόδου')
    ->setFormFile(base64_encode(file_get_contents('early_retirement_agreement.pdf')));
```

## Response Handling

```php
$response = (new VoluntaryExitCompensation())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε5Ε123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Multiple Compensated Exits

For voluntary separation programs affecting multiple employees:

```php
$declarations = [];

foreach ($employees as $emp) {
    $declarations[] = CompensatedExitDeclaration::make()
        ->setBranchCode($emp['branch'])
        ->setLaborInspectionServiceCode('12345')
        ->setDypaServiceCode('123456')
        ->setLastName($emp['lastName'])
        ->setFirstName($emp['firstName'])
        // ... other fields ...
        ->setGrossSalary($emp['salary'])
        ->setCompensationAmount($emp['severance'])
        ->setFormFile(base64_encode(file_get_contents($emp['agreementPath'])));
}

$response = (new VoluntaryExitCompensation())->handle($declarations);
```

## Compensation Calculation

While ERGANI doesn't validate compensation amounts, typical calculations consider:

- Years of service
- Monthly salary
- Legal minimums for voluntary separation
- Company policy and program terms

```php
// Example: Calculate based on tenure
$yearsOfService = 20;
$monthlySalary = 3000.00;
$compensationMultiplier = min($yearsOfService, 24);  // Cap at 24 months

$compensation = $monthlySalary * $compensationMultiplier;
// $compensation = 60000.00 (20 months × €3,000)
```

## Important Notes

1. **Both Fields Required**: E5E requires both salary AND compensation amount.

2. **Agreement Document**: Attach the signed voluntary separation agreement.

3. **Not for Dismissal**: E5E is for voluntary exits. For employer-initiated terminations with severance, use E6 forms.

4. **Tax Implications**: Severance compensation may have different tax treatment - consult with payroll/tax experts.

5. **EFKA Reporting**: Additional social security notifications may be required.

## See Also

- [Termination Overview](./index) - Common fields and enums
- [Voluntary Resignation (E5N)](./voluntary) - Simple resignation without compensation
- [Voluntary Retirement (E5S)](./voluntary-retirement) - Age-based retirement
- [Dismissal Without Notice (E6NXP)](/guide/dismissal/without-notice) - Employer-initiated with severance
