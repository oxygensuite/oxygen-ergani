# Personal Information Enums

Enums for employee personal information fields.

## Sex

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

## MaritalStatus

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
