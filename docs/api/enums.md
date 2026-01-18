# Enums

All enums in the package use the `HasLabels` trait, providing bilingual labels (English and Greek) for each value.

## Using Enums

```php
use OxygenSuite\OxygenErgani\Enums\Sex;

// Get value
$value = Sex::MALE->value;  // 0

// Get English label
$label = Sex::MALE->label();  // "Male"

// Get Greek label
$labelGreek = Sex::MALE->labelGreek();  // "Άνδρας"

// Get all labels
$labels = Sex::labels();       // ['0' => 'Male', '1' => 'Female']
$labelsGr = Sex::labelsGreek(); // ['0' => 'Άνδρας', '1' => 'Γυναίκα']
```

---

## Personal Information

### Sex

Employee's biological sex.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum Sex: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `MALE` | 0 | Male | Άνδρας |
| `FEMALE` | 1 | Female | Γυναίκα |

**Used in:** All declaration forms (`f_sex` field)

---

### MaritalStatus

Employee's marital status.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum MaritalStatus: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `SINGLE` | 0 | Single | Άγαμος/η |
| `MARRIED` | 1 | Married | Έγγαμος/η |
| `DIVORCED` | 2 | Divorced | Διαζευγμένος/η |
| `WIDOWED` | 3 | Widowed | Χήρος/α |

**Used in:** Declaration forms (`f_marital_status` field)

---

## Employment Classification

### EmploymentStatus

Employment status indicating the type of working hours arrangement.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum EmploymentStatus: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `FULL` | 0 | Full-time | Πλήρης απασχόληση |
| `PARTIAL` | 1 | Part-time | Μερική απασχόληση |
| `ROTATION` | 2 | Rotational | Εκ περιτροπής απασχόληση |
| `ON_DEMAND` | 3 | On-demand | Διαλείπουσα απασχόληση |

**Used in:** E3, E6, E7, MA forms (`f_kathestosapasxolisis` field)

---

### WorkerType

Classification of worker type.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum WorkerType: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `WORKER` | 0 | Worker | Εργάτης |
| `EMPLOYEE` | 1 | Employee | Υπάλληλος |

**Used in:** E3, E6, E7, MA forms (`f_xaraktirismos` field)

---

### EmploymentType

Type of employment contract duration.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum EmploymentType: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `INDEFINITE` | 0 | Indefinite term | Αορίστου χρόνου |
| `FIXED_TERM` | 1 | Fixed term | Ορισμένου χρόνου |
| `PROJECT` | 2 | Project-based | Έργου |
| `BORROWED` | 3 | Borrowed employee | Δανειζόμενος |

**Used in:** E3, E7, MA forms (`f_sxeshapasxolisis` field)

::: warning E7N Restriction
E7N only accepts `FIXED_TERM (1)` or `PROJECT (2)` — not `INDEFINITE (0)` or `BORROWED (3)`.
:::

---

### WorkLocation

Location where the employee performs work.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum WorkLocation: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `EMPLOYER_BRANCH` | 0 | Employer's branch | Έδρα/Παράρτημα εργοδότη |
| `OTHER` | 1 | Other location | Άλλος τόπος |

**Used in:** E3, MA forms (`f_topos_ergasias` field)

---

### ResponsiblePosition

Indicates whether the employee holds a supervisory, managerial, or confidential position.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum ResponsiblePosition: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `NONE` | '' | Not applicable | Δεν εφαρμόζεται |
| `NO` | '1' | No | Όχι |
| `MANAGERIAL_AUTHORITY` | '2' | Position with managerial authority | Θέση με διευθυντικό δικαίωμα |
| `SALARY_4X_MINIMUM` | '3' | Salary at least 4x minimum wage | Αποδοχές τουλάχιστον 4πλάσιες του κατώτατου μισθού |
| `SALARY_6X_MINIMUM` | '4' | Salary at least 6x minimum wage | Αποδοχές τουλάχιστον 6πλάσιες του κατώτατου μισθού |

**Used in:** E3 forms (`f_responsible_position` field)

---

### IndividualContract

