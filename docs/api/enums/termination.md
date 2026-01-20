# Termination Enums

Enums for termination and dismissal forms.

## FixedTermTerminationReason

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

## NoticePeriodMonths

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
