# Borrowed Employee (E3PD)

The E3PD form is used when you (the indirect employer) are receiving a borrowed employee from another company (the direct employer). This form is submitted by the company where the employee will actually work.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `HiringWithLending` |
| Action Code | `WebE3PD` |
| Declaration Model | `LendingDeclaration` |
| Use Case | Receiving a borrowed employee from another company |

## When to Use E3PD

Use E3PD when:
- You are receiving an employee on loan from another company
- You are receiving workers from a Temporary Employment Agency (EPA)
- The employee will work at your premises but remains employed by the lending company

::: tip E3D vs E3PD
- **E3D**: Submitted by the **direct employer** (lender)
- **E3PD**: Submitted by the **indirect employer** (borrower)

Both forms are required for a lending arrangement to be complete.
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringWithLending;
use OxygenSuite\OxygenErgani\Models\Hiring\LendingDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = LendingDeclaration::make()
    // Branch (your branch where employee will work)
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

    // Employment at your site
    ->setHiringDate('01/02/2025')
    ->setStartTime('09:00')
    ->setEndTime('17:00')
    ->setWeeklyHours(40.0)
    ->setSpecialtyCode('123456')
    ->setGrossSalary(1500.00)
    ->setEmploymentType(EmploymentType::BORROWED)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)

    // Lending arrangement - who is lending the employee
    ->setLendingDateFrom('01/02/2025')
    ->setLendingDateTo('30/06/2025')
    ->setDirectEmployerAfm('999999999')
    ->setDirectEmployerName('LENDER COMPANY LTD')

    // Insurance
    ->setMainInsurance('101')

    // Acceptance (required for E3PD)
    ->setBasicsAcceptance(true)
    ->setAcceptanceFile(base64_encode(file_get_contents('acceptance.pdf')));

$response = (new HiringWithLending())->handle($declaration);
```

## E3PD-Specific Fields

In addition to [common fields](./index#common-fields), E3PD includes:

### Lending Arrangement (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setLendingDateFrom()` | `f_borrow_date_from` | string | Yes | Lending start date (DD/MM/YYYY) |
| `setLendingDateTo()` | `f_borrow_date_to` | string | Yes | Lending end date (DD/MM/YYYY) |
| `setDirectEmployerAfm()` | `f_borrow_company_afm` | string | Yes | Lender's AFM (9 digits) |
| `setDirectEmployerName()` | `f_borrow_company_eponimia` | string | Yes | Lender's company name (max 230 chars) |

### Employment Type & Salary

Same as E3N, but `EmploymentType` should typically be `BORROWED`:

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setHiringDate()` | `f_proslipsidate` | string | Yes | Start date at your company |
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Monthly gross salary |
| `setEmploymentType()` | `f_sxeshapasxolisis` | EmploymentType | Yes | Typically `BORROWED` (3) |

### Trial Period

Same as E3N - see [New Hire](./new#trial-period).

### Insurance

Same as E3N - see [New Hire](./new#insurance).

### DYPA Programs

Same as E3N - see [New Hire](./new#dypa-programs).

### Wage Payment

Same as E3N - see [New Hire](./new#wage-payment).

### Acceptance Files (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBasicsAcceptance()` | `f_basics_acceptance` | bool | Yes | Essential terms accepted |
| `setAcceptanceFile()` | `f_file` | string | Yes | Base64 PDF of acceptance form |
| `setContractFile()` | `f_file_symbash` | string | No | Base64 PDF of contract |

## Complete Examples

### Receiving Borrowed Employee

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringWithLending;
use OxygenSuite\OxygenErgani\Models\Hiring\LendingDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\WeekDays;

