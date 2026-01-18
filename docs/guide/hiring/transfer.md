# Transfer (E3M)

The E3M form is used for reporting employee transfers from another company to ERGANI. This applies when an employee's employment relationship is transferred due to business sale, merger, or similar corporate actions.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `HiringModification` |
| Action Code | `WebE3M` |
| Declaration Model | `ModificationDeclaration` |
| Use Case | Employee transferred from another company |

## When to Use E3M

Use E3M when:
- A business is sold and employees transfer to the new owner
- Companies merge and employees move to the surviving entity
- A business unit is acquired with its employees
- Any situation where employment continues but the employer changes

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringModification;
use OxygenSuite\OxygenErgani\Models\Hiring\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = ModificationDeclaration::make()
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

    // Employment Details
    ->setHiringDate('20/01/2025')
    ->setStartTime('09:00')
    ->setEndTime('17:00')
    ->setWeeklyHours(40.0)
    ->setSpecialtyCode('123456')
    ->setGrossSalary(1500.00)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)

    // Transfer-specific fields (required)
    ->setTransferDate('15/01/2025')
    ->setTransferCompanyAfm('987654321')
    ->setTransferCompanyName('PREVIOUS COMPANY LTD')

    // Insurance
    ->setMainInsurance('101');

$response = (new HiringModification())->handle($declaration);
```

## E3M-Specific Fields

In addition to [common fields](./index#common-fields), E3M includes:

### Transfer Information (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setTransferDate()` | `f_date_metabibashs` | string | Yes | Transfer date (DD/MM/YYYY) |
| `setTransferCompanyAfm()` | `f_transfer_company_afm` | string | Yes | Previous company's AFM (9 digits) |
| `setTransferCompanyName()` | `f_transfer_company_eponimia` | string | Yes | Previous company's name (max 230 chars) |

### Employment Type & Salary

Same as E3N - see [New Hire](./new#employment-type--salary).

### Trial Period

Same as E3N - see [New Hire](./new#trial-period).

### Insurance

Same as E3N - see [New Hire](./new#insurance).

### DYPA Programs

Same as E3N - see [New Hire](./new#dypa-programs).

### Wage Payment

Same as E3N - see [New Hire](./new#wage-payment).

::: info Note
Unlike E3N, E3M does **not** require acceptance files (`f_basics_acceptance`, `f_file`). The original employment terms from the previous employer continue to apply.
:::

## Complete Example

### Business Acquisition Transfer

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringModification;
use OxygenSuite\OxygenErgani\Models\Hiring\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = ModificationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΑΝΤΩΝΙΟΥ')
    ->setFirstName('ΜΑΡΙΑ')
    ->setFatherName('ΓΕΩΡΓΙΟΣ')
    ->setMotherName('ΕΛΕΝΗ')
    ->setBirthDate('05/11/1985')
    ->setSex(Sex::FEMALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΘΕΣΣΑΛΟΝΙΚΗΣ')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('05118512345')

    // Employment
    ->setHiringDate('01/02/2025')  // Date employment continues with new employer
    ->setStartTime('08:00')
    ->setEndTime('16:00')
    ->setWeeklyHours(40.0)
    ->setSpecialtyCode('345678')
    ->setSpecialtyDescription('ΛΟΓΙΣΤΗΣ')
    ->setExperienceYears(12)  // Total experience including previous employer
    ->setGrossSalary(2000.00)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)

    // Transfer Details
    ->setTransferDate('31/01/2025')  // Last day with previous employer
    ->setTransferCompanyAfm('111222333')
    ->setTransferCompanyName('ΠΑΛΑΙΑ ΕΤΑΙΡΕΙΑ Α.Ε.')

    // Work Organization
    ->setWorkingTimeDigitalOrganization(true)
    ->setFullEmploymentHours(40.0)
    ->setWorkingCard(true)

    // Insurance (continues from previous employer)
    ->setMainInsurance('101')

    // Wage Payment
    ->setCollectiveAgreementApplicable(true)
    ->setCollectiveAgreementComments('ΕΓΣΣΕ');

$response = (new HiringModification())->handle($declaration);
```

## Multiple Transfers

When acquiring a business with multiple employees:

```php
$employees = [
    ['afm' => '111111111', 'name' => 'ΠΑΠΑΔΟΠΟΥΛΟΣ', 'firstName' => 'ΙΩΑΝΝΗΣ'],
    ['afm' => '222222222', 'name' => 'ΓΕΩΡΓΙΟΥ', 'firstName' => 'ΜΑΡΙΑ'],
    ['afm' => '333333333', 'name' => 'ΝΙΚΟΛΑΟΥ', 'firstName' => 'ΠΕΤΡΟΣ'],
];

$declarations = [];
foreach ($employees as $emp) {
    $declarations[] = ModificationDeclaration::make()
        ->setBranchCode(0)
        ->setLaborInspectionServiceCode('12345')
        ->setDypaServiceCode('123456')
        ->setLastName($emp['name'])
        ->setFirstName($emp['firstName'])
        ->setAfm($emp['afm'])
        // ... other fields ...
        ->setTransferDate('31/01/2025')
        ->setTransferCompanyAfm('111222333')
        ->setTransferCompanyName('ΠΑΛΑΙΑ ΕΤΑΙΡΕΙΑ Α.Ε.')
        ->setMainInsurance('101');
}

$response = (new HiringModification())->handle($declarations);
```

## Response Handling

```php
$response = (new HiringModification())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε3Μ456')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## See Also

- [Hiring Overview](./index) - Common fields and enums
- [New Hire (E3N)](./new) - Standard new employee hiring
- [Dismissal Transfer (E6M)](/guide/dismissal) - Reporting the departure from the old employer's side
