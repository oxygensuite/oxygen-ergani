# Construction Work Documents (E12)

The E12 forms are used for construction work (oikodomiko ergo / techniko ergo) personnel declarations. There are two variants:
- **E12**: Daily personnel declaration for construction work
- **E12Apogr**: Census (periodic summary) declaration for construction work

## Overview

| Form | Action Code | Container Model | Employee Model | Use Case |
|------|-------------|-----------------|----------------|----------|
| E12 | `E12` | `ConstructionWork` | `ConstructionEmployee` | Daily personnel declaration |
| E12Apogr | `E12Apogr` | `ConstructionCensus` | `ConstructionCensusEmployee` | Periodic census declaration |

## E12 vs E12Apogr

| Feature | E12 | E12Apogr |
|---------|-----|----------|
| Granularity | Per-day, per-employee | Monthly summary |
| Employee Time | Date + from/to times | Days worked count |
| Cancellation | Per-entry cancellation flag | Not applicable |
| Year/Month | Not included (implicit from dates) | Required fields |

---

## E12: Construction Work Declaration

### Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Construction\ConstructionWorkDeclaration;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionWork;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionEmployee;

$employee = ConstructionEmployee::make()
    ->setAfm('123456789')
    ->setAmka('15038512345')
    ->setAma('12345678')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setDate('15/01/2025')
    ->setTimeFrom('07:00')
    ->setTimeTo('15:00')
    ->setCancellation(false)
    ->setSpecialtyCode('123456')
    ->setHireDate('01/01/2024')
    ->setGrossDailyWage(80.00);

$declaration = ConstructionWork::make()
    ->setBranchCode(0)
    ->setAmoe('ΑΜΟΕ123456')
    ->setDateFrom('15/01/2025')
    ->setDateTo('15/01/2025')
    ->setLaborInspectionCode('12345')
    ->setMunicipalityCode('0101')
    ->addEmployee($employee);

$response = (new ConstructionWorkDeclaration())->handle($declaration);
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);
$responses = $ergani->sendConstructionWork($declaration);
```

### Container Fields (ConstructionWork)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number |
| `setAmoe()` | `f_amoe` | string | Yes | AMOE registration number |
| `setRelatedProtocol()` | `f_rel_protocol` | string | No | Related submission protocol |
| `setRelatedDate()` | `f_rel_date` | DateTime\|string | No | Related submission date |
| `setDateFrom()` | `f_date_from` | DateTime\|string | Yes | Period start date (DD/MM/YYYY) |
| `setDateTo()` | `f_date_to` | DateTime\|string | Yes | Period end date (DD/MM/YYYY) |
| `setPhase()` | `f_phase` | string | No | Construction phase |
| `setLaborInspectionCode()` | `f_ypiresia_sepe` | string | Yes | SEPE service code |
| `setMunicipalityCode()` | `f_kallikratis_pararthmatos` | string | No | Municipality code |
| `setComments()` | `f_comments` | string | No | Additional comments |

### Employee Fields (ConstructionEmployee)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setAfm()` | `f_afm` | string | Yes | Employee tax ID |
| `setAmka()` | `f_amka` | string | Yes | Social security number |
| `setAma()` | `f_ama` | string | No | IKA insurance number |
| `setLastName()` | `f_eponymo` | string | Yes | Last name |
| `setFirstName()` | `f_onoma` | string | Yes | First name |
| `setFatherName()` | `f_onoma_patera` | string | Yes | Father's name |
| `setDate()` | `f_date` | DateTime\|string | Yes | Work date (DD/MM/YYYY) |
| `setTimeFrom()` | `f_from` | string | Yes | Shift start time (HH:MM) |
| `setTimeTo()` | `f_to` | string | Yes | Shift end time (HH:MM) |
| `setCancellation()` | `f_cancellation` | string\|bool | No | Cancel this entry (0/1) |
| `setSpecialtyCode()` | `f_step` | string | Yes | Specialty code |
| `setWorkPermitNumber()` | `f_ar_adeias` | string | No | Work permit number |
| `setHireDate()` | `f_hire_date` | DateTime\|string | Yes | Hire date (DD/MM/YYYY) |
| `setGrossDailyWage()` | `f_apodoxes` | float | Yes | Gross daily wage (Greek float) |
| `setNotes()` | `f_notes` | string | No | Notes |

### Managing Employees

```php
// Add one at a time
$declaration->addEmployee($employee1);
$declaration->addEmployee($employee2);

// Or set all at once
$declaration->setEmployees([$employee1, $employee2]);

// Access employees
$employees = $declaration->getEmployees();
$first = $declaration->getEmployee(0);
```

