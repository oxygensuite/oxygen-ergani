# Work Cards

Work Cards (WRKCardSE) track employee check-ins and check-outs at the workplace. Each submission records when employees start or end their work shifts, enabling compliance with Greek labor law requirements.

## Overview

The Work Card system uses a hierarchical structure:

- **Card**: Represents a submission for a specific employer branch
- **CardDetail**: Individual employee check-in or check-out entries

A single submission can contain multiple `Card` objects (for different branches), and each `Card` can contain multiple `CardDetail` entries (for different employees).

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

$card = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0)
    ->addDetails(
        CardDetail::make()
            ->setTin('123456789')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T08:00:00.000+02:00')
    );

$response = (new WorkCard())->handle($card);
```

## Card Model

The `Card` model represents a work card submission for a specific employer branch.

### Fields Reference

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setEmployerTin()` | `f_afm_ergodoti` | string | Yes | Employer's Tax Identification Number (AFM), 9 digits |
| `setBranchCode()` | `f_aa` | int | Yes | Branch serial number. Use `0` for the main establishment |
| `setComments()` | `f_comments` | string | No | Optional comments for the submission |
| `addDetails()` | `Details` | array | Yes | Array of CardDetail entries |

### Example

```php
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;

$card = Card::make()
    ->setEmployerTin('999999999')  // 9-digit AFM
    ->setBranchCode(0)              // 0 = main branch
    ->setComments('Daily check-ins for warehouse staff');
```

## CardDetail Model

Each `CardDetail` represents one employee's check-in or check-out event.

### Fields Reference

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setTin()` | `f_afm` | string | Yes | Employee's Tax Identification Number (AFM), 9 digits |
| `setLastName()` | `f_eponymo` | string | Yes | Employee's last name (uppercase Greek recommended) |
| `setFirstName()` | `f_onoma` | string | Yes | Employee's first name (uppercase Greek recommended) |
| `setType()` | `f_type` | CardDetailType | Yes | Movement type: `CHECK_IN` (0) or `CHECK_OUT` (1) |
| `setReferenceDate()` | `f_reference_date` | string | Yes | The work date in `YYYY-MM-DD` format |
| `setDate()` | `f_date` | string | Yes | Exact timestamp in ISO 8601 format |
| `setReasonCode()` | `f_aitiologia` | string | No | Reason code for late submissions or corrections |

### Field Details

#### f_afm (Employee TIN)
The employee's 9-digit Tax Identification Number. Must be a valid Greek AFM.

#### f_eponymo / f_onoma (Name)
Employee's surname and first name. While the API accepts any text, using uppercase Greek characters is recommended for consistency with official records.

#### f_type (Movement Type)
Indicates whether this is a check-in (arrival) or check-out (departure):
- `0` = Check-in (Προσέλευση) - Employee starting work
- `1` = Check-out (Αποχώρηση) - Employee ending work

#### f_reference_date (Reference Date)
The actual work date, regardless of when the submission is made. Format: `YYYY-MM-DD`.

For example, if an employee worked on January 15th but the submission is made on January 16th, the reference date should be `2025-01-15`.

#### f_date (Timestamp)
The exact date and time of the movement in ISO 8601 format with timezone: `YYYY-MM-DDTHH:MM:SS.sss+HH:MM`

Examples:
- `2025-01-15T08:00:00.000+02:00` (Greek winter time, EET)
- `2025-07-15T08:00:00.000+03:00` (Greek summer time, EEST)

#### f_aitiologia (Reason Code)
Optional reason code for late submissions or corrections. Common codes include:
- `001` - System failure
- `002` - Power outage
- `003` - Network issues

Consult the ERGANI parameter lookup for the complete list of valid reason codes.

### Example

```php
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

