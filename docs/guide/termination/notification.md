# Resignation Notification (E5O)

The E5O form is used to notify ERGANI about a possible voluntary resignation when an employee is absent without justification. This form serves as a formal record that the employer has attempted to contact the employee.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `ResignationNotification` |
| Action Code | `WebE5O` |
| Declaration Model | `NotificationDeclaration` |
| Use Case | Employee absent, possible resignation |

## When to Use E5O

Use E5O when:
- An employee is absent from work without notice or explanation
- The employer has attempted to contact the employee
- The employer suspects the employee may have resigned informally
- You need to document the situation before confirming the resignation

::: warning Two-Step Process
E5O is the **first step** in a two-step process:
1. **E5O**: Notify that employee may have resigned (this form)
2. **E5AO**: Confirm the resignation after notification

The E5AO links back to the E5O using the protocol number.
:::

## Key Differences from E5N

| Feature | E5O (Notification) | E5N (Resignation) |
|---------|-------------------|-------------------|
| Salary field | No | Yes |
| Form file | No | Yes |
| Notification methods | Yes | No |
| Stand-alone | No (needs E5AO) | Yes |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationNotification;
use OxygenSuite\OxygenErgani\Models\Termination\NotificationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = NotificationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')

    // Personal Information
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
    ->setDepartureDate('15/01/2025')  // Last day seen at work

    // Notification methods used to contact employee
    ->setNotificationMethods('Τηλεφωνική επικοινωνία, email, συστημένη επιστολή');

$response = (new ResignationNotification())->handle($declaration);

// Save the protocol number for E5AO
$notificationProtocol = $response[0]->protocol;
```

## E5O-Specific Fields

In addition to [common fields](./index#common-fields), E5O includes:

### Notification Methods (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setNotificationMethods()` | `f_tropoi_oxlhshs` | string | Yes | Description of contact attempts (max 500 chars) |

::: tip Notification Methods
Document all attempts to contact the employee:
- Phone calls (dates and times)
- Emails sent
- Registered letters
- Home visits
- Messages through colleagues
:::

### Fields NOT Included in E5O

Unlike other E5 forms, E5O does **not** include:
- `f_apodoxes` (salary) - Not applicable for notification
- `f_file` (form file) - No signed form required

## Complete Examples

### Standard Notification

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationNotification;
use OxygenSuite\OxygenErgani\Models\Termination\NotificationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;

$declaration = NotificationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΚΩΝΣΤΑΝΤΙΝΟΥ')
    ->setFirstName('ΝΙΚΟΛΑΟΣ')
    ->setFatherName('ΓΕΩΡΓΙΟΣ')
    ->setMotherName('ΕΛΕΝΗ')
    ->setBirthDate('05/11/1992')
    ->setSex(Sex::MALE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ654321')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('05119212345')

    // Employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::INDEFINITE)
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('345678')
    ->setHiringDate('01/03/2021')
    ->setDepartureDate('10/01/2025')

    // Detailed notification attempts
    ->setNotificationMethods(
        'Τηλεφωνική επικοινωνία στις 11/01/2025 (χωρίς απάντηση). ' .
        'Email στις 12/01/2025. Συστημένη επιστολή στις 13/01/2025.'
    )

    ->setComments('Ο εργαζόμενος δεν εμφανίστηκε από 11/01/2025');

$response = (new ResignationNotification())->handle($declaration);

// Store for E5AO
$notificationProtocol = $response[0]->protocol;
$notificationDate = $response[0]->submissionDate->format('d/m/Y');
```

### Fixed-Term Employee Notification

```php
$declaration = NotificationDeclaration::make()
    // ... branch and personal fields ...

    // Fixed-term employment
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setEmploymentType(EmploymentType::FIXED_TERM)  // Only INDEFINITE or FIXED_TERM allowed
    ->setFixedTermFrom('01/06/2024')
    ->setFixedTermTo('31/05/2025')
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setSpecialtyCode('234567')
    ->setHiringDate('01/06/2024')
    ->setDepartureDate('15/01/2025')

    ->setNotificationMethods('Τηλεφωνικές κλήσεις και email χωρίς ανταπόκριση');
```

::: warning Employment Type Restriction
E5O only accepts:
- `EmploymentType::INDEFINITE` (0)
- `EmploymentType::FIXED_TERM` (1)

`EmploymentType::PROJECT` (2) is **not** allowed for E5O.
:::

## Response Handling

```php
$response = (new ResignationNotification())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε5Ο456')
    echo $result->submissionDate->format('d/m/Y H:i:s');

    // Store these for E5AO submission later
    $protocol = $result->protocol;
    $submissionDate = $result->submissionDate;
}
```

## Complete E5O → E5AO Workflow

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationNotification;
use OxygenSuite\OxygenErgani\Http\Documents\Termination\ResignationAfterNotification;
use OxygenSuite\OxygenErgani\Models\Termination\NotificationDeclaration;
use OxygenSuite\OxygenErgani\Models\Termination\ResignationAfterNotificationDeclaration;

// Step 1: Submit E5O notification
$notification = NotificationDeclaration::make()
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    // ... other fields ...
    ->setNotificationMethods('Τηλέφωνο, email, συστημένη επιστολή');

$notificationResponse = (new ResignationNotification())->handle($notification);
$e5oProtocol = $notificationResponse[0]->protocol;
$e5oDate = $notificationResponse[0]->submissionDate->format('d/m/Y');

// Step 2: After confirming resignation, submit E5AO
$resignation = ResignationAfterNotificationDeclaration::make()
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΙΩΑΝΝΗΣ')
    // ... same employee data ...
    ->setGrossSalary(1500.00)

    // Link to E5O
    ->setNotificationProtocol($e5oProtocol)
    ->setNotificationDate($e5oDate);

$resignationResponse = (new ResignationAfterNotification())->handle($resignation);
```

## Important Notes

1. **No Salary or Form File**: E5O is a notification only - salary and signed forms come with E5AO.

2. **Document Everything**: The notification methods field should detail all attempts to contact the employee.

3. **Store the Protocol**: You'll need the E5O protocol number to submit E5AO later.

4. **Timing**: Submit E5O as soon as you've made reasonable contact attempts.

5. **Employment Type**: Only `INDEFINITE` or `FIXED_TERM` are allowed - not `PROJECT`.

## See Also

- [Termination Overview](./index) - Common fields and enums
- [Resignation After Notification (E5AO)](./after-notification) - Confirm resignation
- [Voluntary Resignation (E5N)](./voluntary) - Standard resignation with signed form
