# Fixed-Term Contract Termination (E7)

The E7N form is used to report the termination of fixed-term employment contracts. This is distinct from E5 (employee-initiated terminations) and E6 (employer-initiated dismissals) — E7 handles cases where a fixed-term contract naturally expires or is terminated early.

## Overview

| Property | Value |
|----------|-------|
| Document Class | `FixedTermTermination` |
| Action Code | `WebE7N` |
| Declaration Model | `FixedTermTerminationDeclaration` |
| Use Case | Fixed-term or project-based contract endings |

## When to Use E7N

Use E7N when:
- A fixed-term contract reaches its natural end date
- A project-based contract completes
- A fixed-term contract is terminated early by either party
- Both parties mutually agree to end a fixed-term contract early

::: warning Employment Type Restriction
E7N only accepts fixed-term (1) or project-based (2) contracts. It does **not** accept indefinite-term contracts (0). For indefinite contracts, use E5 or E6 forms instead.
:::

## Key Differences from E5/E6

| Feature | E7N | E5/E6 |
|---------|-----|-------|
| Contract Type | Fixed-term/Project only | Any type |
| Uses | `f_apolysisdate` | E5: `f_apoxwrisidate`, E6: `f_apolysisdate` |
| Signed Form | Not required (`f_file`) | Usually required |
| Termination Reason | Required (enum) | Type-specific |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\FixedTermTermination;
use OxygenSuite\OxygenErgani\Models\Termination\FixedTermTerminationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\FixedTermTerminationReason;

$declaration = FixedTermTerminationDeclaration::make()
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

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setSpecialtyCode('123456')
    ->setEmploymentRelationship(EmploymentType::FIXED_TERM)

    // Contract and Termination Dates
    ->setHiringDate('01/01/2024')
    ->setContractEndDate('31/12/2024')
    ->setTerminationDate('31/12/2024')

    // Salary
    ->setGrossSalary(1500.00)

    // Termination Reason
    ->setTerminationReason(FixedTermTerminationReason::CONTRACT_EXPIRATION);

$response = (new FixedTermTermination())->handle($declaration);
```

## Field Reference

### Employment Classification (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setEmploymentStatus()` | `f_kathestosapasxolisis` | EmploymentStatus | Yes | Full/Part-time |
| `setWorkerType()` | `f_xaraktirismos` | WorkerType | Yes | Worker/Employee |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty code |
| `setEmploymentRelationship()` | `f_sxeshapasxolisis` | EmploymentType | Yes | **Only 1 or 2** |

### Contract Dates (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setHiringDate()` | `f_proslipsidate` | string | Yes | Contract start date |
| `setContractEndDate()` | `f_lixisymbashdate` | string | Yes | Contractual end date |
| `setTerminationDate()` | `f_apolysisdate` | string | Yes | Actual termination date |

### Compensation Clause

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCompensationClause()` | `f_oros` | bool | No | Whether Article 40 compensation applies |

::: info Article 40 Compensation
Per Law 3986/2011 Article 40, fixed-term contracts may include a clause that applies indefinite contract severance rules if terminated early. Set `setCompensationClause(true)` if this clause exists.
:::

### Salary (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary at termination |

### Termination Reason (Required)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setTerminationReason()` | `f_logosperatosis` | FixedTermTerminationReason | Yes | Reason code |
| `setTerminationReasonComments()` | `f_logosperatosiscomments` | string | No | Additional comments (max 100 chars) |

## FixedTermTerminationReason Enum

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `CONTRACT_EXPIRATION` | 0 | Contract Expiration | Λήξη Συμπεφωνημένου Χρόνου |
| `WORK_COMPLETION` | 3 | Work Completion | Ολοκλήρωση Έργου με Όρο Πρόωρης Καταγγελίας |
| `EARLY_BY_EMPLOYER` | 4 | Early Termination by Employer | Καταγγελία πριν Λήξη για Σπουδαίο Λόγο |
| `EARLY_BY_EMPLOYEE` | 5 | Early Termination by Employee | Καταγγελία πριν Λήξη χωρίς Σπουδαίο Λόγο |
| `MUTUAL_AGREEMENT` | 6 | Mutual Agreement | Συναινετική Λύση πριν Λήξη |

