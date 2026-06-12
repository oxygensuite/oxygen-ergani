# Pre-Announcement Exemption (ExProan)

The ExProan form is used to declare an exemption from pre-announcement obligations. Businesses can declare that a specific branch is excluded from certain pre-announcement requirements for a given month.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `PreAnnouncementExemption` |
| Action Code | `ExProan` |
| Declaration Model | `ExemptionDeclaration` |
| Use Case | Declaring exemption from pre-announcement obligations |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\PreAnnouncement\PreAnnouncementExemption;
use OxygenSuite\OxygenErgani\Models\PreAnnouncement\ExemptionDeclaration;

$declaration = ExemptionDeclaration::make()
    ->setBranchCode(0)
    ->setIsExcluded(true)
    ->setMonth(1)
    ->setYear(2025)
    ->setComments('Εξαίρεση λόγω αναστολής λειτουργίας');

$response = (new PreAnnouncementExemption())->handle($declaration);
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Models\PreAnnouncement\ExemptionDeclaration;

$ergani = new Ergani($accessToken);

$declaration = ExemptionDeclaration::make()
    ->setBranchCode(0)
    ->setIsExcluded(true)
    ->setMonth(1)
    ->setYear(2025);

$responses = $ergani->sendPreAnnouncementExemption($declaration);
```

## Field Reference

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number (0 for HQ) |
| `setIsExcluded()` | `f_is_excluded` | string\|bool | Yes | Whether the branch is excluded (0/1) |
| `setMonth()` | `f_month` | string\|int | Yes | Month (1-12, auto zero-padded) |
| `setYear()` | `f_year` | string\|int | Yes | Year (e.g., 2025) |
| `setComments()` | `f_comments` | string | No | Additional comments |

::: info Month Auto-Padding
The `setMonth()` method automatically zero-pads single-digit months:
```php
$declaration->setMonth(1);   // Stored as "01"
$declaration->setMonth(12);  // Stored as "12"
```
:::

## Response Handling

```php
$response = (new PreAnnouncementExemption())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

After a successful submission, retrieve the official PDF document:

```php
$pdfBase64 = (new PreAnnouncementExemption())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

// Save to file
file_put_contents('pre-announcement-exemption.pdf', base64_decode($pdfBase64));
```

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\PreAnnouncement\ExemptionDeclaration;

$declaration = ExemptionDeclaration::factory()->make();

// With state methods
$declaration = ExemptionDeclaration::factory()
    ->mainBranch()
    ->excluded()
    ->forPeriod(2, 2026)
    ->make();

// Not excluded
$declaration = ExemptionDeclaration::factory()
    ->notExcluded()
    ->make();
```

---

## See Also

- [Work Time](/guide/work-time) - Work time organization declarations
- [Sixth Day](/guide/sixth-day) - Extra shift declarations
