# Sixth Day Declaration (SixthDay)

The SixthDay form is used to declare employment for an extra (6th day) shift, typically for businesses with continuous operation that need employees to work on what would normally be a rest day.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `SixthDay` |
| Action Code | `SixthDay` |
| Declaration Model | `SixthDayDeclaration` |
| Use Case | Declaring extra shift / 6th working day |

## When to Use

Use this form when:
- An employee must work on their 6th day (normally a rest day)
- The business operates continuously and requires extra shifts
- A special occasion requires additional staffing

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\SixthDay\SixthDay;
use OxygenSuite\OxygenErgani\Models\SixthDay\SixthDayDeclaration;

$declaration = SixthDayDeclaration::make()
    ->setBranchCode(0)
    ->setContinuousOperation(true)
    ->setMainActivityCode('5610')
    ->setSpecialOccasionDescription('Αυξημένη ζήτηση λόγω εορτών')
    ->setDateFrom('20/12/2025')
    ->setDateTo('20/12/2025')
    ->setComments('Έκτακτη βάρδια Σαββάτου');

$response = (new SixthDay())->handle($declaration);
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Models\SixthDay\SixthDayDeclaration;

$ergani = new Ergani($accessToken);

$declaration = SixthDayDeclaration::make()
    ->setBranchCode(0)
    ->setContinuousOperation(true)
    ->setMainActivityCode('5610')
    ->setSpecialOccasionDescription('Αυξημένη ζήτηση')
    ->setDateFrom('20/12/2025')
    ->setDateTo('20/12/2025');

$responses = $ergani->sendSixthDayDeclaration($declaration);
```

## Field Reference

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number (0 for HQ) |
| `setContinuousOperation()` | `f_continuous_operation` | string\|bool | Yes | Whether business has continuous operation (0/1) |
| `setMainActivityCode()` | `f_kad_kyria` | string | Yes | Main activity code (KAD) |
| `setSpecialOccasionDescription()` | `f_special_occasion_description` | string | No | Description of the special occasion |
| `setDateFrom()` | `f_date_special_from` | DateTime\|string | Yes | Start date (DD/MM/YYYY) |
| `setDateTo()` | `f_date_special_to` | DateTime\|string | Yes | End date (DD/MM/YYYY) |
| `setComments()` | `f_comments` | string | No | Additional comments |

::: tip DateTime Support
Date fields accept both `DateTime` objects and strings:
```php
$declaration->setDateFrom('20/12/2025');                    // String
$declaration->setDateFrom(new DateTime('2025-12-20'));      // DateTime
```
:::

::: info Activity Codes
Use the `SixthDayKAD` parameter type with `getParameters()` to retrieve valid activity codes for sixth day declarations:
```php
$codes = $ergani->getParameters(ParameterLookup::SIXTH_DAY_KAD);
```
:::

## Response Handling

```php
$response = (new SixthDay())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

After a successful submission, retrieve the official PDF document:

```php
$pdfBase64 = (new SixthDay())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

// Save to file
file_put_contents('sixth-day.pdf', base64_decode($pdfBase64));
```

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\SixthDay\SixthDayDeclaration;

$declaration = SixthDayDeclaration::factory()->make();

// With state methods
$declaration = SixthDayDeclaration::factory()
    ->mainBranch()
    ->continuousOperation()
    ->forDateRange('21/02/2026', '21/02/2026')
    ->make();

// Special occasion
$declaration = SixthDayDeclaration::factory()
    ->withSpecialOccasion('Urgent maintenance')
    ->make();
```

---

## See Also

- [Work Time](/guide/work-time) - Regular work time declarations
- [Services & Queries](/guide/services) - Parameter lookups for activity codes
