# Employment Modifications (MA)

The MA forms are used to report modifications to employment terms. There are two variants:
- **WebMA**: For regular (direct) employees
- **WebMAD**: For borrowed/loaned employees

## Overview

| Form | Action Code | Declaration Model | Use Case |
|------|-------------|-------------------|----------|
| MA | `WebMA` | `ModificationDeclaration` | Regular employee modifications |
| MAD | `WebMAD` | `BorrowedModificationDeclaration` | Borrowed employee modifications |

## When to Use MA/MAD

Use these forms when employment terms change:
- Salary or wage changes
- Work schedule modifications
- Employment status changes (full-time to part-time)
- Specialty or position changes
- Work location changes
- Collective agreement changes
- Insurance modifications
- Employment type changes (indefinite to fixed-term)

## MA vs MAD

| Feature | MA (WebMA) | MAD (WebMAD) |
|---------|------------|--------------|
| Employee Type | Direct employees | Borrowed/loaned employees |
| Loan Details | Optional | **Required** |
| Salary Payment Source | Not applicable | **Required** |
| Modification Types | Required array | Not included |
| Settlement Type | Available | Not included |
| Reference Period | Available | Not included |

---

## MA: Regular Employee Modifications

### Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Modification\EmploymentModification;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationTypeSelection;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\SettlementType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;

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
    ->setBirthDate('15/03/1985')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15038512345')

    // Modification Details
    ->setModificationDate('01/02/2025')
    ->setSettlementType(SettlementType::INDIVIDUAL)

    // Employment Details
    ->setSpecialtyCode('123456')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)

    // Updated Salary
    ->setGrossSalary(1800.00)
    ->setHourlyWage(10.50)
    ->setSalaryPaymentTiming('Μηνιαία καταβολή')

    // What changed (required)
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setType('1')  // e.g., Salary change
    );

$response = (new EmploymentModification())->handle($declaration);
```

### MA-Specific Fields

#### Modification Details (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setModificationDate()` | `f_date_metabolhs` | string | Yes | Effective date (DD/MM/YYYY) |
| `setSettlementType()` | `f_eidos_dieuthethshs` | SettlementType | No | Settlement arrangement |
| `setSettlementTypeComment()` | `f_eidos_dieuthethshs_comments` | string | No | Settlement details (max 200 chars) |
| `setReferencePeriodFrom()` | `f_periodos_anaforas_from` | string | No | Reference period start |
| `setReferencePeriodTo()` | `f_periodos_anaforas_to` | string | No | Reference period end |

#### Modification Type Selections (Required)

Each MA submission must include at least one modification type:

```php
use OxygenSuite\OxygenErgani\Models\Modification\ModificationTypeSelection;

$declaration
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setType('1')  // Salary
    )
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setType('3')  // Schedule
    );

// Or set all at once
$declaration->setModificationTypeSelections([
    ModificationTypeSelection::make()->setType('1'),
    ModificationTypeSelection::make()->setType('3'),
]);
```

#### Salary and Employment

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setExperienceYears()` | `f_proipiresia` | int | No | Years of prior experience |
| `setGrossSalary()` | `f_apodoxes` | float | No | Gross monthly salary |
| `setHourlyWage()` | `f_hour_apodoxes` | float | No | Hourly wage rate |
| `setSalaryPaymentTiming()` | `f_xronos_katabolhs` | string | No | Payment timing (max 200 chars) |

#### Employment Type

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setEmploymentType()` | `f_sxeshapasxolisis` | EmploymentType | No | Contract duration type |
| `setFixedTermFrom()` | `f_orismenou_apo` | string | No | Fixed-term start date |
| `setFixedTermTo()` | `f_orismenou_ews` | string | No | Fixed-term end date |
| `setSpecialCase()` | `f_special_case` | SpecialCase | No | Public sector classification |

#### Collective Agreement

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCollectiveAgreementApplies()` | `f_efarmostea_sillogiki_simbasi` | bool | No | Whether CLA applies |
| `setCollectiveAgreementComment()` | `f_efarmostea_sillogiki_simbasi_comments` | string | No | CLA details (max 200 chars) |

#### Insurance

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setPrimaryInsurance()` | `f_kyria_asfalish` | string | No | Primary insurance code |
| `setAdditionalInsuranceBenefits()` | `f_prosthetes_asfalistikes_paroxes` | string | No | Additional benefits (max 200 chars) |
| `setMandatoryTraining()` | `f_ipoxreotiki_katartisi` | bool | No | Whether training is mandatory |

