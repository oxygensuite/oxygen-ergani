# Hiring (E3)

The E3 hiring declarations report new employee hirings, transfers, and lending arrangements to ERGANI. There are four types of E3 forms, each for a different hiring scenario.

## Form Types

| Class | Action | Form | Description | Guide |
|-------|--------|------|-------------|-------|
| `HiringNew` | WebE3N | E3N | New employee hiring | [New Hire](./new) |
| `HiringModification` | WebE3M | E3M | Employee transfer from another company | [Transfer](./transfer) |
| `HiringDeletion` | WebE3D | E3D | Employee lending FROM direct employer | [Lending](./lending) |
| `HiringWithLending` | WebE3PD | E3PD | Employee hiring TO indirect employer | [Borrowed](./borrowed) |

### When to Use Each Form

- **[E3N (New Hire)](./new)**: Standard new hire - employee starting fresh employment
- **[E3M (Transfer)](./transfer)**: Employee transferred from another company (business sale, merger, etc.)
- **[E3D (Lending)](./lending)**: You are lending your employee to another company (you are the direct employer)
- **[E3PD (Borrowed)](./borrowed)**: You are receiving a borrowed employee (you are the indirect employer)

## Common Fields

All E3 declarations share a common base class (`Declaration`) with personal and employment fields. Each form type adds specific fields documented in their respective pages.

### Branch/Location

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int | Yes | Branch code (0 = main) |
| `setRelatedProtocol()` | `f_rel_protocol` | string | No | Related submission protocol |
| `setRelatedDate()` | `f_rel_date` | string | No | Related submission date (DD/MM/YYYY) |
| `setLaborInspectionServiceCode()` | `f_ypiresia_sepe` | string | Yes | 5-digit SEPE code |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | 6-digit DYPA code |
| `setBranchActivityCode()` | `f_kad_pararthmatos` | string | No | 4-digit KAD code |
| `setMunicipalityCode()` | `f_kallikratis_pararthmatos` | string | No | 8-digit Kallikratis code |

### Personal Information

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setLastName()` | `f_eponymo` | string | Yes | Last name (max 50 chars) |
| `setFirstName()` | `f_onoma` | string | Yes | First name (max 30 chars) |
| `setFatherName()` | `f_onoma_patros` | string | Yes | Father's name (max 30 chars) |
| `setMotherName()` | `f_onoma_mitros` | string | Yes | Mother's name (max 30 chars) |
| `setBirthDate()` | `f_birthdate` | string | Yes | Birth date (DD/MM/YYYY) |
| `setSex()` | `f_sex` | Sex | Yes | Sex (0=Male, 1=Female) |
| `setMaritalStatus()` | `f_marital_status` | MaritalStatus | No | Marital status |
| `setNumberOfChildren()` | `f_arithmos_teknon` | int | No | Number of children |

### Identity/Nationality

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setNationality()` | `f_yphkoothta` | string | Yes | 3-digit nationality code |
| `setIdType()` | `f_typos_taytothtas` | string | Yes | ID type code (e.g., ΑΤ) |
| `setIdNumber()` | `f_ar_taytothtas` | string | Yes | ID number (max 20 chars) |
| `setIdIssuingAuthority()` | `f_ekdousa_arxh` | string | No | Issuing authority (max 50 chars) |
| `setIdIssueDate()` | `f_date_ekdosis` | string | No | Issue date (DD/MM/YYYY) |
| `setIdExpiryDate()` | `f_date_ekdosis_lixi` | string | No | Expiry date (DD/MM/YYYY) |

### Foreign Worker Permits

For third-country nationals with direct labor market access:

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setResPermitDirectAccess()` | `f_res_permit_inst` | bool | No | Has direct access permit |
| `setResPermitDirectAccessType()` | `f_res_permit_inst_type` | string | Cond. | Permit type code |
| `setResPermitDirectAccessNumber()` | `f_res_permit_inst_ar` | string | Cond. | Permit number |
| `setResPermitDirectAccessExpiry()` | `f_res_permit_inst_lixi` | string | Cond. | Expiry date |

For permits requiring additional approval:

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setResPermitApproval()` | `f_res_permit_ap` | bool | No | Has approval permit |
| `setResPermitApprovalType()` | `f_res_permit_ap_type` | string | Cond. | Permit type code |
| `setResPermitApprovalNumber()` | `f_res_permit_ap_ar` | string | Cond. | Permit number |
| `setResPermitApprovalExpiry()` | `f_res_permit_ap_lixi` | string | Cond. | Expiry date |

For seasonal work visas:

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setSeasonalWorkVisa()` | `f_res_permit_visa` | bool | No | Has seasonal visa |
| `setSeasonalWorkVisaNumber()` | `f_res_permit_visa_ar` | string | Cond. | Visa number |
| `setSeasonalWorkVisaFrom()` | `f_res_permit_visa_from` | string | Cond. | Validity start |
| `setSeasonalWorkVisaTo()` | `f_res_permit_visa_to` | string | Cond. | Validity end |

### Tax/Insurance IDs

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setAfm()` | `f_afm` | string | Yes | 9-digit Tax ID (AFM) |
| `setTaxOffice()` | `f_doy` | string | No | 4-digit Tax Office code |
| `setAmika()` | `f_amika` | string | No | IKA insurance number |
| `setAmka()` | `f_amka` | string | Yes | 11-digit Social Security Number |
| `setUnemploymentCardNumber()` | `f_code_anergias` | string | No | Unemployment card number |
| `setMinorWorkBookNumber()` | `f_ar_vivliou_anilikou` | string | Cond. | Minor's work booklet (if under 18) |
| `setEducationLevel()` | `f_epipedo_morfosis` | string | No | Education level code |

