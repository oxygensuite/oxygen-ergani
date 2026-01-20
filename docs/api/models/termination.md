# Termination Models (E5/E7)

Models for voluntary termination declarations.

All termination models extend the base `Termination\Declaration` class.

## Common Traits

- `HasSalary` - Gross salary at departure
- `HasCompensation` - Severance amount
- `HasFormFile` - Signed PDF form
- `HasNotificationReference` - Link to prior E5O

---

## VoluntaryResignationDeclaration (E5N)

Standard voluntary resignation.

```php
namespace OxygenSuite\OxygenErgani\Models\Termination;

class VoluntaryResignationDeclaration extends Declaration
```

### Traits Used

- `HasSalary`
- `HasFormFile`

### Key Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setTerminationDate($date)` | `f_apoxwrisidate` | Termination date |
| `setGrossSalary($amount)` | `f_apodoxes` | Salary at departure |
| `setFormFile($base64)` | `f_file` | Signed form (Base64 PDF) |

---

## NotificationDeclaration (E5O)

Notification of possible resignation (employee absent).

```php
namespace OxygenSuite\OxygenErgani\Models\Termination;

class NotificationDeclaration extends Declaration
```

::: info No Signed Form
E5O does not require a signed form or salary information.
:::

---

## ResignationAfterNotificationDeclaration (E5AO)

Confirmed resignation after E5O notification.

```php
namespace OxygenSuite\OxygenErgani\Models\Termination;

class ResignationAfterNotificationDeclaration extends Declaration
```

### Traits Used

- `HasSalary`
- `HasFormFile`
- `HasNotificationReference`

### Additional Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setNotificationSubmissionId($id)` | `f_submissions_id` | E5O submission ID |
| `setNotificationProtocol($protocol)` | `f_protocol` | E5O protocol number |
| `setNotificationDate($date)` | `f_notification_date` | E5O notification date |

---

## DeathTerminationDeclaration (E5D)

Termination due to employee death.

```php
namespace OxygenSuite\OxygenErgani\Models\Termination;

class DeathTerminationDeclaration extends Declaration
```

### Traits Used

- `HasSalary`
- `HasFormFile`

---

## CompensatedExitDeclaration (E5E)

Voluntary exit with severance pay.

```php
namespace OxygenSuite\OxygenErgani\Models\Termination;

class CompensatedExitDeclaration extends Declaration
```

### Traits Used

- `HasSalary`
- `HasCompensation`
- `HasFormFile`

### Additional Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setCompensationAmount($amount)` | `f_posoapozimiosis` | Severance amount |

---

## VoluntaryRetirementDeclaration (E5S)

Voluntary retirement with compensation.

```php
namespace OxygenSuite\OxygenErgani\Models\Termination;

class VoluntaryRetirementDeclaration extends Declaration
```

### Traits Used

- `HasSalary`
- `HasCompensation`
- `HasFormFile`

---

## MandatoryRetirementDeclaration (E5DS)

Mandatory retirement (15 years/age limit).

```php
namespace OxygenSuite\OxygenErgani\Models\Termination;

class MandatoryRetirementDeclaration extends Declaration
```

### Traits Used

- `HasSalary`
- `HasCompensation`
- `HasFormFile`

---

## FixedTermTerminationDeclaration (E7N)

Fixed-term contract termination.

```php
namespace OxygenSuite\OxygenErgani\Models\Termination;

class FixedTermTerminationDeclaration extends Dismissal\Declaration
```

::: warning Different Base Class
E7N extends `Dismissal\Declaration` (uses `f_apolysisdate` instead of `f_apoxwrisidate`).
:::

### Traits Used

- `HasEmploymentClassification` (from Dismissal)
- `HasSalary`

### Key Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setDismissalDate($date)` | `f_apolysisdate` | Termination date |
| `setTerminationReason($reason)` | `f_logosperatosis` | Reason for termination |
| `setEmploymentStatus($status)` | `f_kathestosapasxolisis` | Employment status |
| `setWorkerType($type)` | `f_xaraktirismos` | Worker/Employee |

::: info No Signed Form
E7N does NOT have `f_file` (signed form) - only `f_foreign_file` and `f_young_file`.
:::

::: warning Employment Type Restriction
`f_sxeshapasxolisis` only accepts `FIXED_TERM (1)` or `PROJECT (2)` - NOT `INDEFINITE (0)`.
:::

## See Also

- [Termination Guide](/guide/termination/) - Complete E5 usage guide
- [Fixed-Term Guide](/guide/fixed-term) - E7 usage guide
- [Common Fields](./common-fields) - Shared declaration fields
- [Termination Enums](/api/enums/termination) - Termination reasons