#### DYPA Programs

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setDypaPlacement()` | `f_topothetisioaed` | bool | No | Placed via DYPA |
| `setDypaProgram()` | `f_programaoaed` | string | No | DYPA program code |

#### Trial Period

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setTrialPeriod()` | `f_trial_period` | bool | No | Whether trial period |
| `setTrialPeriodEndDate()` | `f_trial_date_to` | string | No | Trial period end date |

#### Acceptance and Files

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBasicsAcceptance()` | `f_basics_acceptance` | BasicsAcceptance | No | Terms acceptance method |
| `setAcceptanceFile()` | `f_file` | string | No | Signed acceptance (Base64 PDF) |
| `setRotationDecisionFile()` | `f_epibolh_file` | string | No | Rotation decision (Base64 PDF) |

---

## MAD: Borrowed Employee Modifications

### Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Modification\BorrowedEmploymentModification;
use OxygenSuite\OxygenErgani\Models\Modification\BorrowedModificationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Enums\SalaryPaymentSource;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = BorrowedModificationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
    ->setLastName('ΓΕΩΡΓΙΟΥ')
    ->setFirstName('ΜΑΡΙΑ')
    ->setFatherName('ΠΕΤΡΟΣ')
    ->setMotherName('ΑΝΝΑ')
    ->setBirthDate('22/08/1990')
    ->setSex(Sex::FEMALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')

    // Tax/Insurance
    ->setAfm('987654321')
    ->setAmka('22089012345')

    // Modification Date
    ->setModificationDate('01/02/2025')

    // Loan Details (REQUIRED for MAD)
    ->setLoanType(LoanType::GENUINE)
    ->setLoanStartDate('01/01/2024')
    ->setLoanEndDate('31/12/2025')
    ->setBorrowingCompanyAfm('555555555')
    ->setBorrowingCompanyName('BORROWING COMPANY LTD')

    // Salary Payment Source (REQUIRED for MAD)
    ->setSalaryPaymentSource(SalaryPaymentSource::DIRECT_EMPLOYER)

    // Employment Details
    ->setSpecialtyCode('234567')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE);

$response = (new BorrowedEmploymentModification())->handle($declaration);
```

### MAD-Specific Fields

#### Loan Details (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setLoanType()` | `f_borrow_type` | LoanType | Yes | Genuine or EPA |
| `setLoanStartDate()` | `f_borrow_date_from` | string | Yes | Loan start date |
| `setLoanEndDate()` | `f_borrow_date_to` | string | Yes | Loan end date |
| `setBorrowingCompanyAfm()` | `f_borrow_company_afm` | string | Yes | Borrower's AFM |
| `setBorrowingCompanyName()` | `f_borrow_company_eponimia` | string | Yes | Borrower's name |

#### Salary Payment Source (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setSalaryPaymentSource()` | `f_kataboli_apodoxon` | SalaryPaymentSource | Yes | Who pays wages |

::: info Salary Fields
When `SalaryPaymentSource::INDIRECT_EMPLOYER` is set, you should also provide salary details:
```php
->setSalaryPaymentSource(SalaryPaymentSource::INDIRECT_EMPLOYER)
->setGrossSalary(1800.00)
->setHourlyWage(10.50)
```
:::

---

## Enums

### SettlementType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `COLLECTIVE` | 0 | Collective agreement | Συλλογική σύμβαση |
| `INDIVIDUAL` | 1 | Individual agreement | Ατομική συμφωνία |
| `NO` | 2 | No settlement | Χωρίς διευθέτηση |

### SalaryPaymentSource

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `DIRECT_EMPLOYER` | 0 | Direct employer/EPA | Άμεσος εργοδότης/ΕΠΑ |
| `INDIRECT_EMPLOYER` | 1 | Indirect employer | Έμμεσος εργοδότης |

### EmploymentType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `INDEFINITE` | 0 | Indefinite term | Αορίστου χρόνου |
| `FIXED_TERM` | 1 | Fixed term | Ορισμένου χρόνου |
| `PROJECT` | 2 | Project-based | Έργου |
| `BORROWED` | 3 | Borrowed employee | Δανειζόμενος |

### LoanType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `GENUINE` | 0 | Genuine borrowing | Γνήσιος δανεισμός |
| `EPA` | 1 | Temporary Employment Agency | Ε.Π.Α. |

---

