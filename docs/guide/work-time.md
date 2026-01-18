# Work Time

Work Time Organization (WTO) declarations inform ERGANI about employee work schedules, including work hours, breaks, leave, and days off. The system supports both daily declarations (for specific dates) and weekly schedules (recurring patterns).

## Overview

The Work Time system uses a hierarchical structure:

- **WorkTime**: Container for a branch's work time declaration
- **WorkTimeEmployee**: Individual employee's schedule for a date or day of week
- **WorkTimeEntry**: Specific time entries (work periods, breaks, leave, etc.)

## Document Types

| Class | Action Code | Description |
|-------|-------------|-------------|
| `DailyWorkTime` | WTODaily | Daily work time declaration |
| `WeeklyWorkTime` | WTOWeek | Weekly recurring schedule |
| `DailyWorkTimeRetrospective` | WTODailyA | Retrospective daily declaration |
| `DailyWorkTimeDrivers` | WTODailyD | Daily declaration for drivers |
| `WorkTimeLeave` | WTOLeave | Leave declaration |
| `WorkTimeLeaveCorrection` | WTOLeaveC | Leave correction |

## Basic Usage

### Daily Work Time

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;

$workTime = WorkTime::make()
    ->setBranchCode(0)
    ->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('123456789')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setDate('15/01/2025')
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::WORK)
                    ->setFromTime('09:00')
                    ->setToTime('17:00')
            )
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::BREAK)
                    ->setFromTime('13:00')
                    ->setToTime('13:30')
            )
    );

$response = (new DailyWorkTime())->handle($workTime);
```

### Weekly Work Time

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WeeklyWorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;
use OxygenSuite\OxygenErgani\Enums\DayOfWeek;

$workTime = WorkTime::make()
    ->setBranchCode(0)
    ->setFromDate('20/01/2025')
    ->setToDate('26/01/2025')
    ->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('123456789')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setDay(DayOfWeek::MONDAY)
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::WORK)
                    ->setFromTime('09:00')
                    ->setToTime('17:00')
            )
    );

$response = (new WeeklyWorkTime())->handle($workTime);
```

## WorkTime Model

The `WorkTime` model is the container for work time declarations.

### Fields Reference

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch serial number. Use `0` for main establishment |
| `setRelatedProtocol()` | `f_rel_protocol` | string | No | Protocol number of related submission (for corrections) |
| `setRelatedDate()` | `f_rel_date` | string | No | Date of related submission (DD/MM/YYYY) |
| `setComments()` | `f_comments` | string | No | Optional comments |
| `setFromDate()` | `f_from_date` | string | Weekly | Start date of period (DD/MM/YYYY) - required for weekly |
| `setToDate()` | `f_to_date` | string | Weekly | End date of period (DD/MM/YYYY) - required for weekly |
| `addEmployee()` | `Ergazomenoi` | array | Yes | Employee entries |

### Example

```php
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;

$workTime = WorkTime::make()
    ->setBranchCode(0)
    ->setComments('Weekly schedule for January')
    ->setFromDate('20/01/2025')
    ->setToDate('26/01/2025');
```

## WorkTimeEmployee Model

Each `WorkTimeEmployee` represents one employee's schedule.

### Fields Reference

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setTin()` | `f_afm` | string | Yes | Employee's Tax Identification Number (AFM), 9 digits |
| `setLastName()` | `f_eponymo` | string | Yes | Employee's last name |
| `setFirstName()` | `f_onoma` | string | Yes | Employee's first name |
| `setDate()` | `f_date` | string | Daily | Specific date (DD/MM/YYYY) - for daily declarations |
| `setDay()` | `f_day` | DayOfWeek\|string | Weekly | Day of week (1-7) - for weekly declarations |
| `addAnalytics()` | `ErgazomenosAnalytics` | array | Yes | Time entries for this employee |

### Field Details

#### f_date vs f_day

- **Daily declarations** use `f_date` with a specific date in DD/MM/YYYY format
- **Weekly declarations** use `f_day` with a day number (1=Monday through 7=Sunday)

You should use one or the other, not both.

### Example

```php
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Enums\DayOfWeek;

