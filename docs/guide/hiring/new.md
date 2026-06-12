# New Hire (E3N)

The E3N form is used for reporting new employee hirings to ERGANI. This is the standard form for employees starting fresh employment with your company.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `HiringNew` |
| Action Code | `WebE3N` |
| Declaration Model | `NewDeclaration` |
| Use Case | New employee starting work |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = NewDeclaration::make()
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
    ->setNationality('001')  // Greek
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setTaxOffice('1234')
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

    // Insurance
    ->setMainInsurance('101')

    // Acceptance (required for E3N)
    ->setBasicsAcceptance(true)
    ->setAcceptanceFile(base64_encode(file_get_contents('acceptance.pdf')));

$response = (new HiringNew())->handle($declaration);
```

## E3N-Specific Fields

In addition to [common fields](./index#common-fields), E3N includes:

### Employment Type & Salary

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setHiringDate()` | `f_proslipsidate` | string | Yes | Hiring date (DD/MM/YYYY) |
| `setExperienceYears()` | `f_proipiresia` | int | No | Years of experience |
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Monthly gross salary |
| `setHourlyWage()` | `f_hour_apodoxes` | float | No | Hourly wage |
| `setEmploymentType()` | `f_sxeshapasxolisis` | EmploymentType | Yes | Indefinite/Fixed-term/Project |
| `setFixedTermFrom()` | `f_orismenou_apo` | string | Cond. | Fixed-term start date |
| `setFixedTermTo()` | `f_orismenou_ews` | string | Cond. | Fixed-term end date |
| `setSpecialCase()` | `f_special_case` | SpecialCase | No | Public sector special case |

### Trial Period

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setTrialPeriod()` | `f_trial_period` | bool | No | Has trial period |
| `setTrialPeriodEndDate()` | `f_trial_date_to` | string | Cond. | Trial end date (DD/MM/YYYY) |

### Insurance

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setMainInsurance()` | `f_kyria_asfalisi` | string | Yes | Main insurance code |
| `addSupplementaryInsurance()` | `SupplementaryInsuranceSelections` | array | No | Supplementary insurance |
| `setAdditionalInsuranceBenefits()` | `f_prosthetes_asfalistikes` | string | No | Additional benefits |

### DYPA Programs

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setDypaPlacement()` | `f_topothetisioaed` | bool | No | DYPA program placement |
| `setDypaProgram()` | `f_programaoaed` | string | Cond. | Program code |
| `setReplacementProgram()` | `f_replaceprograma` | bool | No | Replacing another employee |
| `setReplacementAfm()` | `f_replaceprograma_afm` | string | Cond. | Replaced employee's AFM |
| `setReplacementAmka()` | `f_replaceprograma_amka` | string | Cond. | Replaced employee's AMKA |

### Wage Payment

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setWagePaymentTime()` | `f_xronos_katavolis_apodoxon` | string | No | Payment schedule |
| `setMandatoryTraining()` | `f_ipoxreotiki_katartisi` | bool | No | Mandatory training required |
| `setCollectiveAgreementApplicable()` | `f_efarmoste_sillogiki_simbasi` | bool | No | Collective agreement applies |
| `setCollectiveAgreementComments()` | `f_efarmoste_sillogiki_simbasi_comments` | string | Cond. | Agreement details |

### Acceptance Files (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBasicsAcceptance()` | `f_basics_acceptance` | bool | Yes | Essential terms accepted |
| `setAcceptanceFile()` | `f_file` | string | Yes | Base64 PDF of acceptance form |
| `setContractFile()` | `f_file_symbash` | string | No | Base64 PDF of employment contract |

## Complete Examples

### Full-Time Indefinite Employee

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringNew;
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;
use OxygenSuite\OxygenErgani\Enums\WeekDays;