## Complete Examples

### MA: Salary Increase

```php
use OxygenSuite\OxygenErgani\Http\Documents\Modification\EmploymentModification;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationTypeSelection;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\SettlementType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;

$declaration = ModificationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΑΝΤΩΝΙΟΥ')
    ->setFirstName('ΔΗΜΗΤΡΙΟΣ')
    ->setFatherName('ΓΕΩΡΓΙΟΣ')
    ->setMotherName('ΕΛΕΝΗ')
    ->setBirthDate('10/04/1988')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΘΙ456789')
    ->setIdIssuingAuthority('Α.Τ. ΘΕΣΣΑΛΟΝΙΚΗΣ')

    // Tax/Insurance
    ->setAfm('444555666')
    ->setTaxOffice('1234')
    ->setAmka('10048812345')

    // Modification
    ->setModificationDate('01/02/2025')
    ->setSettlementType(SettlementType::INDIVIDUAL)

    // Employment stays the same
    ->setSpecialtyCode('345678')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)

    // Updated salary (the change)
    ->setGrossSalary(2200.00)  // Was 1800.00
    ->setHourlyWage(12.50)
    ->setSalaryPaymentTiming('Μηνιαία καταβολή στις 25 εκάστου')

    // Mark what changed
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setType('1')  // Salary modification
    )

    ->setComments('Αύξηση αποδοχών λόγω προαγωγής');

$response = (new EmploymentModification())->handle($declaration);
```

### MA: Full-Time to Part-Time Change

```php
$declaration = ModificationDeclaration::make()
    // ... branch and personal fields ...

    ->setModificationDate('01/03/2025')
    ->setSettlementType(SettlementType::INDIVIDUAL)

    // Change to part-time
    ->setEmploymentStatus(EmploymentStatus::PART)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)

    // Updated work hours
    ->setWeekHours(25.0)
    ->setFullEmploymentHours(40.0)
    ->setWeekDays(5)

    // Adjusted salary
    ->setGrossSalary(1125.00)  // Proportional
    ->setHourlyWage(11.25)

    // Modification types
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setType('2')  // Employment status
    )
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setType('1')  // Salary
    )

    ->setComments('Μετατροπή σε μερική απασχόληση κατόπιν αιτήματος εργαζομένου');
```

### MA: Indefinite to Fixed-Term Conversion

```php
$declaration = ModificationDeclaration::make()
    // ... branch and personal fields ...

    ->setModificationDate('01/01/2025')

    // Change employment type
    ->setEmploymentType(EmploymentType::FIXED_TERM)
    ->setFixedTermFrom('01/01/2025')
    ->setFixedTermTo('30/06/2025')

    // Mark the change
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setType('4')  // Employment type
    );
```

### MA: Position Change with New Specialty

```php
$declaration = ModificationDeclaration::make()
    // ... branch and personal fields ...

    ->setModificationDate('15/02/2025')
    ->setSettlementType(SettlementType::COLLECTIVE)
    ->setSettlementTypeComment('Εφαρμογή ΣΣΕ εμπορίου')

    // New specialty/position
    ->setSpecialtyCode('567890')  // New code
    ->setSpecialtyDescription('Υπεύθυνος Πωλήσεων')
    ->setHasResponsiblePosition(true)

    // Salary adjustment
    ->setGrossSalary(2500.00)
    ->setExperienceYears(8)

    // Applied collective agreement
    ->setCollectiveAgreementApplies(true)
    ->setCollectiveAgreementComment('Κλαδική ΣΣΕ Εμπορικών Υπαλλήλων')

    // Mark changes
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setType('5')  // Specialty
    )
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()->setType('1')  // Salary
    );
```

### MAD: Modify Borrowed Employee Schedule

