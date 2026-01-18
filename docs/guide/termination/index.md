# Termination (E5)

Employee terminations (voluntary departures initiated by the employee) are reported to ERGANI using E5 forms. This package provides dedicated classes for each termination scenario.

## Overview

| Form | Document Class | Action Code | Use Case |
|------|----------------|-------------|----------|
| E5N | `VoluntaryResignation` | `WebE5N` | Standard voluntary resignation |
| E5O | `ResignationNotification` | `WebE5O` | Notification of possible resignation (absent employee) |
| E5AO | `ResignationAfterNotification` | `WebE5AO` | Confirmed resignation after E5O |
| E5D | `TerminationByDeath` | `WebE5D` | Termination due to employee death |
| E5E | `VoluntaryExitCompensation` | `WebE5E` | Voluntary exit with severance pay |
| E5S | `VoluntaryRetirement` | `WebE5S` | Voluntary retirement with compensation |
| E5DS | `MandatoryRetirement` | `WebE5DS` | Mandatory retirement (15 years/age limit) |

## E5 vs E6: Key Difference

- **E5 (Termination)**: Employee-initiated departures - the employee decides to leave
- **E6 (Dismissal)**: Employer-initiated terminations - the employer ends the relationship

## Form Selection Guide

```
Employee Departure Scenario
├── Voluntary resignation (written letter)
│   └── Use E5N
├── Employee absent without notice
│   ├── Initial notification → Use E5O
│   └── Confirmed resignation → Use E5AO (links to E5O)
├── Employee deceased
│   └── Use E5D
├── Voluntary exit program with compensation
│   └── Use E5E
└── Retirement
    ├── Employee requests retirement → Use E5S (voluntary)
    └── 15+ years or age limit reached → Use E5DS (mandatory)
```

## Common Fields

All E5 forms share these fields from the base `Declaration` class:

### Branch/Location

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | string | Yes | Branch number (0 for HQ) |
| `setLaborInspectionServiceCode()` | `f_ypiresia_sepe` | string | Yes | SEPE code (5 digits) |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | DYPA code (6 digits) |
| `setBranchActivityCode()` | `f_kad_pararthmatos` | string | No | Economic activity code (KAD) |
| `setMunicipalityCode()` | `f_kallikratis_pararthmatos` | string | No | Kallikratis code (8 digits) |

### Personal Information

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setLastName()` | `f_eponymo` | string | Yes | Last name (max 50 chars) |
| `setFirstName()` | `f_onoma` | string | Yes | First name (max 30 chars) |
| `setFatherName()` | `f_onoma_patros` | string | Yes | Father's name (max 30 chars) |
| `setMotherName()` | `f_onoma_mitros` | string | No | Mother's name (max 30 chars) |
| `setBirthDate()` | `f_birthdate` | string | Yes | Birth date (DD/MM/YYYY) |
| `setSex()` | `f_sex` | Sex | Yes | Gender (MALE/FEMALE) |

### Identity

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setNationality()` | `f_yphkoothta` | string | Yes | Nationality code (3 digits) |
| `setIdType()` | `f_typos_taytothtas` | string | Yes | ID type code (e.g., 'ΑΤ') |
| `setIdNumber()` | `f_ar_taytothtas` | string | Yes | ID document number |
| `setIdIssuingAuthority()` | `f_ekdousa_arxh` | string | No | Issuing authority |
| `setIdIssueDate()` | `f_date_ekdosis` | string | No | Issue date (DD/MM/YYYY) |
| `setIdExpiryDate()` | `f_date_ekdosis_lixi` | string | No | Expiry date (DD/MM/YYYY) |