::: warning Non-Sequential Values
Note that the enum values are non-sequential (0, 3, 4, 5, 6) as defined in the XSD schema.
:::

## EmploymentType Enum (E7 Usage)

For E7N, only these values are valid:

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `FIXED_TERM` | 1 | Fixed term | Ορισμένου χρόνου |
| `PROJECT` | 2 | Project-based | Έργου |

::: danger Not Allowed for E7
`INDEFINITE (0)` and `BORROWED (3)` are **not valid** for E7N submissions.
:::

## Complete Examples

### Natural Contract Expiration

```php
use OxygenSuite\OxygenErgani\Http\Documents\Termination\FixedTermTermination;
use OxygenSuite\OxygenErgani\Models\Termination\FixedTermTerminationDeclaration;
use OxygenSuite\OxygenErgani\Enums\Sex;
use OxygenSuite\OxygenErgani\Enums\EmploymentStatus;
use OxygenSuite\OxygenErgani\Enums\WorkerType;
use OxygenSuite\OxygenErgani\Enums\EmploymentType;
use OxygenSuite\OxygenErgani\Enums\FixedTermTerminationReason;
use OxygenSuite\OxygenErgani\Enums\MaritalStatus;

$declaration = FixedTermTerminationDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionServiceCode('12345')
    ->setDypaServiceCode('123456')
    ->setBranchActivityCode('4711')

    // Personal
    ->setLastName('ΚΩΝΣΤΑΝΤΙΝΟΥ')
    ->setFirstName('ΜΑΡΙΑ')
    ->setFatherName('ΔΗΜΗΤΡΙΟΣ')
    ->setMotherName('ΕΛΕΝΗ')
    ->setBirthDate('22/08/1992')
    ->setSex(Sex::FEMALE)
    ->setMaritalStatus(MaritalStatus::SINGLE)

    // Identity
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΖΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΑΘΗΝΩΝ')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('22089212345')

    // Employment Classification
    ->setEmploymentStatus(EmploymentStatus::FULL)
    ->setWorkerType(WorkerType::EMPLOYEE)
    ->setSpecialtyCode('345678')
    ->setEmploymentRelationship(EmploymentType::FIXED_TERM)

    // Contract expired on agreed date
    ->setHiringDate('01/07/2024')
    ->setContractEndDate('31/12/2024')
    ->setTerminationDate('31/12/2024')

    // Salary
    ->setGrossSalary(1800.00)

    // Natural expiration
    ->setTerminationReason(FixedTermTerminationReason::CONTRACT_EXPIRATION)
    ->setComments('Λήξη σύμβασης ορισμένου χρόνου');

$response = (new FixedTermTermination())->handle($declaration);
```

### Project Completion

```php
$declaration = FixedTermTerminationDeclaration::make()
    // ... branch and personal fields ...

    // Project-based employment
    ->setEmploymentRelationship(EmploymentType::PROJECT)

    // Project dates
    ->setHiringDate('01/03/2024')
    ->setContractEndDate('30/06/2024')  // Estimated end
    ->setTerminationDate('15/06/2024')  // Actual completion (early)

    ->setGrossSalary(2500.00)

    // Project completed
    ->setTerminationReason(FixedTermTerminationReason::WORK_COMPLETION)
    ->setTerminationReasonComments('Έργο ολοκληρώθηκε νωρίτερα');
```

### Early Termination with Compensation Clause

```php
$declaration = FixedTermTerminationDeclaration::make()
    // ... branch and personal fields ...

    ->setEmploymentRelationship(EmploymentType::FIXED_TERM)

    // Contract with compensation clause (Article 40)
    ->setCompensationClause(true)

    ->setHiringDate('01/01/2024')
    ->setContractEndDate('31/12/2024')
    ->setTerminationDate('31/03/2024')  // Early termination

    ->setGrossSalary(2000.00)

    // Employer-initiated early termination
    ->setTerminationReason(FixedTermTerminationReason::EARLY_BY_EMPLOYER)
    ->setTerminationReasonComments('Αναδιάρθρωση τμήματος');
```

