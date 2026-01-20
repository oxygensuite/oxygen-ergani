# Work Card Models

Models for work card (check-in/check-out) submissions.

## Card

Main work card container for a specific employer branch.

```php
namespace OxygenSuite\OxygenErgani\Models\WorkCard;

class Card
```

### Methods

| Method | Type | Description |
|--------|------|-------------|
| `setEmployerTin($tin)` | string | Employer's tax ID (9 digits) |
| `setBranchCode($code)` | int\|string | Branch sequence number (0 for main) |
| `setComments($comments)` | string\|null | Optional comments |
| `addDetails($detail)` | CardDetail\|array | Add one or more card details |
| `setDetails($details)` | array | Set all card details |

### Example

```php
use OxygenSuite\OxygenErgani\Models\WorkCard\Card;
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

$card = Card::make()
    ->setEmployerTin('123456789')
    ->setBranchCode(0)
    ->addDetails(
        CardDetail::make()
            ->setTin('987654321')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setType(CardDetailType::CHECK_IN)
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T09:00:00.000+02:00')
    )
    ->addDetails(
        CardDetail::make()
            ->setTin('987654321')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setType(CardDetailType::CHECK_OUT)
            ->setReferenceDate('2025-01-15')
            ->setDate('2025-01-15T17:00:00.000+02:00')
    );
```

---

## CardDetail

Individual check-in or check-out entry for an employee.

```php
namespace OxygenSuite\OxygenErgani\Models\WorkCard;

class CardDetail
```

### Methods

| Method | Type | Description |
|--------|------|-------------|
| `setTin($tin)` | string | Employee's tax ID (9 digits) |
| `setLastName($name)` | string | Employee's last name |
| `setFirstName($name)` | string | Employee's first name |
| `setType($type)` | CardDetailType | Check-in (0) or check-out (1) |
| `setReferenceDate($date)` | DateTime\|string | Work date (YYYY-MM-DD) |
| `setDate($date)` | DateTime\|string | Exact timestamp (ISO 8601) |
| `setReasonCode($code)` | string\|null | Delay reason code (optional) |

### Example

```php
use OxygenSuite\OxygenErgani\Models\WorkCard\CardDetail;
use OxygenSuite\OxygenErgani\Enums\CardDetailType;

// Regular check-in
$checkIn = CardDetail::make()
    ->setTin('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setType(CardDetailType::CHECK_IN)
    ->setReferenceDate('2025-01-15')
    ->setDate('2025-01-15T09:00:00.000+02:00');

// Late submission with reason code
$lateCheckIn = CardDetail::make()
    ->setTin('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setType(CardDetailType::CHECK_IN)
    ->setReferenceDate('2025-01-15')
    ->setDate('2025-01-15T09:00:00.000+02:00')
    ->setReasonCode('003');
```

## See Also

- [Work Cards Guide](/guide/work-cards) - Complete usage guide
- [CardDetailType Enum](/api/enums/work-time#carddetailtype) - Check-in/check-out values
- [WorkCardDelayReason Enum](/api/enums/work-time#workcarddelayreason) - Delay reasons
