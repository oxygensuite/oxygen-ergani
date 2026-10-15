# Urgent Hiring (FastAna)

The FastAna form ("Πρόσληψη για κάλυψη επειγουσών αναγκών") is a short hiring announcement used when an employee is hired to cover urgent needs. It records the first (and optionally a second) working day with start and end times, the specialty, earnings and insurance.

## Overview

| Property | Value |
|----------|-------|
| Document Classes | `UrgentHiring`, `UrgentHiringCorrection` |
| Action Codes | `FastAna`, `FastAnaC` (correction / change) |
| Declaration Model | `UrgentHiringDeclaration` |
| Schema | `FastAna_v1.xsd` |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Enums\WeekDays;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\WorkLocation;
use OxygenSuite\OxygenErgani\Http\Documents\UrgentHiring\UrgentHiring;
use OxygenSuite\OxygenErgani\Models\UrgentHiring\UrgentHiringDeclaration;

$declaration = UrgentHiringDeclaration::make()
    ->setBranchCode(0)
    ->setRelatedProtocol('')
    ->setRelatedDate('')
    ->setBranchActivityCode('4673')
    ->setMunicipalityCode('91790101')
    ->setAfm('151619217')
    ->setPhone('6912345678')
    ->setHiringDate('06/04/2026')
    ->setStartTime('09:00')
    ->setEndTime('11:00')
    ->setSecondWorkingDay('07/04/2026', '09:00', '12:00')
    ->setSpecialtyCode('313200')
    ->setSpecialtyDescription('')
    ->setExperienceYears(1)
    ->setGrossSalary(1500.00)
    ->setWagePaymentTime('1')
    ->setWorkLocation(WorkLocation::EMPLOYER_BRANCH)
    ->setWorkLocationComment('')
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setCollectiveAgreementApplies(false)
    ->setCollectiveAgreementComment('')
    ->setMainInsurance('001')
    ->addSupplementaryInsurance('001')
    ->setWeekDays(WeekDays::FIVE_DAY)
    ->setBreakMinutes(30)
    ->setBreakWithinSchedule(true)
    ->setFlexibleArrivalMinutes(15)
    ->setComments('');

$response = (new UrgentHiring())->handle($declaration);
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);

$responses = $ergani->sendUrgentHiring($declaration);
```

### Correction / Change (FastAnaC)

To correct or change a submitted declaration, reference the original submission and send the full declaration again:

```php
$declaration
    ->setRelatedProtocol($original->protocol)
    ->setRelatedDate($original->submissionDate);

