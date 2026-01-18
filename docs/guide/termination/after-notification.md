# Resignation After Notification (E5AO)

The E5AO form is used to confirm an employee's resignation after a previous E5O notification was submitted. This form links back to the original notification and finalizes the termination.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `ResignationAfterNotification` |
| Action Code | `WebE5AO` |
| Declaration Model | `ResignationAfterNotificationDeclaration` |
| Use Case | Confirm resignation after E5O notification |

## When to Use E5AO

Use E5AO when:
- You previously submitted an E5O notification for an absent employee
- The resignation has now been confirmed (employee contacted, time elapsed, etc.)
- You need to finalize the termination in ERGANI

::: warning Prerequisite
E5AO **requires** a prior E5O submission. You must have the E5O protocol number and submission date.
:::

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationAfterNotification;
use OxygenSuite\OxygenErgani\Models\Termination\ResignationAfterNotificationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = ResignationAfterNotificationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information (same as E5O)
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    ->setFatherName('ΝΙΚΟΛΑΟΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthDate('15/03/1990')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')

    // Tax/Insurance
    ->setAfm('123456789')
    ->setAmka('15039012345')

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('123456')
    ->setHiringDate('01/01/2020')
    ->setDepartureDate('15/01/2025')
    ->setGrossSalary(1500.00)

    // Link to E5O notification (REQUIRED)
    ->setNotificationProtocol('Ε5Ο12345')
    ->setNotificationDate('11/01/2025');

$response = (new ResignationAfterNotification())->handle($declaration);
```

## E5AO-Specific Fields

In addition to [common fields](./index#common-fields), E5AO includes:

### Salary (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary at departure |

### Notification Reference (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setNotificationProtocol()` | `f_oxlhsh_protocol` | string | Yes | E5O protocol number |
| `setNotificationDate()` | `f_oxlhsh_date_ypovolis` | string | Yes | E5O submission date (DD/MM/YYYY) |

::: info No Form File
Unlike E5N, E5AO does **not** require a signed form file (`f_file`). The E5O notification serves as the documentation.
:::

## Complete Example

### Full E5O → E5AO Workflow

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationNotification;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationAfterNotification;
use OxygenSuite\OxygenErgani\Models\Termination\NotificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\ResignationAfterNotificationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

// === Step 1: Submit E5O (when employee disappears) ===

$notification = NotificationDeclaration::make()
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    ->setLastName('ΚΩΝΣΤΑΝΤΙΝΟΥ')
    ->setFirstName('ΠΕΤΡΟΣ')
    ->setFatherName('ΑΝΤΩΝΙΟΣ')
    ->setMotherName('ΣΟΦΙΑ')
    ->setBirthDate('20/07/1988')
    ->setSex(Sex::MALE)

    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΘΙ345678')

    ->setAfm('444555666')
    ->setAmka('20078812345')

    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('456789')
    ->setHiringDate('01/03/2019')
    ->setDepartureDate('10/01/2025')

    ->setNotificationMethods(
        'Τηλεφωνική επικοινωνία 11/01/2025, 12/01/2025, 13/01/2025 (χωρίς απάντηση). ' .
        'Email στις 11/01/2025 και 14/01/2025. Συστημένη επιστολή στις 15/01/2025.'
    );

$notificationResponse = (new ResignationNotification())->handle($notification);

// Store the E5O details
$e5oProtocol = $notificationResponse[0]->protocol;  // e.g., 'Ε5Ο789'
$e5oDate = $notificationResponse[0]->submissionDate->format('d/m/Y');  // e.g., '15/01/2025'

// === Step 2: Submit E5AO (when resignation is confirmed) ===
// (typically after waiting period or employee confirmation)

$resignation = ResignationAfterNotificationDeclaration::make()
    // Same branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Same personal information
    ->setLastName('ΚΩΝΣΤΑΝΤΙΝΟΥ')
    ->setFirstName('ΠΕΤΡΟΣ')
    ->setFatherName('ΑΝΤΩΝΙΟΣ')
    ->setMotherName('ΣΟΦΙΑ')
    ->setBirthDate('20/07/1988')
    ->setSex(Sex::MALE)

    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΘΙ345678')

    ->setAfm('444555666')
    ->setAmka('20078812345')

    // Same employment details
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('456789')
    ->setHiringDate('01/03/2019')
    ->setDepartureDate('10/01/2025')  // Same as E5O

    // Salary (required for E5AO, not in E5O)
    ->setGrossSalary(1800.00)

    // Link to E5O notification (REQUIRED)
    ->setNotificationProtocol($e5oProtocol)
    ->setNotificationDate($e5oDate)

    ->setComments('Επιβεβαίωση οικειοθελούς αποχώρησης');

$resignationResponse = (new ResignationAfterNotification())->handle($resignation);
```

## Response Handling

```php
$response = (new ResignationAfterNotification())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε5ΑΟ123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Employment Type Restriction

Like E5O, E5AO only accepts:
- `EmploymentType::INDEFINITE` (0)
- `EmploymentType::FIXED_TERM` (1)

`EmploymentType::PROJECT` (2) is **not** allowed.

## Important Notes

1. **E5O Required First**: You cannot submit E5AO without a prior E5O submission.

2. **Protocol Must Match**: The `setNotificationProtocol()` must exactly match the protocol returned from E5O.

3. **Consistent Data**: Employee information should be consistent between E5O and E5AO.

4. **No Form File**: Unlike E5N, E5AO does not require a signed resignation form.

5. **Salary Required**: Unlike E5O, E5AO must include the employee's gross salary.

## See Also

- [Termination Overview](./index) - Common fields and enums
- [Resignation Notification (E5O)](./notification) - First step (notification)
- [Voluntary Resignation (E5N)](./voluntary) - Alternative when you have a signed form
