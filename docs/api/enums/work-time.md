# Work Time Enums

Enums for work cards and work time declarations.

## WorkCardDelayReason

Reasons for delayed work card submissions.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum WorkCardDelayReason: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `POWER_OR_TELECOM_ISSUE` | 001 | Power or telecom issue | Πρόβλημα στην ηλεκτροδότηση/τηλεπικοινωνίες |
| `EMPLOYER_SYSTEM_ISSUE` | 002 | Employer system issue | Πρόβλημα στα συστήματα του εργοδότη |
| `ERGANI_CONNECTION_ISSUE` | 003 | ERGANI connection issue | Πρόβλημα σύνδεσης με το ΠΣ ΕΡΓΑΝΗ |

**Used in:** Work card submissions when submitting after the allowed time window

---

## WorkTimeType

Type of work time entry for work time declarations (WTO). Codes are sourced from `ParameterLookup::WORK_TIME_TYPE`.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum WorkTimeType: string
```

### Work Types

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `WORK` | ΕΡΓ | Work | Εργασία |
| `REMOTE_WORK` | ΤΗΛ | Remote work | Τηλεργασία |

### Rest/Non-Work

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `REST` | ΑΝ | Rest/Day off | Ανάπαυση/Ρεπό |
| `NON_WORKING` | ΜΕ | Non-working | Μη εργασία |

### Leave Types (Full-Day)

The enum includes 29 full-day leave types. Common ones include:

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `LEAVE_REGULAR` | ΑΔΚΑΝ | Regular leave | Κανονική άδεια |
| `LEAVE_SICK` | ΑΔΑΣ | Sick leave | Άδεια ασθένειας |
| `LEAVE_MATERNITY` | ΑΔΜΗ | Maternity leave | Άδεια μητρότητας |
| `LEAVE_PATERNITY` | ΑΔΠΑ | Paternity leave | Άδεια πατρότητας |
| `LEAVE_PARENTAL` | ΑΔΓΟΝ | Parental leave | Γονική άδεια |
| `LEAVE_MARRIAGE` | ΑΔΓΑΜ | Marriage leave | Άδεια γάμου |
| `LEAVE_BEREAVEMENT` | ΑΔΘΣΥΓ | Bereavement leave | Άδεια λόγω θανάτου συγγενούς |
| `LEAVE_UNPAID` | ΑΔΑΑ | Unpaid leave | Άδεια άνευ αποδοχών |
| `LEAVE_BLOOD_DONATION` | ΑΔΑΙΜ | Blood donation leave | Αιμοδοτική άδεια |
| `LEAVE_EXAMINATION` | ΑΔΕΞ | Examination leave | Άδεια εξετάσεων |
| `LEAVE_OTHER` | ΑΔΑΛ | Other leave | Άδεια Άλλη |

### Leave Types (Hourly)

7 hourly leave variants for partial-day absences:

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `HOURLY_CHILD_CARE` | ΩΑΦΠ | Child care leave (hours) | Άδεια φροντίδας παιδιού (ΩΡΕΣ) |
| `HOURLY_PARENTAL` | ΩΑΓΟΝ | Parental leave (hours) | Γονική άδεια (ΩΡΕΣ) |
| `HOURLY_FORCE_MAJEURE` | ΩΑΑΠΑΒ | Force majeure absence (hours) | Απουσία για λόγους ανωτέρας βίας (ΩΡΕΣ) |
| `HOURLY_FLEXIBLE` | ΩΑΕΡΕ | Flexible work arrangements (hours) | Ευέλικτες ρυθμίσεις εργασίας (ΩΡΕΣ) |
| `HOURLY_PRENATAL` | ΩΑΠΕ | Prenatal examination leave (hours) | Άδεια εξετάσεων προγεννητικού ελέγχου (ΩΡΕΣ) |
| `HOURLY_SCHOOL_PERFORMANCE` | ΩΑΠΣΕΤ | School performance leave (hours) | Άδεια παρακολούθησης σχολικής επίδοσης τέκνου (ΩΡΕΣ) |
| `HOURLY_OTHER` | ΩΑΑΛ | Other leave (hours) | Άδεια Άλλη (ΩΡΕΣ) |

### Overtime

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `OVERTIME` | ΥΠ | Overtime | Υπερωρία |
| `NO_OVERTIME` | ΧΥΠ | No overtime | Χωρίς υπερωρία |

**Used in:** Work time declarations (`f_type` field)

### Category Helper Methods

`WorkTimeType` provides static methods to get subsets of cases:

```php
// Get arrays of cases by category
WorkTimeType::work();         // [WORK, REMOTE_WORK]
WorkTimeType::rest();         // [REST, NON_WORKING]
WorkTimeType::schedule();     // work + rest combined (for weekly schedule dropdowns)
WorkTimeType::dayLeaves();    // 29 full-day leave types
WorkTimeType::hourlyLeaves(); // 7 hourly leave types
WorkTimeType::leaves();       // all 36 leave types combined
WorkTimeType::overtime();     // [OVERTIME, NO_OVERTIME]
```

### Instance Check Methods

```php
$type->isWork();        // true for WORK, REMOTE_WORK
$type->isRest();        // true for REST, NON_WORKING
$type->isSchedule();    // true for work + rest types
$type->isDayLeave();    // true for full-day leaves
$type->isHourlyLeave(); // true for hourly leaves
$type->isLeave();       // true for any leave type
$type->isOvertime();    // true for OVERTIME, NO_OVERTIME
```

### Creating Dropdowns

```php
// English dropdown for schedule types
$scheduleOptions = WorkTimeType::labelsFor(WorkTimeType::schedule());

// Greek dropdown for all leave types
$leaveOptions = WorkTimeType::labelsFor(WorkTimeType::leaves(), 'greek');
```

---

## CardDetailType

Work card entry type.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum CardDetailType: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `CHECK_IN` | 0 | Check-in | Προσέλευση |
| `CHECK_OUT` | 1 | Check-out | Αποχώρηση |

**Used in:** Work card submissions

---

## DayOfWeek

Day of the week.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum DayOfWeek: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `MONDAY` | 1 | Monday | Δευτέρα |
| `TUESDAY` | 2 | Tuesday | Τρίτη |
| `WEDNESDAY` | 3 | Wednesday | Τετάρτη |
| `THURSDAY` | 4 | Thursday | Πέμπτη |
| `FRIDAY` | 5 | Friday | Παρασκευή |
| `SATURDAY` | 6 | Saturday | Σάββατο |
| `SUNDAY` | 7 | Sunday | Κυριακή |

**Used in:** Weekly work time declarations

---

## WeekDays

Number of working days per week.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum WeekDays: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `FIVE_DAY` | 5 | Five-day week | Πενθήμερο |
| `SIX_DAY` | 6 | Six-day week | Εξαήμερο |

**Used in:** E3, MA forms (`f_week_days` field)
