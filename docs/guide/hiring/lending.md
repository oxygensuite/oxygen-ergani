# Lending (E3D)

The E3D form is used when you (the direct employer) are lending an employee to another company (the indirect employer). This form is submitted by the company that originally hired the employee.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `HiringDeletion` |
| Action Code | `WebE3D` |
| Declaration Model | `DeletionDeclaration` |
| Use Case | Lending your employee to another company |

## When to Use E3D

Use E3D when:
- You are lending an employee to work at another company
- You are a Temporary Employment Agency (EPA) placing workers
- The employment relationship remains with you, but the employee works elsewhere

::: tip E3D vs E3PD
- **E3D**: Submitted by the **direct employer** (lender)
- **E3PD**: Submitted by the **indirect employer** (borrower)

Both forms are required for a lending arrangement to be complete.
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringDeletion;
use OxygenSuite\OxygenErgani\Models\Hiring\DeletionDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Enums\SalaryPaymentSource;

$declaration = DeletionDeclaration::make()
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

    // Lending arrangement
    ->setBorrowType(LoanType::GENUINE)
    ->setBorrowDateFrom('01/02/2025')
    ->setBorrowDateTo('30/06/2025')
    ->setBorrowCompanyAfm('555555555')
    ->setBorrowCompanyName('BORROWER COMPANY LTD')

    // Employment at borrower's site
    ->setStartTime('09:00')
    ->setEndTime('17:00')
    ->setWeeklyHours(40.0)
    ->setSpecialtyCode('123456')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)

    // Wage payment
    ->setWagePaymentBy(SalaryPaymentSource::DIRECT_EMPLOYER)
    ->setGrossSalary(1500.00)

    // Employee consent
    ->setEmployeeBorrowAgreement(true);

$response = (new HiringDeletion())->handle($declaration);
```

## E3D-Specific Fields

In addition to [common fields](./index#common-fields), E3D includes:

### Lending Arrangement (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBorrowType()` | `f_borrow_type` | LoanType | Yes | Type of lending arrangement |
| `setBorrowDateFrom()` | `f_borrow_date_from` | string | Yes | Lending start date (DD/MM/YYYY) |
| `setBorrowDateTo()` | `f_borrow_date_to` | string | Yes | Lending end date (DD/MM/YYYY) |
| `setBorrowCompanyAfm()` | `f_borrow_company_afm` | string | Yes | Indirect employer's AFM (9 digits) |
| `setBorrowCompanyName()` | `f_borrow_company_eponimia` | string | Yes | Indirect employer's name (max 230 chars) |

### Wage Payment

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setWagePaymentBy()` | `f_kataboli_apodoxon` | SalaryPaymentSource | Yes | Who pays wages |
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Monthly gross salary |
| `setHourlyWage()` | `f_hour_apodoxes` | float | No | Hourly wage |
| `setWagePaymentTime()` | `f_xronos_katavolis_apodoxon` | string | No | Payment schedule |
| `setCollectiveAgreementApplicable()` | `f_efarmoste_sillogiki_simbasi` | bool | No | Collective agreement applies |
| `setCollectiveAgreementComments()` | `f_efarmoste_sillogiki_simbasi_comments` | string | Cond. | Agreement details |

### Employee Consent

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setEmployeeBorrowAgreement()` | `f_ergazom_borrow_agreement` | bool | Yes | Employee agreed to lending |

## Enums

### LoanType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `GENUINE` | 0 | Genuine borrowing | Γνήσιος δανεισμός |
| `EPA` | 1 | Temporary Employment Agency | Ε.Π.Α. |

### SalaryPaymentSource

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `DIRECT_EMPLOYER` | 0 | Direct employer/EPA | Άμεσος εργοδότης/ΕΠΑ |
| `INDIRECT_EMPLOYER` | 1 | Indirect employer | Έμμεσος εργοδότης |

## Complete Examples

### Genuine Employee Lending

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringDeletion;
use OxygenSuite\OxygenErgani\Models\Hiring\DeletionDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Enums\SalaryPaymentSource;

