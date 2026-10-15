# Internship Declaration (E3.5)

The E3.5 form (action code `WebE35`) is used to declare internship placements. It is a comprehensive form covering personal information, education, internship terms, weekly schedule, and certifier details.

## Overview

Follow-up forms correct the declaration, stop the internship early, or change its weekly schedule.

| Form | Action Code | Document Class | Declaration Model | Use Case |
|------|-------------|----------------|-------------------|----------|
| E3.5 | `WebE35` | `Internship` | `InternshipDeclaration` | Internship start or modification |
| E3.5 Correction | `WebE35C` | `InternshipCorrection` | `InternshipDeclaration` | Correct a submitted E3.5 |
| E3.5 Stop | `WebE35D` | `InternshipTermination` | `InternshipTerminationDeclaration` | Early termination (interruption) of an internship |
| E3.5 Schedule Change | `WebE35T` | `InternshipScheduleChange` | `InternshipScheduleChangeDeclaration` | New weekly schedule for an internship |

| Facade Method | Form |
|---------------|------|
| `sendInternshipDeclaration()` | E3.5 |
| `sendInternshipCorrection()` | E3.5 Correction |
| `sendInternshipTermination()` | E3.5 Stop |
| `sendInternshipScheduleChange()` | E3.5 Schedule Change |

## Basic Usage

```php
use OxygenSuite\OxygenErgani\Http\Documents\Internship\Internship;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;

$declaration = InternshipDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionCode('12345')
    ->setDypaServiceCode('123456')
    ->setMainActivityCode('6201')
    ->setBranchActivityCode('6201')
    ->setMunicipalityCode('0101')

    // Personal
    ->setLastName('ΠΑΠΑΔΟΠΟΥΛΟΣ')
    ->setFirstName('ΝΙΚΟΛΑΟΣ')
    ->setFatherName('ΙΩΑΝΝΗΣ')
    ->setMotherName('ΜΑΡΙΑ')
    ->setBirthPlace('ΑΘΗΝΑ')
    ->setBirthDate('15/03/2003')
    ->setSex('1')
    ->setNationality('001')
    ->setIdType('ΑΤ')
    ->setIdNumber('ΑΒ123456')
    ->setAfm('123456789')
    ->setAmka('15030312345')

    // Education
    ->setEducationLevel('3')
    ->setInstituteNationality('001')
    ->setInstituteName('ΤΕΙ ΑΘΗΝΩΝ')
    ->setSchool('ΣΧΟΛΗ ΤΕΧΝΟΛΟΓΙΚΩΝ ΕΦΑΡΜΟΓΩΝ')
    ->setDepartment('ΠΛΗΡΟΦΟΡΙΚΗΣ')

    // Internship terms
    ->setPlacementDate('01/02/2025')
    ->setPlacementTime('09:00')
    ->setWeeklyHours(30.0)
    ->setTotalHours(720.0)
    ->setSpecialtyCode('123456')
    ->setCompensation(600.00)
    ->setHourlyCompensation(5.00)
    ->setStartDate('01/02/2025')
    ->setEndDate('31/07/2025')

    // Schedule (Monday-Friday, 09:00-15:00)
    ->setSchedule(1, '09:00', '15:00')
    ->setSchedule(2, '09:00', '15:00')
    ->setSchedule(3, '09:00', '15:00')
    ->setSchedule(4, '09:00', '15:00')
    ->setSchedule(5, '09:00', '15:00')

    // Certifier
    ->setCertifierLastName('ΓΕΩΡΓΙΟΥ')
    ->setCertifierFirstName('ΔΗΜΗΤΡΙΟΣ')
    ->setCertifierCapacity('ΔΙΕΥΘΥΝΤΗΣ')
    ->setCertifierAddress('ΛΕΩΦ. ΑΛΕΞΑΝΔΡΑΣ 1')
    ->setCertifierAfm('987654321');

$response = (new Internship())->handle($declaration);
```

### Via Ergani Facade

```php
use OxygenSuite\OxygenErgani\Ergani;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;

$ergani = new Ergani($accessToken);
$responses = $ergani->sendInternshipDeclaration($declaration);
```

## Field Reference

