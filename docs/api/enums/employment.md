# Employment Enums

Enums for employment classification and contract details.

## EmploymentStatus

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

## WorkerType

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

## EmploymentType

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

## WorkLocation

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

## ResponsiblePosition

Indicates whether the employee holds a supervisory, managerial, or confidential position.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum ResponsiblePosition: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `NONE` | *(empty)* | Not applicable | Δεν εφαρμόζεται |
| `NO` | 1 | No | Όχι |
| `MANAGERIAL_AUTHORITY` | 2 | Position with managerial authority | Θέση με διευθυντικό δικαίωμα |
| `SALARY_4X_MINIMUM` | 3 | Salary at least 4x minimum wage | Αποδοχές τουλάχιστον 4πλάσιες του κατώτατου μισθού |
| `SALARY_6X_MINIMUM` | 4 | Salary at least 6x minimum wage | Αποδοχές τουλάχιστον 6πλάσιες του κατώτατου μισθού |

**Used in:** E3 forms (`f_responsible_position` field)

---

## IndividualContract

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

## SpecialCase

Special employment case for public sector employees under private law.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum SpecialCase: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `NONE` | *(empty)* | Not applicable | Δεν εφαρμόζεται |
| `PRIVATE_LAW_NARROW_PUBLIC` | 2 | Private law - Narrow public sector | Ιδιωτικού δικαίου - Στενός δημόσιος τομέας |
| `PRIVATE_LAW_BROADER_PUBLIC` | 3 | Private law - Broader public sector | Ιδιωτικού δικαίου - Ευρύτερος δημόσιος τομέας |

**Used in:** E3, MA forms (`f_special_case` field)
