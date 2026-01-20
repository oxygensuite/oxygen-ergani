# Modification Models (MA/MAD)

Models for employment modification declarations.

## ModificationDeclaration (MA)

Regular employee modifications.

```php
namespace OxygenSuite\OxygenErgani\Models\Modification;

class ModificationDeclaration extends Declaration
```

### Key Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setModificationDate($date)` | `f_date_metabolhs` | Effective date |
| `setSettlementType($type)` | `f_eidos_dieuthethshs` | Settlement type |
| `addModificationTypeSelection($sel)` | `ModificationTypeSelections` | What changed |

### Modification Types

Use `ModificationTypeSelection` to specify what's being modified:

```php
use OxygenSuite\OxygenErgani\Models\Modification\ModificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Modification\ModificationTypeSelection;

$declaration = ModificationDeclaration::make()
    ->setBranchCode(0)
    ->setAfm('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setModificationDate('20/01/2025')
    ->setSettlementType(SettlementType::INDIVIDUAL)
    ->addModificationTypeSelection(
        ModificationTypeSelection::make()
            ->setCode('01')  // Salary change
    )
    ->setGrossSalary(1800.00);
```

---

## BorrowedModificationDeclaration (MAD)

Borrowed employee modifications.

```php
namespace OxygenSuite\OxygenErgani\Models\Modification;

class BorrowedModificationDeclaration extends Declaration
```

### Key Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setModificationDate($date)` | `f_date_metabolhs` | Effective date |
| `setSalaryPaymentSource($source)` | `f_kataboli_apodoxon` | Who pays salary |
| `setLoanType($type)` | `f_borrow_type` | Genuine or EPA |
| `setLoanStartDate($date)` | `f_borrow_date_from` | Loan start date |
| `setLoanEndDate($date)` | `f_borrow_date_to` | Loan end date |
| `setLendingCompanyAfm($afm)` | `f_borrow_company_afm` | Lending company AFM |

### Example

```php
use OxygenSuite\OxygenErgani\Models\Modification\BorrowedModificationDeclaration;
use OxygenSuite\OxygenErgani\Enums\{LoanType, SalaryPaymentSource};

$declaration = BorrowedModificationDeclaration::make()
    ->setBranchCode(0)
    ->setAfm('123456789')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setModificationDate('20/01/2025')
    ->setLoanType(LoanType::GENUINE)
    ->setSalaryPaymentSource(SalaryPaymentSource::DIRECT_EMPLOYER)
    ->setLoanStartDate('01/01/2025')
    ->setLoanEndDate('30/06/2025')
    ->setLendingCompanyAfm('111222333')
    ->setLendingCompanyName('LENDING COMPANY SA');
```

---

## ModificationTypeSelection

Nested model for specifying modification type codes (MA only).

```php
namespace OxygenSuite\OxygenErgani\Models\Modification;

class ModificationTypeSelection
```

### Methods

| Method | API Field | Description |
|--------|-----------|-------------|
| `setCode($code)` | `f_metabolhcode` | Modification type code |

### Common Codes

| Code | Description |
|------|-------------|
| `01` | Salary change |
| `02` | Working hours change |
| `03` | Employment status change |
| `04` | Work location change |
| `05` | Specialty change |

## See Also

- [Modifications Guide](/guide/modifications) - Complete MA/MAD usage guide
- [Common Fields](./common-fields) - Shared declaration fields
- [SettlementType Enum](/api/enums/administrative#settlementtype) - Settlement types
- [SalaryPaymentSource Enum](/api/enums/loan#salarypaymentcource) - Payment source