// For daily declaration
$dailyEmployee = WorkTimeEmployee::make()
    ->setTin('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setDate('15/01/2025');

// For weekly declaration
$weeklyEmployee = WorkTimeEmployee::make()
    ->setTin('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setDay(DayOfWeek::MONDAY);
```

## WorkTimeEntry Model

Each `WorkTimeEntry` represents a time period (work, break, leave, etc.).

### Fields Reference

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setType()` | `f_type` | WorkTimeType\|string | Yes | Type of time entry (work, break, leave, etc.) |
| `setFromTime()` | `f_from` | string | Yes* | Start time (HH:MM format) |
| `setToTime()` | `f_to` | string | Yes* | End time (HH:MM format) |
| `setYear()` | `f_year` | string | Leave | Year for leave entries |
| `setRequestedDays()` | `f_req_days` | string | Leave | Number of leave days requested |

*Not required for day off (ΡΕΠΟ) or certain leave types.

### Example

```php
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;

// Work period
$work = WorkTimeEntry::make()
    ->setType(WorkTimeType::WORK)
    ->setFromTime('09:00')
    ->setToTime('17:00');

// Break
$break = WorkTimeEntry::make()
    ->setType(WorkTimeType::BREAK)
    ->setFromTime('13:00')
    ->setToTime('13:30');

// Day off
$dayOff = WorkTimeEntry::make()
    ->setType(WorkTimeType::DAY_OFF);

// Leave with days
$leave = WorkTimeEntry::make()
    ->setType(WorkTimeType::LEAVE_REGULAR)
    ->setYear('2025')
    ->setRequestedDays('5');
```

## Work Time Types

The `WorkTimeType` enum defines all available time entry types:

### Work Types

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `WORK` | ΕΡΓ | Work | Εργασία |
| `WORK_EXTERNAL` | ΕΡΓ.ΕΞ | External work | Εργασία εκτός έδρας |
| `OVERTIME` | ΥΠ | Overtime | Υπερωρία |
| `NON_OVERTIME` | ΜΗ.ΥΠ | Non-overtime extra hours | Υπερεργασία |

### Break and Day Off

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `BREAK` | ΔΛ | Break | Διάλειμμα |
| `DAY_OFF` | ΡΕΠΟ | Day off | Ρεπό |

### Leave Types

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `LEAVE_REGULAR` | ΑΔ.ΚΑΝ | Regular leave | Κανονική άδεια |
| `LEAVE_BLOOD_DONATION` | ΑΔ.ΑΙΜ | Blood donation leave | Άδεια αιμοδοσίας |
| `LEAVE_EXAMINATION` | ΑΔ.ΕΞ | Examination leave | Άδεια εξετάσεων |
| `LEAVE_PARENTAL` | ΑΔ.ΑΝ.Π | Parental leave | Γονική άδεια |
| `LEAVE_UNPAID` | ΑΔ.ΑΝ | Unpaid leave | Άδεια άνευ αποδοχών |
| `LEAVE_MATERNITY` | ΑΔ.ΜΗΤ | Maternity leave | Άδεια μητρότητας |
| `LEAVE_PATERNITY` | ΑΔ.ΠΑΤ | Paternity leave | Άδεια πατρότητας |
| `LEAVE_SICK` | ΑΔ.ΑΣΘ | Sick leave | Άδεια ασθενείας |
| `LEAVE_SPECIAL` | ΑΔ.ΕΙΔ | Special leave | Ειδική άδεια |

### Other Types

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `HOLIDAY` | ΑΡΓ | Holiday | Αργία |
| `ABSENCE` | ΑΠ | Absence | Απουσία |
| `SUSPENSION` | ΑΝΑΣΤ | Suspension | Αναστολή |

### Using Labels

```php
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;

WorkTimeType::WORK->label();       // 'Work'
WorkTimeType::WORK->labelGreek();  // 'Εργασία'

// Get all labels for dropdowns
WorkTimeType::labels();       // ['ΕΡΓ' => 'Work', ...]
WorkTimeType::labelsGreek();  // ['ΕΡΓ' => 'Εργασία', ...]
```

## Day of Week Enum

For weekly declarations, use the `DayOfWeek` enum:

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `MONDAY` | 1 | Monday | Δευτέρα |
| `TUESDAY` | 2 | Tuesday | Τρίτη |
| `WEDNESDAY` | 3 | Wednesday | Τετάρτη |
| `THURSDAY` | 4 | Thursday | Πέμπτη |
| `FRIDAY` | 5 | Friday | Παρασκευή |
| `SATURDAY` | 6 | Saturday | Σάββατο |
| `SUNDAY` | 7 | Sunday | Κυριακή |

```php
use OxygenSuite\OxygenErgani\Enums\DayOfWeek;

$employee->setDay(DayOfWeek::MONDAY);
// or
$employee->setDay('1');
```

## Complete Examples

### Full Day with Break

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTime;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEmployee;
use OxygenSuite\OxygenErgani\Models\WorkTime\WorkTimeEntry;
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;

$workTime = WorkTime::make()
    ->setBranchCode(0)
    ->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('123456789')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setDate('15/01/2025')
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::WORK)
                    ->setFromTime('09:00')
                    ->setToTime('13:00')
            )
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::BREAK)
                    ->setFromTime('13:00')
                    ->setToTime('13:30')
            )
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::WORK)
                    ->setFromTime('13:30')
                    ->setToTime('17:30')
            )
    );