$declaration = NewDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1990')
    ->setSex(Sex::MALE)
    ->setMaritalStatus(MaritalStatus::SINGLE)
    ->setNumberOfChildren(0)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')
    ->setIdIssueDate('10/05/2015')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setTaxOffice('1234')
    ->setAmka('15039012345')
    ->setEducationLevel('6')

    // Employment
    ->setHiringDate('20/01/2025')
    ->setStartTime('09:00')
    ->setEndTime('17:00')
    ->setWeeklyHours(40.0)
    ->setSpecialtyCode('123456')
    ->setSpecialtyDescription('ΥΠΑΛΛΗΛΟΣ ΓΡΑΦΕΙΟΥ')
    ->setExperienceYears(5)
    ->setGrossSalary(1500.00)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)

    // Work Organization
    ->setWorkingTimeDigitalOrganization(true)
    ->setFullEmploymentHours(40.0)
    ->setWeekDays(WeekDays::FIVE)
    ->setWorkingCard(true)
    ->setBreakMinutes(30)
    ->setBreakWithinSchedule(true)

    // Trial Period
    ->setTrialPeriod(true)
    ->setTrialPeriodEndDate('20/07/2025')

    // Insurance
    ->setMainInsurance('101')

    // Wage Payment
    ->setWagePaymentTime('Μηνιαία')
    ->setCollectiveAgreementApplicable(true)
    ->setCollectiveAgreementComments('ΕΓΣΣΕ')

    // Acceptance
    ->setBasicsAcceptance(true)
    ->setAcceptanceFile(base64_encode(file_get_contents('acceptance.pdf')));

$response = (new HiringNew())->handle($declaration);
```

### Part-Time Fixed-Term Employee

```php
$declaration = NewDeclaration::make()
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    ->setLastName('ΓΕΩΡΓΙΟΥ')
    ->setFirstName('ΕΛΕΝΗ')
    ->setFatherName('ΔΗΜΗΤΡΙΟΣ')
    ->setMotherName('ΑΙΚΑΤΕΡΙΝΗ')
    ->setBirthDate('22/08/1995')
    ->setSex(Sex::FEMALE)

    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΓΔ654321')

    ->setAfm('987654321')
    ->setAmka('22089512345')

    // Part-time employment
    ->setHiringDate('01/02/2025')
    ->setStartTime('09:00')
    ->setEndTime('13:00')
    ->setWeeklyHours(20.0)
    ->setSpecialtyCode('234567')
    ->setGrossSalary(750.00)
    ->setEmploymentStatus(EmploymentStatus::PARTIAL)
    ->setWorkerType(WorkerType::EMPLOYEE)

    // Fixed-term contract
    ->setEmploymentType(EmploymentType::FIXED_TERM)
    ->setFixedTermFrom('01/02/2025')
    ->setFixedTermTo('31/07/2025')

    ->setMainInsurance('101')
    ->setBasicsAcceptance(true)
    ->setAcceptanceFile(base64_encode(file_get_contents('acceptance.pdf')));
```

### Foreign Worker

```php
$declaration = NewDeclaration::make()
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    ->setLastName('SMITH')
    ->setFirstName('JOHN')
    ->setFatherName('WILLIAM')
    ->setMotherName('MARY')
    ->setBirthDate('10/06/1988')
    ->setSex(Sex::MALE)

    ->setNationality('003')  // Non-EU country

    // Direct labor market access permit
    ->setResPermitDirectAccess(true)
    ->setResPermitDirectAccessType('01')
    ->setResPermitDirectAccessNumber('RP123456')
    ->setResPermitDirectAccessExpiry('31/12/2026')

    // ... employment fields ...

    ->setMainInsurance('101')
    ->setBasicsAcceptance(true)
    ->setAcceptanceFile(base64_encode(file_get_contents('acceptance.pdf')))

    // Attach work authorization documents
    ->setForeignWorkerFile(base64_encode(file_get_contents('work_permit.pdf')));
```

### Using Factory for Testing

```php
use OxygenSuite\OxygenErgani\Models\Hiring\NewDeclaration;

// Create with random valid data
$declaration = NewDeclaration::factory()->make();

// With specific overrides
$declaration = NewDeclaration::factory()->make([
    'f_eponymo' => 'ΤΕΣΤ',
    'f_onoma' => 'ΧΡΗΣΤΗΣ',
]);

// Using state methods
$declaration = NewDeclaration::factory()
    ->fixedTerm('01/01/2025', '30/06/2025')
    ->partTime(20.0)
    ->female()
    ->withTrialPeriod('30/03/2025')
    ->make();

// Foreign national
$declaration = NewDeclaration::factory()
    ->foreignNationalDirectAccess('002')
    ->make();
```

## Response Handling

```php
$response = (new HiringNew())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε3Ν123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## See Also

- [Hiring Overview](./index) - Common fields and enums
- [Transfer (E3M)](./transfer) - Employee transfer from another company
- [Termination (E5)](/guide/termination/) - Employee termination
