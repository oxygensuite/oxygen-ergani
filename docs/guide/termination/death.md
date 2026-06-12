# Death Termination (E5D)

The E5D form is used to report the termination of employment due to employee death. This is a special termination form that documents the end of employment when the employee has passed away.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `TerminationByDeath` |
| Action Code | `WebE5D` |
| Declaration Model | `DeathTerminationDeclaration` |
| Use Case | Employee has passed away |

## When to Use E5D

Use E5D when:
- An employee has died while still employed
- You need to officially terminate the employment record in ERGANI
- Processing final payments and benefits for heirs

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\TerminationByDeath;
use OxygenSuite\OxygenErgani\Models\Termination\DeathTerminationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = DeathTerminationDeclaration::make()
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
    ->setHiringDate('01/01/2010')
    ->setDepartureDate('20/01/2025')  // Date of death
    ->setGrossSalary(2000.00)

    // Death certificate or related documentation
    ->setFormFile(base64_encode(file_get_contents('death_certificate.pdf')));

$response = (new TerminationByDeath())->handle($declaration);
```

## E5D-Specific Fields

In addition to [common fields](./index#common-fields), E5D includes:

### Salary (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary at time of death |

### Form File (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Death certificate or supporting docs (Base64 PDF) |

## Complete Example

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\TerminationByDeath;
use OxygenSuite\OxygenErgani\Models\Termination\DeathTerminationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = DeathTerminationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΑΝΤΩΝΙΟΥ')
    ->setFirstName('ΓΕΩΡΓΙΟΣ')
    ->setFatherName('ΔΗΜΗΤΡΙΟΣ')
    ->setMotherName('ΕΛΕΝΗ')
    ->setBirthDate('10/05/1958')
    ->setSex(Sex::MALE)
    ->setMaritalStatus(MaritalStatus::MARRIED)
    ->setNumberOfChildren(2)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΠΕΙΡΑΙΑ')
    ->setIdIssueDate('15/03/2010')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('10055812345')
    ->setAmika('12345678')

    // Education
    ->setEducationLevel('5')

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('345678')
    ->setHiringDate('01/06/2005')
    ->setDepartureDate('15/01/2025')  // Date of death
    ->setGrossSalary(2500.00)

    // Comments
    ->setComments('Λήξη σύμβασης λόγω θανάτου εργαζομένου')

    // Documentation
    ->setFormFile(base64_encode(file_get_contents('death_certificate.pdf')));

$response = (new TerminationByDeath())->handle($declaration);
```

## Response Handling

```php
$response = (new TerminationByDeath())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε5Δ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Important Notes

1. **Departure Date**: Use the date of death as the departure date.

2. **Documentation**: Attach the death certificate or relevant documentation.

3. **No Compensation Field**: E5D does not include a severance field - any death benefits are handled separately through insurance and pension systems.

4. **Final Payments**: Calculate final wages, unused leave, and any other amounts owed for payment to the estate/heirs.

5. **EFKA Notification**: Additional notifications to EFKA (insurance) may be required separately.

## See Also

- [Termination Overview](./index) - Common fields and enums
- [Voluntary Resignation (E5N)](./voluntary) - Standard resignation