$detail = CardDetail::make()
    ->setTin('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setType(CardDetailType::CHECK_IN)
    ->setReferenceDate('2025-01-15')
    ->setDate('2025-01-15T08:00:00.000+02:00')
    ->setReasonCode('001'); // Optional
```

## Movement Types

The `CardDetailType` enum defines the two types of work card movements:

```php
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

// Check-in: Employee arriving at work
CardDetailType::CHECK_IN   // Value: '0', Label: 'Προσέλευση'

// Check-out: Employee leaving work
CardDetailType::CHECK_OUT  // Value: '1', Label: 'Αποχώρηση'
```

### Getting Labels

```php
CardDetailType::CHECK_IN->label();       // 'Check-in'
CardDetailType::CHECK_IN->labelGreek();  // 'Προσέλευση'

CardDetailType::CHECK_OUT->label();      // 'Check-out'
CardDetailType::CHECK_OUT->labelGreek(); // 'Αποχώρηση'
```

## Multiple Employees

Add multiple employees to a single card submission:

```php
$card = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0)
    ->addDetails(
        CardDetail::make()
            ->setTin('111111111')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T08:00:00.000+02:00')
    )
    ->addDetails(
        CardDetail::make()
            ->setTin('222222222')
            ->setLastName('ΓΕΩΡΓΙΟΥ')
            ->setFirstName('ΜΑΡΙΑ')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T08:15:00.000+02:00')
    )
    ->addDetails(
        CardDetail::make()
            ->setTin('333333333')
            ->setLastName('ΝΙΚΟΛΑΟΥ')
            ->setFirstName('ΠΕΤΡΟΣ')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T08:30:00.000+02:00')
    );
```

## Multiple Branches

Submit work cards for multiple branches in a single API call:

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

// Main branch (branch code 0)
$mainBranch = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0)
    ->addDetails(
        CardDetail::make()
            ->setTin('111111111')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T08:00:00.000+02:00')
    );

// Secondary branch (branch code 1)
$secondaryBranch = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(1)
    ->addDetails(
        CardDetail::make()
            ->setTin('444444444')
            ->setLastName('ΑΛΕΞΙΟΥ')
            ->setFirstName('ΕΛΕΝΗ')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T09:00:00.000+02:00')
    );

// Submit both branches
$response = (new WorkCard())->handle($mainBranch, $secondaryBranch);
```

## Check-Out Example

Recording employee departures:

```php
$card = Card::make()
    ->setEmployerTin('999999999')
    ->setBranchCode(0)
    ->addDetails(
        CardDetail::make()
            ->setTin('111111111')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setType(CardDetailType::CHECK_OUT)  // Check-out
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T16:00:00.000+02:00')
    );

$response = (new WorkCard())->handle($card);
```

## Response Handling

The `handle()` method returns an array of `SubmissionResponse` objects:

```php
$response = (new WorkCard())->handle($card);

foreach ($response as $result) {
    // Unique submission ID
    echo $result->id;

    // Protocol number (e.g., 'ΕΥΣ92', 'ΕΥΣ93')
    echo $result->protocol;

    // Submission timestamp (DateTimeInterface)
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

### Response Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | string | Unique identifier for the submission |
| `protocol` | string | Official protocol number from ERGANI |
| `submissionDate` | DateTimeInterface | When the submission was recorded |

## Retrieve PDF

After a successful submission, you can retrieve the official PDF document from ERGANI using the `pdf()` method:

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkCard\WorkCard;

// Submit the work card
$response = (new WorkCard())->handle($card);

// Later, retrieve the PDF using protocol and submission date
$workCardDoc = new WorkCard();
$pdfBase64 = $workCardDoc->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

// Decode and save to file
file_put_contents('work-card.pdf', base64_decode($pdfBase64));

// Or display in browser
$pdfBinary = base64_decode($pdfBase64);
header('Content-Type: application/pdf');
header('Content-Length: ' . strlen($pdfBinary));
header('Content-Disposition: inline; filename="work-card.pdf"');
echo $pdfBinary;
```

### Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `$protocol` | string | Protocol number from submission response |
| `$submittedDate` | DateTime\|int\|string | Submission date (DateTime object or `Ymd` format, e.g., `20250115`) |