### Branch/Location

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number |
| `setRelatedProtocol()` | `f_rel_protocol` | string | No | Related submission protocol |
| `setRelatedDate()` | `f_rel_date` | DateTime\|string | No | Related submission date |
| `setLaborInspectionCode()` | `f_ypiresia_sepe` | string | Yes | SEPE service code |
| `setDypaServiceCode()` | `f_ypiresia_oaed` | string | Yes | DYPA/OAED service code |
| `setEmployerOrganization()` | `f_ergodotikh_organwsh` | string | No | Employer organization |
| `setMainActivityCode()` | `f_kad_kyria` | string | No | Main activity code (KAD) |
| `setSecondaryActivityCode1()` | `f_kad_deyt_1` | string | No | Secondary activity code 1 |
| `setSecondaryActivityCode2()` | `f_kad_deyt_2` | string | No | Secondary activity code 2 |
| `setSecondaryActivityCode3()` | `f_kad_deyt_3` | string | No | Secondary activity code 3 |
| `setSecondaryActivityCode4()` | `f_kad_deyt_4` | string | No | Secondary activity code 4 |
| `setBranchActivityCode()` | `f_kad_pararthmatos` | string | No | Branch activity code |
| `setMunicipalityCode()` | `f_kallikratis_pararthmatos` | string | No | Municipality code |

### Personal Information

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setLastName()` | `f_eponymo` | string | Yes | Last name |
| `setFirstName()` | `f_onoma` | string | Yes | First name |
| `setFatherName()` | `f_onoma_patros` | string | Yes | Father's name |
| `setMotherName()` | `f_onoma_mitros` | string | No | Mother's name |
| `setBirthPlace()` | `f_topos_gennhshs` | string | No | Place of birth |
| `setBirthDate()` | `f_birthdate` | DateTime\|string | Yes | Birth date (DD/MM/YYYY) |
| `setSex()` | `f_sex` | Sex\|string\|int | Yes | Sex (0=Male, 1=Female) |
| `setNationality()` | `f_yphkoothta` | string | Yes | Nationality code |
| `setMaritalStatus()` | `f_marital_status` | MaritalStatus\|string\|int | No | Marital status (0-3) |
| `setNumberOfChildren()` | `f_arithmos_teknon` | int\|string | No | Number of children |

### Identity Document

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setIdType()` | `f_typos_taytothtas` | string | Yes | ID document type |
| `setIdNumber()` | `f_ar_taytothtas` | string | Yes | ID document number |
| `setIdIssuingAuthority()` | `f_ekdousa_arxh` | string | No | Issuing authority |
| `setIdIssueDate()` | `f_date_ekdosis` | DateTime\|string | No | Issue date |
| `setIdExpiryDate()` | `f_date_ekdosis_lixi` | DateTime\|string | No | Expiry date |

### Residence Permit (Foreign Nationals)

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setResidencePermit()` | `f_res_permit_int` | string\|bool | No | Has residence permit (0/1) |
| `setResidencePermitType()` | `f_res_permit_int_type` | string | No | Permit type |
| `setResidencePermitNumber()` | `f_res_permit_int_ar` | string | No | Permit number |
| `setResidencePermitExpiry()` | `f_res_permit_int_lixi` | DateTime\|string | No | Permit expiry date |

### Tax/Insurance

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setAfm()` | `f_afm` | string | Yes | Tax ID (AFM) |
| `setTaxOffice()` | `f_doy` | string | No | Tax office code |
| `setInsuranceAgency()` | `f_insurance_agency` | string | No | Main insurance agency code (`ParameterLookup::PRIMARY_INSURANCE`) |
| `setAmika()` | `f_amika` | string | No | IKA number |
| `setAmka()` | `f_amka` | string | Yes | Social security number |
| `setMinorBookNumber()` | `f_ar_vivliou_anilikou` | string | No | Minor work permit book |
| `setPhone()` | `f_til` | string | No | Phone number |
| `setEmail()` | `f_email` | string | No | Email address |

