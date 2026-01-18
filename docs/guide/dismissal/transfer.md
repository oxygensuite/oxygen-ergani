# Transfer (E6M)

The E6M form is used when an employee is transferred to another company. This typically occurs in business sales, mergers, or corporate restructuring where employment continues with a new employer.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `Transfer` |
| Action Code | `WebE6M` |
| Declaration Model | `TransferDeclaration` |
| Use Case | Employee moving to another company |

## When to Use E6M

Use E6M when:
- A business is sold and employees move to the new owner
- Companies merge and employees transfer to the surviving entity
- A business unit is acquired with its employees
- Any situation where employment continues with a different employer

::: tip E6M vs E3M
- **E6M**: Submitted by the **old employer** (reporting the departure)
- **E3M**: Submitted by the **new employer** (reporting the arrival)

Both forms are required for a transfer to be complete.
:::

## Key Differences

E6M is the simplest E6 form:
- **No salary field** - Employment continues with new employer
- **No compensation** - Not a termination
- **No form file** - Just administrative record
- **No employment classification** - Basic employee info only

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\Transfer;
use OxygenSuite\OxygenErgani\Models\Dismissal\TransferDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;

$declaration = TransferDeclaration::make()
    // Branch (your branch)
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

    // Transfer Details
    ->setTransferDate('31/01/2025')
    ->setTransferCompanyAfm('999888777')
    ->setTransferCompanyName('NEW COMPANY LTD');

$response = (new Transfer())->handle($declaration);
```

## E6M-Specific Fields

In addition to [common fields](./index#common-fields), E6M includes:

### Transfer Details (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setTransferDate()` | `f_date_metabibashs` | string | Yes | Transfer date (DD/MM/YYYY) |
| `setTransferCompanyAfm()` | `f_transfer_company_afm` | string | Yes | New employer's AFM (9 digits) |
| `setTransferCompanyName()` | `f_transfer_company_eponimia` | string | Yes | New employer's name (max 150 chars) |

## Complete Examples

### Business Acquisition Transfer

```php
use OxygenSuite\OxygenErgani\Http\Documents\Dismissal\Transfer;
use OxygenSuite\OxygenErgani\Models\Dismissal\TransferDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = TransferDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΚΩΝΣΤΑΝΤΙΝΟΥ')
    ->setFirstName('ΜΑΡΙΑ')
    ->setFatherName('ΓΕΩΡΓΙΟΣ')
    ->setMotherName('ΕΛΕΝΗ')
    ->setBirthDate('22/08/1978')
    ->setSex(Sex::FEMALE)
    ->setMaritalStatus(MaritalStatus::MARRIED)
    ->setNumberOfChildren(2)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΘΕΣΣΑΛΟΝΙΚΗΣ')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('22087812345')

    // Transfer to acquiring company
    ->setTransferDate('01/02/2025')
    ->setTransferCompanyAfm('111222333')
    ->setTransferCompanyName('ΝΕΟΣ ΙΔΙΟΚΤΗΤΗΣ Α.Ε.')

    ->setComments('Μεταβίβαση λόγω εξαγοράς επιχείρησης');

$response = (new Transfer())->handle($declaration);
```

### Multiple Employees Transfer (Business Sale)

```php
$employees = [
    ['afm' => '111111111', 'lastName' => 'ΠΑΠΑΔΟΠΟΥΛΟΣ', 'firstName' => 'ΙΩΑΝΝΗΣ'],
    ['afm' => '222222222', 'lastName' => 'ΓΕΩΡΓΙΟΥ', 'firstName' => 'ΜΑΡΙΑ'],
    ['afm' => '333333333', 'lastName' => 'ΝΙΚΟΛΑΟΥ', 'firstName' => 'ΠΕΤΡΟΣ'],
];

$declarations = [];
foreach ($employees as $emp) {
    $declarations[] = TransferDeclaration::make()
        ->setBranchCode(0)
        ->setLaborInspectionServiceCode('12345')
        ->setDypaServiceCode('123456')
        ->setLastName($emp['lastName'])
        ->setFirstName($emp['firstName'])
        ->setAfm($emp['afm'])
        // ... other personal fields ...
        ->setTransferDate('01/02/2025')
        ->setTransferCompanyAfm('111222333')
        ->setTransferCompanyName('ACQUIRING COMPANY S.A.');
}

$response = (new Transfer())->handle($declarations);
```

## Response Handling

```php
$response = (new Transfer())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε6Μ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Complete Transfer Workflow

When transferring employees to a new company, both parties must submit forms:

```
Old Employer (You)              New Employer (Acquirer)
       |                               |
       |--- Submit E6M --------------->|
       |   (reports departure)         |
       |                               |--- Submit E3M
       |                               |   (reports arrival)
       |                               |
       v                               v
   Employees now with new employer
```

### Your Side (E6M)

```php
// Submit E6M for each transferring employee
$e6mDeclaration = TransferDeclaration::make()
    // ... employee info ...
    ->setTransferDate('01/02/2025')
    ->setTransferCompanyAfm('111222333')
    ->setTransferCompanyName('NEW OWNER COMPANY');

(new Transfer())->handle($e6mDeclaration);
```

### New Employer's Side (E3M)

The new employer will submit E3M:

```php
use OxygenSuite\OxygenErgani\Http\Documents\Hiring\HiringModification;
use OxygenSuite\OxygenErgani\Models\Hiring\ModificationDeclaration;

$e3mDeclaration = ModificationDeclaration::make()
    // ... same employee info ...
    ->setTransferDate('31/01/2025')  // Last day with old employer
    ->setTransferCompanyAfm('YOUR_AFM')  // Old employer's AFM
    ->setTransferCompanyName('YOUR COMPANY NAME')
    // ... employment terms continuing ...
;

(new HiringModification())->handle($e3mDeclaration);
```

## Important Notes

1. **No Financial Fields**: E6M doesn't include salary or compensation - employment continues.

2. **No Form File**: Unlike other E6 forms, E6M doesn't require a signed document.

3. **Paired with E3M**: The receiving company must submit E3M to complete the transfer.

4. **Transfer Date**: This is typically the last day with the old employer (E6M) and first day with new employer is the next day (E3M).

5. **Employment Rights Preserved**: Transferred employees retain their seniority and rights.

## See Also

- [Dismissal Overview](./index) - Common fields and enums
- [Transfer Hiring (E3M)](/guide/hiring/transfer) - New employer's side