---

## E12Apogr: Construction Work Census

### Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Construction\ConstructionWorkCensus;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensus;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensusEmployee;

$employee = ConstructionCensusEmployee::make()
    ->setAfm('123456789')
    ->setAmka('15038512345')
    ->setAma('12345678')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setDaysWorked(22)
    ->setSpecialtyCode('123456')
    ->setHireDate('01/01/2024')
    ->setGrossEarnings(1500.00);

$declaration = ConstructionCensus::make()
    ->setBranchCode(0)
    ->setAmoe('ΑΜΟΕ123456')
    ->setDateFrom('01/01/2025')
    ->setDateTo('31/01/2025')
    ->setYear(2025)
    ->setMonth(1)
    ->setLaborInspectionCode('12345')
    ->setMunicipalityCode('0101')
    ->addEmployee($employee);

$response = (new ConstructionWorkCensus())->handle($declaration);
```

### Via Ergani Facade

```php
$ergani = new Ergani($accessToken);
$responses = $ergani->sendConstructionWorkCensus($declaration);
```

### Container Fields (ConstructionCensus)

Same as `ConstructionWork` plus:

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setYear()` | `f_year` | string\|int | Yes | Census year |
| `setMonth()` | `f_month` | string\|int | Yes | Census month |

### Employee Fields (ConstructionCensusEmployee)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setAfm()` | `f_afm` | string | Yes | Employee tax ID |
| `setAmka()` | `f_amka` | string | Yes | Social security number |
| `setAma()` | `f_ama` | string | No | IKA insurance number |
| `setLastName()` | `f_eponymo` | string | Yes | Last name |
| `setFirstName()` | `f_onoma` | string | Yes | First name |
| `setFatherName()` | `f_onoma_patera` | string | Yes | Father's name |
| `setDaysWorked()` | `f_days_worked` | int\|string | Yes | Total days worked in period |
| `setSpecialtyCode()` | `f_step` | string | Yes | Specialty code |
| `setWorkPermitNumber()` | `f_ar_adeias` | string | No | Work permit number |
| `setHireDate()` | `f_hire_date` | DateTime\|string | Yes | Hire date (DD/MM/YYYY) |
| `setGrossEarnings()` | `f_apodoxes` | float | Yes | Gross earnings for period (Greek float) |
| `setNotes()` | `f_notes` | string | No | Notes |

---

## Response Handling

```php
// E12
$response = (new ConstructionWorkDeclaration())->handle($declaration);

// E12Apogr
$response = (new ConstructionWorkCensus())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

```php
$pdfBase64 = (new ConstructionWorkDeclaration())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

file_put_contents('construction-work.pdf', base64_decode($pdfBase64));
```

---

## Best Practices

1. **AMOE Number**: Always provide the correct AMOE registration number for the construction site.

2. **Municipality Code**: Use the Kallikratis code for the construction site location, not the company's registered address.

3. **Daily vs Census**: Use E12 for daily personnel tracking and E12Apogr for monthly summary reports.

4. **Cancellation**: To cancel an individual employee entry in E12, resubmit with `setCancellation(true)` rather than cancelling the entire declaration.

5. **Greek Float**: Wage fields (`f_apodoxes`) are automatically formatted as Greek floats (e.g., `1.500,00`).

## Testing with Factories

All construction models support factories for generating test data:

```php
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionWork;
use OxygenSuite\OxygenErgani\Models\Construction\ConstructionCensus;

// E12 - Construction Work Declaration
$work = ConstructionWork::factory()->make();

// With state methods
$work = ConstructionWork::factory()
    ->mainBranch()
    ->withPhase('Foundation')
    ->withEmployees(3)
    ->make();

// E12Apogr - Construction Work Census
$census = ConstructionCensus::factory()
    ->forPeriod(1, 2026)
    ->withEmployees(5)
    ->make();

// Employee factories
$employee = ConstructionEmployee::factory()
    ->withDailyWage(120.00)
    ->withHours('07:00', '15:00')
    ->make();

$censusEmployee = ConstructionCensusEmployee::factory()
    ->withDaysWorked(22)
    ->withGrossEarnings(1760.00)
    ->make();
```

---

## See Also

- [Work Time](/guide/work-time) - Regular work time organization
- [New Hire (E3N)](/guide/hiring/new) - New employee hiring
- [Services & Queries](/guide/services) - Parameter lookups