$response = (new DailyWorkTime())->handle($workTime);
```

### Multiple Employees

```php
$workTime = WorkTime::make()
    ->setBranchCode(0)
    ->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('111111111')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setDate('15/01/2025')
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::WORK)
                    ->setFromTime('08:00')
                    ->setToTime('16:00')
            )
    )
    ->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('222222222')
            ->setLastName('ΓΕΩΡΓΙΟΥ')
            ->setFirstName('ΜΑΡΙΑ')
            ->setDate('15/01/2025')
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::WORK)
                    ->setFromTime('10:00')
                    ->setToTime('18:00')
            )
    )
    ->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('333333333')
            ->setLastName('ΝΙΚΟΛΑΟΥ')
            ->setFirstName('ΠΕΤΡΟΣ')
            ->setDate('15/01/2025')
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::DAY_OFF)
            )
    );
```

### Weekly Schedule

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WeeklyWorkTime;
use OxygenSuite\OxygenErgani\Enums\DayOfWeek;

$workTime = WorkTime::make()
    ->setBranchCode(0)
    ->setFromDate('20/01/2025')
    ->setToDate('26/01/2025');

// Add Monday-Friday work schedule
foreach ([DayOfWeek::MONDAY, DayOfWeek::TUESDAY, DayOfWeek::WEDNESDAY,
          DayOfWeek::THURSDAY, DayOfWeek::FRIDAY] as $day) {
    $workTime->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('123456789')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setDay($day)
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::WORK)
                    ->setFromTime('09:00')
                    ->setToTime('17:00')
            )
    );
}

// Add Saturday/Sunday as days off
foreach ([DayOfWeek::SATURDAY, DayOfWeek::SUNDAY] as $day) {
    $workTime->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('123456789')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setDay($day)
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::DAY_OFF)
            )
    );
}

$response = (new WeeklyWorkTime())->handle($workTime);
```

### Leave Declaration

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\WorkTimeLeave;
use OxygenSuite\OxygenErgani\Enums\WorkTimeType;

$workTime = WorkTime::make()
    ->setBranchCode(0)
    ->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('123456789')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setDate('20/01/2025')
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::LEAVE_REGULAR)
                    ->setYear('2025')
                    ->setRequestedDays('5')
            )
    );

$response = (new WorkTimeLeave())->handle($workTime);
```

### Retrospective Declaration

For late submissions or corrections:

```php
use OxygenSuite\OxygenErgani\Http\Documents\WorkTime\DailyWorkTimeRetrospective;

$workTime = WorkTime::make()
    ->setBranchCode(0)
    ->setRelatedProtocol('ΩΡΕ123')  // Original protocol
    ->setRelatedDate('10/01/2025')  // Original submission date
    ->addEmployee(
        WorkTimeEmployee::make()
            ->setTin('123456789')
            ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
            ->setFirstName('ΙΩΑΝΝΗΣ')
            ->setDate('10/01/2025')
            ->addAnalytics(
                WorkTimeEntry::make()
                    ->setType(WorkTimeType::WORK)
                    ->setFromTime('09:00')
                    ->setToTime('18:00')  // Corrected end time
            )
    );

$response = (new DailyWorkTimeRetrospective())->handle($workTime);
```

## Response Handling

All work time documents return an array of `WorkTimeResponse` objects:

```php
$response = (new DailyWorkTime())->handle($workTime);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'ΩΡΕ456')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

### Response Properties

