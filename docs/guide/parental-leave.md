# Parental Leave (E14)

The E14 form is the employer's declaration that parental leave was granted to an employee (article 28 of Law 4808/2021). A correction variant (WebE14C) corrects or changes a previous declaration.

## Overview

| Property | Value |
|----------|-------|
| Document Classes | `ParentalLeave`, `ParentalLeaveCorrection` |
| Action Codes | `WebE14`, `WebE14C` |
| Declaration Model | `ParentalLeaveDeclaration` |
| Nested Models | `ParentalLeaveChild` (children), `ParentalLeaveDate` (leave dates) |
| Schema | `E14_v1.xsd` |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\LeaveGrantMethod;
use OxygenSuite\OxygenErgani\Enums\WeekDays;
use OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave\ParentalLeave;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveChild;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDate;
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDeclaration;

$declaration = ParentalLeaveDeclaration::make()
    ->setBranchCode(0)
    ->setRelatedProtocol('')
    ->setRelatedDate('')
    ->setLaborInspectionCode('11050')
    ->setDypaServiceCode('101213')
    ->setPaidByEntity(false)
    ->setCategory('')
    ->setAfm('888888888')
    ->setAmka('01010101010')
    ->setAma('53454486')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΥ')
    ->setFirstName('ΜΑΡΙΑ')
    ->setHireDate('12/09/2019')
    ->setBetterArrangementExists(false)
    ->setBetterArrangementDescription('')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setWeekDays(WeekDays::FIVE_DAY)
    ->setWeeklyHours(40.0)
    ->setMainInsuranceAgency('e-ΕΦΚΑ')
    ->setSupplementaryInsuranceAgency('e-ΕΦΚΑ')
    ->setSingleParent(false)
    ->setSingleParentCase('')
    ->setMultipleBirth(false)
    ->setGrantMethod(LeaveGrantMethod::FLEXIBLE)
    ->setLeavePeriod('23/10/2026', '27/10/2026')
    ->setComments('')
    ->setLegalRepresentativeAfm('078776545')
    ->addChild(
        ParentalLeaveChild::make()
            ->setAmka('01010101010')
            ->setLeaveGranted(true)
            ->setMultipleBirth(false)
    )
    // Hourly leave inside the day's schedule
    ->addDate(
        ParentalLeaveDate::make()
            ->setDate('23/10/2026')
            ->setHours('12:00', '17:00')
            ->setSchedule('10:00', '18:00')
    )
    // Full-day leave
    ->addDate(ParentalLeaveDate::make()->setDate('26/10/2026')->setFullDay());

$response = (new ParentalLeave())->handle($declaration);
```

::: tip All fields are sent
Most E14 fields are required by the schema even when empty (e.g. `f_rel_protocol`, `f_category`, `f_better_setting_descr`). Set them to `''` as above, or call `withDefaults()` on the models to fill any missing field with an empty string.
:::

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;

$ergani = new Ergani($accessToken);
$responses = $ergani->sendParentalLeave($declaration);
```

## Corrections (WebE14C)

To correct or change a submitted declaration, send the full declaration again with the protocol and date of the original submission:

```php
use OxygenSuite\OxygenErgani\Http\Documents\ParentalLeave\ParentalLeaveCorrection;

$declaration
    ->setRelatedProtocol('ΕΡΓ-12345')
    ->setRelatedDate('15/10/2026');

$response = (new ParentalLeaveCorrection())->handle($declaration);
// or: $ergani->sendParentalLeaveCorrection($declaration);
```

## Field Reference