### Employment Details

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setStartTime()` | `f_proslipsitime` | string | Yes | Work start time (HH:MM) |
| `setEndTime()` | `f_apoxwrisitime` | string | Yes | Work end time (HH:MM) |
| `setWeeklyHours()` | `f_week_hours` | float | Yes | Weekly hours (e.g., 40.0) |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty/occupation code |
| `setSpecialtyDescription()` | `f_eidikothta_anal` | string | No | Detailed specialty description |
| `setEmploymentStatus()` | `f_kathestosapasxolisis` | EmploymentStatus | Yes | Full/Part-time/Rotational/On-demand |
| `setWorkerType()` | `f_xaraktirismos` | WorkerType | Yes | Worker (0) or Employee (1) |
| `setResponsiblePosition()` | `f_responsible_position` | ResponsiblePosition | No | Managerial position |

### Work Organization

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setWorkingTimeDigitalOrganization()` | `f_working_time_digital_organization` | bool | No | Digital time tracking |
| `setFullEmploymentHours()` | `f_full_employment_hours` | float | No | Full employment hours |
| `setWeekDays()` | `f_week_days` | WeekDays | No | 5 or 6 day week |
| `setFlexibleArrivalMinutes()` | `f_euelikto_wrario_minutes` | int | No | Flexible arrival minutes |
| `setWorkingCard()` | `f_working_card` | bool | No | Work card required |
| `setBreakMinutes()` | `f_dialeimma_minutes` | int | No | Break duration |
| `setBreakWithinSchedule()` | `f_dialeimma_entos_wrariou` | bool | No | Break within schedule |

### Work Location

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setWorkLocation()` | `f_topos_ergasias` | WorkLocation | No | 0=Branch, 1=Other |
| `setWorkLocationComment()` | `f_topos_ergasias_comment` | string | Cond. | Location details |

### Unpredictable Schedule

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setUnpredictableSchedule()` | `f_mh_provlepsimo_programma` | bool | No | On-demand schedule |
| `setReferenceDaysHours()` | `f_paraggelia_hmeres_hours` | string | Cond. | Reference days/hours |
| `setMinNotificationPeriod()` | `f_paraggelia_min_notification` | string | Cond. | Minimum notice period |
| `setAssignmentCancellationDeadline()` | `f_paraggelia_notes` | string | Cond. | Cancellation deadline |

### Files

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setComments()` | `f_comments` | string | No | Comments (max 100 chars) |
| `setForeignWorkerFile()` | `f_foreign_file` | string | Cond. | Base64 PDF for foreign workers |
| `setMinorWorkerFile()` | `f_young_file` | string | Cond. | Base64 PDF for minors |

## Common Enums

### Sex

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `MALE` | 0 | Male | Άνδρας |
| `FEMALE` | 1 | Female | Γυναίκα |

### EmploymentType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `INDEFINITE` | 0 | Indefinite term | Αορίστου χρόνου |
| `FIXED_TERM` | 1 | Fixed term | Ορισμένου χρόνου |
| `PROJECT` | 2 | Project-based | Έργου |
| `BORROWED` | 3 | Borrowed employee | Δανειζόμενος |

### EmploymentStatus

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `FULL` | 0 | Full-time | Πλήρης απασχόληση |
| `PARTIAL` | 1 | Part-time | Μερική απασχόληση |
| `ROTATION` | 2 | Rotational | Εκ περιτροπής απασχόληση |
| `ON_DEMAND` | 3 | On-demand | Διαλείπουσα απασχόληση |

### WorkerType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `WORKER` | 0 | Worker | Εργάτης |
| `EMPLOYEE` | 1 | Employee | Υπάλληλος |

### MaritalStatus

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `SINGLE` | 0 | Single | Άγαμος |
| `MARRIED` | 1 | Married | Έγγαμος |
| `DIVORCED` | 2 | Divorced | Διαζευγμένος |
| `WIDOWED` | 3 | Widowed | Χήρος |

## Response Handling

All E3 documents return an array of `SubmissionResponse` objects:

```php
$response = (new HiringNew())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number (e.g., 'Ε3Ν123')
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Multiple Declarations

Submit multiple declarations in a single API call:

```php
$declarations = [
    NewDeclaration::make()->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')/* ... */,
    NewDeclaration::make()->setLastName('ΓΕΩΡΓΙΟΥ')/* ... */,
];

$response = (new HiringNew())->handle($declarations);
```

## Best Practices

### 1. Use Enums for Type Safety

```php
// Good
->setSex(Sex::MALE)
->setEmploymentType(EmploymentType::INDEFINITE)
->setEmploymentStatus(EmploymentStatus::FULL)

// Avoid
->setSex(0)
```

### 2. Handle File Attachments

```php
$pdfContent = file_get_contents('/path/to/document.pdf');
$declaration->setForeignWorkerFile(base64_encode($pdfContent));
```

### 3. Store Protocol Numbers

```php
foreach ($response as $result) {
    $this->storeHiringRecord([
        'employee_afm' => $declaration->getAfm(),
        'protocol' => $result->protocol,
        'submission_date' => $result->submissionDate,
    ]);
}
```

## See Also

- [New Hire (E3N)](./new) - Standard new employee hiring
- [Transfer (E3M)](./transfer) - Employee transfer from another company
- [Lending (E3D)](./lending) - Lending employee to another company
- [Borrowed (E3PD)](./borrowed) - Receiving a borrowed employee
