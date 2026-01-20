# Administrative Enums

Enums for administrative and system-related fields.

## BasicsAcceptance

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

## SettlementType

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

## UserType

Type of user authenticating with ERGANI.

```php
namespace OxygenSuite\OxygenErgani\Enums;

enum UserType: string
```

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `EXTERNAL` | 01 | External user | Εξωτερικός χρήστης |
| `ERGANI` | 02 | ERGANI user | Χρήστης ΕΡΓΑΝΗ |
| `EFKA` | 03 | EFKA user | Χρήστης ΕΦΚΑ |

**Used in:** Authentication

---

## Environment

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

::: tip
Use `Environment::TEST` during development to avoid affecting production data.
:::
