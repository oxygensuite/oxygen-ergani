# Work Time Models

Models for work time organization declarations (WTO).

## WorkTime

Work time declaration container for a specific branch.

```php
namespace OxygenSuite\OxygenErgani\Models\WorkTime;

class WorkTime
```

### Methods

| Method | Type | Description |
|--------|------|-------------|
| `setBranchCode($code)` | int\|string | Branch sequence number (0 for main) |
| `setComments($comments)` | string | Declaration comments |
| `setFromDate($date)` | DateTime\|string | Period start date (DD/MM/YYYY) - for weekly |
| `setToDate($date)` | DateTime\|string | Period end date (DD/MM/YYYY) - for weekly |
| `setRelatedProtocol($protocol)` | string | Related submission protocol (for corrections) |
| `setRelatedDate($date)` | DateTime\|string | Related submission date (DD/MM/YYYY) |
| `addEmployee($employee)` | WorkTimeEmployee | Add employee entry |
| `setEmployees($employees)` | array | Set all employee entries |

### Example

```php
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;

$workTime = WorkTime::make()
    ->setBranchCode(0)
    ->setComments('Daily schedule')
    ->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('987654321')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setDate('20/01/2025')
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::WORK)
                    ->setFromTime('09:00')
                    ->setToTime('17:00')
            )
    );
```

---

## WorkTimeEmployee

Employee entry in work time declaration.

```php
namespace OxygenSuite\OxygenErgani\Models\WorkTime;

class WorkTimeEmployee
```

### Methods

| Method | Type | Description |
|--------|------|-------------|
| `setTin($tin)` | string | Employee's tax ID (9 digits) |
| `setLastName($name)` | string | Last name |
| `setFirstName($name)` | string | First name |
| `setDate($date)` | DateTime\|string | Date (DD/MM/YYYY) for daily declarations |
| `setDay($day)` | DayOfWeek\|string | Day (1-7) for weekly declarations |
| `addAnalytics($entry)` | WorkTimeEntry | Add time entry |
| `setAnalytics($entries)` | array | Set all time entries |

### Daily vs Weekly

```php
// Daily declaration - use specific date
$employee = WorkTimeEmployee::make()
    ->setTin('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setDate('20/01/2025')
    ->addAnalytics(...);

// Weekly declaration - use day of week
use OxygenSuite\OxygenErgani\Enums\DayOfWeek;

$employee = WorkTimeEmployee::make()
    ->setTin('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setDay(DayOfWeek::MONDAY)
    ->addAnalytics(...);
```

---

## WorkTimeEntry

Individual time entry (work, leave, overtime, etc.).

```php
namespace OxygenSuite\OxygenErgani\Models\WorkTime;

class WorkTimeEntry
```

### Methods

| Method | Type | Description |
|--------|------|-------------|
| `setType($type)` | WorkTimeType\|string | Entry type |
| `setFromTime($time)` | string | Start time (HH:MM) |
| `setToTime($time)` | string | End time (HH:MM) |
| `setYear($year)` | string | Leave year (for leave types) |
| `setRequestedDays($days)` | string | Requested leave days |

### Entry Types

```php
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;

// Regular work
$work = WorkTimeEntry::make()
    ->setType(WorkTimeType::WORK)
    ->setFromTime('09:00')
    ->setToTime('17:00');

// Day off (no times needed)
$dayOff = WorkTimeEntry::make()
    ->setType(WorkTimeType::REST);

// Full-day leave
$leave = WorkTimeEntry::make()
    ->setType(WorkTimeType::LEAVE_REGULAR)
    ->setYear('2025')
    ->setRequestedDays('015');

// Overtime
$overtime = WorkTimeEntry::make()
    ->setType(WorkTimeType::OVERTIME)
    ->setFromTime('17:00')
    ->setToTime('19:00');
```

### Factory Support

```php
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;

// Using factory state methods
$entry = WorkTimeEntry::factory()->work('08:00', '16:00')->make();
$entry = WorkTimeEntry::factory()->overtime('17:00', '19:00')->make();
$entry = WorkTimeEntry::factory()->dayOff()->make();
$entry = WorkTimeEntry::factory()->leaveRegular('2025', '010')->make();
$entry = WorkTimeEntry::factory()->morningShift()->make();
$entry = WorkTimeEntry::factory()->nightShift()->make();
```

## See Also

- [Work Time Guide](/guide/work-time) - Complete usage guide
- [WorkTimeType Enum](/api/enums/work-time#worktimetype) - All work time types
- [DayOfWeek Enum](/api/enums/work-time#dayofweek) - Days of the week