### Education

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setEducationLevel()` | `f_epipedo_morfosis` | string | No | Education level (0-5) |
| `setInstituteNationality()` | `f_educational_institute_nationality` | string | No | Institute country |
| `setInstituteName()` | `f_educational_institute_name` | string | No | Institute name |
| `setSchool()` | `f_sxolh` | string | No | School/Faculty |
| `setDepartment()` | `f_department` | string | No | Department |

### Internship Details

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setApprovalNumber()` | `f_approval_number` | string | No | Approval number |
| `setPlacementDate()` | `f_date_proslipsis` | DateTime\|string | Yes | Internship placement date |
| `setPlacementTime()` | `f_date_time_proslipsis` | string | Yes | Placement time (HH:MM) |
| `setWeeklyHours()` | `f_week_hours` | float | Yes | Weekly hours (Greek float:1) |
| `setTotalHours()` | `f_total_hours` | float | No | Total internship hours (Greek float:1) |
| `setSpecialtyCode()` | `f_eidikothta` | string | Yes | Specialty code |
| `setCompensation()` | `f_apodoxes` | float | Yes | Gross monthly compensation (Greek float) |
| `setHourlyCompensation()` | `f_hour_apodoxes` | float | No | Hourly compensation (Greek float) |
| `setStartDate()` | `f_orismenou_apo` | DateTime\|string | Yes | Internship period start |
| `setEndDate()` | `f_orismenou_ews` | DateTime\|string | Yes | Internship period end |
| `setDypaPlacement()` | `f_topothetisioaed` | string\|bool | No | DYPA placement (0/1) |
| `setComments()` | `f_comments` | string | No | Additional comments |

### Weekly Schedule

The internship form requires a weekly schedule with up to three time ranges per day (primary, split shift, and break).

#### Schedule Helper Methods

```php
// Primary schedule: Monday (1) through Sunday (7)
$declaration->setSchedule(1, '09:00', '15:00');    // Monday
$declaration->setSchedule(2, '09:00', '15:00');    // Tuesday

// Split shift (second shift block)
$declaration->setSplitSchedule(1, '17:00', '20:00');

// Break times
$declaration->setBreakSchedule(1, '12:00', '12:30');
```

Each day (1-7) has six underlying API fields. The helper methods set them in pairs:

| Helper Method | Sets API Fields |
|---------------|-----------------|
| `setSchedule($day, $from, $to)` | `f_time_from_{day}`, `f_time_to_{day}` |
| `setSplitSchedule($day, $from, $to)` | `f_second_time_from_{day}`, `f_second_time_to_{day}` |
| `setBreakSchedule($day, $from, $to)` | `f_break_time_from_{day}`, `f_break_time_to_{day}` |

Where `{day}` is the day number (1=Monday, 7=Sunday).

Read a day back with `getSchedule($day)`, `getSplitSchedule($day)` or `getBreakSchedule($day)`, each returning `['from' => ..., 'to' => ...]`.

### Certifier

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setCertifierLastName()` | `f_eponymo_idiotitas` | string | Yes | Certifier last name |
| `setCertifierFirstName()` | `f_onoma_idiotitas` | string | Yes | Certifier first name |
| `setCertifierCapacity()` | `f_idiotita_idiotitas` | string | Yes | Certifier role/capacity |
| `setCertifierAddress()` | `f_dieythinsi_idiotitas` | string | No | Certifier address |
| `setCertifierAfm()` | `f_afm_idiotitas` | string | Yes | Certifier tax ID |

### Files

| Method | API Field | Type | Description |
|--------|-----------|------|-------------|
| `setLegalRepresentativeAfm()` | `f_afm_proswpoy` | string | Representative's AFM |
| `setFormFile()` | `f_file` | string | Main document (Base64 PDF) |
| `setForeignFile()` | `f_foreign_file` | string | Foreign national docs (Base64 PDF) |
| `setMinorFile()` | `f_young_file` | string | Minor worker docs (Base64 PDF) |

---

## Complete Example

```php
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;