Individual employment contract status.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum IndividualContract: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `NO` | 0 | No | Όχι |
| `WITH_FILE` | 1 | Acceptance with attached file | Αποδοχή με επισυναπτόμενο αρχείο |
| `PENDING` | 2 | Pending | Εκκρεμεί |

**Used in:** E3 forms (`f_atomikh_symbash` field)

---

### SpecialCase

Special employment case for public sector employees under private law.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum SpecialCase: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `NONE` | '' | Not applicable | Δεν εφαρμόζεται |
| `PRIVATE_LAW_NARROW_PUBLIC` | '2' | Private law - Narrow public sector | Ιδιωτικού δικαίου - Στενός δημόσιος τομέας |
| `PRIVATE_LAW_BROADER_PUBLIC` | '3' | Private law - Broader public sector | Ιδιωτικού δικαίου - Ευρύτερος δημόσιος τομέας |

**Used in:** E3, MA forms (`f_special_case` field)

---

## Loan/Borrowing

### LoanType

Type of employee loan arrangement.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum LoanType: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `GENUINE` | 0 | Genuine borrowing | Γνήσιος δανεισμός |
| `EPA` | 1 | Temporary Employment Agency | Ε.Π.Α. |

**Used in:** E3D, E3PD, E6LD, MAD forms (`f_borrow_type` field)

---

### SalaryPaymentSource

Source of salary payment for borrowed employees.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum SalaryPaymentSource: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `DIRECT_EMPLOYER` | 0 | Direct employer/EPA | Άμεσος εργοδότης/ΕΠΑ |
| `INDIRECT_EMPLOYER` | 1 | Indirect employer | Έμμεσος εργοδότης |

**Used in:** MAD forms (`f_kataboli_apodoxon` field)

---

## Termination/Dismissal

### FixedTermTerminationReason

Termination reasons for fixed-term contracts (E7N).

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum FixedTermTerminationReason: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `CONTRACT_EXPIRATION` | 0 | Contract Expiration | Λήξη Συμπεφωνημένου Χρόνου |
| `WORK_COMPLETION` | 3 | Work Completion | Ολοκλήρωση Έργου με Όρο Πρόωρης Καταγγελίας |
| `EARLY_BY_EMPLOYER` | 4 | Early Termination by Employer | Καταγγελία πριν Λήξη για Σπουδαίο Λόγο |
| `EARLY_BY_EMPLOYEE` | 5 | Early Termination by Employee | Καταγγελία πριν Λήξη χωρίς Σπουδαίο Λόγο |
| `MUTUAL_AGREEMENT` | 6 | Mutual Agreement | Συναινετική Λύση πριν Λήξη |

**Used in:** E7N forms (`f_logosperatosis` field)

::: info Non-Sequential Values
Values are non-sequential (0, 3, 4, 5, 6) as defined in the XSD schema.
:::

---

### NoticePeriodMonths

Number of months for advance notice period in dismissals.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum NoticePeriodMonths: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `ONE` | 1 | 1 Month | 1 Μήνας |
| `TWO` | 2 | 2 Months | 2 Μήνες |
| `THREE` | 3 | 3 Months | 3 Μήνες |
| `FOUR` | 4 | 4 Months | 4 Μήνες |

**Used in:** E6NMP forms (`f_minesproidopoihsh` field)

---

## Work Time

### WorkTimeType