$declaration = LendingDeclaration::make()
    // Your branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4520')

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

    // Lending arrangement
    ->setLendingDateFrom('01/03/2025')
    ->setLendingDateTo('31/08/2025')
    ->setDirectEmployerAfm('111222333')
    ->setDirectEmployerName('PARTNER COMPANY S.A.')

    // Employment at your site
    ->setHiringDate('01/03/2025')
    ->setStartTime('08:00')
    ->setEndTime('16:00')
    ->setWeeklyHours(40.0)
    ->setSpecialtyCode('456789')
    ->setSpecialtyDescription('ΤΕΧΝΙΚΟΣ ΣΥΝΤΗΡΗΣΗΣ')
    ->setExperienceYears(8)
    ->setGrossSalary(1800.00)
    ->setEmploymentType(EmploymentType::BORROWED)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::WORKER)

    // Work organization
    ->setWorkingTimeDigitalOrganization(true)
    ->setFullEmploymentHours(40.0)
    ->setWeekDays(WeekDays::FIVE)
    ->setWorkingCard(true)
    ->setBreakMinutes(30)
    ->setBreakWithinSchedule(true)

    // Insurance
    ->setMainInsurance('101')

    // Wage Payment
    ->setWagePaymentTime('Μηνιαία')
    ->setCollectiveAgreementApplicable(true)
    ->setCollectiveAgreementComments('ΕΓΣΣΕ')

    // Acceptance
    ->setBasicsAcceptance(true)
    ->setAcceptanceFile(base64_encode(file_get_contents('acceptance.pdf')));

$response = (new HiringWithLending())->handle($declaration);
```

### Receiving EPA Worker

```php
$declaration = LendingDeclaration::make()
    // Your branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // ... personal fields ...

    // EPA as the direct employer
    ->setLendingDateFrom('15/02/2025')
    ->setLendingDateTo('15/05/2025')
    ->setDirectEmployerAfm('999888777')
    ->setDirectEmployerName('TEMP AGENCY EPA')

    // Employment
    ->setHiringDate('15/02/2025')
    ->setStartTime('09:00')
    ->setEndTime('17:00')
    ->setWeeklyHours(40.0)
    ->setSpecialtyCode('123456')
    ->setGrossSalary(1200.00)
    ->setEmploymentType(EmploymentType::BORROWED)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)

    ->setMainInsurance('101')
    ->setBasicsAcceptance(true)
    ->setAcceptanceFile(base64_encode(file_get_contents('acceptance.pdf')));
```

### Part-Time Borrowed Employee

```php
$declaration = LendingDeclaration::make()
    // ... branch and personal fields ...

    // Lending arrangement
    ->setLendingDateFrom('01/04/2025')
    ->setLendingDateTo('30/09/2025')
    ->setDirectEmployerAfm('555666777')
    ->setDirectEmployerName('PARTNER LTD')

    // Part-time employment
    ->setHiringDate('01/04/2025')
    ->setStartTime('09:00')
    ->setEndTime('13:00')
    ->setWeeklyHours(20.0)
    ->setSpecialtyCode('345678')
    ->setGrossSalary(900.00)
    ->setEmploymentType(EmploymentType::BORROWED)
    ->setEmploymentStatus(EmploymentStatus::PARTIAL)  // Part-time
    ->setWorkerType(WorkerType::EMPLOYEE)

    ->setMainInsurance('101')
    ->setBasicsAcceptance(true)
    ->setAcceptanceFile(base64_encode(file_get_contents('acceptance.pdf')));
```

## Response Handling

```php
$response = (new HiringWithLending())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε3ΠΔ012')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Important Notes

1. **Acceptance Required**: Unlike E3M, E3PD requires acceptance files since you are establishing new working conditions at your premises.

2. **Paired with E3D**: When you submit E3PD, the lending company must also submit E3D.

3. **Employment Type**: Use `EmploymentType::BORROWED` (value 3) for the `f_sxeshapasxolisis` field.

4. **End of Lending**: When the lending period ends, submit E6LD (End of Loan) - see [Dismissal documentation](/guide/dismissal).

## See Also

- [Hiring Overview](./index) - Common fields and enums
- [Lending (E3D)](./lending) - The lender's side of the arrangement
- [End of Loan (E6LD)](/guide/dismissal) - Ending a lending arrangement