```php
use OxygenSuite\OxygenErgani\Http\Documents\Modification\BorrowedEmploymentModification;
use OxygenSuite\OxygenErgani\Models\Modification\BorrowedModificationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Enums\SalaryPaymentSource;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = BorrowedModificationDeclaration::make()
    // Branch (borrower's branch)
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΝΙΚΟΛΑΟΥ')
    ->setFirstName('ΕΛΕΝΗ')
    ->setFatherName('ΚΩΝΣΤΑΝΤΙΝΟΣ')
    ->setMotherName('ΣΟΦΙΑ')
    ->setBirthDate('08/11/1992')
    ->setSex(Sex::FEMALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΜ123456')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')

    // Tax/Insurance
    ->setAfm('777888999')
    ->setTaxOffice('1234')
    ->setAmka('08119212345')

    // Modification date
    ->setModificationDate('01/02/2025')

    // Loan details (required)
    ->setLoanType(LoanType::GENUINE)
    ->setLoanStartDate('01/06/2024')
    ->setLoanEndDate('31/05/2025')
    ->setBorrowingCompanyAfm('333444555')
    ->setBorrowingCompanyName('LENDING COMPANY S.A.')

    // Salary payment (required)
    ->setSalaryPaymentSource(SalaryPaymentSource::DIRECT_EMPLOYER)

    // Employment details
    ->setSpecialtyCode('456789')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)

    // Work schedule change
    ->setWeekHours(40.0)
    ->setFullEmploymentHours(40.0)
    ->setWeekDays(5)
    ->setWorkCardRequired(true)

    ->setComments('Αλλαγή ωραρίου δανειζόμενου εργαζομένου');

$response = (new BorrowedEmploymentModification())->handle($declaration);
```

### MAD: EPA Worker Salary Paid by Indirect Employer

```php
$declaration = BorrowedModificationDeclaration::make()
    // ... branch and personal fields ...

    ->setModificationDate('01/02/2025')

    // EPA loan arrangement
    ->setLoanType(LoanType::EPA)
    ->setLoanStartDate('01/01/2024')
    ->setLoanEndDate('31/12/2024')
    ->setBorrowingCompanyAfm('111222333')
    ->setBorrowingCompanyName('EPA AGENCY LTD')

    // Salary paid by indirect employer (client)
    ->setSalaryPaymentSource(SalaryPaymentSource::INDIRECT_EMPLOYER)
    ->setGrossSalary(1600.00)
    ->setHourlyWage(9.50)
    ->setSalaryPaymentTiming('Μηνιαία')

    // Applied collective agreement
    ->setCollectiveAgreementApplies(true)
    ->setCollectiveAgreementComment('ΣΣΕ ΕΠΑ');
```

---

## Common Fields (Both MA and MAD)

Both declaration types inherit from the same base class and share these common field groups:

::: tip DateTime Support
All date fields accept both `DateTime` objects and strings. When a `DateTime` is passed, it's automatically formatted to DD/MM/YYYY:
```php
$declaration->setBirthDate('15/01/1990');           // String
$declaration->setBirthDate(new DateTime('1990-01-15')); // DateTime
```
:::

### Branch/Location

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int | Yes | Branch sequence number (0 for HQ) |
| `setRelatedProtocol()` | `f_rel_protocol` | string | No | Related submission protocol |
| `setRelatedDate()` | `f_rel_date` | string | No | Related submission date |
| `setLaborInspectionServiceCode()` | `f_ypiresia_sepe` | string | Yes | SEPE service code |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | DYPA/OAED service code |
| `setBranchActivityCode()` | `f_kad_pararthmatos` | string | No | Activity code (KAD) |
| `setKallikratisCode()` | `f_kallikratis_pararthmatos` | string | No | Municipality code |

### Personal Information

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setLastName()` | `f_eponymo` | string | Yes | Last name (uppercase Greek) |
| `setFirstName()` | `f_onoma` | string | Yes | First name (uppercase Greek) |
| `setFatherName()` | `f_onoma_patros` | string | Yes | Father's first name |
| `setMotherName()` | `f_onoma_mitros` | string | Yes | Mother's first name |
| `setBirthDate()` | `f_birthdate` | string | Yes | Birth date (DD/MM/YYYY) |
| `setSex()` | `f_sex` | Sex | Yes | Sex (1=Male, 2=Female) |
| `setMaritalStatus()` | `f_marital_status` | MaritalStatus | No | Marital status |
| `setNumberOfChildren()` | `f_arithmos_teknon` | int | No | Number of children |

### Identity/Nationality

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setNationality()` | `f_yphkoothta` | string | Yes | Nationality code |
| `setIdType()` | `f_typos_taytothtas` | string | Yes | ID document type |
| `setIdNumber()` | `f_ar_taytothtas` | string | Yes | ID document number |
| `setIdIssuingAuthority()` | `f_ekdousa_arxh` | string | No | Issuing authority |
| `setIdIssueDate()` | `f_date_ekdosis` | string | No | Issue date |
| `setIdExpiryDate()` | `f_date_ekdosis_lixi` | string | No | Expiry date |