$responses = $ergani->sendUrgentHiringCorrection($declaration);
```

## Field Reference

All fields are required by the XSD; send an empty string for fields that do not apply (e.g. the second working day).

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number (0 for HQ) |
| `setRelatedProtocol()` | `f_rel_protocol` | string | Yes | Original protocol (corrections only, else empty) |
| `setRelatedDate()` | `f_rel_date` | DateTime\|string | Yes | Original submission date (corrections only, else empty) |
| `setBranchActivityCode()` | `f_kad_pararthmatos` | string | Yes | Branch activity code (KAD, 4 digits) |
| `setMunicipalityCode()` | `f_kallikratis_pararthmatos` | string | Yes | Kallikratis municipal/local community code (8 digits) |
| `setAfm()` | `f_afm` | string | Yes | Employee tax ID (9 digits) |
| `setPhone()` | `f_til` | string | Yes | Employee mobile phone |
| `setHiringDate()` | `f_proslipsidate` | DateTime\|string | Yes | Hiring date (DD/MM/YYYY) |
| `setStartTime()` | `f_proslipsitime` | string | Yes | Start time on the hiring day (HH:MM) |
| `setEndTime()` | `f_apoxwrisitime` | string | Yes | End time on the hiring day (HH:MM) |
| `setSecondWorkingDate()` | `f_second_working_date` | DateTime\|string | Yes | Second working day (DD/MM/YYYY, or empty) |
| `setSecondStartTime()` | `f_second_working_datetime` | string | Yes | Start time on the second day (HH:MM, or empty) |
| `setSecondEndTime()` | `second_working_datetime_apoxwrisis` | string | Yes | End time on the second day (HH:MM, or empty) |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty code (`ParameterLookup::SPECIALTY`) |
| `setSpecialtyDescription()` | `f_eidikothta_anal` | string | Yes | Specialty description (may be empty) |
| `setExperienceYears()` | `f_proipiresia` | int | Yes | Prior experience in years |
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Total gross earnings (Greek float) |
| `setWagePaymentTime()` | `f_xronos_katavolis_apodoxon` | string | Yes | 0 = every day, 1 = on the last day |
| `setWorkLocation()` | `f_topos_ergasias` | WorkLocation\|string\|int | Yes | 0 = employer's branch, 1 = other |
| `setWorkLocationComment()` | `f_topos_ergasias_comment` | string | Yes | Work location notes |
| `setWorkerType()` | `f_xaraktirismos` | WorkerType\|string\|int | Yes | 0 = worker, 1 = employee |
| `setCollectiveAgreementApplies()` | `f_efarmostea_sillogiki_simbasi` | string\|bool | Yes | Applicable collective agreement (0/1) |
| `setCollectiveAgreementComment()` | `f_efarmostea_sillogiki_simbasi_comments` | string | Yes | Collective agreement notes |
| `setMainInsurance()` | `f_kyria_asfalisi` | string | Yes | Main insurance code (`ParameterLookup::PRIMARY_INSURANCE`) |
| `addSupplementaryInsurance()` | `EpikourikiSelections` | SupplementaryInsuranceSelection\|string | No | Supplementary insurance code (`ParameterLookup::SUPPLEMENTARY_INSURANCE`) |
| `setWeekDays()` | `f_week_days` | WeekDays\|string\|int | Yes | 5 = five-day, 6 = six-day week |
| `setBreakMinutes()` | `f_dialeimma_minutes` | int | Yes | Break duration in minutes |
| `setBreakWithinSchedule()` | `f_dialeimma_entos_wrariou` | string\|bool | Yes | Break within working hours (0/1) |
| `setFlexibleArrivalMinutes()` | `f_euelikto_wrario_minutes` | int | Yes | Flexible arrival allowance in minutes |
| `setComments()` | `f_comments` | string | Yes | Comments (max 100 characters) |

Convenience: `setSecondWorkingDay($date, $startTime, $endTime)` sets the three second-day fields at once.

::: warning ERGANI sample typo
ERGANI's `testFastAn.json` / `testFastAn.xml` samples use `f_efarmoste_sillogiki_simbasi`. The XSD and the live schema use `f_efarmostea_sillogiki_simbasi`, which is what this package sends.
:::

## Response Handling

```php
$response = (new UrgentHiring())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

```php
$pdfBase64 = (new UrgentHiring())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

file_put_contents('urgent-hiring.pdf', base64_decode($pdfBase64));
```

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\UrgentHiring\UrgentHiringDeclaration;

$declaration = UrgentHiringDeclaration::factory()->make();

$declaration = UrgentHiringDeclaration::factory()
    ->forBranch(1)
    ->withSecondWorkingDay('07/04/2026', '09:00', '13:00')
    ->asEmployee()
    ->withCollectiveAgreement('ΕΓΣΣΕ')
    ->withSupplementaryInsurance(['001'])
    ->make();

// Correction
$correction = UrgentHiringDeclaration::factory()
    ->asCorrection('ΑΠ123', '06/04/2026')
    ->make();
```

## See Also

- [New Hire (E3N)](/guide/hiring/new) - Standard hiring declaration
- [Services & Queries](/guide/services) - Parameter lookups
- [Cancel Submissions](/guide/cancel-submissions)