$declaration = InternshipDeclaration::make()
    // Branch
    ->setBranchCode(0)
    ->setLaborInspectionCode('12345')
    ->setDypaServiceCode('123456')
    ->setMainActivityCode('6201')
    ->setBranchActivityCode('6201')
    ->setMunicipalityCode('0101')

    // Personal info
    ->setLastName('ΑΝΤΩΝΙΟΥ')
    ->setFirstName('ΕΛΕΝΗ')
    ->setFatherName('ΔΗΜΗΤΡΙΟΣ')
    ->setMotherName('ΑΙΚΑΤΕΡΙΝΗ')
    ->setBirthPlace('ΘΕΣΣΑΛΟΝΙΚΗ')
    ->setBirthDate('22/08/2003')
    ->setSex('1')
    ->setNationality('001')
    ->setMaritalStatus('0')

    // Identity
    ->setIdType('ΑΤ')
    ->setIdNumber('ΘΗ789012')
    ->setIdIssuingAuthority('Α.Τ. ΘΕΣΣΑΛΟΝΙΚΗΣ')

    // Tax/Insurance
    ->setAfm('555666777')
    ->setTaxOffice('1234')
    ->setAmka('22080312345')
    ->setEmail('eleni@example.com')
    ->setPhone('6971234567')

    // Education
    ->setEducationLevel('3')
    ->setInstituteNationality('001')
    ->setInstituteName('ΠΑΝΕΠΙΣΤΗΜΙΟ ΘΕΣΣΑΛΟΝΙΚΗΣ')
    ->setSchool('ΣΧΟΛΗ ΘΕΤΙΚΩΝ ΕΠΙΣΤΗΜΩΝ')
    ->setDepartment('ΠΛΗΡΟΦΟΡΙΚΗΣ')

    // Internship
    ->setPlacementDate('01/03/2025')
    ->setPlacementTime('09:00')
    ->setWeeklyHours(30.0)
    ->setTotalHours(720.0)
    ->setSpecialtyCode('123456')
    ->setCompensation(600.00)
    ->setHourlyCompensation(5.00)
    ->setStartDate('01/03/2025')
    ->setEndDate('31/08/2025')
    ->setDypaPlacement(false)

    // Schedule: Mon-Fri 09:00-15:00 with 30min break
    ->setSchedule(1, '09:00', '15:00')
    ->setSchedule(2, '09:00', '15:00')
    ->setSchedule(3, '09:00', '15:00')
    ->setSchedule(4, '09:00', '15:00')
    ->setSchedule(5, '09:00', '15:00')
    ->setBreakSchedule(1, '12:00', '12:30')
    ->setBreakSchedule(2, '12:00', '12:30')
    ->setBreakSchedule(3, '12:00', '12:30')
    ->setBreakSchedule(4, '12:00', '12:30')
    ->setBreakSchedule(5, '12:00', '12:30')

    // Certifier
    ->setCertifierLastName('ΓΕΩΡΓΙΟΥ')
    ->setCertifierFirstName('ΚΩΝΣΤΑΝΤΙΝΟΣ')
    ->setCertifierCapacity('ΔΙΕΥΘΥΝΤΗΣ HR')
    ->setCertifierAddress('ΕΓΝΑΤΙΑ 45, ΘΕΣΣΑΛΟΝΙΚΗ')
    ->setCertifierAfm('987654321')

    ->setComments('Πρακτική άσκηση μέσω προγράμματος ΕΣΠΑ');
```

---

## E3.5 Correction (WebE35C)

A correction resubmits the full `InternshipDeclaration` and references the original declaration through `setRelatedProtocol()` and `setRelatedDate()`.

```php
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipCorrection;

$declaration
    ->setRelatedProtocol($original->protocol)
    ->setRelatedDate($original->submissionDate->format('d/m/Y'));

$response = (new InternshipCorrection())->handle($declaration);

// or via the facade
$responses = $ergani->sendInternshipCorrection($declaration);
```

---

## E3.5 Stop (WebE35D)

Reports the early termination of an internship declared with a previous E3.5.

```php
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipTermination;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipTerminationDeclaration;

$declaration = InternshipTerminationDeclaration::make()
    ->setBranchCode(0)
    ->setRelatedProtocol('ΠΡ1')            // Protocol of the original E3.5
    ->setRelatedDate('10/04/2026')         // Submission date of the original E3.5
    ->setAfm('555666777')                  // Intern's AFM
    ->setTerminationDate('08/09/2026')
    ->setTerminationReason('001')
    ->setCertifierLastName('ΓΕΩΡΓΙΟΥ')
    ->setCertifierFirstName('ΚΩΝΣΤΑΝΤΙΝΟΣ')
    ->setCertifierCapacity('ΛΟΓΙΣΤΗΣ')
    ->setCertifierAddress('ΕΓΝΑΤΙΑ 45, ΘΕΣΣΑΛΟΝΙΚΗ')
    ->setCertifierAfm('987654321')
    ->setLegalRepresentativeAfm('123456789')
    ->withDefaults();                      // Send unset file fields as empty strings

