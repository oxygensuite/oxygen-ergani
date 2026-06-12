# Voluntary Resignation (E5N)

The E5N form is used for reporting standard voluntary resignations to ERGANI. This is the most common termination form - used when an employee submits a written resignation letter.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `VoluntaryResignation` |
| Action Code | `WebE5N` |
| Declaration Model | `VoluntaryResignationDeclaration` |
| Use Case | Employee voluntarily resigns |

## When to Use E5N

Use E5N when:
- Employee submits a written resignation letter
- Employee gives verbal notice of resignation
- Resignation is voluntary and unambiguous
- No severance payment is involved

::: tip E5N vs E5E
- **E5N**: Simple resignation, no severance pay
- **E5E**: Voluntary exit with compensation (e.g., early retirement incentive programs)
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryResignation;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryResignationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = VoluntaryResignationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1990')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15039012345')

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('123456')
    ->setHiringDate('01/01/2020')
    ->setDepartureDate('31/01/2025')
    ->setGrossSalary(1500.00)

    // Signed resignation form
    ->setFormFile(base64_encode(file_get_contents('resignation.pdf')));

$response = (new VoluntaryResignation())->handle($declaration);
```

## E5N-Specific Fields

In addition to [common fields](./index#common-fields), E5N includes:

### Salary (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary at departure |

### Form File (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Signed resignation form (Base64 PDF) |

## Complete Examples

### Full-Time Indefinite Employee Resignation

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\VoluntaryResignation;
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryResignationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = VoluntaryResignationDeclaration::make()
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
    ->setBirthDate('22/08/1985')
    ->setSex(Sex::FEMALE)
    ->setMaritalStatus(MaritalStatus::MARRIED)
    ->setNumberOfChildren(2)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΘΕΣΣΑΛΟΝΙΚΗΣ')
    ->setIdIssueDate('10/05/2015')

    // Tax/Insurance
    ->setAfm('987654321')
    ->setTaxOffice('1234')
    ->setAmka('22088512345')
    ->setAmika('12345678')

    // Education
    ->setEducationLevel('6')

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('345678')
    ->setHiringDate('15/06/2018')
    ->setDepartureDate('28/02/2025')
    ->setGrossSalary(2200.00)

    // Comments
    ->setComments('Οικειοθελής αποχώρηση')

    // Signed form
    ->setFormFile(base64_encode(file_get_contents('resignation.pdf')));

$response = (new VoluntaryResignation())->handle($declaration);
```

### Fixed-Term Employee Resignation

```php
$declaration = VoluntaryResignationDeclaration::make()
    // ... branch and personal fields ...

    // Employment - Fixed-term
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::FIXED_TERM)
    ->setFixedTermFrom('01/01/2025')
    ->setFixedTermTo('31/12/2025')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('123456')
    ->setHiringDate('01/01/2025')
    ->setDepartureDate('15/03/2025')  // Early resignation
    ->setGrossSalary(1800.00)

    ->setFormFile(base64_encode(file_get_contents('resignation.pdf')));
```

### Part-Time Employee Resignation

```php
$declaration = VoluntaryResignationDeclaration::make()
    // ... branch and personal fields ...

    // Employment - Part-time
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::PARTIAL)
    ->setSpecialtyCode('234567')
    ->setHiringDate('01/06/2022')
    ->setDepartureDate('31/01/2025')
    ->setGrossSalary(900.00)  // Part-time salary

    ->setFormFile(base64_encode(file_get_contents('resignation.pdf')));
```

### Foreign Worker Resignation

```php
$declaration = VoluntaryResignationDeclaration::make()
    // ... branch fields ...

    // Personal
    ->setLastName('SMITH')
    ->setFirstName('JOHN')
    ->setFatherName('WILLIAM')
    ->setMotherName('MARY')
    ->setBirthDate('10/06/1988')
    ->setSex(Sex::MALE)

    // Identity - Foreign national
    ->setNationality('003')  // Non-EU country
    ->setIdType('ΔΙΑΒ')
    ->setIdNumber('AB1234567')

    // Residence permit (direct access)
    ->setResPermitDirectAccess(true)
    ->setResPermitDirectAccessType('01')
    ->setResPermitDirectAccessNumber('RP123456')
    ->setResPermitDirectAccessExpiry('31/12/2026')

    // Tax/Insurance
    ->setAfm('111222333')
    ->setAmka('10068812345')

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('456789')
    ->setHiringDate('01/01/2023')
    ->setDepartureDate('31/01/2025')
    ->setGrossSalary(1500.00)

    // Both resignation form and foreign worker docs
    ->setFormFile(base64_encode(file_get_contents('resignation.pdf')))
    ->setForeignWorkerFile(base64_encode(file_get_contents('work_permit.pdf')));
```

### Using Factory for Testing

```php
use OxygenSuite\OxygenErgani\Models\Termination\VoluntaryResignationDeclaration;

// Create with random valid data
$declaration = VoluntaryResignationDeclaration::factory()->make();

// With specific overrides
$declaration = VoluntaryResignationDeclaration::factory()->make([
    'f_eponymo' => 'ΤΕΣΤ',
    'f_apodoxes' => 2000.00,
]);
```

## Response Handling

```php
$response = (new VoluntaryResignation())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε5Ν123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

After a successful submission, retrieve the official PDF document:

```php
$pdfBase64 = (new VoluntaryResignation())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

// Save to file
file_put_contents('resignation.pdf', base64_decode($pdfBase64));
```

## Multiple Resignations

Submit multiple resignations in a single API call:

```php
$declarations = [
    VoluntaryResignationDeclaration::make()
        ->setAfm('111111111')
        // ... other fields ...
    ,
    VoluntaryResignationDeclaration::make()
        ->setAfm('222222222')
        // ... other fields ...
    ,
];

$response = (new VoluntaryResignation())->handle($declarations);
```

## Important Notes

1. **Form File Required**: A signed resignation form (PDF) must be attached.

2. **Departure Date**: This is the last day of employment, not the date the resignation was submitted.

3. **No Compensation**: E5N does not include severance pay fields. Use E5E for compensated exits.

4. **Employment Type Continuity**: If the employee was on a fixed-term contract, include the original contract dates.

## See Also

- [Termination Overview](./index) - Common fields and enums
- [Compensated Exit (E5E)](./compensated-exit) - Resignation with severance
- [Resignation Notification (E5O)](./notification) - For absent employees
