# Dismissal (E6)

Employer-initiated terminations are reported to ERGANI using E6 forms. These forms cover various scenarios where the employer ends the employment relationship.

## Overview

| Form | Document Class | Action Code | Use Case |
|------|----------------|-------------|----------|
| E6NXP | `DismissalWithoutNotice` | `WebE6NXP` | Immediate dismissal without notice |
| E6NMP | `DismissalWithNotice` | `WebE6NMP` | Dismissal with advance notice period |
| E6SXP | `RetirementDismissal` | `WebE6SXP` | Employer-initiated retirement dismissal |
| E6LD | `EndOfLoan` | `WebE6LD` | End of employee loan arrangement |
| E6LT | `TrialPeriodTermination` | `WebE6LT` | Trial period termination |
| E6M | `Transfer` | `WebE6M` | Employee transfer to another company |

## E5 vs E6: Key Difference

- **E5 (Termination)**: Employee-initiated departures - the employee decides to leave
- **E6 (Dismissal)**: Employer-initiated terminations - the employer ends the relationship

## Form Selection Guide

```
Employer Termination Scenario
├── Regular dismissal
│   ├── Immediate (no notice period) → Use E6NXP
│   └── With advance notice → Use E6NMP
├── Retirement
│   └── Employer requires retirement → Use E6SXP
├── Employee loan ending
│   └── Borrowed employee returns → Use E6LD
├── Trial period
│   └── Terminating during/after trial → Use E6LT
└── Transfer to another company
    └── Employee moving to new employer → Use E6M
```

## Key Structural Differences from E5

E6 forms differ from E5 forms in several ways:

| Aspect | E5 (Termination) | E6 (Dismissal) |
|--------|------------------|----------------|
| Date field | `f_apoxwrisidate` (departure) | `f_apolysisdate` (dismissal) |
| Employment type | Has `f_sxeshapasxolisis` | No employment type field |
| Fixed-term dates | Has `f_orismenou_*` fields | No fixed-term fields |

## Common Fields

All E6 forms share these fields from the base `Declaration` class:

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

### Files

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setForeignWorkerFile()` | `f_foreign_file` | string | No | Foreign worker docs (Base64 PDF) |
| `setMinorWorkerFile()` | `f_young_file` | string | No | Minor worker docs (Base64 PDF) |
| `setComments()` | `f_comments` | string | No | Additional comments (max 100 chars) |

## E6 Traits Overview

### HasEmploymentClassification

Used by: E6NXP, E6NMP, E6SXP

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setEmploymentStatus()` | `f_kathestosapasxolisis` | EmploymentStatus | Yes | Full/Part-time |
| `setWorkerType()` | `f_xaraktirismos` | WorkerType | Yes | Worker/Employee |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty code |

### HasSalary

Used by: E6NXP, E6NMP, E6SXP, E6LT

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setGrossSalary()` | `f_apodoxes` | float | Yes | Gross salary |

### HasCompensation

Used by: E6NXP, E6NMP, E6SXP

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCompensationAmount()` | `f_posoapozimiosis` | float | Yes | Severance amount |

### HasFormFile

Used by: E6NXP, E6NMP, E6SXP, E6LT

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setFormFile()` | `f_file` | string | Yes | Signed dismissal form (Base64 PDF) |

### HasCollectiveDismissal

Used by: E6NXP, E6NMP only

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCollectiveDismissal()` | `f_omadiki` | bool | No | Part of collective layoff |
| `setCollectiveDismissalNumber()` | `f_omadikiarithmos` | string | Cond. | Decision number |
| `setCollectiveDismissalDate()` | `f_omadikidate` | string | Cond. | Decision date |

### HasTerminationNotification

Used by: E6NXP, E6SXP

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setTerminationNotificationDate()` | `f_koinopoihshdate` | string | Yes | Date employee was notified |

### HasNoticePeriod

Used by: E6NMP only

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setNoticeDate()` | `f_proidopoihshdate` | string | Yes | Date notice was given |
| `setNoticePeriodMonths()` | `f_minesproidopoihsh` | int | Yes | Notice period in months (1-4) |

### HasLoanDetails

Used by: E6LD only

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setLoanType()` | `f_borrow_type` | LoanType | Yes | Genuine/EPA |
| `setLoanStartDate()` | `f_borrow_date_from` | string | Yes | Loan start date |
| `setLoanEndDate()` | `f_borrow_date_to` | string | Yes | Loan end date |
| `setBorrowingCompanyAfm()` | `f_borrow_company_afm` | string | Yes | Borrower's AFM |
| `setBorrowingCompanyName()` | `f_borrow_company_eponimia` | string | Yes | Borrower's name |

## Enums

### EmploymentStatus

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `FULL` | 0 | Full-time | Πλήρης |
| `PARTIAL` | 1 | Part-time | Μερική |
| `ROTATIONAL` | 2 | Rotational | Εκ περιτροπής |

### WorkerType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `WORKER` | 0 | Blue-collar worker | Εργάτης |
| `EMPLOYEE` | 1 | White-collar employee | Υπάλληλος |

### LoanType

| Case | Value | English | Greek |
|------|-------|---------|-------|
| `GENUINE` | 0 | Genuine borrowing | Γνήσιος δανεισμός |
| `EPA` | 1 | Temporary Employment Agency | Ε.Π.Α. |

### NoticePeriodMonths

| Value | Description |
|-------|-------------|
| 1 | 1 month notice |
| 2 | 2 months notice |
| 3 | 3 months notice |
| 4 | 4 months notice |

## Quick Comparison

| Feature | E6NXP | E6NMP | E6SXP | E6LD | E6LT | E6M |
|---------|-------|-------|-------|------|------|-----|
| Employment Classification | Yes | Yes | Yes | No | No | No |
| Salary | Yes | Yes | Yes | No | Yes | No |
| Compensation | Yes | Yes | Yes | No | No | No |
| Form File | Yes | Yes | Yes | No | Yes | No |
| Collective Dismissal | Yes | Yes | No | No | No | No |
| Notification Date | Yes | No | Yes | No | No | No |
| Notice Period | No | Yes | No | No | No | No |
| Loan Details | No | No | No | Yes | No | No |
| Transfer Details | No | No | No | No | No | Yes |

## See Also

- [Dismissal Without Notice (E6NXP)](./without-notice) - Immediate dismissal
- [Dismissal With Notice (E6NMP)](./with-notice) - Advance notice dismissal
- [Retirement Dismissal (E6SXP)](./retirement) - Employer-initiated retirement
- [End of Loan (E6LD)](./end-of-loan) - Employee loan ending
- [Trial Period Termination (E6LT)](./trial-period) - Trial period end
- [Transfer (E6M)](./transfer) - Employee transfer
- [Termination (E5)](/guide/termination/) - Employee-initiated departures
