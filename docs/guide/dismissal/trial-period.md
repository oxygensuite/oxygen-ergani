# Trial Period Termination (E6LT)

The E6LT form is used when employment terminates at the end of a trial period. This occurs when either party decides not to continue the employment after the trial period expires.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `TrialPeriodTermination` |
| Action Code | `WebE6LT` |
| Declaration Model | `TrialPeriodTerminationDeclaration` |
| Use Case | Trial period ends without continuation |

## When to Use E6LT

Use E6LT when:
- An employee's trial period has ended
- The employer decides not to continue the employment
- The employee decided not to continue during/after trial
- No severance compensation is required

::: info No Compensation
Trial period terminations do not require severance payment, as both parties understood the conditional nature of the employment.
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\TrialPeriodTermination;
use OxygenSuite\OxygenErgani\Models\Dismissal\TrialPeriodTerminationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;

$declaration = TrialPeriodTerminationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1995')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15039512345')

    // Employment Dates
    ->setHiringDate('01/10/2024')       // Trial started
    ->setTerminationDate('31/12/2024')  // Trial ended

    // Salary (no compensation required)
    ->setGrossSalary(1500.00)

    // Termination document
    ->setFormFile(base64_encode(file_get_contents('trial_end.pdf')));

$response = (new TrialPeriodTermination())->handle($declaration);
```

## E6LT-Specific Fields

In addition to [common fields](./index#common-fields), E6LT includes:

### Employment Dates

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setHiringDate()` | `f_proslipsidate` | string | Yes | Trial start date |
| `setTerminationDate()` | `f_apolysisdate` | string | Yes | Trial end date |

### Salary (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary during trial |

### Form File (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Termination document (Base64 PDF) |

::: warning No Compensation Field
E6LT does NOT include `setCompensationAmount()`. Severance is not required for trial period terminations.
:::

::: warning No Employment Classification
E6LT does NOT include employment classification fields (employment status, worker type, specialty) that other E6 forms have.
:::

## Complete Example

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\TrialPeriodTermination;
use OxygenSuite\OxygenErgani\Models\Dismissal\TrialPeriodTerminationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = TrialPeriodTerminationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal - New employee
    ->setLastName('ΓΕΩΡΓΙΟΥ')
    ->setFirstName('ΑΛΕΞΑΝΔΡΑ')
    ->setFatherName('ΠΕΤΡΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('12/05/1998')
    ->setSex(Sex::FEMALE)
    ->setMaritalStatus(MaritalStatus::SINGLE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ654321')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')

    // Tax/Insurance
    ->setAfm('987654321')
    ->setTaxOffice('1234')
    ->setAmka('12059812345')

    // Trial period dates (6 months typical)
    ->setHiringDate('01/07/2024')
    ->setTerminationDate('31/12/2024')

    // Salary for the trial period
    ->setGrossSalary(1400.00)

    // Comments
    ->setComments('Λήξη δοκιμαστικής περιόδου')

    // Documentation
    ->setFormFile(base64_encode(file_get_contents('trial_termination.pdf')));

$response = (new TrialPeriodTermination())->handle($declaration);
```

## Response Handling

```php
$response = (new TrialPeriodTermination())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε6ΛΤ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Trial Period Guidelines

Greek labor law allows trial periods up to:

| Employment Type | Maximum Trial Period |
|----------------|---------------------|
| Indefinite term | 12 months |
| Fixed term | Varies (typically proportional) |

During the trial period:
- Either party can terminate without notice
- No severance compensation is required
- Normal labor protections still apply

## Important Notes

1. **No Severance**: Unlike E6NXP/E6NMP, trial period termination requires no compensation.

2. **Simple Form**: E6LT is simpler than other E6 forms - no employment classification or collective dismissal fields.

3. **Documentation**: Still requires a signed termination document.

4. **Timing**: Must be submitted at the end of the trial period, not before.

5. **E3N Reference**: The original E3N hiring should have indicated a trial period.

## See Also

- [Dismissal Overview](./index) - Common fields and enums
- [New Hire (E3N)](/guide/hiring/new) - Setting up trial period during hiring
- [Dismissal Without Notice (E6NXP)](./without-notice) - For dismissals after trial period