### Mutual Agreement to End Early

```php
$declaration = FixedTermTerminationDeclaration::make()
    // ... branch and personal fields ...

    ->setEmploymentRelationship(EmploymentType::FIXED_TERM)

    ->setHiringDate('01/01/2024')
    ->setContractEndDate('31/12/2024')
    ->setTerminationDate('30/06/2024')  // Agreed end date

    ->setGrossSalary(1600.00)

    // Mutual agreement
    ->setTerminationReason(FixedTermTerminationReason::MUTUAL_AGREEMENT)
    ->setTerminationReasonComments('Κοινή συμφωνία εργοδότη-εργαζομένου');
```

### Foreign National with Seasonal Visa

```php
$declaration = FixedTermTerminationDeclaration::make()
    // ... branch fields ...

    // Personal - Foreign national
    ->setLastName('SMITH')
    ->setFirstName('JOHN')
    ->setFatherName('DAVID')
    ->setMotherName('MARY')
    ->setBirthDate('10/05/1988')
    ->setSex(Sex::MALE)

    // Foreign nationality with seasonal visa
    ->setNationality('004')  // Albania
    ->setIdType('ΔΙΑΒ')
    ->setIdNumber('AB1234567')

    // Seasonal work visa
    ->setHasSeasonalVisa(true)
    ->setSeasonalVisaNumber('VISA2024001')
    ->setSeasonalVisaFrom('01/06/2024')
    ->setSeasonalVisaTo('30/09/2024')

    // ... tax/insurance ...

    ->setEmploymentRelationship(EmploymentType::FIXED_TERM)

    // Seasonal contract ended
    ->setHiringDate('01/06/2024')
    ->setContractEndDate('30/09/2024')
    ->setTerminationDate('30/09/2024')

    ->setGrossSalary(1400.00)

    ->setTerminationReason(FixedTermTerminationReason::CONTRACT_EXPIRATION)

    // Attach foreign national documentation
    ->setForeignFile(base64_encode(file_get_contents('foreign_worker.pdf')));
```

## Response Handling

```php
$response = (new FixedTermTermination())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε7Ν123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

After a successful submission, retrieve the official PDF document:

```php
$pdfBase64 = (new FixedTermTermination())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

// Save to file
file_put_contents('fixed-term-termination.pdf', base64_decode($pdfBase64));
```

## Files

E7N supports optional documentation files:

| Method | API Field | Description |
|--------|-----------|-------------|
| `setForeignFile()` | `f_foreign_file` | Foreign national documentation (Base64 PDF) |
| `setYoungFile()` | `f_young_file` | Minor worker documentation (Base64 PDF) |

::: info No Signed Form Required
Unlike E5 and E6 forms, E7N does **not** require a signed termination form (`f_file`). Only the foreign/young worker files are supported.
:::

## Best Practices

1. **Match Dates**: For natural expiration, ensure `terminationDate` equals `contractEndDate`.

2. **Early Termination**: When ending early, set `terminationDate` before `contractEndDate` and choose the appropriate reason.

3. **Compensation Clause**: Document whether Article 40 applies before submission.

4. **Project Work**: For project-based contracts, the actual completion date may differ from the estimated end.

5. **Foreign Nationals**: Include visa/permit documentation when applicable.

## Important Notes

1. **Employment Type**: Only fixed-term (1) or project (2) — never indefinite (0).

2. **No Signed Form**: E7N doesn't require `f_file` unlike other termination forms.

3. **Reason Required**: Always specify the termination reason from the enum.

4. **Article 40 Clause**: Set compensation clause flag if severance rules apply.

5. **Seasonal Workers**: Foreign seasonal workers need visa documentation.

## See Also

- [New Hire (E3N)](/guide/hiring/new) - Creating fixed-term contracts
- [Voluntary Resignation (E5N)](/guide/termination/voluntary) - Employee-initiated termination
- [Dismissal Overview](/guide/dismissal/) - Employer-initiated termination