::: tip Store Protocol Numbers
Always store the protocol number and submission date after each submission. These are required to retrieve the PDF later or to cancel the submission.
:::

## Date Formatting

The ERGANI API requires specific date formats:

### Reference Date
Simple date format for the work day:
```php
// Format: YYYY-MM-DD
$referenceDate = '2025-01-15';

// Using date() function
$referenceDate = date('Y-m-d');

// Using DateTime
$referenceDate = (new DateTime())->format('Y-m-d');
```

### Timestamp
ISO 8601 format with milliseconds and timezone:
```php
// Format: YYYY-MM-DDTHH:MM:SS.sss+HH:MM
$timestamp = '2025-01-15T08:00:00.000+02:00';

// Using date() function (note: 'u' gives microseconds, truncate to 3 digits)
$timestamp = date('Y-m-d\TH:i:s') . '.000' . date('P');

// Using DateTime with proper formatting
$dt = new DateTime();
$timestamp = $dt->format('Y-m-d\TH:i:s.v') . $dt->format('P');

// Carbon (if using Laravel)
$timestamp = now()->format('Y-m-d\TH:i:s.v') . now()->format('P');
```

### Timezone Considerations
Greece uses:
- **EET (Eastern European Time)**: UTC+02:00 (winter)
- **EEST (Eastern European Summer Time)**: UTC+03:00 (summer)

PHP will automatically use the correct offset if your server timezone is set to `Europe/Athens`:
```php
date_default_timezone_set('Europe/Athens');
```

## JSON Structure

For reference, here's the JSON structure sent to the ERGANI API:

```json
{
  "Cards": {
    "Card": [
      {
        "f_afm_ergodoti": "999999999",
        "f_aa": "0",
        "f_comments": "Optional comments",
        "Details": {
          "CardDetails": [
            {
              "f_afm": "123456789",
              "f_eponymo": "ΠΑΠΑΔΟΠΟΥΛΟΣ",
              "f_onoma": "ΙΩΑΝΝΗΣ",
              "f_type": "0",
              "f_reference_date": "2025-01-15",
              "f_date": "2025-01-15T08:00:00.000+02:00",
              "f_aitiologia": ""
            }
          ]
        }
      }
    ]
  }
}
```

## Best Practices

### 1. Submit in Real-Time
Work cards should be submitted as close to the actual check-in/check-out time as possible. Late submissions may require a reason code (`f_aitiologia`).

### 2. Use Correct Branch Codes
Always verify the branch code before submission. Use `0` for the main establishment. Branch codes can be retrieved using the `BranchInfo` service.

### 3. Handle Errors Gracefully
```php
use OxygenSuite\OxygenErgani\Exceptions\ConnectionException;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;

try {
    $response = (new WorkCard())->handle($card);
} catch (AuthenticationException $e) {
    // Token expired or invalid credentials
    log_error('Authentication failed: ' . $e->getMessage());
} catch (ConnectionException $e) {
    // Network or API issues
    log_error('Connection failed: ' . $e->getMessage());
}
```

### 4. Store Protocol Numbers
Always store the returned protocol number for each submission. This is required for any future corrections or cancellations.

### 5. Validate Before Submission
Ensure all required fields are populated and properly formatted before calling `handle()`:

```php
// Validate employee TIN format
if (!preg_match('/^\d{9}$/', $employeeTin)) {
    throw new InvalidArgumentException('Invalid employee TIN format');
}

// Validate date format
if (!DateTime::createFromFormat('Y-m-d', $referenceDate)) {
    throw new InvalidArgumentException('Invalid reference date format');
}
```

## See Also

- [Work Time](/guide/work-time) - Daily and weekly work time declarations
- [Cancel Submissions](/guide/cancel-submissions) - How to cancel erroneous submissions
- [Error Handling](/guide/error-handling) - Handling API errors and exceptions