Type of work time entry for daily work time declarations.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum WorkTimeType: string
```

**Work Types:**

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `WORK` | 'ΕΡΓ' | Work | Εργασία |
| `WORK_EXTERNAL` | 'ΕΡΓ.ΕΞ' | External work | Εργασία εκτός έδρας |
| `OVERTIME` | 'ΥΠ' | Overtime | Υπερωρία |
| `NON_OVERTIME` | 'ΜΗ.ΥΠ' | Non-overtime extra hours | Υπερεργασία |

**Break and Day Off:**

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `BREAK` | 'ΔΛ' | Break | Διάλειμμα |
| `DAY_OFF` | 'ΡΕΠΟ' | Day off | Ρεπό |

**Leave Types:**

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `LEAVE_REGULAR` | 'ΑΔ.ΚΑΝ' | Regular leave | Κανονική άδεια |
| `LEAVE_BLOOD_DONATION` | 'ΑΔ.ΑΙΜ' | Blood donation leave | Άδεια αιμοδοσίας |
| `LEAVE_EXAMINATION` | 'ΑΔ.ΕΞ' | Examination leave | Άδεια εξετάσεων |
| `LEAVE_PARENTAL` | 'ΑΔ.ΑΝ.Π' | Parental leave | Γονική άδεια |
| `LEAVE_UNPAID` | 'ΑΔ.ΑΝ' | Unpaid leave | Άδεια άνευ αποδοχών |
| `LEAVE_MATERNITY` | 'ΑΔ.ΜΗΤ' | Maternity leave | Άδεια μητρότητας |
| `LEAVE_PATERNITY` | 'ΑΔ.ΠΑΤ' | Paternity leave | Άδεια πατρότητας |
| `LEAVE_SICK` | 'ΑΔ.ΑΣΘ' | Sick leave | Άδεια ασθενείας |
| `LEAVE_SPECIAL` | 'ΑΔ.ΕΙΔ' | Special leave | Ειδική άδεια |

**Other:**

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `HOLIDAY` | 'ΑΡΓ' | Holiday | Αργία |
| `ABSENCE` | 'ΑΠ' | Absence | Απουσία |
| `SUSPENSION` | 'ΑΝΑΣΤ' | Suspension | Αναστολή |

**Used in:** Work time declarations (`f_type` field)

---

### CardDetailType

Work card entry type.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum CardDetailType: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `CHECK_IN` | '0' | Check-in | Προσέλευση |
| `CHECK_OUT` | '1' | Check-out | Αποχώρηση |

**Used in:** Work card submissions

---

### DayOfWeek

Day of the week.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum DayOfWeek: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `MONDAY` | '1' | Monday | Δευτέρα |
| `TUESDAY` | '2' | Tuesday | Τρίτη |
| `WEDNESDAY` | '3' | Wednesday | Τετάρτη |
| `THURSDAY` | '4' | Thursday | Πέμπτη |
| `FRIDAY` | '5' | Friday | Παρασκευή |
| `SATURDAY` | '6' | Saturday | Σάββατο |
| `SUNDAY` | '7' | Sunday | Κυριακή |

**Used in:** Weekly work time declarations

---

### WeekDays

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

---

## Administrative

### BasicsAcceptance

Method of submitting the employment basics/terms acceptance document.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum BasicsAcceptance: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `WITH_FILE` | 0 | Submit with file | Υποβολή με αρχείο |
| `AWAIT_MY_ERGANI` | 1 | Await acceptance via MyErgani | Αναμονή αποδοχής μέσω MyErgani |
| `NOT_REQUIRED` | 2 | Not required | Δεν απαιτείται |

**Used in:** E3N, E3PD, MA forms (`f_basics_acceptance` field)

---

### SettlementType

Type of work arrangement/settlement for employment changes.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum SettlementType: int
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `COLLECTIVE` | 0 | Collective agreement | Συλλογική σύμβαση |
| `INDIVIDUAL` | 1 | Individual agreement | Ατομική συμφωνία |
| `NO` | 2 | No settlement | Χωρίς διευθέτηση |

**Used in:** MA forms (`f_eidos_dieuthethshs` field)

---

### UserType

Type of user authenticating with ERGANI.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum UserType: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `EXTERNAL` | '01' | External user | Εξωτερικός χρήστης |
| `ERGANI` | '02' | ERGANI user | Χρήστης ΕΡΓΑΝΗ |
| `EFKA` | '03' | EFKA user | Χρήστης ΕΦΚΑ |

**Used in:** Authentication

---

### Environment

ERGANI API environment. Note: This is a pure enum (not backed), so it does not use `HasLabels`.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum Environment
{
    case PRODUCTION;
    case TEST;

    public function getApiUrl(): string;
}
```

| Case | API URL |
|------|---------|
| `PRODUCTION` | `https://eservices.yeka.gr/WebservicesAPI/api/` |
| `TEST` | `https://trialv2eservices.yeka.gr/WebservicesAPI/Api/` |

**Used in:** Client configuration

---

## See Also

- [Models](/api/models) - Model classes using these enums
- [Responses](/api/responses) - Response classes