$response = (new InternshipTermination())->handle($declaration);

// or via the facade
$responses = $ergani->sendInternshipTermination($declaration);
```

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number |
| `setRelatedProtocol()` | `f_rel_protocol` | string | Yes | Protocol of the original E3.5 |
| `setRelatedDate()` | `f_rel_date` | DateTime\|string | Yes | Submission date of the original E3.5 |
| `setAfm()` | `f_afm` | string | Yes | Intern's tax ID (AFM) |
| `setTerminationDate()` | `f_diakoph_date` | DateTime\|string | Yes | Termination date (DD/MM/YYYY) |
| `setTerminationReason()` | `f_diakoph_aitiologia` | string | Yes | Termination reason code (3 digits) |
| `setCertifierLastName()` | `f_eponymo` | string | Yes | Certifier last name |
| `setCertifierFirstName()` | `f_onoma` | string | Yes | Certifier first name |
| `setCertifierCapacity()` | `f_idiotita` | string | Yes | Certifier role/capacity |
| `setCertifierAddress()` | `f_dieythinsi` | string | Yes | Certifier home address |
| `setCertifierAfm()` | `f_afm_bebaiwn` | string | Yes | Certifier tax ID |
| `setLegalRepresentativeAfm()` | `f_afm_proswpoy` | string | Yes | Legal representative's AFM |
| `setFormFile()` | `f_file` | string | No | Signed form (Base64 PDF) |
| `setForeignFile()` | `f_foreign_file` | string | No | Third-country national docs (Base64 PDF) |
| `setMinorFile()` | `f_young_file` | string | No | Minor's work booklet (Base64 PDF) |

::: info Certifier fields
The stop form uses different API fields for the certifier (`f_eponymo`, `f_onoma`, ...) than E3.5 (`f_eponymo_idiotitas`, ...), but the setter names are the same.
:::

---

## E3.5 Schedule Change (WebE35T)

Reports a new weekly schedule for an internship declared with a previous E3.5. It uses the same schedule helpers and certifier setters as `InternshipDeclaration`.

```php
use OxygenSuite\OxygenErgani\Http\Documents\Internship\InternshipScheduleChange;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipScheduleChangeDeclaration;

$declaration = InternshipScheduleChangeDeclaration::make()
    ->setBranchCode(0)
    ->setRelatedProtocol('ΠΡ1')            // Protocol of the original E3.5
    ->setRelatedDate('10/04/2026')         // Submission date of the original E3.5
    ->setAfm('555666777')                  // Intern's AFM
    ->setChangeDate('13/09/2026')          // Date of the schedule change
    ->setSchedule(1, '10:00', '16:00')
    ->setSchedule(3, '10:00', '16:00')
    ->setBreakSchedule(1, '13:00', '13:15')
    ->setCertifierLastName('ΓΕΩΡΓΙΟΥ')
    ->setCertifierFirstName('ΚΩΝΣΤΑΝΤΙΝΟΣ')
    ->setCertifierCapacity('ΛΟΓΙΣΤΗΣ')
    ->setCertifierAddress('ΕΓΝΑΤΙΑ 45, ΘΕΣΣΑΛΟΝΙΚΗ')
    ->setCertifierAfm('987654321')
    ->setLegalRepresentativeAfm('123456789')
    ->withDefaults();                      // Send unused time and file fields as empty strings

$response = (new InternshipScheduleChange())->handle($declaration);

