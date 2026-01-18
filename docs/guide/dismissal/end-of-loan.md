# End of Loan (E6LD)

The E6LD form is used when an employee loan arrangement ends and the employee returns to their original employer. This form is submitted by the lending (direct) employer.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `EndOfLoan` |
| Action Code | `WebE6LD` |
| Declaration Model | `EndOfLoanDeclaration` |
| Use Case | Borrowed employee returns to original employer |

## When to Use E6LD

Use E6LD when:
- An employee loan arrangement (E3D/E3PD) is ending
- The borrowed employee is returning to their original employer
- The loan period has expired or is being terminated early

::: tip Loan Lifecycle
1. **E3D**: Lender reports lending an employee
2. **E3PD**: Borrower reports receiving a loaned employee
3. **E6LD**: Lender reports end of loan (this form)
:::

## Key Differences

E6LD is unique among E6 forms:
- **No salary field** - Employment continues with original employer
- **No compensation** - Not a termination of employment
- **No form file** - Just administrative record update
- **Has loan details** - Loan arrangement information

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\EndOfLoan;
use OxygenSuite\OxygenErgani\Models\Dismissal\EndOfLoanDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\LoanType;

$declaration = EndOfLoanDeclaration::make()
    // Branch (original employer's branch)
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1985')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15038512345')

    // Loan Details
    ->setLoanType(LoanType::GENUINE)
    ->setLoanStartDate('01/02/2024')
    ->setLoanEndDate('31/01/2025')
    ->setBorrowingCompanyAfm('555555555')
    ->setBorrowingCompanyName('BORROWER COMPANY LTD');

$response = (new EndOfLoan())->handle($declaration);
```

## E6LD-Specific Fields

In addition to [common fields](./index#common-fields), E6LD includes:

### Loan Details (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setLoanType()` | `f_borrow_type` | LoanType | Yes | Genuine loan or EPA |
| `setLoanStartDate()` | `f_borrow_date_from` | string | Yes | Loan start date |
| `setLoanEndDate()` | `f_borrow_date_to` | string | Yes | Loan end date |
| `setBorrowingCompanyAfm()` | `f_borrow_company_afm` | string | Yes | Borrower's AFM |
| `setBorrowingCompanyName()` | `f_borrow_company_eponimia` | string | Yes | Borrower's name |

### LoanType Enum

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `GENUINE` | 0 | Genuine borrowing | Γνήσιος δανεισμός |
| `EPA` | 1 | Temporary Employment Agency | Ε.Π.Α. |

## Complete Examples

### Genuine Loan Ending

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\EndOfLoan;
use OxygenSuite\OxygenErgani\Models\Dismissal\EndOfLoanDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\LoanType;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = EndOfLoanDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΚΩΝΣΤΑΝΤΙΝΟΥ')
    ->setFirstName('ΔΗΜΗΤΡΙΟΣ')
    ->setFatherName('ΑΝΤΩΝΙΟΣ')
    ->setMotherName('ΣΟΦΙΑ')
    ->setBirthDate('20/07/1988')
    ->setSex(Sex::MALE)
    ->setMaritalStatus(MaritalStatus::SINGLE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΘΙ345678')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')

    // Tax/Insurance
    ->setAfm('444555666')
    ->setTaxOffice('1234')
    ->setAmka('20078812345')

    // Genuine loan between partner companies
    ->setLoanType(LoanType::GENUINE)
    ->setLoanStartDate('01/03/2024')
    ->setLoanEndDate('28/02/2025')
    ->setBorrowingCompanyAfm('777888999')
    ->setBorrowingCompanyName('ΣΥΝΕΡΓΑΤΗΣ Α.Ε.')

    ->setComments('Λήξη δανεισμού εργαζομένου');

$response = (new EndOfLoan())->handle($declaration);
```

### EPA Worker Return

```php
$declaration = EndOfLoanDeclaration::make()
    // ... branch and personal fields ...

    // EPA placement ending
    ->setLoanType(LoanType::EPA)
    ->setLoanStartDate('15/02/2024')
    ->setLoanEndDate('15/05/2024')
    ->setBorrowingCompanyAfm('888999000')
    ->setBorrowingCompanyName('CLIENT COMPANY S.A.')

    ->setComments('Λήξη τοποθέτησης ΕΠΑ');
```

### Early Loan Termination

```php
$declaration = EndOfLoanDeclaration::make()
    // ... branch and personal fields ...

    // Loan ending early
    ->setLoanType(LoanType::GENUINE)
    ->setLoanStartDate('01/06/2024')
    ->setLoanEndDate('15/10/2024')  // Original end was 31/12/2024
    ->setBorrowingCompanyAfm('111222333')
    ->setBorrowingCompanyName('PARTNER LTD')

    ->setComments('Πρόωρη λήξη δανεισμού');
```

## Response Handling

```php
$response = (new EndOfLoan())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε6ΛΔ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Important Notes

1. **Submitted by Lender**: E6LD is submitted by the direct (lending) employer, not the borrower.

2. **No Salary or Compensation**: Unlike other E6 forms, E6LD doesn't include financial fields.

3. **Employment Continues**: The employee returns to work at the original employer.

4. **Loan End Date**: This is the actual end date, which may differ from the originally planned date.

5. **Match E3D/E3PD**: The loan details should match the original E3D submission.

## See Also

- [Dismissal Overview](./index) - Common fields and enums
- [Lending (E3D)](/guide/hiring/lending) - Submitting employee loans
- [Borrowed (E3PD)](/guide/hiring/borrowed) - Receiving loaned employees
