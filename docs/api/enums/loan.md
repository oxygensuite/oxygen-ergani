# Loan/Borrowing Enums

Enums for employee loan and borrowing arrangements.

## LoanType

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

## SalaryPaymentSource

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