| Property | Type | Description |
|----------|------|-------------|
| `id` | string | Unique identifier for the submission |
| `protocol` | string | Official protocol number from ERGANI |
| `submissionDate` | DateTimeInterface | When the submission was recorded |

## JSON Structure

For reference, here's the JSON structure sent to the ERGANI API:

### Daily Declaration

```json
{
  "WTOS": {
    "WTO": [
      {
        "f_aa_pararthmatos": "0",
        "f_rel_protocol": "",
        "f_rel_date": "",
        "f_comments": "",
        "f_from_date": "",
        "f_to_date": "",
        "Ergazomenoi": {
          "ErgazomenoiWTO": [
            {
              "f_afm": "123456789",
              "f_eponymo": "ΠΑΠΑΔΟΠΟΥΛΟΣ",
              "f_onoma": "ΙΩΑΝΝΗΣ",
              "f_date": "15/01/2025",
              "ErgazomenosAnalytics": {
                "ErgazomenosWTOAnalytics": [
                  {
                    "f_type": "ΕΡΓ",
                    "f_from": "09:00",
                    "f_to": "17:00"
                  }
                ]
              }
            }
          ]
        }
      }
    ]
  }
}
```

### Weekly Declaration

```json
{
  "WTOS": {
    "WTO": [
      {
        "f_aa_pararthmatos": "0",
        "f_rel_protocol": "",
        "f_rel_date": "",
        "f_comments": "",
        "f_from_date": "20/01/2025",
        "f_to_date": "26/01/2025",
        "Ergazomenoi": {
          "ErgazomenoiWTO": [
            {
              "f_afm": "123456789",
              "f_eponymo": "ΠΑΠΑΔΟΠΟΥΛΟΣ",
              "f_onoma": "ΙΩΑΝΝΗΣ",
              "f_day": "1",
              "ErgazomenosAnalytics": {
                "ErgazomenosWTOAnalytics": [
                  {
                    "f_type": "ΕΡΓ",
                    "f_from": "09:00",
                    "f_to": "17:00"
                  }
                ]
              }
            }
          ]
        }
      }
    ]
  }
}
```

## Best Practices

### 1. Use Appropriate Document Type

- **DailyWorkTime**: For declaring specific dates' schedules
- **WeeklyWorkTime**: For recurring weekly patterns
- **DailyWorkTimeRetrospective**: For late submissions or corrections
- **DailyWorkTimeDrivers**: Special rules apply for drivers
- **WorkTimeLeave**: For leave declarations specifically

### 2. Include Breaks

Always declare breaks explicitly when employees have them:

```php
->addAnalytics(
    WorkTimeEntry::make()
        ->setType(WorkTimeType::BREAK)
        ->setFromTime('13:00')
        ->setToTime('13:30')
)
```

### 3. Split Work Periods Around Breaks

When an employee works before and after a break, create two work entries:

```php
// Morning work
->addAnalytics(
    WorkTimeEntry::make()
        ->setType(WorkTimeType::WORK)
        ->setFromTime('09:00')
        ->setToTime('13:00')
)
// Break
->addAnalytics(
    WorkTimeEntry::make()
        ->setType(WorkTimeType::BREAK)
        ->setFromTime('13:00')
        ->setToTime('13:30')
)
// Afternoon work
->addAnalytics(
    WorkTimeEntry::make()
        ->setType(WorkTimeType::WORK)
        ->setFromTime('13:30')
        ->setToTime('17:30')
)
```

### 4. Time Format

Always use 24-hour HH:MM format for times:
- Correct: `'09:00'`, `'17:30'`, `'23:00'`
- Incorrect: `'9:00'`, `'5:30 PM'`

### 5. Date Format

Use DD/MM/YYYY format for dates:
- Correct: `'15/01/2025'`
- Incorrect: `'2025-01-15'`, `'01/15/2025'`

### 6. Store Protocol Numbers

Always store the returned protocol number for corrections or cancellations:

```php
foreach ($response as $result) {
    $this->storeProtocol($employeeId, $result->protocol, $result->submissionDate);
}
```

## See Also

- [Work Cards](/guide/work-cards) - Employee check-in/check-out tracking
- [Cancel Submissions](/guide/cancel-submissions) - How to cancel erroneous submissions
- [Error Handling](/guide/error-handling) - Handling API errors and exceptions