### Tax/Insurance

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setAfm()` | `f_afm` | string | Yes | Tax ID (9 digits) |
| `setTaxOffice()` | `f_doy` | string | No | Tax office code |
| `setAmika()` | `f_amika` | string | No | IKA number |
| `setAmka()` | `f_amka` | string | Yes | Social security number |
| `setUnemploymentCode()` | `f_code_anergias` | string | No | Unemployment registration code |
| `setMinorBookNumber()` | `f_ar_vivliou_anilikou` | string | No | Minor work permit book number |

### Work Organization

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setDigitalWorkOrganization()` | `f_working_time_digital_organization` | bool | No | Digital scheduling |
| `setUnpredictableSchedule()` | `f_mh_problepsimo_programma` | bool | No | Variable schedule |
| `setOnDemandDaysHours()` | `f_paraggelia_hmeres_hours` | string | No | On-demand scheduling |
| `setOnDemandMinNotification()` | `f_paraggelia_min_notification` | string | No | Minimum notice period |
| `setWeekHours()` | `f_week_hours` | float | No | Weekly hours |
| `setFullEmploymentHours()` | `f_full_employment_hours` | float | No | Full-time equivalent hours |
| `setWeekDays()` | `f_week_days` | int | No | Work days per week |
| `setFlexibleArrivalMinutes()` | `f_euelikto_wrario_minutes` | int | No | Flexible arrival window |
| `setWorkCardRequired()` | `f_working_card` | bool | No | Work card mandatory |
| `setBreakMinutes()` | `f_dialeimma_minutes` | int | No | Break duration |
| `setBreakWithinSchedule()` | `f_dialeimma_entos_wrariou` | bool | No | Break within work hours |

### Work Location

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setWorkLocation()` | `f_topos_ergasias` | string | No | Work location code |
| `setWorkLocationComment()` | `f_topos_ergasias_comments` | string | No | Location details |

### Employment Classification

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setEmploymentStatus()` | `f_kathestosapasxolisis` | EmploymentStatus | No | Full/Part-time |
| `setWorkerType()` | `f_xaraktirismos` | WorkerType | No | Worker/Employee |
| `setHasResponsiblePosition()` | `f_responsible_position` | bool | No | Managerial position |

### Specialty

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setSpecialtyCode()` | `f_eidikothta` | string | No | Specialty code |
| `setSpecialtyDescription()` | `f_eidikothta_anal` | string | No | Specialty description |

### Supplementary Insurance (MA only)

```php
use OxygenSuite\OxygenErgani\Models\Hiring\SupplementaryInsuranceSelection;

$declaration->addSupplementaryInsuranceSelection(
    SupplementaryInsuranceSelection::make()->setCode('201')
);
```

---

## Response Handling

```php
// MA
$response = (new EmploymentModification())->handle($declaration);

// MAD
$response = (new BorrowedEmploymentModification())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'ΜΑ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

After a successful submission, retrieve the official PDF document:

```php
$pdfBase64 = (new EmploymentModification())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

// Save to file
file_put_contents('modification.pdf', base64_decode($pdfBase64));
```

---

## Best Practices

1. **Specify What Changed**: Always include modification type selections (MA) to document exactly what was modified.

2. **Complete Loan Details**: For MAD, always provide all loan details even if unchanged.

3. **Salary Payment Source**: For MAD, correctly indicate who pays the salary.

4. **Reference Period**: When applicable (MA), specify the reference period for the changes.

5. **Settlement Type**: Document whether changes are via collective agreement or individual negotiation.

6. **Keep Original E3**: Modifications reference the original hiring — ensure the E3 submission exists.

---

## Important Notes

1. **MA vs MAD**: Use MA for direct employees, MAD for borrowed/loaned employees.

2. **Modification Types**: MA requires at least one modification type selection.

3. **Loan Details**: MAD requires loan details; they're optional for MA.

4. **Salary Payment**: MAD requires specifying who pays the salary.

5. **Common Base**: Both forms share the same personal information and identity fields.

6. **No Form File**: MAD doesn't have `f_file` — only foreign/young files.

---

## See Also

- [New Hire (E3N)](/guide/hiring/new) - Original hiring submission
- [Lending (E3D)](/guide/hiring/lending) - Employee lending arrangements
- [Borrowed (E3PD)](/guide/hiring/borrowed) - Receiving borrowed employees
- [End of Loan (E6LD)](/guide/dismissal/end-of-loan) - Ending loan arrangements