### Declaration (ParentalLeaveDeclaration)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number (0 for HQ) |
| `setRelatedProtocol()` | `f_rel_protocol` | string | Yes (empty) | Protocol of the declaration being corrected |
| `setRelatedDate()` | `f_rel_date` | DateTime\|string | Yes (empty) | Date of the declaration being corrected |
| `setLaborInspectionCode()` | `f_ypiresia_sepe` | string | Yes | Labour inspectorate service code (5 digits) |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | DYPA service code (6 digits) |
| `setPaidByEntity()` | `f_payment_from_forea` | string\|bool | Yes | Leave paid by the entity itself (art. 56 & 60, Law 4830/2021) |
| `setCategory()` | `f_category` | ParentalLeaveCategory\|string | Yes (empty) | Employee category, when not paid by the entity |
| `setAfm()` | `f_afm_ergazom` | string | Yes | Employee tax ID |
| `setAmka()` | `f_amka_ergazom` | string | Yes | Employee AMKA |
| `setAma()` | `f_am_ika_ergazom` | string | Yes | e-EFKA registry number |
| `setLastName()` | `f_eponimo` | string | Yes | Last name |
| `setFirstName()` | `f_onoma` | string | Yes | First name |
| `setHireDate()` | `f_date_proslipsis` | DateTime\|string | Yes | Hire date (DD/MM/YYYY) |
| `setBetterArrangementExists()` | `f_better_setting_exists` | string\|bool | Yes | More favourable arrangement exists (art. 28 par. 2) |
| `setBetterArrangementDescription()` | `f_better_setting_descr` | string | Yes (empty) | Description of the arrangement (max 100) |
| `setBetterArrangement()` | both of the above | string | - | Shortcut: sets exists = 1 and the description |
| `setEmploymentStatus()` | `f_kathestos_apasxolisis` | EmploymentStatus\|string\|int | Yes | 0=Full, 1=Part-time, 2=Rotational (`ON_DEMAND` not accepted) |
| `setEmploymentType()` | `f_sxesh_apasxolisis` | EmploymentType\|string\|int | Yes | 0=Indefinite, 1=Fixed-term, 2=Project (`BORROWED` not accepted) |
| `setWeekDays()` | `f_week_days` | WeekDays\|string\|int | Yes | 5 or 6 working days |
| `setWeeklyHours()` | `f_week_hours` | float | Yes | Weekly hours (Greek float, 1 decimal) |
| `setMainInsuranceAgency()` | `f_foreas_kyrias_asfalisis` | string | Yes | Main insurance agency (free text) |
| `setSupplementaryInsuranceAgency()` | `f_foreas_epikourikhs_asfalisis` | string | Yes | Supplementary insurance agency (free text) |
| `setSingleParent()` | `f_one_parent` | string\|bool | Yes | Single parent (art. 28 par. 3) |
| `setSingleParentCase()` | `f_one_parent_case` | string | Yes (empty) | Single parent case code (`ParameterLookup::SINGLE_PARENT_CASE`) |
| `setMultipleBirth()` | `f_tekno_from_polydymh_kyhsh` | string\|bool | Yes | Child from a multiple birth |
| `setGrantMethod()` | `f_tropos_xorhghshs_adeias` | LeaveGrantMethod\|string | Yes | 0=Continuous, 1=Partial/flexible |
| `setLeaveStartDate()` | `f_date_enarxis_adeias` | DateTime\|string | Yes | Leave start date |
| `setLeaveEndDate()` | `f_date_lhxhs_adeias` | DateTime\|string | Yes | Leave end date |
| `setLeavePeriod()` | both of the above | DateTime\|string | - | Shortcut for start and end dates |
| `setComments()` | `f_comments` | string | Yes (empty) | Comments (max 200) |
| `setLegalRepresentativeAfm()` | `f_afm_proswpoy` | string | Yes | Legal representative tax ID |
| `addChild()` / `setChildren()` | `Amkas.AmkasE14` | ParentalLeaveChild | Yes (1+) | Children |
| `addDate()` / `setDates()` | `Dates.DatesE14` | ParentalLeaveDate | No | Leave dates (flexible leave) |

### Child (ParentalLeaveChild)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setAmka()` | `f_amka` | string | Yes | Child AMKA |
| `setLeaveGranted()` | `f_xorhghsh_adeias` | string\|bool | Yes | Leave is granted for this child |
| `setMultipleBirth()` | `f_polydymh_kyhsh` | string\|bool | Yes | Child of a multiple birth |

### Leave Date (ParentalLeaveDate)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setDate()` | `f_date_leave` | DateTime\|string | Yes | Leave date |
| `setHourFrom()` / `setHourTo()` | `f_hour_from`, `f_hour_to` | string | No | Leave hours (HH:MM) |
| `setHours()` | both of the above | string | - | Shortcut for leave hours |
| `setScheduleFrom()` / `setScheduleTo()` | `f_orario_from`, `f_orario_to` | string | No | Employee's schedule that day (HH:MM) |
| `setSchedule()` | both of the above | string | - | Shortcut for the schedule |
| `setFullDay()` | `f_plires_orario` | string\|bool | No | Leave covers the full schedule (defaults to `true`) |

See [Employment Enums](/api/enums/employment) for `ParentalLeaveCategory` and `LeaveGrantMethod`.

## Response Handling

```php
$response = (new ParentalLeave())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

```php
$pdfBase64 = (new ParentalLeave())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

file_put_contents('parental-leave.pdf', base64_decode($pdfBase64));
```

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\ParentalLeave\ParentalLeaveDeclaration;

$declaration = ParentalLeaveDeclaration::factory()->make();

$declaration = ParentalLeaveDeclaration::factory()
    ->forBranch(1)
    ->partTime(20.0)
    ->singleParent('001')
    ->withChildren(2)
    ->flexible(3)                         // 3 full-day leave dates
    ->make();

$correction = ParentalLeaveDeclaration::factory()
    ->asCorrection('ΕΡΓ-12345', '15/10/2026')
    ->make();
```

## See Also

- [Work Time](/guide/work-time) - Leave entries in work time organization
- [Services & Queries](/guide/services) - Parameter lookups (`SINGLE_PARENT_CASE`)
- [Cancel Submissions](/guide/cancel-submissions) - Cancel a submitted declaration