### Tax/Insurance IDs

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setAfm()` | `f_afm` | string | Yes | Tax ID (9 digits) |
| `setTaxOffice()` | `f_doy` | string | No | Tax office code (4 digits) |
| `setAmka()` | `f_amka` | string | Yes | Social Security Number |
| `setAmika()` | `f_amika` | string | No | IKA insurance number |

### Employment Details

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setWorkerType()` | `f_xaraktirismos` | WorkerType | Yes | Worker classification |
| `setEmploymentType()` | `f_sxeshapasxolisis` | EmploymentType | Yes | Employment relationship |
| `setEmploymentStatus()` | `f_kathestosapasxolisis` | EmploymentStatus | Yes | Full/Part-time/Rotational |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty code (1-6 digits) |
| `setHiringDate()` | `f_proslipsidate` | string | Yes | Hiring date (DD/MM/YYYY) |
| `setDepartureDate()` | `f_apoxwrisidate` | string | Yes | Departure date (DD/MM/YYYY) |

### Fixed-Term Dates (Conditional)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFixedTermFrom()` | `f_orismenou_apo` | string | Cond. | Fixed-term start date |
| `setFixedTermTo()` | `f_orismenou_ews` | string | Cond. | Fixed-term end date |

### Files

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setForeignWorkerFile()` | `f_foreign_file` | string | No | Foreign worker docs (Base64 PDF) |
| `setMinorWorkerFile()` | `f_young_file` | string | No | Minor worker docs (Base64 PDF) |
| `setComments()` | `f_comments` | string | No | Additional comments (max 100 chars) |

## Shared Traits

E5 forms use these traits for common functionality:

### HasSalary

Used by: E5N, E5AO, E5D, E5E, E5S, E5DS (NOT E5O)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary at departure |

### HasCompensation

Used by: E5E, E5S, E5DS only

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCompensationAmount()` | `f_posoapozimiosis` | float | Yes | Severance/compensation amount |

### HasFormFile

Used by: E5N, E5D, E5E, E5S, E5DS (NOT E5O, E5AO)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Signed form (Base64 PDF) |

### HasNotificationReference

Used by: E5AO only

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setNotificationProtocol()` | `f_oxlhsh_protocol` | string | Yes | E5O protocol number |
| `setNotificationDate()` | `f_oxlhsh_date_ypovolis` | string | Yes | E5O submission date |

## Enums

### WorkerType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `WORKER` | 0 | Blue-collar worker | Εργάτης |
| `EMPLOYEE` | 1 | White-collar employee | Υπάλληλος |

### EmploymentType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `INDEFINITE` | 0 | Indefinite term | Αορίστου χρόνου |
| `FIXED_TERM` | 1 | Fixed term | Ορισμένου χρόνου |
| `PROJECT` | 2 | Project-based | Ορισμένου έργου |

::: warning E5O Restriction
E5O (Notification) only accepts `INDEFINITE` or `FIXED_TERM` - not `PROJECT`.
:::

### EmploymentStatus

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `FULL` | 0 | Full-time | Πλήρης |
| `PARTIAL` | 1 | Part-time | Μερική |
| `ROTATIONAL` | 2 | Rotational | Εκ περιτροπής |

### Sex

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `MALE` | 0 | Male | Άνδρας |
| `FEMALE` | 1 | Female | Γυναίκα |

## Quick Comparison

| Feature | E5N | E5O | E5AO | E5D | E5E | E5S | E5DS |
|---------|-----|-----|------|-----|-----|-----|------|
| Salary | Yes | No | Yes | Yes | Yes | Yes | Yes |
| Compensation | No | No | No | No | Yes | Yes | Yes |
| Form File | Yes | No | No | Yes | Yes | Yes | Yes |
| Notification Ref | No | No | Yes | No | No | No | No |
| Notification Methods | No | Yes | No | No | No | No | No |

## See Also

- [Voluntary Resignation (E5N)](./voluntary) - Standard resignation
- [Resignation Notification (E5O)](./notification) - Absent employee notification
- [Resignation After Notification (E5AO)](./after-notification) - Confirm after E5O
- [Death Termination (E5D)](./death) - Employee death
- [Compensated Exit (E5E)](./compensated-exit) - Voluntary exit with severance
- [Voluntary Retirement (E5S)](./voluntary-retirement) - Retirement by choice
- [Mandatory Retirement (E5DS)](./mandatory-retirement) - Required retirement
- [Dismissal (E6)](/guide/dismissal/) - Employer-initiated terminations