$declaration = DeletionDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal
    ->setLastName('ΚΩΝΣΤΑΝΤΙΝΟΥ')
    ->setFirstName('ΔΗΜΗΤΡΙΟΣ')
    ->setFatherName('ΑΝΤΩΝΙΟΣ')
    ->setMotherName('ΣΟΦΙΑ')
    ->setBirthDate('20/07/1988')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΘΙ345678')
    ->setIdIssuingAuthority('Α.Τ. ΠΕΙΡΑΙΑ')

    // Tax/Insurance
    ->setAfm('444555666')
    ->setTaxOffice('1234')
    ->setAmka('20078812345')

    // Lending arrangement - genuine borrowing
    ->setBorrowType(LoanType::GENUINE)
    ->setBorrowDateFrom('01/03/2025')
    ->setBorrowDateTo('31/08/2025')
    ->setBorrowCompanyAfm('777888999')
    ->setBorrowCompanyName('ΣΥΝΕΡΓΑΤΗΣ Α.Ε.')

    // Employment details at borrower's site
    ->setStartTime('08:00')
    ->setEndTime('16:00')
    ->setWeeklyHours(40.0)
    ->setSpecialtyCode('456789')
    ->setSpecialtyDescription('ΤΕΧΝΙΚΟΣ')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::WORKER)

    // Work organization
    ->setWorkingTimeDigitalOrganization(true)
    ->setWorkingCard(true)

    // Wage payment - direct employer pays
    ->setWagePaymentBy(SalaryPaymentSource::DIRECT_EMPLOYER)
    ->setGrossSalary(1800.00)
    ->setWagePaymentTime('Μηνιαία')
    ->setCollectiveAgreementApplicable(true)
    ->setCollectiveAgreementComments('ΕΓΣΣΕ')

    // Employee consent
    ->setEmployeeBorrowAgreement(true);

$response = (new HiringDeletion())->handle($declaration);
```

### Temporary Employment Agency (EPA)

```php
$declaration = DeletionDeclaration::make()
    // ... personal fields ...

    // EPA placement
    ->setBorrowType(LoanType::EPA)
    ->setBorrowDateFrom('15/02/2025')
    ->setBorrowDateTo('15/05/2025')
    ->setBorrowCompanyAfm('888999000')
    ->setBorrowCompanyName('CLIENT COMPANY S.A.')

    // ... employment fields ...

    // EPA pays wages
    ->setWagePaymentBy(SalaryPaymentSource::DIRECT_EMPLOYER)
    ->setGrossSalary(1200.00)

    ->setEmployeeBorrowAgreement(true);
```

### Indirect Employer Pays Wages

```php
$declaration = DeletionDeclaration::make()
    // ... personal fields ...

    ->setBorrowType(LoanType::GENUINE)
    ->setBorrowDateFrom('01/04/2025')
    ->setBorrowDateTo('30/09/2025')
    ->setBorrowCompanyAfm('111222333')
    ->setBorrowCompanyName('PARTNER COMPANY LTD')

    // ... employment fields ...

    // Indirect employer pays wages
    ->setWagePaymentBy(SalaryPaymentSource::INDIRECT_EMPLOYER)
    ->setGrossSalary(2000.00)

    ->setEmployeeBorrowAgreement(true);
```

## Response Handling

```php
$response = (new HiringDeletion())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε3Δ789')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Important Notes

1. **Employee Consent Required**: The employee must agree to the lending arrangement (`setEmployeeBorrowAgreement(true)`).

2. **Paired with E3PD**: When you submit E3D, the borrowing company must also submit E3PD.

3. **End of Lending**: When the lending period ends, submit E6LD (End of Loan) - see [Dismissal documentation](/guide/dismissal).

## See Also

- [Hiring Overview](./index) - Common fields and enums
- [Borrowed (E3PD)](./borrowed) - The borrower's side of the arrangement
- [End of Loan (E6LD)](/guide/dismissal) - Ending a lending arrangement
