# Dismissal Models (E6)

Models for employer-initiated termination declarations.

All dismissal models extend the base `Dismissal\Declaration` class.

## Common Traits

- `HasEmploymentClassification` - Employment status, worker type, specialty
- `HasCollectiveDismissal` - Collective layoff tracking
- `HasTerminationNotification` - Notification date
- `HasNoticePeriod` - Notice period details (E6NMP)
- `HasLoanDetails` - Loan details (E6LD)
- `HasSalary`, `HasCompensation`, `HasFormFile` (reused from E5)

---

## DismissalWithoutNoticeDeclaration (E6NXP)

Immediate dismissal without notice period.

```php
namespace OxygenSuite\OxygenErgani\Models\Dismissal;

class DismissalWithoutNoticeDeclaration extends Declaration
```

### Traits Used

- `HasEmploymentClassification`
- `HasCollectiveDismissal`
- `HasTerminationNotification`
- `HasSalary`
- `HasCompensation`
- `HasFormFile`

### Key Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setDismissalDate($date)` | `f_apolysisdate` | Dismissal date |
| `setTerminationNotificationDate($date)` | `f_koinopoihshdate` | Notification date |
| `setIsCollectiveDismissal($bool)` | `f_omadikhap` | Collective dismissal flag |
| `setCompensationAmount($amount)` | `f_posoapozimiosis` | Severance amount |

---

## DismissalWithNoticeDeclaration (E6NMP)

Dismissal with advance notice period.

```php
namespace OxygenSuite\OxygenErgani\Models\Dismissal;

class DismissalWithNoticeDeclaration extends Declaration
```

### Traits Used

- `HasEmploymentClassification`
- `HasCollectiveDismissal`
- `HasNoticePeriod`
- `HasSalary`
- `HasCompensation`
- `HasFormFile`

### Additional Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setNoticePeriodMonths($months)` | `f_minesproidopoihsh` | Notice period (1-4 months) |
| `setNoticePeriodEndDate($date)` | `f_date_proidop_lixh` | Notice period end date |

---

## RetirementDismissalDeclaration (E6SXP)

Employer-initiated retirement dismissal.

```php
namespace OxygenSuite\OxygenErgani\Models\Dismissal;

class RetirementDismissalDeclaration extends Declaration
```

### Traits Used

- `HasEmploymentClassification`
- `HasTerminationNotification`
- `HasSalary`
- `HasCompensation`
- `HasFormFile`

---

## EndOfLoanDeclaration (E6LD)

End of employee loan arrangement.

```php
namespace OxygenSuite\OxygenErgani\Models\Dismissal;

class EndOfLoanDeclaration extends Declaration
```

### Traits Used

- `HasLoanDetails`
- `HasSalary`
- `HasFormFile`

### Additional Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setLoanType($type)` | `f_borrow_type` | Genuine or EPA |
| `setLoanStartDate($date)` | `f_borrow_date_from` | Loan start date |
| `setLoanEndDate($date)` | `f_borrow_date_to` | Loan end date |
| `setLendingCompanyAfm($afm)` | `f_borrow_company_afm` | Lending company AFM |

---

## TrialPeriodTerminationDeclaration (E6LT)

Trial period automatic termination.

```php
namespace OxygenSuite\OxygenErgani\Models\Dismissal;

class TrialPeriodTerminationDeclaration extends Declaration
```

### Traits Used

- `HasSalary`
- `HasFormFile`

---

## TransferDeclaration (E6M)

Employee transfer to another company.

```php
namespace OxygenSuite\OxygenErgani\Models\Dismissal;

class TransferDeclaration extends Declaration
```

### Traits Used

- `HasSalary`
- `HasFormFile`

### Additional Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setTransferCompanyAfm($afm)` | `f_transfer_company_afm` | Receiving company AFM |
| `setTransferCompanyName($name)` | `f_transfer_company_eponimia` | Receiving company name |

---

## Summary Table

| Class | Form | Notice | Compensation | Collective |
|-------|------|--------|--------------|------------|
| `DismissalWithoutNoticeDeclaration` | E6NXP | No | Yes | Yes |
| `DismissalWithNoticeDeclaration` | E6NMP | Yes | Yes | Yes |
| `RetirementDismissalDeclaration` | E6SXP | No | Yes | No |
| `EndOfLoanDeclaration` | E6LD | No | No | No |
| `TrialPeriodTerminationDeclaration` | E6LT | No | No | No |
| `TransferDeclaration` | E6M | No | No | No |

## See Also

- [Dismissal Guide](/guide/dismissal/) - Complete E6 usage guide
- [Common Fields](./common-fields) - Shared declaration fields
- [NoticePeriodMonths Enum](/api/enums/termination#noticeperiodmonths) - Notice period values
