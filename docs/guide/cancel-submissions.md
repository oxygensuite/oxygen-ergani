# Cancel Submissions

The ERGANI system allows cancellation of certain submitted declarations. This is useful when corrections are needed or submissions were made in error.

## Overview

| Property | Value |
|----------|-------|
| Class | `CancelSubmittedDocument` |
| Endpoint | `Documents/CancelSubmittedDocument` |
| HTTP Method | `POST` |

::: warning Limited Scope
Cancellation is only available for specific document types. Currently, it is supported for:
- **Work Time Organization - Leaves** (Οργάνωση Χρόνου Εργασίας – Άδειες)
- **Work Time Organization - Leaves Correction** (Οργάνωση Χρόνου Εργασίας – Άδειες ΟΡΘΗ ΕΠΑΝΑΛΗΨΗ)

Other document types (E3, E5, E6, E7, MA, Work Cards) cannot be cancelled via this method.
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\CancelSubmittedDocument;

$cancel = new CancelSubmittedDocument($accessToken, $environment);

$success = $cancel->handle(
    documentType: 'WTD',              // Document type code
    protocol: 'ΟΧΕ123456',            // Protocol number from submission
    submissionDate: '20250115'         // Date in yyyymmdd format
);

if ($success) {
    echo "Submission cancelled successfully";
} else {
    echo "Cancellation failed";
}
```

## Parameters

| Parameter | Type | Description |
|-----------|------|-------------|
| `$documentType` | string | The document type code (action code from submission) |
| `$protocol` | string | Protocol number received from original submission |
| `$submissionDate` | DateTime\|int\|string | Submission date in `yyyymmdd` format |

## Date Formats

The `submissionDate` parameter accepts multiple formats:

```php
use DateTime;

// String format (recommended)
$cancel->handle('WTD', 'ΟΧΕ123456', '20250115');

// DateTime object (automatically formatted)
$date = new DateTime('2025-01-15');
$cancel->handle('WTD', 'ΟΧΕ123456', $date);

// Integer format
$cancel->handle('WTD', 'ΟΧΕ123456', 20250115);
```

## Getting Submission Details

When you submit a document, the response includes the protocol and submission date needed for cancellation:

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;

// Original submission
$workTime = WorkTime::make()
    // ... set fields ...
;

$response = (new DailyWorkTime())->handle($workTime);

// Store these for potential cancellation
foreach ($response as $result) {
    $protocol = $result->protocol;           // e.g., 'ΟΧΕ123456'
    $submissionDate = $result->submissionDate; // DateTime object

    // Save to database for later reference
    saveSubmission([
        'document_type' => 'WTD',
        'protocol' => $protocol,
        'submission_date' => $submissionDate->format('Ymd'),
    ]);
}
```

## Complete Example

```php
use OxygenSuite\OxygenErgani\Http\Documents\CancelSubmittedDocument;
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;

// Retrieve stored submission details
$submission = getSubmissionFromDatabase($id);

try {
    $cancel = new CancelSubmittedDocument($accessToken, $environment);

    $success = $cancel->handle(
        $submission['document_type'],
        $submission['protocol'],
        $submission['submission_date']
    );

    if ($success) {
        // Mark as cancelled in database
        markSubmissionCancelled($id);
        echo "Cancellation successful";
    } else {
        echo "Cancellation failed - may already be cancelled or past deadline";
    }
} catch (ErganiException $e) {
    echo "Error: " . $e->getMessage();
}
```

## Using with Token Manager

```php
use OxygenSuite\OxygenErgani\Storage\Token;
use OxygenSuite\OxygenErgani\Storage\FileToken;
use OxygenSuite\OxygenErgani\Enums\Environment;
use OxygenSuite\OxygenErgani\Http\Documents\CancelSubmittedDocument;

// Set up token manager
Token::setCurrentTokenManager(
    new FileToken('username', 'password'),
    Environment::PRODUCTION
);

// Cancel without explicitly passing token
$cancel = new CancelSubmittedDocument();
$success = $cancel->handle('WTD', 'ΟΧΕ123456', '20250115');
```

## Error Handling

```php
use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Exceptions\AuthenticationException;

try {
    $success = $cancel->handle('WTD', 'ΟΧΕ123456', '20250115');
} catch (AuthenticationException $e) {
    // Token invalid or expired
    echo "Authentication error: " . $e->getMessage();
} catch (ErganiException $e) {
    // API error (invalid protocol, document type not cancellable, etc.)
    echo "Cancellation error: " . $e->getMessage();
}
```

## Important Notes

1. **Document Type Support**: Only specific document types support cancellation. Most hiring, termination, and dismissal forms cannot be cancelled via this endpoint.

2. **Time Limits**: Cancellation may have time limits. Check ERGANI guidelines for the deadline after submission.

3. **Already Cancelled**: Attempting to cancel an already-cancelled submission will fail.

4. **Protocol Format**: Use the exact protocol number as returned from the original submission.

5. **Date Format**: When using string format, use `yyyymmdd` (e.g., `20250115`).

6. **Corrections**: If cancellation isn't available for your document type, you may need to submit a correction/amendment form instead.

## Alternatives to Cancellation

For document types that don't support cancellation:

### E3 Hiring Forms
- Submit a new E3 with corrections
- Contact ERGANI support for manual corrections

### E5/E6 Termination/Dismissal Forms
- Submit correction forms if available
- Contact ERGANI support

### Work Cards
- Cannot be cancelled
- Submit late cards with delay reason codes

### MA Employment Modifications
- Submit a new modification form with corrections

---

## See Also

- [Work Time](/guide/work-time) - Work time submissions (cancellable)
- [Error Handling](/guide/error-handling) - Exception handling patterns
- [Services & Queries](/guide/services) - Query employer data