// or via the facade
$responses = $ergani->sendInternshipScheduleChange($declaration);
```

| Method | API Field | Type | Required | Description |
|--------|-----------|------|----------|-------------|
| `setBranchCode()` | `f_aa_pararthmatos` | int\|string | Yes | Branch sequence number |
| `setRelatedProtocol()` | `f_rel_protocol` | string | Yes | Protocol of the original E3.5 |
| `setRelatedDate()` | `f_rel_date` | DateTime\|string | Yes | Submission date of the original E3.5 |
| `setAfm()` | `f_afm` | string | Yes | Intern's tax ID (AFM) |
| `setChangeDate()` | `f_trpopoihsh_date` | DateTime\|string | Yes | Date of the schedule change (DD/MM/YYYY) |
| `setSchedule()` / `setSplitSchedule()` / `setBreakSchedule()` | `f_time_*`, `f_second_time_*`, `f_break_time_*` | string | Yes | Weekly schedule (see [Weekly Schedule](#weekly-schedule)) |
| `setCertifierLastName()` | `f_eponymo_idiotitas` | string | Yes | Certifier last name |
| `setCertifierFirstName()` | `f_onoma_idiotitas` | string | Yes | Certifier first name |
| `setCertifierCapacity()` | `f_idiotita_idiotitas` | string | Yes | Certifier role/capacity |
| `setCertifierAddress()` | `f_dieythinsi_idiotitas` | string | Yes | Certifier home address |
| `setCertifierAfm()` | `f_afm_idiotitas` | string | Yes | Certifier tax ID |
| `setLegalRepresentativeAfm()` | `f_afm_proswpoy` | string | Yes | Legal representative's AFM |
| `setFormFile()` / `setForeignFile()` / `setMinorFile()` | `f_file`, `f_foreign_file`, `f_young_file` | string | No | Attached files (Base64 PDF) |

::: tip All fields are sent
The XSD lists every time field as required (an empty string means "no time"). Call `withDefaults()` after setting the days you need, so the remaining fields are sent as empty strings.
:::

---

## Response Handling

```php
$response = (new Internship())->handle($declaration);

foreach ($response as $result) {
    echo $result->id;              // Unique submission ID
    echo $result->protocol;        // Protocol number
    echo $result->submissionDate->format('d/m/Y H:i:s');
}
```

## Retrieve PDF

```php
$pdfBase64 = (new Internship())->pdf(
    $response[0]->protocol,
    $response[0]->submissionDate
);

file_put_contents('internship.pdf', base64_decode($pdfBase64));
```

---

## Best Practices

1. **Schedule Helpers**: Use `setSchedule()`, `setSplitSchedule()`, and `setBreakSchedule()` instead of setting individual time fields via `->set()`.

2. **Weekly Hours**: Ensure `setWeeklyHours()` matches the sum of scheduled hours across all days.

3. **Certifier**: The certifier is the person signing on behalf of the employer — typically an HR manager or company director.

4. **Education Fields**: Fill in the educational institution details as they appear on the internship agreement.

5. **DYPA Placement**: Set `setDypaPlacement(true)` only if the intern was placed through DYPA/OAED.

## Testing with Factories

```php
use OxygenSuite\OxygenErgani\Models\Internship\InternshipDeclaration;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipScheduleChangeDeclaration;
use OxygenSuite\OxygenErgani\Models\Internship\InternshipTerminationDeclaration;

$declaration = InternshipDeclaration::factory()->make();

// With state methods
$declaration = InternshipDeclaration::factory()
    ->mainBranch()
    ->female()
    ->partTime(20.0)
    ->make();

// Foreign national
$declaration = InternshipDeclaration::factory()
    ->foreignNational('002', 'TYPE_A', 'PERM123', '31/12/2026')
    ->make();

// OAED placement
$declaration = InternshipDeclaration::factory()
    ->withOaedPlacement()
    ->make();

// Stop (WebE35D)
$termination = InternshipTerminationDeclaration::factory()
    ->forDeclaration('ΠΡ1', '10/04/2026')
    ->terminatedOn('08/09/2026', '001')
    ->make();

// Schedule change (WebE35T)
$change = InternshipScheduleChangeDeclaration::factory()
    ->forDeclaration('ΠΡ1', '10/04/2026')
    ->withSchedule('10:00', '16:00', [1, 3, 5])
    ->make();
```

---

## See Also

- [New Hire (E3N)](/guide/hiring/new) - Regular employee hiring
- [Services & Queries](/guide/services) - Parameter lookups for specialties
